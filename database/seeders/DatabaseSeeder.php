<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Create 30 Districts with varying subdistricts
        $districts = [
            'Kamrup' => ['Guwahati', 'Rangia', 'Boko'],
            'Nagaon' => ['Hojai', 'Lumding', 'Nagaon Town'],
            'Sonitpur' => ['Tezpur', 'Rangapara', 'Dhekiajuli'],
            'Barpeta' => ['Bajali', 'Sarthebari', 'Pathsala'],
            'Dibrugarh' => ['Dibrugarh Town', 'Naharkatia', 'Chabua'],
            'Cachar' => ['Silchar', 'Lakhipur', 'Katigora'],
            'Jorhat' => ['Jorhat Town', 'Titabar', 'Mariani'],
            'Golaghat' => ['Golaghat Town', 'Bokakhat', 'Sarupathar'],
            'Sivasagar' => ['Sivasagar Town', 'Nazira', 'Demow'],
            'Tinsukia' => ['Tinsukia Town', 'Margherita', 'Doomdooma'],
            'Dhubri' => [], // No subdistricts
            'Kokrajhar' => [], // No subdistricts
            'Bongaigaon' => ['Bongaigaon Town', 'Abhayapuri'],
            'Goalpara' => ['Dudhnoi', 'Lakhipur'],
            'Kamrup Metro' => ['East Guwahati', 'West Guwahati'],
            'Karbi Anglong' => ['Diphu', 'Bokajan', 'Howraghat'],
            'Dima Hasao' => [], // No subdistricts
            'Karimganj' => ['Karimganj Town', 'Patharkandi'],
            'Hailakandi' => [], // No subdistricts
            'Nalbari' => ['Nalbari Town', 'Tihu', 'Barkhetri'],
            'Morigaon' => ['Morigaon Town', 'Jagiroad'],
            'Darrang' => ['Mangaldoi', 'Dalgaon', 'Sipajhar'],
            'Udalguri' => ['Udalguri Town', 'Bhergaon'],
            'Lakhimpur' => ['North Lakhimpur', 'Dhakuakhana'],
            'Dhemaji' => [], // No subdistricts
            'Charaideo' => [], // No subdistricts
            'Majuli' => [], // No subdistricts
            'Biswanath' => ['Biswanath Chariali', 'Gohpur'],
            'Hojai' => ['Hojai Town', 'Lanka'],
            'South Salmara' => [], // No subdistricts
        ];

        $districtModels = [];
        foreach ($districts as $districtName => $subdistricts) {
            $district = \App\Models\District::create(['name' => $districtName]);
            $districtModels[$districtName] = $district;

            foreach ($subdistricts as $subdistrictName) {
                $district->subdistricts()->create(['name' => $subdistrictName]);
            }
        }

        // Create some sample users for different districts
        User::factory()->create([
            'name' => 'User Kamrup',
            'email' => 'kamrup@example.com',
            'role' => 'user',
            'district_id' => $districtModels['Kamrup']->id,
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'User Dibrugarh',
            'email' => 'dibrugarh@example.com',
            'role' => 'user',
            'district_id' => $districtModels['Dibrugarh']->id,
            'password' => bcrypt('password'),
        ]);

        // Create sample tenders for various districts
        $statuses = ['draft', 'active', 'closed'];
        $departments = ['Public Works', 'Health', 'Education', 'Transport', 'Agriculture', 'Rural Development'];
        $tenderCount = 0;
        
        foreach ($districtModels as $name => $district) {
            if ($tenderCount >= 50) break; // Limit to 50 tenders total
            
            $numTenders = rand(0, 3); // 0-3 tenders per district
            for ($i = 0; $i < $numTenders; $i++) {
                $tenderCount++;
                $publishDate = now()->subDays(rand(1, 30));
                $closingDate = $publishDate->copy()->addDays(rand(15, 60));
                
                \App\Models\Tender::create([
                    'tender_no' => 'TND-' . str_pad($tenderCount, 5, '0', STR_PAD_LEFT),
                    'title' => "Tender for $name - Project " . ($i + 1),
                    'description' => "This is a sample tender for $name district",
                    'department' => $departments[array_rand($departments)],
                    'state' => 'Assam',
                    'publish_date' => $publishDate,
                    'closing_date' => $closingDate,
                    'value' => rand(100000, 10000000) / 100, // Random value between 1000 and 100000
                    'district_id' => $district->id,
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }
}
