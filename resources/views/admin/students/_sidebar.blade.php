<div class="w-2/10 bg-white rounded shadow-sm border border-gray-200 p-4">
    <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-4 px-3">Student Workspace</h3>

    <nav class="space-y-1">
        <!-- Personal Info Link (Always Accessible) -->
        <a href="{{ isset($student) ? route('admin.student.edit.personal', $student->student_id) : '#' }}"
           class="flex items-center px-3 py-2.5 text-sm font-bold rounded-lg transition-colors
           {{ request()->routeIs('admin.student.create') || request()->routeIs('admin.student.edit.personal')
              ? 'bg-blue-50 text-blue-700'
              : 'text-gray-600 hover:bg-gray-50' }}">
            <i class="fas fa-user w-5 text-center mr-2"></i> Personal Info
        </a>

        <!-- Education Info Link (LOCKED if creating a new student) -->
        @if(isset($student))
            <a href="{{ route('admin.student.edit.education', $student->student_id) }}"
               class="flex items-center px-3 py-2.5 text-sm font-bold rounded-lg transition-colors
               {{ request()->routeIs('admin.student.edit.education') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">
                <i class="fas fa-graduation-cap w-5 text-center mr-2"></i> Academic Enrollment
            </a>
        @else
            <!-- Disabled State -->
            <div
                class="flex items-center px-3 py-2.5 text-sm font-bold rounded-lg text-gray-400 cursor-not-allowed bg-gray-50"
                title="Save personal info first to unlock">
                <i class="fas fa-lock w-5 text-center mr-2"></i> Academic Enrollment
            </div>
        @endif

        <!-- You can easily add Fees, Documents, Address here in the future! -->
    </nav>
</div>
