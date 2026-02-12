<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\Tender;

class TenderAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_only_see_own_district_tenders()
    {
        $d1 = District::create(['name' => 'D1']);
        $d2 = District::create(['name' => 'D2']);

        $user = User::factory()->create(['district_id' => $d1->id, 'role' => 'user']);
        
        $t1 = Tender::create(['title' => 'T1', 'description' => '..', 'district_id' => $d1->id, 'status' => 'draft']);
        $t2 = Tender::create(['title' => 'T2', 'description' => '..', 'district_id' => $d2->id, 'status' => 'draft']);

        $response = $this->actingAs($user)->getJson('/tenders');

        $response->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['title' => 'T1'])
                 ->assertJsonMissing(['title' => 'T2']);
    }

    public function test_subdistrict_user_sees_only_own_subdistrict()
    {
        $d1 = District::create(['name' => 'D1']);
        $s1 = $d1->subdistricts()->create(['name' => 'S1']);
        $s2 = $d1->subdistricts()->create(['name' => 'S2']);

        $user = User::factory()->create([
            'district_id' => $d1->id, 
            'subdistrict_id' => $s1->id, 
            'role' => 'user'
        ]);

        $t1 = Tender::create(['title' => 'S1 Tender', 'description' => '.', 'district_id' => $d1->id, 'subdistrict_id' => $s1->id, 'status' => 'draft']);
        $t2 = Tender::create(['title' => 'S2 Tender', 'description' => '.', 'district_id' => $d1->id, 'subdistrict_id' => $s2->id, 'status' => 'draft']);
        
        $response = $this->actingAs($user)->getJson('/tenders');
        
        $response->assertJsonCount(1, 'data')
                 ->assertJsonFragment(['title' => 'S1 Tender'])
                 ->assertJsonMissing(['title' => 'S2 Tender']);
    }

    public function test_admin_sees_all()
    {
        $d1 = District::create(['name' => 'D1']);
        $t1 = Tender::create(['title' => 'T1', 'description' => '.', 'district_id' => $d1->id, 'status' => 'draft']);
        
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->getJson('/tenders');
        $response->assertJsonCount(1, 'data');
    }
}
