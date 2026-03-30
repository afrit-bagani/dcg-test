<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreBasicInfoRequest;
use App\Http\Requests\Student\UpdateBasicInfoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        $students = DB::select("
        SELECT s.*, s.full_name as name, s.mobile_no as phone_no,
                p.code as programme_code, c.name as course_name, b.name as batch_name
        FROM student_basic_information s
        LEFT JOIN student_paper_selection sp ON s.student_id = sp.student_id
        LEFT JOIN programme_master p on sp.programme_id = p.programme_id
        LEFT JOIN course_master c ON sp.course_id = c.course_id
        LEFT JOIN batch_master b ON sp.batch_id = b.batch_id
        $whereSql
        ORDER BY s.student_id DESC
        LIMIT ? OFFSET ?
        ", $dataBindings);

        $totalRecords = DB::selectOne("
            SELECT COUNT(*) as count
            FROM student_basic_information s
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

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate(['is_active' => 'required|boolean']);

        DB::update('UPDATE student_basic_information SET is_active = ?, updated_at = NOW() WHERE student_id = ?', [
            $request->is_active,
            $id
        ]);

        return redirect()->back()->with('success', 'Student status updated!');
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

        DB::update("
            UPDATE student_basic_information
            SET is_active = ?, updated_at = ?
            WHERE student_id IN ($placeholders)
        ", $bindings);

        return redirect()->route('admin.student.index')->with('success', count($ids) . ' students updated!');
    }

    /******************************************
     * Tab 1: Basic Information
     ******************************************/

    public function createBasicInfo()
    {
        return view('admin.students.basic_info');
    }

    public function storeBasicInfo(StoreBasicInfoRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $columns = implode(', ', array_keys($validated)) . ', created_at, updated_at';
        $placeholders = implode(', ', array_fill(0, count($validated), '?')) . ', NOW(), NOW()';

        DB::insert("INSERT INTO student_basic_information ($columns) VALUES ($placeholders)", array_values($validated));

        $newStudentId = DB::getPdo()->lastInsertId();

        return redirect()->route('admin.student.paper_selection.edit', $newStudentId)->with('success', 'Basic Information saved successfully! Please complete Paper Selection.');
    }

    public function editBasicInfo(int $id): View
    {
        $student = DB::selectOne('SELECT * FROM student_basic_information WHERE student_id = ?', [$id]);
        if (!$student) abort(404);

        return view('admin.students.basic_info', ['student' => $student]);
    }

    public function updateBasicInfo(UpdateBasicInfoRequest $request, $id): RedirectResponse
    {
        $validated = $request->validated();
        $setClauses = [];
        $bindings = [];

        foreach ($validated as $column => $value) {
            $setClauses[] = "$column = ?";
            $bindings[] = $value;
        }

        $setClauses[] = "updated_at = NOW()";
        $bindings[] = $id;

        $setString = implode(', ', $setClauses);

        DB::update("UPDATE student_basic_information SET $setString WHERE student_id = ?", $bindings);

        return back()->with('success', 'Basic Information Updated Successfully!');
    }

    /******************************************
     * Tab 2: Paper Selection
     ******************************************/

    public function editPaperSelection(int $id): View
    {
        // 1. Get core identity to display the header
        $student = DB::selectOne('SELECT student_id, full_name, reg_no FROM student_basic_information WHERE student_id = ?', [$id]);
        if (!$student) abort(404);

        // 2. Get existing paper selection (if any)
        $paperSelection = DB::selectOne('SELECT * FROM student_paper_selection WHERE student_id = ?', [$id]);

        // 3. Fetch master data for dropdowns
        $programmes = DB::select('SELECT programme_id, code, name FROM programme_master WHERE is_active = 1 ORDER BY name ASC');
        $batches = DB::select('SELECT batch_id, name FROM batch_master WHERE is_active = 1 ORDER BY name ASC');

        // 4. Fetch dependent courses if a programme was already selected previously
        $courses = [];
        if ($paperSelection && $paperSelection->programme_id) {
            $courses = DB::select('SELECT course_id, name, code FROM course_master WHERE programme_id = ? AND is_active = 1', [$paperSelection->programme_id]);
        }

        return view('admin.students.paper_selection', [
            'student' => $student,
            'paperSelection' => $paperSelection,
            'programmes' => $programmes,
            'batches' => $batches,
            'courses' => $courses
        ]);
    }

    public function updatePaperSelection(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'programme_id' => 'required|integer|exists:programme_master,programme_id',
            'course_id' => 'required|integer|exists:course_master,course_id',
            'batch_id' => 'required|integer|exists:batch_master,batch_id',
        ]);

        // Enterprise UPSERT Logic: Insert if missing, Update if exists.
        $exists = DB::selectOne('SELECT id FROM student_paper_selection WHERE student_id = ?', [$id]);

        if ($exists) {
            DB::update('
                UPDATE student_paper_selection
                SET programme_id = ?, course_id = ?, batch_id = ?, updated_at = NOW()
                WHERE student_id = ?
            ', [$request->programme_id, $request->course_id, $request->batch_id, $id]);
        } else {
            DB::insert('
                INSERT INTO student_paper_selection (student_id, programme_id, course_id, batch_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, NOW(), NOW())
            ', [$id, $request->programme_id, $request->course_id, $request->batch_id]);
        }

        // We redirect back for now. Later we will redirect to Tab 3 (Upload Docs).
        return redirect()->back()->with('success', 'Academic Paper Selection saved successfully!');
    }

    /******************************************
     * Tab 3: Upload Documents
     ******************************************/

    public function editUploadDocs(int $id): View
    {
        $student = DB::selectOne('SELECT student_id, full_name, reg_no FROM student_basic_information WHERE student_id = ?', [$id]);
        if (!$student) abort(404);

        $docs = DB::selectOne('SELECT * FROM student_upload_document WHERE student_id = ?', [$id]);

        return view('admin.students.upload_docs', [
            'student' => $student,
            'docs' => $docs
        ]);
    }

    public function updateUploadDocs(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'signature' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $existingDocs = DB::selectOne('SELECT * FROM student_upload_document WHERE student_id = ?', [$id]);
        $photoPath = $existingDocs->photo_path ?? null;
        $signaturePath = $existingDocs->signature_path ?? null;

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            if ($photoPath) Storage::disk('public')->delete($photoPath);
            $photoPath = $request->file('photo')->store('student_docs/photos', 'public');
        }

        // Handle Signature Upload
        if ($request->hasFile('signature')) {
            if ($signaturePath) Storage::disk('public')->delete($signaturePath);
            $signaturePath = $request->file('signature')->store('student_docs/signatures', 'public');
        }

        // UPSERT Logic
        if ($existingDocs) {
            DB::update('UPDATE student_upload_document SET photo_path = ?, signature_path = ?, updated_at = NOW() WHERE student_id = ?', [$photoPath, $signaturePath, $id]);
        } else {
            DB::insert('INSERT INTO student_upload_document (student_id, photo_path, signature_path, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())', [$id, $photoPath, $signaturePath]);
        }

        return redirect()->route('admin.student.payment_info.edit', $id)
            ->with('success', 'Documents uploaded! Please enter payment details.');
    }

    /******************************************
     * Tab 4: Payment Information
     ******************************************/

    public function editPaymentInfo(int $id): View
    {
        $student = DB::selectOne('SELECT student_id, full_name, reg_no FROM student_basic_information WHERE student_id = ?', [$id]);
        if (!$student) abort(404);

        $payment = DB::selectOne('SELECT * FROM student_payment_information WHERE student_id = ?', [$id]);

        return view('admin.students.payment_info', [
            'student' => $student,
            'payment' => $payment
        ]);
    }

    public function updatePaymentInfo(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'transaction_id' => 'required|string|max:100|unique:student_payment_information,transaction_id,' . $id,
            'payment_date' => 'required|date',
            'payment_status' => 'required|string',
        ]);

        $exists = DB::selectOne('SELECT id FROM student_payment_information WHERE student_id = ?', [$id]);

        if ($exists) {
            DB::update('UPDATE student_payment_information SET amount = ?, transaction_id = ?, payment_date = ?, payment_status = ?, updated_at = NOW() WHERE student_id = ?',
                [$request->amount, $request->transaction_id, $request->payment_date, $request->payment_status, $id]);
        } else {
            DB::insert('INSERT INTO student_payment_information (student_id, amount, transaction_id, payment_date, payment_status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())',
                [$id, $request->amount, $request->transaction_id, $request->payment_date, $request->payment_status]);
        }

        // FINAL REDIRECT: Registration is complete! Send them back to the Directory.
        return redirect()->route('admin.student.index')
            ->with('success', 'Registration completely finalized for ' . $request->transaction_id . '!');
    }
}
