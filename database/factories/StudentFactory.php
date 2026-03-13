<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Programme;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'reg_no' => $this->faker->unique()->numerify('REG-####'),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'batch_id' => Batch::inRandomOrder()->value('batch_id'),
            'programme_id' => Programme::inRandomOrder()->value('programme_id'),
            'course_id' => Course::inRandomOrder()->value('course_id'),
            'is_active' => $this->faker->boolean(80),
            'created_by' => User::whereBetween('id', [1, 5])->inRandomOrder()->value('id'),
        ];
    }
}
