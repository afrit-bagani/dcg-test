<?php

namespace Database\Factories;

use App\Models\StudentBasicInformation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class StudentBasicInformationFactory extends Factory
{
    protected $model = StudentBasicInformation::class;

    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $surname = $this->faker->lastName();
        $gender = $this->faker->randomElement(['Male', 'Female']);

        // Realistic marks calculation
        $maxMarks = 500;
        $obtained = $this->faker->numberBetween(250, 480);
        $percentage = ($obtained / $maxMarks) * 100;

        return [
            // Identity
            'surname' => $surname,
            'first_name' => $firstName,
            'middle_name' => '',
            'full_name' => strtoupper($surname . ' ' . $firstName),
            'mother_name' => $this->faker->firstNameFemale(),
            'father_name' => $this->faker->firstNameMale(),
            'gender' => $gender,
            'dob' => $this->faker->dateTimeBetween('-22 years', '-18 years')->format('Y-m-d'),

            // IDs & Demographics
            'reg_no' => 'REG-' . date('Y') . '-' . $this->faker->unique()->numerify('####'),
            'abc_id' => 'ABC' . $this->faker->unique()->numerify('########'),
            'aadhaar_no' => $this->faker->unique()->numerify('############'),
            'nationality' => 'Indian',
            'domicile' => 'Maharashtra',
            'mobile_no' => $this->faker->unique()->numerify('9#########'),
            'email' => $this->faker->unique()->safeEmail(),
            'religion' => $this->faker->randomElement(['Hindu', 'Islam', 'Christian', 'Sikh']),
            'category' => $this->faker->randomElement(['OPEN', 'OBC', 'SC', 'ST']),
            'blood_group' => $this->faker->randomElement(['A+', 'B+', 'O+', 'AB+']),
            'marital_status' => 'Single',
            'annual_family_income' => $this->faker->randomFloat(2, 50000, 800000),
            'parent_mobile' => $this->faker->numerify('8#########'),

            // Flags (Mostly false, 5% chance of being true)
            'is_blind' => $this->faker->boolean(5),
            'is_bpl' => $this->faker->boolean(15),
            'is_minority' => $this->faker->boolean(10),
            'is_ph' => $this->faker->boolean(5),

            // Present Address
            'present_address_1' => $this->faker->streetAddress(),
            'present_city' => $this->faker->city(),
            'present_country' => 'India',
            'present_state' => 'Maharashtra',
            'present_district' => $this->faker->city(),
            'present_pin' => $this->faker->numerify('4#####'),

            // Permanent Address
            'permanent_address_1' => $this->faker->streetAddress(),
            'permanent_city' => $this->faker->city(),
            'permanent_country' => 'India',
            'permanent_state' => 'Maharashtra',
            'permanent_district' => $this->faker->city(),
            'permanent_pin' => $this->faker->numerify('4#####'),

            // Previous Education
            'admission_type' => 'Regular',
            'exam_name' => 'Higher Secondary (12th)',
            'passing_month_year' => 'March 2023',
            'board_type' => 'State Board',
            'institution_name' => $this->faker->company() . ' Junior College',
            'board_university_name' => 'Maharashtra State Board',
            'max_marks' => $maxMarks,
            'obtained_marks' => $obtained,
            'percentage' => $percentage,

            'is_active' => $this->faker->boolean(90), // 90% active
        ];
    }

    /**
     * Configure the model factory.
     * This hook runs AFTER the basic information is inserted.
     */
    public function configure()
    {
        return $this->afterCreating(function (StudentBasicInformation $student) {

            // 1. Fetch a random active course (to get course_id AND programme_id)
            $course = DB::table('course_master')->where('is_active', 1)->inRandomOrder()->first();

            // 2. Fetch a random active batch
            $batch = DB::table('batch_master')->where('is_active', 1)->inRandomOrder()->first();

            // 3. If we have master data, insert the Paper Selection child record!
            if ($course && $batch) {
                DB::table('student_paper_selection')->insert([
                    'student_id' => $student->getKey(),
                    'programme_id' => $course->programme_id,
                    'course_id' => $course->course_id,
                    'batch_id' => $batch->batch_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
