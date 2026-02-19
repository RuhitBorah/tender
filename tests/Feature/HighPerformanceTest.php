<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Tender;
use App\Imports\TendersImport;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;

class HighPerformanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_location_excel_controller_uses_pagination()
    {
        // Setup
        $user = User::factory()->create(['role' => 'admin']);
        
        $district = District::create(['name' => 'Test District', 'state' => 'Test State']);
        
        // Create 60 tenders manually
        for ($i = 0; $i < 60; $i++) {
            Tender::create([
                'tender_no' => 'T-' . $i,
                'title' => 'Tender ' . $i,
                'district_id' => $district->id,
                'status' => 'active'
            ]);
        }

        // Act
        $response = $this->actingAs($user)
                         ->get(route('admin.locations.excel.index', $district));

        // Assert
        $response->assertStatus(200);
        
        // check if 'tenders' variable is a Paginator
        $tenders = $response->viewData('tenders');
        $this->assertInstanceOf(LengthAwarePaginator::class, $tenders);
        $this->assertEquals(50, $tenders->perPage());
        $this->assertEquals(60, $tenders->total());
    }

    public function test_tenders_import_implements_chunking_interfaces()
    {
        $import = new TendersImport(new District());

        $this->assertInstanceOf(WithChunkReading::class, $import);
        $this->assertInstanceOf(WithBatchInserts::class, $import);
        
        $this->assertEquals(1000, $import->chunkSize());
        $this->assertEquals(1000, $import->batchSize());
    }
}
