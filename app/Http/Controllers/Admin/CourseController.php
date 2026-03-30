<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Http\Requests\UpdateBulkStatusRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseController extends Controller
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
            $whereClauses[] = '(c.code LIKE ? OR c.name LIKE ?)';
            $bindings[] = "%{$search}%";
            $bindings[] = "%{$search}%";
        }

        // Status Filter
        if ($statusFilter !== null && $statusFilter !== 'all') {
            $whereClauses[] = 'c.is_active = ?';
            $bindings[] = $statusFilter;
        }

        $whereSql = '';
        if (count($whereClauses) > 0) {
            $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
        }

        $dataBindings = array_merge($bindings, [$perPage, $offset]);

        $courses = DB::select(
            "SELECT c.*, p.name as programme_name
             FROM course_master as c
             INNER JOIN programme_master as p ON c.programme_id = p.programme_id
             $whereSql
             ORDER BY c.course_id DESC
             LIMIT ? OFFSET ?",
            $dataBindings
        );
        $totalRecords = DB::selectOne(
            "SELECT COUNT(*) as count
              FROM course_master as c
              INNER JOIN programme_master as p ON c.programme_id = p.programme_id
              $whereSql", $bindings
        )->count;

        $paginator = new LengthAwarePaginator(
            $courses,
            $totalRecords,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $programmes = DB::select('
        SELECT programme_id, name
        FROM programme_master
        WHERE is_active = 1
        ORDER BY name ASC
    ');

        return view('admin.dashboard', [
            'courses' => $paginator,
            'programmes' => $programmes,
        ]);
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $userId = Auth::id();
        $now = now()->toDateTimeString();

        DB::insert(
            'INSERT INTO course_master (code, name, programme_id, is_active, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $request->code,
                $request->name,
                $request->programme_id,
                $request->is_active,
                $userId,
                $now,
                $now,
            ]
        );

        return redirect()->route('admin.course.index')->with('Course created successfully');
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate(['is_active' => 'required|boolean']);

        DB::update(
            'UPDATE course_master SET is_active = ?, updated_at = ? WHERE course_id = ?',
            [$request->is_active, now()->toDateTimeString(), $id]
        );

        return redirect()->route('admin.course.index')->with('success', 'Course status updated');
    }

    public function update(UpdateCourseRequest $request, int $id): RedirectResponse
    {
        DB::update(
            'UPDATE course_master SET programme_id = ?, code = ?, name = ?, is_active = ?, updated_at = ? WHERE course_id = ?',
            [
                $request->programme_id,
                $request->code,
                $request->name,
                $request->is_active,
                now()->toDateTimeString(),
                $id,
            ]
        );

        return redirect()->route('admin.course.index')->with('success', 'Course updated successfully');
    }

    public function bulkStatus(UpdateBulkStatusRequest $request): RedirectResponse
    {
        $isActive = $request->bulk_is_active;
        $now = now()->toDateTimeString();
        $ids = $request->selected_ids;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $bindings = array_merge([$isActive, $now], $ids);

        DB::update("UPDATE course_master SET is_active = ?, updated_at = ? WHERE course_id in ($placeholders)", $bindings);

        return redirect()->route('admin.course.index')->with('success', count($ids) . 'course status updated');
    }

    public function getCoursesByProgramme(int $programme_id)
    {
        $courses = DB::select('
        SELECT course_id, name
        FROM course_master
        WHERE programme_id = ? AND is_active = 1
        ORDER BY name ASC
        ', [$programme_id]);

        return response()->json($courses);
    }
}
