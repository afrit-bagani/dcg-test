<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = 10;
        $currentPage = $request->query('page', 1);
        $offset = ($currentPage - 1) * $perPage;

        $search = $request->query('search');
        $statusFilter = $request->query('status');

        $whereClauses = [];
        $bindings = [];

        // Search Filter
        if (!empty($search)) {
            $whereClauses[] = '(s.code LIKE ? OR s.name LIKE ?)';
            $bindings[] = "%{$search}%";
            $bindings[] = "%{$search}%";
        }

        // Status Filter
        if ($statusFilter !== null && $statusFilter !== 'all') {
            $whereClauses[] = 's.is_active = ?';
            $bindings[] = $statusFilter;
        }

        $whereSql = '';
        if (count($whereClauses) > 0) {
            $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
        }

        $dataBindings = array_merge($bindings, [$perPage, $offset]);

        $subjects = DB::select(
            "SELECT s.*, c.name as course_name, p.name as programme_name
                    FROM subject_master as s INNER JOIN course_master as c ON s.course_id = c.course_id INNER JOIN programme_master as p ON c.programme_id = p.programme_id
                    $whereSql
                    ORDER BY s.subject_id DESC
                    LIMIT ? OFFSET ?",
            $dataBindings
        );

        $totalRecords = DB::selectOne(
            "SELECT COUNT(*) as count
                   FROM subject_master as s INNER JOIN course_master as c ON s.course_id = c.course_id INNER JOIN programme_master as p ON c.programme_id = p.programme_id
                   $whereSql", $bindings
        )->count;

        $paginator = new LengthAwarePaginator(
            $subjects,
            $totalRecords,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $programmes = DB::select(
            'SELECT programme_id, name
                    FROM programme_master
                    WHERE is_active = 1
                    ORDER BY name ASC'
        );

        $courses = DB::select('
        SELECT course_id, name
        FROM course_master
        WHERE is_active = 1
        ORDER BY name ASC'
        );


        return view('admin.dashboard', [
            'subjects' => $paginator,
            'courses' => $courses,
            'programmes' => $programmes,
        ]);
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $userId = Auth::id();
        $now = now()->toDateTimeString();

        DB::insert(
            'INSERT INTO subject_master (
                            programme_id,
                            course_id,
                            code,
                            name,
                            is_active,
                            internal_full_marks,
                            internal_pass_marks,
                            theory_full_marks,
                            theory_pass_marks,
                            practical_full_marks,
                            practical_pass_marks,
                            created_by,
                            created_at,
                            updated_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->programme_id,
                $request->course_id,
                $request->code,
                $request->name,
                $request->is_active,
                (float)$request->internal_full_marks,
                (float)$request->internal_pass_marks,
                (float)$request->theory_full_marks,
                (float)$request->theory_pass_marks,
                (float)$request->practical_full_marks,
                (float)$request->practical_pass_marks,
                $userId,
                $now,
                $now,
            ]
        );

        return redirect()->route('admin.subject.index')->with('success', 'Subject created successfully');
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate(['is_active' => 'required|boolean']);

        DB::update(
            'UPDATE subject_master SET is_active = ?, updated_at = ? WHERE subject_id = ?',
            [$request->is_active, now()->toDateTimeString(), $id]
        );

        return redirect()->route('admin.subject.index')->with('success', 'Status updated');
    }

    public function update(UpdateSubjectRequest $request, int $id): RedirectResponse
    {
        $updates = $request->only([
            'programme_id',
            'course_id',
            'code',
            'name',
            'is_active',
            'internal_full_marks',
            'internal_pass_marks',
            'theory_full_marks',
            'theory_pass_marks',
            'practical_full_marks',
            'practical_pass_marks',
        ]);

        if (empty($updates)) {
            return back();
        }

        $setClauses = [];
        $bindings = [];

        foreach ($updates as $column => $value) {
            $setClauses[] = "$column = ?";
            $bindings[] = $value;
        }

        $setClauses[] = 'updated_at = ?';
        $bindings[] = now()->toDateTimeString();
        $bindings[] = $id;

        DB::update(
            'UPDATE subject_master SET ' . implode(', ', $setClauses) . ' WHERE subject_id = ?',
            $bindings
        );

        return redirect()->route('admin.subject.index')->with('success', 'Subject updated successfullly');
    }

    public function bulkStatus(Request $request): RedirectResponse
    {
        $request->validate([
            'bulk_is_active' => 'required|boolean',
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer',
        ]);

        $isActive = $request->bulk_is_active;
        $now = now()->toDateTimeString();
        $ids = $request->selected_ids;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $bindings = array_merge([$isActive, $now], $ids);

        DB::update(
            "UPDATE subject_master SET is_active = ?, updated_at = ? WHERE subject_id IN ($placeholders)",
            $bindings
        );

        return redirect()->route('admin.subject.index')->with('success', count($ids) . 'subjects updated');
    }
}
