<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Edit Student Workspace</h2>
            <p class="text-sm text-gray-500 mt-1">Modifying records for <strong>{{ $student->name }}</strong>
                ({{ $student->reg_no }})</p>
        </div>
        <a href="{{ route('admin.student.index') }}"
           class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded font-bold shadow-sm hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="flex gap-6 items-start">
        @include('admin.students._sidebar')

        <div class="w-3/4 bg-white rounded shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4 rounded-t flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Academic Enrollment</h3>
                <span
                    class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full border border-blue-200">Step 2 of 2</span>
            </div>

            <form method="POST" action="{{ route('admin.student.update.education', $student->student_id) }}"
                  class="p-6 space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Programme <span
                                class="text-red-500">*</span></label>
                        <select name="programme_id" required
                                onchange="fetchCoursesByProgramme(this.value, 'workspace_course_id')"
                                class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="" disabled {{ !$student->programme_id ? 'selected' : '' }}>Select
                                Programme...
                            </option>
                            @foreach ($programmes as $prog)
                                <option
                                    value="{{ $prog->programme_id }}" {{ $student->programme_id == $prog->programme_id ? 'selected' : '' }}>
                                    {{ $prog->code }} - {{ $prog->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Course <span
                                class="text-red-500">*</span></label>
                        <select name="course_id" id="workspace_course_id" required
                                {{ !$student->programme_id ? 'disabled' : '' }}
                                class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white disabled:bg-gray-100">
                            <option value="" disabled {{ !$student->course_id ? 'selected' : '' }}>Select Course...
                            </option>
                            <!-- Existing courses populated by controller if editing -->
                            @foreach ($courses as $course)
                                <option
                                    value="{{ $course->course_id }}" {{ $student->course_id == $course->course_id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Batch <span
                                class="text-red-500">*</span></label>
                        <select name="batch_id" required
                                class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="" disabled {{ !$student->batch_id ? 'selected' : '' }}>Select Batch...
                            </option>
                            @foreach ($batches as $batch)
                                <option
                                    value="{{ $batch->batch_id }}" {{ $student->batch_id == $batch->batch_id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-8 flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 rounded font-bold shadow hover:bg-blue-700 transition">
                        Update Academic Info
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Re-using the excellent Cascading Dropdown logic from your Subject module!
        async function fetchCoursesByProgramme(programmeId, targetDropdownId) {
            const courseDropdown = document.getElementById(targetDropdownId);
            courseDropdown.innerHTML = '<option value="" disabled selected>Loading courses...</option>';
            courseDropdown.disabled = true;

            if (!programmeId) return;

            try {
                const response = await fetch(`/dashboard/api/programmes/${programmeId}/courses`);
                if (!response.ok) throw new Error('Network response was not ok');

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
</x-admin-layout>
