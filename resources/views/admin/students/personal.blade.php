<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">
                {{ isset($student) ? 'Edit Student Workspace' : 'Register New Student' }}
            </h2>
            @if(isset($student))
                <p class="text-sm text-gray-500 mt-1">Modifying records for <strong>{{ $student->name }}</strong>
                    ({{ $student->reg_no }})</p>
            @endif
        </div>
        <a href="{{ route('admin.student.index') }}"
           class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded font-bold shadow-sm hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="flex gap-6 items-start">
        <!-- Include the Sidebar Component -->
        @include('admin.students._sidebar')

        <!-- Main Form Content -->
        <div class="w-3/4 bg-white rounded shadow-sm border border-gray-200">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4 rounded-t">
                <h3 class="text-lg font-bold text-gray-800">Personal Information</h3>
            </div>

            <form method="POST"
                  action="{{ isset($student) ? route('admin.student.update.personal', $student->student_id) : route('admin.student.store') }}"
                  class="p-6 space-y-6">
                @csrf
                @if(isset($student))
                    @method('PATCH')
                @endif

                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Registration No <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="reg_no" value="{{ old('reg_no', $student->reg_no ?? '') }}" required
                               class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Full Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $student->name ?? '') }}" required
                               class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Address <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $student->email ?? '') }}" required
                               class="border border-gray-300 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-8 flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2.5 rounded font-bold shadow hover:bg-blue-700 transition">
                        {{ isset($student) ? 'Update Personal Info' : 'Save & Continue to Education' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
