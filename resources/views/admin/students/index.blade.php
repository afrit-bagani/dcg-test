<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Student Hub</h2>
            <p class="text-sm text-gray-500 mt-1">Manage enrollments, directories, and registrations.</p>
        </div>
    </div>

    <div class="flex gap-6 items-start">

        <!-- UNIVERSAL STUDENT SIDEBAR (HUB LEVEL) -->
        <div class="w-2/10 bg-white rounded shadow-sm border border-gray-200 p-4 sticky top-6">
            <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-4 px-3">Student Menu</h3>

            <nav class="space-y-1">
                <!-- 1. Student List (Active) -->
                <a href="{{ route('admin.student.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm font-bold rounded-lg bg-blue-50 text-blue-700 transition-colors">
                    <i class="fas fa-list w-5 text-center mr-2"></i> Student List
                </a>

                <!-- 2. Register Student -->
                <a href="{{ route('admin.student.create') }}"
                   class="flex items-center px-3 py-2.5 text-sm font-bold rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-user-plus w-5 text-center mr-2"></i> Register Student
                </a>
            </nav>
        </div>

        <div class="w-3/4 space-y-4">

            <!-- TOP ACTION BAR -->
            <div class="flex justify-between items-center bg-white p-4 rounded shadow-sm border border-gray-200">
                <h3 class="text-lg font-extrabold text-gray-800">All Students</h3>
                <a href="{{ route('admin.student.create') }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-plus mr-2"></i> Register Student
                </a>
            </div>

            {{-- Search Filter --}}
            <form method="GET" action="{{ route('admin.student.index') }}"
                  class="bg-white p-4 rounded shadow-sm border border-gray-200 flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Search Name / Reg
                        No / Email</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search students..."
                           class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                </div>
                <div class="w-40">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Status</label>
                    <select name="status"
                            class="border border-gray-300 w-full p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                        <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold shadow hover:bg-blue-700 transition">
                        Filter
                    </button>
                    <a href="{{ route('admin.student.index') }}"
                       class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm font-bold shadow hover:bg-gray-300 transition">Clear</a>
                </div>
            </form>

            {{-- Bulk Form & Table --}}
            <form action="{{ route('admin.student.bulkStatus') }}" method="POST" id="bulkStudentForm">
                @csrf
                <div
                    class="flex justify-end items-center mb-4 gap-3 bg-white p-3 rounded shadow-sm border border-gray-200">
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

                <div class="bg-white rounded shadow-md border border-gray-200 overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap min-w-max">
                        <thead class="bg-slate-50 border-b border-gray-200 text-gray-600">
                        <tr>
                            <th class="py-3 px-4 w-10 text-center">
                                <input type="checkbox" id="selectAllStudents"
                                       class="h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                            </th>
                            <th class="py-3 px-2 w-20 font-bold uppercase tracking-wider text-xs">Action</th>
                            <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Reg No</th>
                            <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Name & Contact</th>
                            <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Programme / Course</th>
                            <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Batch</th>
                            <th class="py-3 px-4 font-bold uppercase tracking-wider text-xs">Status</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @foreach ($students as $student)
                            <tr class="transition-all duration-300 ease-in-out hover:bg-slate-50 bg-white">
                                <td class="py-3 px-4 text-center">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $student->student_id }}"
                                           class="rowCheckboxStudent h-4 w-4 cursor-pointer text-blue-600 rounded border-gray-300">
                                </td>
                                <td class="py-3 px-2">
                                    <div class="flex items-center gap-3">
                                        <button type="button"
                                                onclick="openStudentStatusModal({{ $student->student_id }}, {{ $student->is_active }})"
                                                class="text-amber-500 hover:text-amber-700 transition"
                                                title="Update Status">
                                            <i class="fas fa-sync-alt text-base"></i>
                                        </button>
                                        <!-- Workspace Redirect Link -->
                                        <a href="{{ route('admin.student.edit.personal', $student->student_id) }}"
                                           class="text-blue-500 hover:text-blue-700 transition"
                                           title="Edit Student Workspace">
                                            <i class="fas fa-edit text-base"></i>
                                        </a>
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-gray-800">{{ $student->reg_no }}</td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-gray-800">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $student->email }}</div>
                                    <div class="text-xs text-gray-400">{{ $student->phone_no ?? 'No Phone' }}</div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($student->programme_code)
                                        <span class="font-bold text-indigo-600">{{ $student->programme_code }}</span>
                                        <span class="text-gray-400 mx-1">-</span>
                                        <span class="text-gray-600">{{ $student->course_name }}</span>
                                    @else
                                        <span class="text-xs text-amber-500 italic">Pending Assignment</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-semibold text-gray-700">{{ $student->batch_name ?? '--' }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $student->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $student->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $students->links() }}</div>
            </form>
        </div>
    </div>

    <!-- Status Toggle Modal -->
    <div id="statusStudentModal"
         class="fixed inset-0 z-50 hidden bg-gray-900/60 items-center justify-center backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-0 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-extrabold text-gray-800">Update Status</h3>
                <button type="button" onclick="closeStudentStatusModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times text-xl"></i></button>
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
                            class="bg-white border border-gray-300 text-gray-700 px-5 py-2.5 rounded font-semibold hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-amber-500 text-white px-5 py-2.5 rounded font-bold shadow hover:bg-amber-600 transition">
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Select All Checkbox Logic
        const selectAllStudents = document.getElementById('selectAllStudents');
        const rowCheckboxesStudent = document.querySelectorAll('.rowCheckboxStudent');

        if (selectAllStudents) {
            selectAllStudents.addEventListener('change', e => {
                rowCheckboxesStudent.forEach(cb => cb.checked = e.target.checked);
            });
        }

        // Status Modal Logic
        function openStudentStatusModal(studentId, isActive) {
            const modal = document.getElementById('statusStudentModal');
            const form = document.getElementById('studentStatusForm');
            document.getElementById('studentStatusDropdown').value = isActive;
            form.action = `/dashboard/students/${studentId}/status`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeStudentStatusModal() {
            const modal = document.getElementById('statusStudentModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-admin-layout>
