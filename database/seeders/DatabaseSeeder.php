<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Course;
use App\Models\StudentBasicInformation;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREATE 5 SPECIFIC USERS
        for ($i = 1; $i <= 5; $i++) {
            User::factory()->create([
                'name' => "Test Admin $i",
                'email' => "test{$i}@gmail.com",
                // All users get the password 'test'
                'password' => Hash::make('test'),
            ]);
        }

        // 2. CREATE THE 3 STATIC PROGRAMMES (Using DB Builder for raw speed)
        $programmes = [
            ['code' => 'UG', 'name' => 'Under Graduate'],
            ['code' => 'PG', 'name' => 'Post Graduate'],
            ['code' => 'PHD', 'name' => 'Doctor of Philosophy'],
        ];

        foreach ($programmes as $prog) {
            DB::table('programme_master')->insert([
                'code' => $prog['code'],
                'name' => $prog['name'],
                'is_active' => 1,
                'created_by' => 1, // Assigned to test1@gmail.com
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. GENERATE FACTORY DATA
        // Note: Because we used unique() in the factories, we must match the exact array sizes
        Course::factory(15)->create();
        Subject::factory(50)->create();

        // We can safely generate 50 batches because the factory logic is heavily randomized
        Batch::factory(55)->create();
        StudentBasicInformation::factory()->count(50)->create();
    }
}
