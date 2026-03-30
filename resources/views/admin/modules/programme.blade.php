<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Programme Management</h2>
    <button onclick="openCreateProgModal()"
        class="bg-blue-600 text-white px-5 py-2.5 rounded shadow hover:bg-blue-700 transition font-bold flex items-center">
        <i class="fas fa-plus mr-2"></i> Create Programme
    </button>
</div>

{{-- Form for filter name or code  --}}

<form method="GET" action="{{ route('admin.programme.index') }}"
    class="mb-4 bg-white p-4 rounded shadow-sm border flex gap-4 items-end">

    <div class="flex-1">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Search Code /
            Name</label>
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search by programme code or name"
            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
    </div>

    <div class="w-48">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Status</label>
        <select name="status"
            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses
            </option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Only
            </option>
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-blue-700 transition">Filter</button>
        <a href="{{ route('admin.programme.index') }}"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold shadow hover:bg-gray-300 transition">Clear</a>
    </div>
</form>

{{-- Form for update bulk status id  --}}

<form action="{{ route('admin.programme.bulkStatus') }}" method="POST" id="bulkProgForm">
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
            <thead class="bg-slate-100 border-b border-gray-200 text-gray-700">
                <tr>
                    <th class="p-4 w-12 text-center">
                        <input type="checkbox" id="selectAllProg"
                            class="h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                    </th>
                    <th class="p-4 font-bold uppercase tracking-wider text-xs">Action</th>
                    <th class="p-4 font-bold uppercase tracking-wider text-xs">Sl No</th>
                    <th class="p-4 font-bold uppercase tracking-wider text-xs">Code</th>
                    <th class="p-4 font-bold uppercase tracking-wider text-xs">Name</th>
                    <th class="p-4 font-bold uppercase tracking-wider text-xs">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($programmes as $index => $prog)
                    <tr class="hover:bg-blue-50 transition-colors">
                        <td class="p-4 text-center">
                            <input type="checkbox" name="selected_ids[]" value="{{ $prog->programme_id }}"
                                class="rowCheckboxProg h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                        </td>

                        <td class="p-4 flex gap-4">
                            <button type="button"
                                onclick="openStatusProgModal({{ $prog->programme_id }}, {{ $prog->is_active }})"
                                class="text-amber-500 hover:text-amber-700 transition" title="Update Status">
                                <i class="fas fa-sync-alt text-lg"></i>
                            </button>
                            <button type="button" onclick='openEditProgModal(@json($prog))'
                                class="text-blue-500 hover:text-blue-700 transition" title="Edit Programme">
                                <i class="fas fa-edit text-lg"></i>
                            </button>
                        </td>

                        <td class="p-4 font-mono text-gray-500 bg-gray-50 text-center w-20">
                            {{ ($programmes->currentPage() - 1) * $programmes->perPage() + $loop->iteration }}
                        </td>

                        <td class="p-4 font-bold text-gray-800">{{ $prog->code }}</td>
                        <td class="p-4 text-gray-600">{{ $prog->name }}</td>
                        <td class="p-4">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $prog->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                {{ $prog->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $programmes->links() }}
    </div>
</form>

{{-- ------------------------
Create Modal
------------------------ --}}

<div id="createProgModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-extrabold text-gray-800">Create Programme</h3>
            <button type="button" onclick="closeCreateProgModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        {{-- Form for create programme  --}}

        <form method="POST" action="{{ route('admin.programme.store') }}" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Programme Code <span
                        class="text-red-500">*</span></label>
                <input type="text" name="code" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter programme code">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Programme Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="name" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter programme name">
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
                <button type="button" onclick="closeCreateProgModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- ------------------------
Edit Modal
------------------------ --}}

<div id="editProgModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl font-extrabold text-gray-800">Edit Programme</h3>
            <button type="button" onclick="closeEditProgModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form id="editProgForm" method="POST" action="" class="p-6 space-y-5">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Programme Code <span
                        class="text-red-500">*</span></label>
                <input type="text" name="code" id="edit_prog_code" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Programme Name <span
                        class="text-red-500">*</span></label>
                <input type="text" name="name" id="edit_prog_name" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="is_active" id="edit_prog_is_active" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditProgModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- ------------------------
Status Modal
------------------------ --}}

<div id="statusProgModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-extrabold text-gray-800">Update Status</h3>
            <button type="button" onclick="closeStatusProgModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form id="statusProgForm" method="POST" action="" class="p-6">
            @csrf
            @method('PATCH')

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Select New Status</label>
                <select name="is_active" id="statusProgDropdown" required
                    class="border border-gray-300 w-full p-3 rounded outline-none focus:ring-2 focus:ring-amber-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeStatusProgModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="bg-amber-500 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-amber-600">Update
                    Record</button>
            </div>
        </form>
    </div>
</div>

<script>
    const selectAllProg = document.getElementById('selectAllProg');
    const rowCheckboxesProg = document.querySelectorAll('.rowCheckboxProg');

    if (selectAllProg) {
        selectAllProg.addEventListener('change', function(e) {
            rowCheckboxesProg.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });
    }

    // Create modal

    function openCreateProgModal() {
        const modal = document.getElementById('createProgModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCreateProgModal() {
        const modal = document.getElementById('createProgModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Edit modal

    function openEditProgModal(prog) {
        const modal = document.getElementById('editProgModal');
        const form = document.getElementById('editProgForm');

        document.getElementById('edit_prog_code').value = prog.code;
        document.getElementById('edit_prog_name').value = prog.name;
        document.getElementById('edit_prog_is_active').value = prog.is_active;

        form.action = `/dashboard/programmes/${prog.programme_id}`;

        // swap class
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditProgModal() {
        const modal = document.getElementById('editProgModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Status model

    function openStatusProgModal(progId, isActive) {
        const modal = document.getElementById('statusProgModal');
        const form = document.getElementById('statusProgForm');
        const dropdown = document.getElementById('statusProgDropdown');

        form.action = `/dashboard/programmes/${progId}/status`;
        dropdown.value = isActive;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStatusProgModal() {
        const modal = document.getElementById('statusProgModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
