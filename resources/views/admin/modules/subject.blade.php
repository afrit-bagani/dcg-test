<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Subject Management</h2>
    <button onclick="openCreateSubjModal()"
        class="bg-blue-600 text-white px-5 py-2.5 rounded shadow hover:bg-blue-700 transition font-bold flex items-center">
        <i class="fas fa-plus mr-2"></i> Create Subject
    </button>
</div>

{{-- Form for filter name or code  --}}

<form method="GET" action="{{ route('admin.subject.index') }}"
    class="mb-4 bg-white p-4 rounded shadow-sm border flex gap-4 items-end">
    <div class="flex-1">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Search Code / Name</label>
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search by subject code or name"
            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
    </div>
    <div class="w-48">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Status</label>
        <select name="status"
            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active Only</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive Only</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-blue-700 transition">
            Filter
        </button>
        <a href="{{ route('admin.subject.index') }}"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold shadow hover:bg-gray-300 transition">Clear</a>
    </div>
</form>

{{-- Form for bulk status update  --}}

<form action="{{ route('admin.subject.bulkStatus') }}" method="POST" id="bulkSubjForm">
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
            class="bg-slate-800 text-white px-4 py-2 rounded text-sm hover:bg-slate-900 shadow transition">Submit
        </button>
    </div>

    <div class="bg-white rounded shadow-md border border-gray-200 overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap min-w-max">
            <thead class="bg-slate-50 border-b border-gray-200 text-gray-600">
                <tr>
                    <th class="py-3 px-4 w-10 text-center">
                        <input type="checkbox" id="selectAllSubj"
                            class="h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                    </th>
                    <th class="py-3 px-2 w-20 font-bold uppercase tracking-wider text-xs">Action</th>
                    <th class="py-3 px-2 w-16 font-bold uppercase tracking-wider text-xs text-center">Sl No</th>
                    <th class="py-3 px-2 w-16 font-bold uppercase tracking-wider text-xs text-center">Programme</th>
                    <th class="py-3 px-2 w-16 font-bold uppercase tracking-wider text-xs text-center">Course</th>
                    <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Code</th>
                    <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Name</th>
                    <th
                        class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-center border-l border-gray-200 bg-gray-50/50">
                        Internal (Full/Pass)
                    </th>
                    <th
                        class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-center border-l border-gray-200 bg-gray-50/50">
                        Theory (Full/Pass)
                    </th>
                    <th
                        class="py-3 px-4 font-bold uppercase tracking-wider text-xs text-center border-l border-gray-200 bg-gray-50/50">
                        Practical (Full/Pass)
                    </th>

                    <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($subjects as $index => $subject)
                    <tr
                        class="transition-all duration-300 ease-in-out hover:bg-slate-200 transform hover:scale-[1.02] hover:shadow-lg hover:z-10 relative bg-white">
                        <td class="py-3 px-4 text-center">
                            <input type="checkbox" name="selected_ids[]" value="{{ $subject->subject_id }}"
                                class="rowCheckboxSubj h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                        </td>

                        <!-- Action Column -->
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-3">
                                <button type="button"
                                    onclick="openStatusSubjModal({{ $subject->subject_id }}, {{ $subject->is_active }})"
                                    class="text-amber-500 hover:text-amber-700 transition" title="Update Status">
                                    <i class="fas fa-sync-alt text-base"></i>
                                </button>
                                <button type="button" onclick='openEditSubjModal(@json($subject))'
                                    class="text-blue-500 hover:text-blue-700 transition" title="Edit Subject">
                                    <i class="fas fa-edit text-base"></i>
                                </button>
                            </div>
                        </td>

                        <!-- Cleaned up Sl No Column -->
                        <td class="py-3 px-2 font-mono text-gray-500 text-center">
                            {{ ($subjects->currentPage() - 1) * $subjects->perPage() + $loop->iteration }}
                        </td>
                        <td class="py-3 px-2 text-gray-600">{{ $subject->programme_name }}</td>
                        <td class="py-3 px-2 text-gray-600">{{ $subject->course_name }}</td>
                        <td class="py-3 px-4 font-bold text-gray-800">{{ $subject->code }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $subject->name }}</td>

                        <!-- NEW COMPACT MARKS DATA -->
                        <td class="py-3 px-4 text-center border-l border-gray-100 bg-gray-50/30">
                            <span class="font-bold text-gray-800">{{ $subject->internal_full_marks ?? '0' }}</span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="font-semibold text-rose-500">{{ $subject->internal_pass_marks ?? '0' }}</span>
                        </td>
                        <td class="py-3 px-4 text-center border-l border-gray-100 bg-gray-50/30">
                            <span class="font-bold text-gray-800">{{ $subject->theory_full_marks ?? '0' }}</span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span class="font-semibold text-rose-500">{{ $subject->theory_pass_marks ?? '0' }}</span>
                        </td>
                        <td class="py-3 px-4 text-center border-l border-gray-100 bg-gray-50/30">
                            <span class="font-bold text-gray-800">{{ $subject->practical_full_marks ?? '0' }}</span>
                            <span class="text-gray-400 mx-1">/</span>
                            <span
                                class="font-semibold text-rose-500">{{ $subject->practical_pass_marks ?? '0' }}</span>
                        </td>

                        <td class="py-3 px-4">
                            <span
                                class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $subject->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $subject->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $subjects->links() }}</div>
</form>

<!-- ========================================== -->
<!-- CREATE SUBJECT MODAL                       -->
<!-- ========================================== -->
<div id="createSubjModal"
    class="fixed inset-0 hidden z-50 bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-0 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
            <h3 class="text-xl font-extrabold text-gray-800">Create Subject</h3>
            <button type="button" onclick="closeCreateSubjModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form method="POST" action="{{ route('admin.subject.store') }}" class="p-6 space-y-5">
            @csrf

            {{--            programme name and courses --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Programme <span
                            class="text-red-500">*</span></label>
                    <select name="programme_id" id="create_programme_id" required
                        onchange="fetchCoursesByProgramme(this.value, 'create_course_id')"
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>Select Programme...</option>
                        @foreach ($programmes as $programme)
                            <option value="{{ $programme->programme_id }}">{{ $programme->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Course <span
                            class="text-red-500">*</span></label>
                    <select name="course_id" id="create_course_id" required disabled
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500">
                        <option value="" disabled selected>Select Programme First...</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Subject Code <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="code" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter subject code">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Subject Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter subject name">
                </div>
            </div>

            <!-- Internal Marks -->
            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Internal Assessment</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Marks</label>
                        <input type="number" step="0.01" name="internal_full_marks" value="0.00"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks</label>
                        <input type="number" step="0.01" name="internal_pass_marks" value="0.00"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Theory Marks -->
            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Theory Exam</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Marks</label>
                        <input type="number" step="0.01" name="theory_full_marks" value="0.00"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks</label>
                        <input type="number" step="0.01" name="theory_pass_marks" value="0.00"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Practical Marks -->
            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Practical Exam</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Marks</label>
                        <input type="number" step="0.01" name="practical_full_marks" value="0.00"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks</label>
                        <input type="number" step="0.01" name="practical_pass_marks" value="0.00"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="is_active" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeCreateSubjModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700">Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- EDIT SUBJECT MODAL                         -->
<!-- ========================================== -->
<div id="editSubjModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-0 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div
            class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
            <h3 class="text-xl font-extrabold text-gray-800">Edit Subject</h3>
            <button type="button" onclick="closeEditSubjModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form id="editSubjForm" method="POST" action="" class="p-6 space-y-5">
            @csrf
            @method('PATCH')

            {{--            programme name and courses --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Programme <span
                            class="text-red-500">*</span></label>
                    <select name="programme_id" id="create_programme_id" required
                        onchange="fetchCoursesByProgramme(this.value, 'edit_course_id')"
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>Select Programme...</option>
                        @foreach ($programmes as $programme)
                            <option value="{{ $programme->programme_id }}">{{ $programme->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Course <span
                            class="text-red-500">*</span></label>
                    <select name="course_id" id="edit_course_id" required disabled
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500">
                        <option value="" disabled selected>Select Programme First...</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Subject Code <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="code" id="edit_subj_code" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Subject Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_subj_name" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Internal Marks -->
            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Internal Assessment</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Marks</label>
                        <input type="number" step="0.01" name="internal_full_marks" id="edit_subj_int_full"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks</label>
                        <input type="number" step="0.01" name="internal_pass_marks" id="edit_subj_int_pass"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Theory Marks -->
            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Theory Exam</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Marks</label>
                        <input type="number" step="0.01" name="theory_full_marks" id="edit_subj_th_full"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks</label>
                        <input type="number" step="0.01" name="theory_pass_marks" id="edit_subj_th_pass"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <!-- Practical Marks -->
            <div class="p-3 bg-gray-50 rounded border border-gray-200">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Practical Exam</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Marks</label>
                        <input type="number" step="0.01" name="practical_full_marks" id="edit_subj_pr_full"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks</label>
                        <input type="number" step="0.01" name="practical_pass_marks" id="edit_subj_pr_pass"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                <select name="is_active" id="edit_subj_is_active" required
                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditSubjModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700">Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- UPDATE STATUS MODAL                        -->
<!-- ========================================== -->
<div id="statusSubjModal"
    class="fixed inset-0 z-50 hidden bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-extrabold text-gray-800">Update Status</h3>
            <button type="button" onclick="closeStatusSubjModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>
        <form id="statusSubjForm" method="POST" action="" class="p-6">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Select New Status</label>
                <select name="is_active" id="statusSubjDropdown" required
                    class="border border-gray-300 w-full p-3 rounded outline-none focus:ring-2 focus:ring-amber-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeStatusSubjModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-amber-500 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-amber-600">Update
                    Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const selectAllSubj = document.getElementById('selectAllSubj');
    const rowCheckboxesSubj = document.querySelectorAll('.rowCheckboxSubj');

    if (selectAllSubj) {
        selectAllSubj.addEventListener('change', e => {
            rowCheckboxesSubj.forEach(cb => cb.checked = e.target.checked);
        });
    }

    // Create modal

    function openCreateSubjModal() {
        const modal = document.getElementById('createSubjModal');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCreateSubjModal() {
        const modal = document.getElementById('createSubjModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // Edit modal

    function openEditSubjModal(subject) {
        const modal = document.getElementById('editSubjModal');
        const form = document.getElementById('editSubjForm');

        // Core Fields
        document.getElementById('edit_subj_code').value = subject.code;
        document.getElementById('edit_subj_name').value = subject.name;
        document.getElementById('edit_subj_is_active').value = subject.is_active;

        // Decimal Fields (Marks)
        document.getElementById('edit_subj_int_full').value = subject.internal_full_marks;
        document.getElementById('edit_subj_int_pass').value = subject.internal_pass_marks;
        document.getElementById('edit_subj_th_full').value = subject.theory_full_marks;
        document.getElementById('edit_subj_th_pass').value = subject.theory_pass_marks;
        document.getElementById('edit_subj_pr_full').value = subject.practical_full_marks;
        document.getElementById('edit_subj_pr_pass').value = subject.practical_pass_marks;

        form.action = `/dashboard/subjects/${subject.subject_id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditSubjModal() {
        const modal = document.getElementById('editSubjModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // status model

    function openStatusSubjModal(subjId, isActive) {
        const modal = document.getElementById('statusSubjModal');
        const form = document.getElementById('statusSubjForm');
        const dropdown = document.getElementById('statusSubjDropdown');

        form.action = `/dashboard/subjects/${subjId}/status`;
        dropdown.value = isActive;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStatusSubjModal() {
        const modal = document.getElementById('statusSubjModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    async function fetchCoursesByProgramme(programmeId, targetDropdownId) {
        const courseDropdown = document.getElementById(targetDropdownId);
        courseDropdown.innerHTML = '<option value="" disabled selected>Loading courses...</option>';
        courseDropdown.disabled = false;

        if (!programmeId) return;

        try {
            const response = await fetch(`/dashboard/api/programmes/${programmeId}/courses`);
            if (!response.ok) throw new Error('Network response was not ok')
            const courses = await response.json();

            courseDropdown.innerHTML = '<option value="" disabled selected>Select course...</option>';

            if (courses.length === 0) {
                courseDropdown.innerHTML = '<option value="" disabled selected>No courses available</option>';
                return;
            }

            courses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.course_id;
                option.textContent = course.name;
                courseDropdown.append(option);
            });

            courseDropdown.disabled = false;

        } catch (error) {
            console.error('Error fetching courses:', error);
            courseDropdown.innerHTML = '<option value="" disabled selected>Error Loading data</option>';
        }
    }
</script>
