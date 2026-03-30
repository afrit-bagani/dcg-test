<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Batch Management</h2>
    <button onclick="openCreateModal()"
        class="bg-blue-600 text-white px-5 py-2.5 rounded shadow hover:bg-blue-700 transition font-bold flex items-center">
        <i class="fas fa-plus mr-2"></i> Create Batch
    </button>
</div>

{{-- Form for filter name or code  --}}
<form method="GET" action="{{ route('admin.batch.index') }}"
    class="mb-4 bg-white p-4 rounded shadow-sm border flex gap-4 items-end">

    <div class="flex-1">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Search Code /
            Name</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by batch code or name"
            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
    </div>

    <div class="w-48">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Status</label>
        <select name="status"
            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses
            </option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Only</option>
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-blue-700 transition">
            Filter
        </button>
        <a href="{{ route('admin.batch.index') }}"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold shadow hover:bg-gray-300 transition">Clear</a>
    </div>
</form>

{{-- Form for update bulk id  --}}
<form action="{{ route('admin.batch.bulkStatus') }}" method="POST" id="bulkForm">
    @csrf

    <div class="flex justify-end items-center mb-4 gap-3 bg-white p-3 rounded shadow-sm border">
        <span class="text-sm font-bold text-gray-600 uppercase tracking-wide">Action:</span>
        <select name="bulk_is_active" required
            class="border-gray-300 border p-2 rounded text-sm outline-none focus:ring-2 focus:ring-blue-500">
            <option value="" disabled selected>Select Status...</option>
            <option value="1">Set Active</option>
            <option value="0">Set Inactive</option>
        </select>
        <button type="submit"
            class="bg-slate-800 text-white px-4 py-2 rounded text-sm hover:bg-slate-900 shadow transition">
            Submit
        </button>
    </div>

    <div class="bg-white rounded shadow-md border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-500 border-b border-gray-200 text-gray-900">
                <tr>
                    <th class="py-3 px-4 w-10 text-center">
                        <input type="checkbox" id="selectAll"
                            class="h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                    </th>
                    <th class="py-3 px-2 w-20 font-bold uppercase tracking-wider text-xs">Action</th>
                    <th class="py-3 px-2 w-16 font-bold uppercase tracking-wider text-xs text-center">Sl No</th>
                    <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Code</th>
                    <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Name</th>
                    <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($batches as $index => $batch)
                    <tr
                        class="transition-all duration-300 ease-in-out hover:bg-slate-200 transform hover:shadow-lg hover:z-10 relative bg-white">

                        <td class="py-3 px-4 text-center">
                            <input type="checkbox" name="selected_ids[]" value="{{ $batch->batch_id }}"
                                class="rowCheckbox h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                        </td>

                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <button type="button"
                                    onclick="openStatusModal({{ $batch->batch_id }}, {{ $batch->is_active }})"
                                    class="text-amber-500 hover:text-amber-700 transition" title="Update Status">
                                    <i class="fas fa-sync-alt text-base"></i>
                                </button>
                                <button type="button" onclick='openEditModal(@json($batch))'
                                    class="text-blue-500 hover:text-blue-700 transition" title="Edit Batch">
                                    <i class="fas fa-edit text-base"></i>
                                </button>
                            </div>
                        </td>

                        <td class="py-3 px-2 font-mono text-gray-500 text-center">
                            {{ ($batches->currentPage() - 1) * $batches->perPage() + $loop->iteration }}
                        </td>

                        <td class="py-3 px-4 font-bold text-gray-800">{{ $batch->code }}</td>

                        <td class="py-3 px-4 text-gray-600">{{ $batch->name }}</td>

                        <td class="py-3 px-4">
                            <span
                                class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $batch->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $batch->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $batches->links() }}
    </div>
</form>

{{-- -------------------------
Create Modal
--------------------------- --}}

<div id="createModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60  items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-extrabold text-gray-800">Create New Batch</h3>
            <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.batch.store') }}" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Batch Code <span
                        class="text-red-500">*</span></label>
                <input type="text" name="code" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter batch code">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Batch Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="name" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Enter batch name">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Initial Status</label>
                <select name="is_active" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeCreateModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700 transition">
                    Create Batch
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ------------------------
Edit Modal
------------------------ --}}

<div id="editModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60  items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-extrabold text-gray-800">Edit Batch</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="editBatchForm" method="POST" action="" class="p-6 space-y-5">
            @csrf
            @method('PATCH')
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Batch Code <span
                        class="text-red-500">*</span></label>
                <input type="text" name="code" id="edit_code" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Batch Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit_name" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="is_active" id="edit_is_active" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700 transition">Update
                    Batch
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ------------------------
Status Modal
------------------------ --}}

<div id="statusModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-extrabold text-gray-800">Update Status</h3>
            <button type="button" onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form id="singleStatusForm" method="POST" action="" class="p-6">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Select New Status</label>
                <select name="is_active" id="singleStatusDropdown" required
                    class="border border-gray-300 w-full p-3 rounded outline-none focus:ring-2 focus:ring-amber-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeStatusModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-amber-500 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-amber-600 transition">
                    Update
                    Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Select All Checkbox Logic
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');

    selectAll.addEventListener('change', function(e) {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    // --- Create modal Logic ---

    function openCreateModal() {
        const modal = document.getElementById('createModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCreateModal() {
        const modal = document.getElementById('createModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }


    // --- Edit Modal Logic ---

    function openEditModal(batch) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editBatchForm');

        // Populate the form fields dynamically
        document.getElementById('edit_code').value = batch.code;
        document.getElementById('edit_name').value = batch.name;
        document.getElementById('edit_is_active').value = batch.is_active;

        form.action = `/dashboard/batches/${batch.batch_id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditModal() {
        const modal = document.getElementById('editModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // --- Status Modal Logic ---

    function openStatusModal(batchId, isActive) {
        const modal = document.getElementById('statusModal');
        const form = document.getElementById('singleStatusForm');
        const dropdown = document.getElementById('singleStatusDropdown');


        form.action = `/dashboard/batches/${batchId}/status`;
        dropdown.value = isActive;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
