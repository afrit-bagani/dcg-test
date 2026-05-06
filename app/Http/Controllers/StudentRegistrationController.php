<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentRegistrationController extends Controller
{
    /**
     * Show the public student registration form.
     */
    public function create()
    {
        $programmes = DB::select('SELECT programme_id, code, name FROM programme_master WHERE is_active = 1 ORDER BY name ASC');
        $batches = DB::select('SELECT batch_id, name FROM batch_master WHERE is_active = 1 ORDER BY name ASC');
        $courses = DB::select('SELECT course_id, name, programme_id FROM course_master WHERE is_active = 1 ORDER BY name ASC');

        return view('student.register', compact('programmes', 'batches', 'courses'));
    }

    /**
     * Handle the submission of the student registration form.
     */
    public function store(Request $request)
    {
        // 1. Data Validation
        $validated = $request->validate([
            // Basic Information
            'surname' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'full_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:100',
            'father_name' => 'required|string|max:100',
            'gender' => 'required|string|in:Male,Female,Other',
            'dob' => 'required|date',
            'abc_id' => 'nullable|string|max:50',
            'aadhaar_no' => 'required|string|max:20|unique:student_basic_information,aadhaar_no',
            'nationality' => 'required|string|max:50',
            'domicile' => 'required|string|max:100',
            'mobile_no' => 'required|string|max:20',
            'email' => 'required|email|max:150|unique:student_basic_information,email',
            'religion' => 'nullable|string|max:50',
            'category' => 'required|string|max:50',
            'caste' => 'nullable|string|max:100',
            'blood_group' => 'nullable|string|max:10',
            'marital_status' => 'required|string|max:20',
            'annual_family_income' => 'nullable|numeric',
            'parent_mobile' => 'nullable|string|max:20',

            // Flags
            'is_blind' => 'required|boolean',
            'is_bpl' => 'required|boolean',
            'is_minority' => 'required|boolean',
            'is_ph' => 'required|boolean',

            // Present Address
            'present_address_1' => 'required|string|max:255',
            'present_address_2' => 'nullable|string|max:255',
            'present_city' => 'required|string|max:100',
            'present_country' => 'required|string|max:100',
            'present_state' => 'required|string|max:100',
            'present_district' => 'required|string|max:100',
            'present_pin' => 'required|string|max:20',

            // Permanent Address
            'permanent_address_1' => 'required|string|max:255',
            'permanent_address_2' => 'nullable|string|max:255',
            'permanent_city' => 'required|string|max:100',
            'permanent_country' => 'required|string|max:100',
            'permanent_state' => 'required|string|max:100',
            'permanent_district' => 'required|string|max:100',
            'permanent_pin' => 'required|string|max:20',

            // Exam Details
            'admission_type' => 'required|string|max:50',
            'exam_name' => 'required|string|max:150',
            'passing_month_year' => 'required|string|max:50',
            'board_type' => 'required|string|max:100',
            'institution_name' => 'required|string|max:255',
            'board_university_name' => 'required|string|max:255',
            'division_class' => 'nullable|string|max:50',
            'max_marks' => 'nullable|numeric',
            'obtained_marks' => 'nullable|numeric',
            'grade_cgpa' => 'nullable|string|max:20',
            'percentage' => 'nullable|numeric',

            // Paper Selection
            'programme_id' => 'required|integer|exists:programme_master,programme_id',
            'course_id' => 'required|integer|exists:course_master,course_id',
            'batch_id' => 'required|integer|exists:batch_master,batch_id',

            // Documents
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            // Payment
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string|max:100|unique:student_payment_information,transaction_id',
            'payment_date' => 'required|date',
            'payment_status' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // 2. Generate Registration Number
            $batch = DB::selectOne('SELECT name FROM batch_master WHERE batch_id = ?', [$validated['batch_id']]);
            $year = date('Y');
            if ($batch && preg_match('/(20\d{2})/', $batch->name, $matches)) {
                $year = $matches[1];
            }

            $prefix = $year;
            $lastStudent = DB::selectOne("
                SELECT reg_no 
                FROM student_basic_information 
                WHERE reg_no LIKE '{$prefix}%' 
                ORDER BY reg_no DESC 
                LIMIT 1
            ");

            if ($lastStudent && preg_match('/(\d+)$/', $lastStudent->reg_no, $matches)) {
                $sequence = intval(substr($lastStudent->reg_no, 4)) + 1;
            } else {
                $sequence = 1;
            }

            $regNo = $prefix.str_pad($sequence, 5, '0', STR_PAD_LEFT); // e.g. 202200001

            // 3. Insert into student_basic_information
            $basicInfoParams = [
                $regNo,
                $validated['surname'],
                $validated['first_name'],
                $validated['middle_name'],
                $validated['full_name'],
                $validated['mother_name'],
                $validated['father_name'],
                $validated['gender'],
                $validated['dob'],
                $validated['abc_id'] ?? null,
                $validated['aadhaar_no'],
                $validated['nationality'],
                $validated['domicile'],
                $validated['mobile_no'],
                $validated['email'],
                $validated['religion'] ?? null,
                $validated['category'],
                $validated['caste'] ?? null,
                $validated['blood_group'] ?? null,
                $validated['marital_status'],
                $validated['annual_family_income'] ?? null,
                $validated['parent_mobile'] ?? null,
                $validated['is_blind'],
                $validated['is_bpl'],
                $validated['is_minority'],
                $validated['is_ph'],
                $validated['present_address_1'],
                $validated['present_address_2'] ?? null,
                $validated['present_city'],
                $validated['present_country'],
                $validated['present_state'],
                $validated['present_district'],
                $validated['present_pin'],
                $validated['permanent_address_1'],
                $validated['permanent_address_2'] ?? null,
                $validated['permanent_city'],
                $validated['permanent_country'],
                $validated['permanent_state'],
                $validated['permanent_district'],
                $validated['permanent_pin'],
                $validated['admission_type'],
                $validated['exam_name'],
                $validated['passing_month_year'],
                $validated['board_type'],
                $validated['institution_name'],
                $validated['board_university_name'],
                $validated['division_class'] ?? null,
                $validated['max_marks'] ?? null,
                $validated['obtained_marks'] ?? null,
                $validated['grade_cgpa'] ?? null,
                $validated['percentage'] ?? null,
                1, // is_active
            ];

            $placeholders = implode(', ', array_fill(0, count($basicInfoParams), '?'));

            DB::insert("
                INSERT INTO student_basic_information (
                    reg_no, surname, first_name, middle_name, full_name, mother_name, father_name,
                    gender, dob, abc_id, aadhaar_no, nationality, domicile, mobile_no, email,
                    religion, category, caste, blood_group, marital_status, annual_family_income,
                    parent_mobile, is_blind, is_bpl, is_minority, is_ph, present_address_1,
                    present_address_2, present_city, present_country, present_state, present_district,
                    present_pin, permanent_address_1, permanent_address_2, permanent_city, permanent_country,
                    permanent_state, permanent_district, permanent_pin, admission_type, exam_name,
                    passing_month_year, board_type, institution_name, board_university_name,
                    division_class, max_marks, obtained_marks, grade_cgpa, percentage, is_active,
                    created_at, updated_at
                ) VALUES ($placeholders, NOW(), NOW())
            ", $basicInfoParams);

            $studentId = DB::getPdo()->lastInsertId();

            // 4. Insert into student_paper_selection
            DB::insert('
                INSERT INTO student_paper_selection (
                    student_id, programme_id, course_id, batch_id, created_at, updated_at
                ) VALUES (?, ?, ?, ?, NOW(), NOW())
            ', [$studentId, $validated['programme_id'], $validated['course_id'], $validated['batch_id']]);

            // 5. Insert into student_upload_document
            $photoPath = null;
            $signaturePath = null;

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('student_docs/photos', 'public');
            }
            if ($request->hasFile('signature')) {
                $signaturePath = $request->file('signature')->store('student_docs/signatures', 'public');
            }

            DB::insert('
                INSERT INTO student_upload_document (
                    student_id, photo_path, signature_path, created_at, updated_at
                ) VALUES (?, ?, ?, NOW(), NOW())
            ', [$studentId, $photoPath, $signaturePath]);

            // 6. Insert into student_payment_information
            DB::insert('
                INSERT INTO student_payment_information (
                    student_id, amount, transaction_id, payment_date, payment_status, created_at, updated_at
                ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())
            ', [
                $studentId,
                $validated['amount'],
                $validated['transaction_id'],
                $validated['payment_date'],
                $validated['payment_status'],
            ]);

            DB::commit();

            return redirect()->route('student.register.create')
                ->with('success', 'Registration submitted successfully! Your Registration No is: '.$regNo);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student Registration Error: '.$e->getMessage());

            // Clean up uploaded files if DB insert failed
            if (isset($photoPath) && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            if (isset($signaturePath) && Storage::disk('public')->exists($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }

            return back()->withInput()->with('error', 'An error occurred during registration. Please try again.');
        }
    }
}
