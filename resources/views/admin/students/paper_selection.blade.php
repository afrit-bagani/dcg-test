<x-admin-layout title="Paper Selection | Student | Dashboard">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Edit Student Record</h2>
            <p class="text-sm text-gray-500 mt-1">Assigning academic parameters for
                <strong>{{ $student->full_name }}</strong> ({{ $student->reg_no }})
            </p>
        </div>
        <a href="{{ route('admin.student.index') }}"
            class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded font-bold shadow-sm hover:bg-gray-50 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Directory
        </a>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">

        <!-- HORIZONTAL TABS NAVIGATION -->
        <div class="flex border-b border-gray-200 bg-gray-50 overflow-x-auto">
            <!-- Completed Tab 1 -->
            <a href="{{ route('admin.student.basic_info.edit', $student->student_id) }}"
                class="px-6 py-4 text-gray-600 font-bold text-sm hover:bg-gray-100 flex items-center gap-2 transition">
                <span
                    class="bg-green-100 text-green-700 rounded-full h-5 w-5 flex items-center justify-center text-xs"><i
                        class="fas fa-check"></i></span>
                Basic Information
            </a>

            <!-- Active Tab 2 -->
            <div
                class="px-6 py-4 bg-white border-t-2 border-purple-600 text-purple-700 font-bold text-sm shadow-sm border-l border-gray-200 flex items-center gap-2">
                <span
                    class="bg-purple-100 text-purple-700 rounded-full h-5 w-5 flex items-center justify-center text-xs">2</span>
                Paper Selection
            </div>

            <!-- Unlocked Tabs 3 & 4 -->
            <a href="{{ route('admin.student.upload_docs.edit', $student->student_id) }}"
                class="px-6 py-4 text-gray-600 font-bold text-sm border-l border-gray-200 hover:bg-gray-100 flex items-center gap-2">
                <span
                    class="bg-gray-200 text-gray-600 rounded-full h-5 w-5 flex items-center justify-center text-xs">3</span>
                Upload Document
            </a>
            <a href="{{ route('admin.student.payment_info.edit', $student->student_id) }}"
                class="px-6 py-4 text-gray-600 font-bold text-sm border-l border-gray-200 hover:bg-gray-100 flex items-center gap-2">
                <span
                    class="bg-gray-200 text-gray-600 rounded-full h-5 w-5 flex items-center justify-center text-xs">4</span>
                Payment Information
            </a>
        </div>

        <!-- PAPER SELECTION FORM -->
        <form method="POST" action="{{ route('admin.student.paper_selection.update', $student->student_id) }}"
            class="p-6 space-y-8 bg-yellow-50/40">
            @csrf
            @method('PATCH')

            <div class="border border-yellow-200 rounded-lg bg-yellow-50/70 p-6">
                <div class="flex justify-between items-center border-b border-dashed border-yellow-300 pb-3 mb-5">
                    <h3 class="text-lg font-extrabold text-gray-800">Academic Placement</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Programme <span
                                class="text-red-500">*</span></label>
                        <select name="programme_id" required
                            onchange="fetchCoursesByProgramme(this.value, 'paper_course_id')"
                            class="border border-yellow-400 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-purple-500 bg-white shadow-sm">
                            <option value="" disabled {{ !$paperSelection ? 'selected' : '' }}>Select Programme...
                            </option>
                            @foreach ($programmes as $prog)
                                <option value="{{ $prog->programme_id }}"
                                    {{ $paperSelection && $paperSelection->programme_id == $prog->programme_id ? 'selected' : '' }}>
                                    {{ $prog->code }} - {{ $prog->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Course <span
                                class="text-red-500">*</span></label>
                        <select name="course_id" id="paper_course_id" required {{ !$paperSelection ? 'disabled' : '' }}
                            class="border border-yellow-400 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-purple-500 bg-white shadow-sm disabled:bg-gray-100 disabled:border-gray-300 disabled:text-gray-500">
                            <option value="" disabled {{ !$paperSelection ? 'selected' : '' }}>Select Course...
                            </option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->course_id }}"
                                    {{ $paperSelection && $paperSelection->course_id == $course->course_id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Batch / Academic Year <span
                                class="text-red-500">*</span></label>
                        <select name="batch_id" required
                            class="border border-yellow-400 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-purple-500 bg-white shadow-sm">
                            <option value="" disabled {{ !$paperSelection ? 'selected' : '' }}>Select Batch...
                            </option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->batch_id }}"
                                    {{ $paperSelection && $paperSelection->batch_id == $batch->batch_id ? 'selected' : '' }}>
                                    {{ $batch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <!-- Future Feature: Individual Subject/Paper Selection Checkboxes could go here! -->

            <div class="mt-8 flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="bg-purple-600 text-white px-8 py-3 rounded font-bold shadow-lg hover:bg-purple-700 transition">
                    Save Paper Selection & Continue
                </button>
            </div>
        </form>
    </div>

    <!-- Reusing our standard AJAX Cascading Dropdown Script -->
    <script>
        async function fetchCoursesByProgramme(programmeId, targetDropdownId) {
            const courseDropdown = document.getElementById(targetDropdownId);
            courseDropdown.innerHTML = '<option value="" disabled selected>Loading courses...</option>';
            courseDropdown.disabled = true;

            if (!programmeId) return;

            try {
                let fetchUrl = "{{ route('admin.api.courses', 'PLACEHOLDER') }}";

                fetchUrl = fetchUrl.replace('PLACEHOLDER', programmeId);

                const response = await fetch(fetchUrl);
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
