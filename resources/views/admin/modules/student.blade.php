<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-extrabold text-gray-800">Student Registration</h2>
    <button onclick="openCreateStudentModal()"
        class="bg-blue-600 text-white px-5 py-2.5 rounded shadow hover:bg-blue-700 transition font-bold flex items-center">
        <i class="fas fa-user-plus mr-2"></i> Register Student
    </button>
</div>

{{-- Form for filter name or code  --}}

<form method="GET" action="{{ route('admin.student.index') }}"
    class="mb-4 bg-white p-4 rounded shadow-sm border flex gap-4 items-end">
    <div class="flex-1">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Search Name / Reg No /
            Email</label>
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search students by there registration no, name and email id."
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
            class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-blue-700 transition">Filter</button>
        <a href="{{ route('admin.student.index') }}"
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold shadow hover:bg-gray-300 transition">Clear</a>
    </div>
</form>

<!-- ========================================== -->
<!-- DATA TABLE                                 -->
<!-- ========================================== -->
<div class="bg-white rounded shadow-md border border-gray-200 overflow-hidden">
    <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="bg-slate-50 border-b border-gray-200 text-gray-600">
            <tr>
                <th class="py-3 px-2 w-20 font-bold uppercase tracking-wider text-xs pl-4">Action</th>
                <th class="py-3 px-2 w-16 font-bold uppercase tracking-wider text-xs text-center">Sl No</th>
                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Reg No</th>
                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Name & Email</th>
                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Programme & Course</th>
                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Batch</th>
                <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($students as $student)
                <tr
                    class="transition-all duration-300 ease-in-out hover:bg-slate-200 transform hover:scale-[1.02] hover:shadow-lg hover:z-10 relative bg-white">
                    <td class="py-3 px-2 pl-4">
                        <div class="flex items-center gap-3">
                            <button type="button"
                                onclick="openStudentStatusModal({{ $student->student_id }}, {{ $student->is_active }})"
                                class="text-amber-500 hover:text-amber-700 transition" title="Update Status">
                                <i class="fas fa-sync-alt text-base"></i>
                            </button>
                            <button type="button" onclick='openEditStudentModal(@json($student))'
                                class="text-blue-500 hover:text-blue-700 transition" title="Edit Student">
                                <i class="fas fa-edit text-base"></i>
                            </button>
                        </div>
                    </td>

                    <td class="py-3 px-2 font-mono text-gray-500 text-center">
                        {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}
                    </td>

                    <td class="py-3 px-4 font-mono font-bold text-gray-800">{{ $student->reg_no }}</td>
                    <td class="py-3 px-4">
                        <div class="font-bold text-gray-800">{{ $student->name }}</div>
                        <div class="text-xs text-gray-500">{{ $student->email }}</div>
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-bold text-indigo-600">{{ $student->programme_code }}</span>
                        <span class="text-gray-400 mx-1">-</span>
                        <span class="text-gray-600">{{ $student->course_name }}</span>
                    </td>
                    <td class="py-3 px-4 font-semibold text-gray-700">{{ $student->batch_name }}</td>
                    <td class="py-3 px-4">
                        <span
                            class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $student->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $student->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $students->links() }}</div>

<!-- ========================================== -->
<!-- CREATE STUDENT MODAL                       -->
<!-- ========================================== -->
<div id="createStudentModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-0 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
            <h3 class="text-xl font-extrabold text-gray-800">Register New Student</h3>
            <button type="button" onclick="closeCreateStudentModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form method="POST" action="{{ route('admin.student.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Personal Info Block -->
            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded border border-gray-200">
                <div class="col-span-2">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Personal Information</h4>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Registration No <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="reg_no" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Full Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email Address <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Academic Info Block -->
            <div class="grid grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded border border-blue-100">
                <div class="col-span-2">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Academic Enrollment</h4>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Programme <span
                            class="text-red-500">*</span></label>
                    <select name="programme_id" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>Select Programme...</option>
                        @foreach ($activeProgrammes as $prog)
                            <option value="{{ $prog->programme_id }}">{{ $prog->code }} - {{ $prog->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Course <span
                            class="text-red-500">*</span></label>
                    <select name="course_id" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>Select Course...</option>
                        @foreach ($activeCourses as $course)
                            <option value="{{ $course->course_id }}">{{ $course->name }} ({{ $course->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Batch <span
                            class="text-red-500">*</span></label>
                    <select name="batch_id" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled selected>Select Batch...</option>
                        @foreach ($activeBatches as $batch)
                            <option value="{{ $batch->batch_id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                    <select name="is_active" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeCreateStudentModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700">Register
                    Student</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- EDIT STUDENT MODAL                         -->
<!-- ========================================== -->
<div id="editStudentModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl p-0 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div
            class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 z-10">
            <h3 class="text-xl font-extrabold text-gray-800">Edit Student</h3>
            <button type="button" onclick="closeEditStudentModal()" class="text-gray-400 hover:text-gray-600"><i
                    class="fas fa-times text-xl"></i></button>
        </div>

        <form id="editStudentForm" method="POST" action="" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            <!-- Personal Info Block -->
            <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded border border-gray-200">
                <div class="col-span-2">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Personal Information</h4>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Registration No <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="reg_no" id="edit_student_reg" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Full Name <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_student_name" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email Address <span
                            class="text-red-500">*</span></label>
                    <input type="email" name="email" id="edit_student_email" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Academic Info Block -->
            <div class="grid grid-cols-2 gap-4 bg-blue-50/50 p-4 rounded border border-blue-100">
                <div class="col-span-2">
                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Academic Enrollment</h4>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Programme <span
                            class="text-red-500">*</span></label>
                    <select name="programme_id" id="edit_student_prog" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled>Select Programme...</option>
                        @foreach ($activeProgrammes as $prog)
                            <option value="{{ $prog->programme_id }}">{{ $prog->code }} - {{ $prog->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Course <span
                            class="text-red-500">*</span></label>
                    <select name="course_id" id="edit_student_course" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled>Select Course...</option>
                        @foreach ($activeCourses as $course)
                            <option value="{{ $course->course_id }}">{{ $course->name }} ({{ $course->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Batch <span
                            class="text-red-500">*</span></label>
                    <select name="batch_id" id="edit_student_batch" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="" disabled>Select Batch...</option>
                        @foreach ($activeBatches as $batch)
                            <option value="{{ $batch->batch_id }}">{{ $batch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                    <select name="is_active" id="edit_student_status" required
                        class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeEditStudentModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50">Cancel</button>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-blue-700">Update
                    Student</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- STATUS MODAL                         -->
<!-- ========================================== -->
<div id="statusStudentModal"
    class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-60 items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-0 overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-extrabold text-gray-800">Update Status</h3>
            <button type="button" onclick="closeStudentStatusModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="studentStatusForm" method="POST" action="" class="p-6">
            @csrf
            @method('PATCH')
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Select New Status</label>
                <select name="is_active" id="studentStatusDropdown" required
                    class="border border-gray-300 w-full p-3 rounded outline-none focus:ring-2 focus:ring-amber-500 bg-white">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeStudentStatusModal()"
                    class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                    class="bg-amber-500 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-amber-600 transition">Update
                    Status</button>
            </div>
        </form>
    </div>
</div>

<script>
    // create modal
    function openCreateStudentModal() {
        const modal = document.getElementById('createStudentModal');

        modal.classList.remove('hidden')
        modal.classList.add('flex')
    }

    function closeCreateStudentModal() {
        const modal = document.getElementById('createStudentModal');

        modal.classList.remove('flex')
        modal.classList.add('hidden')
    }


    // edit modal

    function openEditStudentModal(student) {
        const modal = document.getElementById('editStudentModal');
        const form = document.getElementById('editStudentForm');

        // Map Text Inputs
        document.getElementById('edit_student_reg').value = student.reg_no;
        document.getElementById('edit_student_name').value = student.name;
        document.getElementById('edit_student_email').value = student.email;

        // Map Select Dropdowns (The browser automatically selects the matching <option>)
        document.getElementById('edit_student_prog').value = student.programme_id;
        document.getElementById('edit_student_course').value = student.course_id;
        document.getElementById('edit_student_batch').value = student.batch_id;
        document.getElementById('edit_student_status').value = student.is_active;

        form.action = `/dashboard/students/${student.student_id}`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeEditStudentModal() {
        const modal = document.getElementById('editStudentModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    // --- Status Modal Logic ---
    function openStudentStatusModal(studentId, isActive) {
        const modal = document.getElementById('statusStudentModal');
        const form = document.getElementById('studentStatusForm');
        const dropdown = document.getElementById('studentStatusDropdown');

        form.action = `/dashboard/students/${studentId}/status`;
        dropdown.value = isActive;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStudentStatusModal() {
        const modal = document.getElementById('statusStudentModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
