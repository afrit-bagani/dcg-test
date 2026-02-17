<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    /**
     * Display the list with Search functionality.
     */
    public function index(Request $request)
    {
        $query = Batch::query();

        // Simple Search Logic
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%");
        }

        // Pagination (10 per page)
        $batches = $query->orderBy('batch_id', 'desc')->paginate(10);

        return view('batches.index', compact('batches'));
    }

    /**
     * Store a new batch.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:batch_master,code',
            'name' => 'required|string|max:255',
        ]);

        Batch::create([
            'code' => $request->code,
            'name' => $request->name,
            'status' => 'Active',
        ]);

        return redirect()->route('batches.index')->with('status', 'Batch created successfully!');
    }

    /**
     * Handle the Bulk Action (Update Status).
     */
    public function bulkAction(Request $request)
    {
        // 1. Validate: Must select an action and at least one checkbox
        $request->validate([
            'action' => 'required|in:Active,Inactive,Delete',
            'selected_ids' => 'required|array',
            'selected_ids.*' => 'exists:batch_master,batch_id', // Security check
        ]);

        $action = $request->action;
        $ids = $request->selected_ids;

        if ($action === 'Delete') {
            Batch::destroy($ids);
            $message = 'Selected batches deleted successfully.';
        } else {
            // Update status to 'Active' or 'Inactive'
            Batch::whereIn('batch_id', $ids)->update(['status' => $action]);
            $message = "Selected batches updated to {$action}.";
        }

        return redirect()->route('batches.index')->with('status', $message);
    }

    /**
     * Update the status of a single batch.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Active,Inactive',
        ]);

        $batch = Batch::findOrFail($id);
        $batch->status = $request->status;
        $batch->save();

        return redirect()->route('batches.index')
            ->with('status', 'Batch status updated successfully!');
    }
}
