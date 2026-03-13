<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProgrammeController extends Controller
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

        // Fetch Data
        $programmes = DB::select(
            "SELECT * FROM programme_master $whereSql ORDER BY programme_id DESC LIMIT ? OFFSET ?",
            $dataBindings
        );

        // Fetch Total Count for Pagination
        $totalRecords = DB::selectOne("SELECT COUNT(*) as count FROM programme_master $whereSql", $bindings)->count;

        $paginator = new LengthAwarePaginator(
            $programmes,
            $totalRecords,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.dashboard', [
            'programmes' => $paginator,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|unique:programme_master,code',
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $userId = Auth::id();
        $now = now()->toDateTimeString();

        DB::insert(
            'INSERT INTO programme_master (code, name, is_active, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)',
            [
                $request->code,
                $request->name,
                $request->is_active,
                $userId,
                $now,
                $now,
            ]
        );

        return redirect()->route('admin.programme.index')->with('success', 'Programme created successfully');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|unique:programme_master,code,'.$id.',programme_id',
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $updates = $request->only(['code', 'name', 'status']);

        DB::update(
            'UPDATE programme_master SET code = ?, name = ?, is_active = ?, updated_at = ? WHERE programme_id = ?',
            [
                $request->code,
                $request->name,
                $request->is_active,
                now()->toDateTimeString(),
                $id,
            ]
        );

        return redirect()->route('admin.programme.index')->with('success', 'Programme updated successfully');
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $now = now()->toDateTimeString();

        DB::update('UPDATE programme_master SET is_active = ?, updated_at = ? WHERE programme_id = ?', [$request->is_active, $now, $id]);

        return redirect()->route('admin.programme.index')->with('success', 'Programme status updated successfully');
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

        DB::update("UPDATE programme_master SET is_active = ?, updated_at = ? WHERE programme_id IN ($placeholders)", $bindings);

        return redirect()->route('admin.programme.index')->with('success', count($ids).'programmes updated');
    }
}
