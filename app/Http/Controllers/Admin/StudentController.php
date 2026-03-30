<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Personal\UpdatePersonalRequest;
use App\Http\Requests\Student\StoreStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $search = $request->query('search');
        $statusFilter = $request->query('status');

        $whereClauses = [];
        $bindings = [];

        // Search by Name, Email, Phone, or Registration Number
        if (!empty($search)) {
            $whereClauses[] = '(s.name LIKE ? OR s.email LIKE ? OR s.phone_no LIKE ? OR s.reg_no LIKE ?)';
            $bindings[] = "%{$search}%";
            $bindings[] = "%{$search}%";
            $bindings[] = "%{$search}%";
            $bindings[] = "%{$search}%";
        }

        // Filter by Status (is_active)
        if ($statusFilter !== null && $statusFilter !== 'all') {
            $whereClauses[] = 's.is_active = ?';
            $bindings[] = $statusFilter;
        }

        $whereSql = '';
        if (count($whereClauses) > 0) {
            $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
        }

        $dataBindings = array_merge($bindings, [$perPage, $offset]);

        // Left Joins ensure we still see the student even if a Programme or Batch gets deleted
        $students = DB::select("
            SELECT s.*,
                   b.name as batch_name,
                   p.code as programme_code,
                   c.name as course_name
            FROM student_registrations s
            LEFT JOIN batch_master b ON s.batch_id = b.batch_id
            LEFT JOIN programme_master p ON s.programme_id = p.programme_id
            LEFT JOIN course_master c ON s.course_id = c.course_id
            $whereSql
            ORDER BY s.student_id DESC
            LIMIT ? OFFSET ?
        ", $dataBindings);

        $totalRecords = DB::selectOne("
            SELECT COUNT(*) as count
            FROM student_registrations s
            $whereSql
        ", $bindings)->count;

        $paginator = new LengthAwarePaginator(
            $students,
            $totalRecords,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.students.index', [
            'students' => $paginator,
        ]);
    }

    public function create()
    {
        $programmes = DB::select('SELECT programme_id, code, name FROM programme_master WHERE is_active = 1 ORDER BY name ASC');
        $batches = DB::select('SELECT batch_id, name FROM batch_master WHERE is_active = 1 ORDER BY name ASC');

        return view('admin.students.create', [
            'programmes' => $programmes,
            'batches' => $batches
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $userId = Auth::id();
        $now = now()->toDateTimeString();

        DB::insert('INSERT INTO student_registrations
                   (reg_no, name, phone_no, email, batch_id, programme_id, course_id, is_active, created_by, created_at, updated_at)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ', [
                $request->reg_no,
                $request->name,
                $request->phone_no,
                $request->email,
                $request->batch_id,
                $request->programme_id,
                $request->course_id,
                1,
                $userId,
                $now,
                $now,
            ]
        );

        $newStudentId = DB::getPdo()->lastInsertId();

        return redirect()->route('admin.student.edit.education', $newStudentId)->with('success', 'Personal info saved. Please assign academic details');
    }

    /******************************************
     * Workspace Tab 1: Personal Info
     ******************************************/

    public function editPersonal(int $id): View
    {
        $student = DB::selectOne('SELECT * FROM student_registrations WHERE student_id = ?', [$id]);

        if (!$student) abort(404);

        return view('admin.students.personal', ['student' => $student]);
    }

    public function updatePersonal(UpdatePersonalRequest $request, int $id): RedirectResponse
    {
        DB::update('
            UPDATE student_registrations
            SET reg_no = ?, name = ?, email = ?, phone_no = ?, updated_at = NOW()
            WHERE student_id = ?
        ', [$request->reg_no, $request->name, $request->email, $request->phone_no, $id]);

        return redirect()->back()->with('success', 'Personal information updated.');
    }

    /******************************************
     * Tab 2: Education
     ******************************************/

    public function editEducation(int $id): View
    {
        $student = DB::selectOne('SELECT * FROM student_registrations WHERE student_id = ?', [$id]);

        if (!$student) abort(404);

        $programmes = DB::select('SELECT programme_id, code, name FROM programme_master WHERE is_active = 1 ORDER BY name ASC');
        $batches = DB::select('SELECT batch_id, name FROM batch_master WHERE is_active = 1 ORDER BY name ASC');

        // We will fetch courses dynamically via JS, but we need the current one if it exists
        $courses = [];
        if ($student->programme_id) {
            $courses = DB::select('SELECT course_id, name, code FROM course_master WHERE programme_id = ? AND is_active = 1', [$student->programme_id]);
        }

        return view('admin.students.education', [
            'student' => $student,
            'programmes' => $programmes,
            'batches' => $batches,
            'courses' => $courses
        ]);
    }

    public function updateEducation(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'programme_id' => 'required|integer|exists:programme_master,programme_id',
            'course_id' => 'required|integer|exists:course_master,course_id',
            'batch_id' => 'required|integer|exists:batch_master,batch_id',
        ]);

        DB::update('
            UPDATE student_registrations
            SET programme_id = ?, course_id = ?, batch_id = ?, updated_at = NOW()
            WHERE student_id = ?
        ', [$request->programme_id, $request->course_id, $request->batch_id, $id]);

        return redirect()->back()->with('success', 'Academic details updated.');
    }

//    public function update(UpdateStudentRequest $request, int $id): RedirectResponse
//    {
//        $updates = $request->validated();
//
//        if (empty($updates)) {
//            return back();
//        }
//
//        $setClauses = [];
//        $bindings = [];
//
//        foreach ($updates as $column => $value) {
//            $setClauses[] = "$column = ?";
//            $bindings[] = $value;
//        }
//
//        $setClauses[] = 'updated_at = ?';
//        $bindings[] = now()->toDateTimeString();
//        $bindings[] = $id;
//
//        DB::update(
//            'UPDATE student_registrations SET ' . implode(', ', $setClauses) . ' WHERE student_id = ?',
//            $bindings
//        );
//
//        return redirect()->route('admin.student.index')->with('success', 'Student updated successfully!');
//    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate(['is_active' => 'required|boolean']);

        DB::update(
            'UPDATE student_registrations SET is_active = ?, updated_at = ? WHERE student_id = ?',
            [$request->is_active, now()->toDateTimeString(), $id]
        );

        return redirect()->route('admin.student.index')->with('success', 'student status updated!');
    }

    public function bulkStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'bulk_is_active' => 'required|boolean',
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer',
        ]);

        $ids = $request->selected_ids;
        $isActive = $request->bulk_is_active;
        $now = now()->toDateTimeString();

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $bindings = array_merge([$isActive, $now], $ids);

        DB::update(
            "UPDATE student_registrations SET is_active = ?, updated_at = ? WHERE student_id IN ($placeholders)",
            $bindings
        );

        return redirect()->route('admin.student.index')->with('success', count($ids) . ' studentes updated!');
    }


}
