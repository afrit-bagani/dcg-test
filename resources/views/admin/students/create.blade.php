<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Register New Student</h2>
        </div>
        <a href="{{ route('admin.student.index') }}"
           class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded font-bold shadow-sm hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to Hub
        </a>
    </div>

    <div class="flex gap-6 items-start">

        <div class="w-1/4 bg-white rounded shadow-sm border border-gray-200 p-4 sticky top-6">
            <h3 class="text-xs font-extrabold text-gray-400 uppercase tracking-widest mb-4 px-3">Student Menu</h3>
            <nav class="space-y-1">
                <a href="{{ route('admin.student.index') }}"
                   class="flex items-center px-3 py-2.5 text-sm font-bold rounded-lg text-gray-600 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-list w-5 text-center mr-2"></i> Student List
                </a>
                <a href="{{ route('admin.student.create') }}"
                   class="flex items-center px-3 py-2.5 text-sm font-bold rounded-lg bg-blue-50 text-blue-700 transition-colors">
                    <i class="fas fa-user-plus w-5 text-center mr-2"></i> Register Student
                </a>
            </nav>
        </div>

        <div class="w-3/4 bg-white rounded shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4 rounded-t">
                <h3 class="text-lg font-bold text-gray-800">Intake Form</h3>
            </div>

            <form method="POST" action="{{ route('admin.student.store') }}" class="p-6 space-y-8">
                @csrf

                <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">1. Personal
                        Details</h4>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Registration No <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="reg_no" value="{{ old('reg_no') }}" required
                                   class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Full Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Email Address <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Phone Number</label>
                            <input type="text" name="phone_no" value="{{ old('phone_no') }}"
                                   class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4 border-b pb-2">2. Academic
                        Placement</h4>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Programme <span
                                    class="text-red-500">*</span></label>
                            <select name="programme_id" required
                                    onchange="fetchCoursesByProgramme(this.value, 'create_course_id')"
                                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="" disabled selected>Select Programme...</option>
                                @foreach($programmes as $prog)
                                    <option value="{{ $prog->programme_id }}">{{ $prog->code }}
                                        - {{ $prog->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Course <span class="text-red-500">*</span></label>
                            <select name="course_id" id="create_course_id" required disabled
                                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white disabled:bg-gray-100 disabled:text-gray-500">
                                <option value="" disabled selected>Select Programme First...</option>
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Batch <span
                                    class="text-red-500">*</span></label>
                            <select name="batch_id" required
                                    class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="" disabled selected>Select Batch...</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->batch_id }}">{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="bg-blue-600 text-white px-8 py-3 rounded font-bold shadow hover:bg-blue-700 transition">
                        Register Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
