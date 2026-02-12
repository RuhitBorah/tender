<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tender;
use App\Models\District;

class ResetTenderData extends Command
{
    protected $signature = 'tenders:reset {--seed : Seed sample data after reset}';
    protected $description = 'Delete all tender data and optionally seed fresh sample tenders';

    public function handle()
    {
        // Confirm deletion
        if (!$this->confirm('⚠️  This will DELETE ALL existing tender data. Are you sure?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        // Delete all tenders
        $count = Tender::count();
        Tender::truncate();
        $this->info("✅ Deleted {$count} tenders.");

        // Seed sample data if requested
        if ($this->option('seed')) {
            $this->info('📊 Seeding fresh sample data...');
            $this->seedSampleTenders();
        }

        $this->info('🎉 Operation completed successfully!');
        return 0;
    }

    private function seedSampleTenders()
    {
        $districts = District::with('subdistricts')->get();

        if ($districts->isEmpty()) {
            $this->error('No districts found. Please create districts and subdistricts first.');
            return;
        }

        $departments = ['Public Works', 'Health', 'Education', 'Transport', 'Agriculture', 'Rural Development'];
        $statuses = ['draft', 'active', 'closed'];
        $tendersCreated = 0;

        foreach ($districts as $district) {
            // If district has subdistricts, create tenders for each subdistrict
            if ($district->subdistricts->isNotEmpty()) {
                foreach ($district->subdistricts as $subdistrict) {
                    // Create 2-3 tenders per subdistrict
                    for ($i = 1; $i <= rand(2, 3); $i++) {
                        $this->createTender($district, $subdistrict, $departments, $statuses);
                        $tendersCreated++;
                    }
                }
            } else {
                // District has no subdistricts, create 2 tenders at district level
                for ($i = 1; $i <= 2; $i++) {
                    $this->createTender($district, null, $departments, $statuses);
                    $tendersCreated++;
                }
            }
        }

        $this->info("✅ Created {$tendersCreated} sample tenders.");
    }

    private function createTender($district, $subdistrict, $departments, $statuses)
    {
        $locationName = $subdistrict ? $subdistrict->name : $district->name;
        $department = $departments[array_rand($departments)];
        $status = $statuses[array_rand($statuses)];
        
        // Generate unique tender number
        $lastTender = Tender::latest('id')->first();
        $nextId = $lastTender ? $lastTender->id + 1 : 1;
        $tenderNo = 'TND-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

        // Random dates
        $publishDate = now()->subDays(rand(1, 30));
        $closingDate = $publishDate->copy()->addDays(rand(15, 45));

        Tender::create([
            'tender_no' => $tenderNo,
            'title' => "{$department} Project - {$locationName}",
            'description' => "Sample tender for {$department} department in {$locationName}. This is demonstration data.",
            'department' => $department,
            'state' => 'Assam',
            'district_id' => $district->id,
            'subdistrict_id' => $subdistrict?->id,
            'publish_date' => $publishDate,
            'closing_date' => $closingDate,
            'value' => rand(100000, 5000000),
            'status' => $status,
        ]);
    }
}
