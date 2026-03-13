<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        if (! empty($search)) {
            $whereClauses[] = '(code LIKE ? OR name LIKE ?)';
            $bindings[] = "%{$search}%";
            $bindings[] = "%{$search}%";
        }

        // Status Filter
        if ($statusFilter !== null && $statusFilter !== 'all') {
            $whereClauses[] = 'is_active = ?';
            $bindings[] = $statusFilter;
        }

        $whereSql = '';
        if (count($whereClauses) > 0) {
            $whereSql = 'WHERE '.implode(' AND ', $whereClauses);
        }

        $dataBindings = array_merge($bindings, [$perPage, $offset]);

        $courses = DB::select(
            "SELECT * FROM course_master $whereSql ORDER BY course_id DESC LIMIT ? OFFSET ?",
            $dataBindings
        );
        $totalRecords = DB::selectOne("SELECT COUNT(*) as count FROM  course_master $whereSql", $bindings)->count;

        $paginator = new LengthAwarePaginator(
            $courses,
            $totalRecords,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.dashboard', [
            'courses' => $paginator,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|unique:course_master,code',
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $userId = Auth::id();
        $now = now()->toDateTimeString();

        DB::insert(
            'INSERT INTO course_master (code, name, is_active, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $request->code,
                $request->name,
                $request->is_active,
                $userId,
                $now,
                $now,
            ]
        );

        return redirect()->route('admin.course.index')->with('Course created successfully');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|unique:course_master,code,'.$id.',course_id',
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        DB::update(
            'UPDATE course_master SET code = ?, name = ?, is_active = ?, updated_at = ? WHERE course_id = ?',
            [
                $request->code,
                $request->name,
                $request->is_active,
                now()->toDateTimeString(),
                $id,
            ]
        );

        return redirect()->route('admin.course.index')->with('success', 'Course updated successfully');
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

        DB::update("UPDATE course_master SET is_active = ?, updated_at = ? WHERE course_id in ($placeholders)", $bindings);

        return redirect()->route('admin.course.index')->with('success', count($ids).'course status updated');
    }
}
