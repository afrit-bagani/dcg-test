<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        $courses = [
            'B.Tech Mechanical Engineering',
            'B.Sc Physics',
            'B.A. English Literature',
            'Bachelor of Medicine (MBBS)',
            'B.Com Accounting',
            'B.Sc Nursing',
            'B.A. Political Science',
            'B.Tech Civil Engineering',
            'B.Sc Agriculture',
            'B.A. Economics',
            'Bachelor of Architecture',
            'B.Sc Biotechnology',
            'B.A. Psychology',
            'Bachelor of Fine Arts',
            'B.Tech Electrical'
        ];

        $courseName = $this->faker->unique()->randomElement($courses);

        $programmeId = DB::table('programme_master')
            ->where('is_active', 1)
            ->inRandomOrder()
            ->value('programme_id');

        return [
            'programme_id' => $programmeId,
            'code' => 'CRS-' . $this->faker->unique()->numerify('####'),
            'name' => $courseName,
            'is_active' => $this->faker->boolean(90),
            'created_by' => $this->faker->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
