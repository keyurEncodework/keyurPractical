<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::transaction(function (){
            $this->command->info('Creating users with diverse data for testing');

            $this->createSearchTestUsers();
            $this->createAgeRangeUsers();
            $this->createCitySpecificUsers();
            $this->createRandomUsers();

            $this->command->info('Seeding completed! created'. User::count() . 'users with its addresses');

        });
    }

    private function createSearchTestUsers(){
        $testUsers = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john.doe@test.com'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane.smith@test.com'],
            ['first_name' => 'Michael', 'last_name' => 'Johnson', 'email' => 'michael.j@test.com'],
            ['first_name' => 'Sarah', 'last_name' => 'Williams', 'email' => 'sarah.w@test.com'],
            ['first_name' => 'David', 'last_name' => 'Brown', 'email' => 'david.brown@test.com'],
            ['first_name' => 'Jennifer', 'last_name' => 'Davis', 'email' => 'jennifer.d@test.com'],
            ['first_name' => 'Robert', 'last_name' => 'Miller', 'email' => 'robert.miller@test.com'],
            ['first_name' => 'Lisa', 'last_name' => 'Wilson', 'email' => 'lisa.wilson@test.com'],
            ['first_name' => 'James', 'last_name' => 'Moore', 'email' => 'james.moore@test.com'],
            ['first_name' => 'Mary', 'last_name' => 'Taylor', 'email' => 'mary.taylor@test.com'],
        ];

        foreach($testUsers as $userData){
            $user = User::factory()->create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'mobile_number' => rand(7000000000, 9999999999),
            ]);

            $addressCount = rand(1, 3);
            Address::factory($addressCount)->create(['user_id' => $user->id]);
        }

        $this->command->info('✓ Created 10 users with specific names for search testing');
    }

    private function createAgeRangeUsers(){
        User::factory(5)->withAge(18,25)->create()->each(function ($user){
            Address::factory(rand(1,2))->create(['user_id' => $user->id]);
        });

        User::factory(8)->withAge(26,35)->create()->each(function ($user){
            Address::factory(rand(1,3))->create(['user_id' => $user->id]);
        });

        User::factory(7)->withAge(36,50)->create()->each(function ($user){
            Address::factory(rand(1,2))->create(['user_id' => $user->id]);
        });

        User::factory(5)->withAge(51,65)->create()->each(function ($user){
            Address::factory(rand(1,2))->create(['user_id' => $user->id]);
        });

        $this->command->info('✓ Created 25 users with specific age ranges');
    }

    private function createCitySpecificUsers(){
        $cityData = [
            ['city' => 'Mumbai', 'state' => 'Maharashtra', 'count' => 8],
            ['city' => 'Delhi', 'state' => 'Delhi', 'count' => 6],
            ['city' => 'Bangalore', 'state' => 'Karnataka', 'count' => 5],
            ['city' => 'Ahmedabad', 'state' => 'Gujarat', 'count' => 4],
            ['city' => 'Chennai', 'state' => 'Tamil Nadu', 'count' => 3],
            ['city' => 'Pune', 'state' => 'Maharashtra', 'count' => 4],
        ];

        foreach($cityData as $cityInfo){
            User::factory($cityInfo['count'])->create()->each(function ($user) use ($cityInfo){
                Address::factory()->forCity($cityInfo['city'], $cityInfo['state'])->create([
                    'user_id' => $user->id,
                ]);
    
                if(rand(1,100) > 60){
                    Address::factory(rand(1,2))->create(['user_id' => $user->id]);
                }
            });
        }

         $this->command->info('✓ Created 30 users from specific cities');
    }

    private function createRandomUsers(){
        $currentUsersCount = User::count();
        $targetCount = 75;
        $remainingCount = max(0, $targetCount - $currentUsersCount);

        if($remainingCount > 0){
            User::factory($remainingCount)->create()->each(function ($user){
                $addressCount = rand(1,4);
                Address::factory($addressCount)->create(['user_id' => $user->id]);
            });

             $this->command->info("✓ Created {$remainingCount} additional random users");
        }
    }
}

class TestScenariosSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Creating specific test scenarios...');

        // Scenario 1: Users with common names for partial search testing
        $commonNames = ['Kumar', 'Singh', 'Sharma', 'Patel', 'Gupta'];
        foreach ($commonNames as $lastName) {
            User::factory(2)->create(['last_name' => $lastName])->each(function ($user) {
                Address::factory(rand(1, 2))->create(['user_id' => $user->id]);
            });
        }

        // Scenario 2: Users with email patterns for email search testing
        $emailDomains = ['gmail.com', 'yahoo.com', 'company.com', 'tech.in'];
        foreach ($emailDomains as $domain) {
            User::factory(2)->create([
                'email' => fake()->userName() . '@' . $domain
            ])->each(function ($user) {
                Address::factory()->create(['user_id' => $user->id]);
            });
        }

        // Scenario 3: Users with multiple addresses in different cities
        User::factory(3)->create()->each(function ($user) {
            Address::factory()->forCity('Mumbai', 'Maharashtra')->home()->create(['user_id' => $user->id]);
            Address::factory()->forCity('Delhi', 'Delhi')->office()->create(['user_id' => $user->id]);
        });

        $this->command->info('✓ Created additional test scenarios');
    }
}
