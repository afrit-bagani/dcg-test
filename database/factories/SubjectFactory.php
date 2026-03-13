<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $subjects = [
            'Thermodynamics',
            'Quantum Mechanics',
            'Ancient History',
            'Macroeconomics',
            'Human Anatomy',
            'Organic Chemistry',
            'Calculus II',
            'Creative Writing',
            'Microbiology',
            'Fluid Mechanics',
            'Business Ethics',
            'Constitutional Law',
            'Genetics',
            'Cognitive Psychology',
            'Astrophysics',
            'Data Structures',
            'Structural Engineering',
            'Graphic Design',
            'World Literature',
            'Linear Algebra',
            'Pathology',
            'Financial Management',
            'Botany',
            'Sociology',
            'Robotics',
            'Philosophy of Mind',
            'Environmental Science',
            'Journalism',
            'Statistics',
            'Geology',
            'Marketing Principles',
            'Network Security',
            'Public Administration',
            'Zoology',
            'Therapeutics',
            'Acoustics',
            'Criminology',
            'Digital Electronics',
            'Ecology',
            'Geopolitics',
            'Human Resources',
            'Immunology',
            'Kinesiology',
            'Linguistics',
            'Meteorology',
            'Nanotechnology',
            'Oceanography',
            'Pharmacology',
            'Surveying',
            'Typography',
        ];

        return [
            'code' => strtoupper($this->faker->unique()->lexify('SUB-???')),
            'name' => $this->faker->unique()->randomElement($subjects),
            'internal_full_marks' => 20.00,
            'internal_pass_marks' => 8.00,
            'theory_full_marks' => 80.00,
            'theory_pass_marks' => 32.00,
            'practical_full_marks' => 20.00,
            'practical_pass_marks' => 8.00,
            'is_active' => $this->faker->boolean(85),
            'created_by' => $this->faker->numberBetween(1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
