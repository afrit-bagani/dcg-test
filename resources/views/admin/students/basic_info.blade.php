<x-admin-layout title="Student | Dashboard">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">
                {{ isset($student) ? 'Edit Student Record' : 'Register New Student' }}
            </h2>
            @if (!isset($student))
                <p class="text-sm text-gray-500 mt-1">Please complete the Basic Information to unlock further steps.</p>
            @else
                <p class="text-sm text-gray-500 mt-1">Editing profile for <strong>{{ $student->full_name }}</strong></p>
            @endif
        </div>
        <a href="{{ route('admin.student.index') }}"
            class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded font-bold shadow-sm hover:bg-gray-50 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Directory
        </a>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">

        <div class="flex border-b border-gray-200 bg-gray-50 overflow-x-auto">
            <a href="{{ isset($student) ? route('admin.student.basic_info.edit', $student->student_id) : '#' }}"
                class="px-6 py-4 bg-white border-t-2 border-purple-600 text-purple-700 font-bold text-sm shadow-sm flex items-center gap-2">
                <span
                    class="bg-purple-100 text-purple-700 rounded-full h-5 w-5 flex items-center justify-center text-xs">1</span>
                Basic Information
            </a>

            @if (isset($student))
                <a href="{{ route('admin.student.paper_selection.edit', $student->student_id) }}"
                    class="px-6 py-4 text-gray-600 font-bold text-sm border-l border-gray-200 hover:bg-gray-100 flex items-center gap-2">
                    <span
                        class="bg-gray-200 text-gray-600 rounded-full h-5 w-5 flex items-center justify-center text-xs">2</span>
                    Paper Selection
                </a>
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
            @else
                <div class="px-6 py-4 text-gray-400 font-bold text-sm border-l border-gray-200 cursor-not-allowed flex items-center gap-2"
                    title="Save Basic Information First">
                    <span
                        class="bg-gray-200 text-gray-500 rounded-full h-5 w-5 flex items-center justify-center text-xs">2</span>
                    Paper Selection <i class="fas fa-lock ml-1 text-xs text-gray-300"></i>
                </div>
                <div class="px-6 py-4 text-gray-400 font-bold text-sm border-l border-gray-200 cursor-not-allowed flex items-center gap-2"
                    title="Save Basic Information First">
                    <span
                        class="bg-gray-200 text-gray-500 rounded-full h-5 w-5 flex items-center justify-center text-xs">3</span>
                    Upload Document <i class="fas fa-lock ml-1 text-xs text-gray-300"></i>
                </div>
                <div class="px-6 py-4 text-gray-400 font-bold text-sm border-l border-gray-200 cursor-not-allowed flex items-center gap-2"
                    title="Save Basic Information First">
                    <span
                        class="bg-gray-200 text-gray-500 rounded-full h-5 w-5 flex items-center justify-center text-xs">4</span>
                    Payment Information <i class="fas fa-lock ml-1 text-xs text-gray-300"></i>
                </div>
            @endif
        </div>

        <form method="POST"
            action="{{ isset($student) ? route('admin.student.basic_info.update', $student->student_id) : route('admin.student.basic_info.store') }}"
            class="p-6 space-y-8 bg-yellow-50/40">
            @csrf
            @if (isset($student))
                @method('PATCH')
            @endif

            <div class="border border-yellow-200 rounded-lg bg-yellow-50/70 p-6">
                <div class="flex justify-between items-center border-b border-dashed border-yellow-300 pb-3 mb-5">
                    <h3 class="text-lg font-extrabold text-gray-800">Personal Information</h3>
                    <a href="#" class="text-sm font-bold text-blue-600 hover:underline">Don't have ABC ID / APAAR
                        Id?
                        Click here to create</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-5">

                    <div class="col-span-1 md:col-span-3 lg:col-span-4 mb-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Registration Number <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="reg_no" value="{{ old('reg_no', $student->reg_no ?? '') }}"
                            required placeholder="e.g. REG-2024-001"
                            class="border border-blue-200 w-full md:w-1/3 p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white font-mono font-bold text-purple-700">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">First Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="surname" value="{{ old('surname', $student->surname ?? '') }}"
                            required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                        <span class="text-[10px] text-purple-600 font-bold">(As per Marks Memo)</span>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">SurName <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="first_name"
                            value="{{ old('first_name', $student->first_name ?? '') }}" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Middle Name</label>
                        <input type="text" name="middle_name"
                            value="{{ old('middle_name', $student->middle_name ?? '') }}"
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div class="col-span-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="full_name"
                            value="{{ old('full_name', $student->full_name ?? '') }}" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-gray-50 text-gray-600">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Mother Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="mother_name"
                            value="{{ old('mother_name', $student->mother_name ?? '') }}" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Father Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="father_name"
                            value="{{ old('father_name', $student->father_name ?? '') }}" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Gender <span
                                class="text-red-500">*</span></label>
                        <select name="gender" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                            <option value="Female"
                                {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>
                                Female
                            </option>
                            <option value="Male"
                                {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>
                                Male
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Date of Birth <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="dob" value="{{ old('dob', $student->dob ?? '') }}" required
                            class="border border-yellow-400 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">ABC Id / APAAR Id</label>
                        <input type="text" name="abc_id" value="{{ old('abc_id', $student->abc_id ?? '') }}"
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Aadhaar No <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="aadhaar_no"
                            value="{{ old('aadhaar_no', $student->aadhaar_no ?? '') }}" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nationality <span
                                class="text-red-500">*</span></label>
                        <select name="nationality" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                            <option value="Indian">Indian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Domicile <span
                                class="text-red-500">*</span></label>
                        <select name="domicile" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Mobile <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="mobile_no"
                            value="{{ old('mobile_no', $student->mobile_no ?? '') }}" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Email Address <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $student->email ?? '') }}"
                            required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Religion</label>
                        <select name="religion"
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                            <option value="Islam"
                                {{ old('religion', $student->religion ?? '') == 'Islam' ? 'selected' : '' }}>
                                Islam
                            </option>
                            <option value="Hindu"
                                {{ old('religion', $student->religion ?? '') == 'Hindu' ? 'selected' : '' }}>
                                Hindu
                            </option>
                            <option value="Christian"
                                {{ old('religion', $student->religion ?? '') == 'Christian' ? 'selected' : '' }}>
                                Christian
                            </option>
                            <option value="Sikh"
                                {{ old('religion', $student->religion ?? '') == 'Sikh' ? 'selected' : '' }}>
                                Sikh
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Category <span
                                class="text-red-500">*</span></label>
                        <select name="category" required
                            class="border border-blue-200 w-full p-2 rounded outline-none focus:ring-2 focus:ring-purple-500 text-sm bg-white">
                            <option value="OPEN"
                                {{ old('category', $student->category ?? '') == 'OPEN' ? 'selected' : '' }}>
                                OPEN
                            </option>
                            <option value="OBC"
                                {{ old('category', $student->category ?? '') == 'OBC' ? 'selected' : '' }}>
                                OBC
                            </option>
                            <option value="SC"
                                {{ old('category', $student->category ?? '') == 'SC' ? 'selected' : '' }}>
                                SC
                            </option>
                            <option value="ST"
                                {{ old('category', $student->category ?? '') == 'ST' ? 'selected' : '' }}>
                                ST
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Marital Status <span
                                class="text-red-500">*</span></label>
                        <select name="marital_status"
                            class="border border-blue-200 w-full p-2 rounded outline-none bg-white text-sm">
                            <option value="Single"
                                {{ old('marital_status', $student->marital_status ?? '') == 'Single' ? 'selected' : '' }}>
                                Single
                            </option>
                            <option value="Married"
                                {{ old('marital_status', $student->marital_status ?? '') == 'Married' ? 'selected' : '' }}>
                                Married
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Is Blind? <span
                                class="text-red-500">*</span></label>
                        <select name="is_blind"
                            class="border border-blue-200 w-full p-2 rounded outline-none bg-white text-sm">
                            <option value="0"
                                {{ old('is_blind', $student->is_blind ?? '') == '0' ? 'selected' : '' }}>No
                            </option>
                            <option value="1"
                                {{ old('is_blind', $student->is_blind ?? '') == '1' ? 'selected' : '' }}>Yes
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Is Ph? <span
                                class="text-red-500">*</span></label>
                        <select name="is_ph"
                            class="border border-blue-200 w-full p-2 rounded outline-none bg-white text-sm">
                            <option value="0" {{ old('is_ph', $student->is_ph ?? '') == '0' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="1" {{ old('is_ph', $student->is_ph ?? '') == '1' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Is BPL? <span
                                class="text-red-500">*</span></label>
                        <select name="is_bpl"
                            class="border border-blue-200 w-full p-2 rounded outline-none bg-white text-sm">
                            <option value="0"
                                {{ old('is_bpl', $student->is_bpl ?? '') == '0' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="1"
                                {{ old('is_bpl', $student->is_bpl ?? '') == '1' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Is Minority? <span
                                class="text-red-500">*</span></label>
                        <select name="is_minority"
                            class="border border-blue-200 w-full p-2 rounded outline-none bg-white text-sm">
                            <option value="0"
                                {{ old('is_minority', $student->is_minority ?? '') == '0' ? 'selected' : '' }}>
                                No
                            </option>
                            <option value="1"
                                {{ old('is_minority', $student->is_minority ?? '') == '1' ? 'selected' : '' }}>
                                Yes
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="border border-yellow-200 rounded-lg bg-yellow-50/70 p-6">
                <div class="border-b border-dashed border-yellow-300 pb-3 mb-5">
                    <h3 class="text-lg font-extrabold text-gray-800">Address</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4 border-r border-dashed border-yellow-300 pr-6">
                        <h4 class="font-bold text-gray-800 text-sm underline decoration-2 underline-offset-4 mb-4">
                            Present Address</h4>

                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">Address Line 1: <span
                                    class="text-red-500">*</span></label><input type="text"
                                name="present_address_1"
                                value="{{ old('present_address_1', $student->present_address_1 ?? '') }}" required
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">Address Line 2:</label><input
                                type="text" name="present_address_2"
                                value="{{ old('present_address_2', $student->present_address_2 ?? '') }}"
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">City: <span
                                    class="text-red-500">*</span></label><input type="text" name="present_city"
                                value="{{ old('present_city', $student->present_city ?? '') }}" required
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">Country: <span
                                    class="text-red-500">*</span></label><input type="text" name="present_country"
                                value="India" readonly
                                class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none bg-gray-50 text-gray-500">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">State: <span
                                    class="text-red-500">*</span></label><input type="text" name="present_state"
                                value="{{ old('present_state', $student->present_state ?? '') }}" required
                                class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">District: <span
                                    class="text-red-500">*</span></label><input type="text"
                                name="present_district"
                                value="{{ old('present_district', $student->present_district ?? '') }}" required
                                class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">PIN: <span
                                    class="text-red-500">*</span></label><input type="text" name="present_pin"
                                value="{{ old('present_pin', $student->present_pin ?? '') }}" required
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                    </div>

                    <div class="space-y-4 pl-2">
                        <div class="flex items-center gap-3 mb-4">
                            <h4 class="font-bold text-gray-800 text-sm underline decoration-2 underline-offset-4">
                                Permanent Address</h4>
                            <label class="flex items-center text-xs font-bold text-gray-600"><input type="checkbox"
                                    class="mr-2 h-3 w-3 text-blue-600" onchange="copyAddress(this)">
                                Same as Present</label>
                        </div>

                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">Address Line 1: <span
                                    class="text-red-500">*</span></label><input type="text" id="perm_addr_1"
                                name="permanent_address_1"
                                value="{{ old('permanent_address_1', $student->permanent_address_1 ?? '') }}" required
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">Address Line 2:</label><input
                                type="text" id="perm_addr_2" name="permanent_address_2"
                                value="{{ old('permanent_address_2', $student->permanent_address_2 ?? '') }}"
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">City: <span
                                    class="text-red-500">*</span></label><input type="text" id="perm_city"
                                name="permanent_city"
                                value="{{ old('permanent_city', $student->permanent_city ?? '') }}" required
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">Country: <span
                                    class="text-red-500">*</span></label><input type="text" id="perm_country"
                                name="permanent_country" value="India" readonly
                                class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none bg-gray-50 text-gray-500">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">State: <span
                                    class="text-red-500">*</span></label><input type="text" id="perm_state"
                                name="permanent_state"
                                value="{{ old('permanent_state', $student->permanent_state ?? '') }}" required
                                class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">District: <span
                                    class="text-red-500">*</span></label><input type="text" id="perm_district"
                                name="permanent_district"
                                value="{{ old('permanent_district', $student->permanent_district ?? '') }}" required
                                class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                        <div class="flex items-center gap-4"><label
                                class="w-32 text-xs font-bold text-gray-700 text-right">PIN: <span
                                    class="text-red-500">*</span></label><input type="text" id="perm_pin"
                                name="permanent_pin"
                                value="{{ old('permanent_pin', $student->permanent_pin ?? '') }}" required
                                class="flex-1 border border-yellow-400 p-2 rounded text-sm outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-yellow-200 rounded-lg bg-yellow-50/70 p-6">
                <div class="border-b border-dashed border-yellow-300 pb-3 mb-5">
                    <h3 class="text-lg font-extrabold text-gray-800">Details about Qualified / Previous Examination
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                    <div class="flex items-center gap-4"><label class="w-48 text-xs font-bold text-gray-700">Admission
                            Type <span class="text-red-500">*</span></label><select name="admission_type"
                            class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none bg-white">
                            <option value="Regular"
                                {{ old('admission_type', $student->admission_type ?? '') == 'Regular' ? 'selected' : '' }}>
                                Regular
                            </option>
                        </select></div>
                    <div class="flex items-center gap-4"><label
                            class="w-32 text-xs font-bold text-gray-700">Month/Year
                            Passing <span class="text-red-500">*</span></label><input type="text"
                            name="passing_month_year"
                            value="{{ old('passing_month_year', $student->passing_month_year ?? '') }}"
                            placeholder="e.g., March 2022"
                            class="flex-1 border border-gray-300 p-2 rounded text-sm outline-none bg-white" required>
                    </div>
                    <div class="flex items-center gap-4"><label class="w-48 text-xs font-bold text-gray-700">Name of
                            Examination <span class="text-red-500">*</span></label><input type="text"
                            name="exam_name" value="{{ old('exam_name', $student->exam_name ?? '') }}"
                            class="flex-1 border border-gray-300 p-2 rounded text-sm bg-white" required></div>
                    <div class="flex items-center gap-4"><label class="w-32 text-xs font-bold text-gray-700">Name of
                            Institution <span class="text-red-500">*</span></label><input type="text"
                            name="institution_name"
                            value="{{ old('institution_name', $student->institution_name ?? '') }}"
                            class="flex-1 border border-blue-200 p-2 rounded text-sm bg-white" required></div>
                    <div class="flex items-center gap-4"><label class="w-48 text-xs font-bold text-gray-700">Board /
                            University Type <span class="text-red-500">*</span></label><input type="text"
                            name="board_type" value="{{ old('board_type', $student->board_type ?? '') }}"
                            class="flex-1 border border-gray-300 p-2 rounded text-sm bg-white" required></div>
                    <div class="flex items-center gap-4"><label class="w-32 text-xs font-bold text-gray-700">Board
                            Name
                            <span class="text-red-500">*</span></label><input type="text"
                            name="board_university_name"
                            value="{{ old('board_university_name', $student->board_university_name ?? '') }}"
                            class="flex-1 border border-blue-200 p-2 rounded text-sm bg-white" required></div>

                    <div class="flex items-center gap-4"><label class="w-48 text-xs font-bold text-gray-700">Obtained
                            Marks</label><input type="number" step="0.01" id="obtained" name="obtained_marks"
                            value="{{ old('obtained_marks', $student->obtained_marks ?? '') }}"
                            class="flex-1 border border-yellow-400 p-2 rounded text-sm bg-white"
                            oninput="calculatePercentage()"></div>
                    <div class="flex items-center gap-4"><label class="w-32 text-xs font-bold text-gray-700">Maximum
                            Marks</label><input type="number" step="0.01" id="maximum" name="max_marks"
                            value="{{ old('max_marks', $student->max_marks ?? '') }}"
                            class="flex-1 border border-yellow-400 p-2 rounded text-sm bg-white"
                            oninput="calculatePercentage()"></div>
                    <div class="flex items-center gap-4"><label
                            class="w-48 text-xs font-bold text-gray-700">Percentage</label><input type="number"
                            step="0.01" id="percentage" name="percentage"
                            value="{{ old('percentage', $student->percentage ?? '') }}" readonly
                            class="flex-1 border border-yellow-400 p-2 rounded text-sm bg-gray-50 text-gray-500">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="bg-purple-600 text-white px-8 py-3 rounded font-bold shadow-lg hover:bg-purple-700 transition">
                    {{ isset($student) ? 'Update Basic Information' : 'Save Basic Information & Continue' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        // Auto-generate Full Name
        const inputs = ['surname', 'first_name', 'middle_name'].map(name => document.querySelector(
            `input[name="${name}"]`));
        const fullNameInput = document.querySelector('input[name="full_name"]');

        inputs.forEach(input => {
            if (input) {
                input.addEventListener('input', () => {
                    fullNameInput.value = inputs.map(i => i.value.trim()).filter(Boolean).join(' ')
                        .toUpperCase();
                });
            }
        });

        // Copy Address Logic
        function copyAddress(checkbox) {
            const fields = ['address_1', 'address_2', 'city', 'state', 'district', 'pin'];
            fields.forEach(field => {
                const source = document.querySelector(`input[name="present_${field}"]`).value;
                const target = document.getElementById(`perm_${field}`);
                if (checkbox.checked) {
                    target.value = source;
                    target.classList.add('bg-gray-50');
                    target.readOnly = true;
                } else {
                    target.value = '';
                    target.classList.remove('bg-gray-50');
                    target.readOnly = false;
                }
            });
        }

        // Percentage Calculator
        function calculatePercentage() {
            const ob = parseFloat(document.getElementById('obtained').value) || 0;
            const max = parseFloat(document.getElementById('maximum').value) || 0;
            const perc = document.getElementById('percentage');
            if (max > 0) {
                perc.value = ((ob / max) * 100).toFixed(2);
            } else {
                perc.value = '';
            }
        }
    </script>
</x-admin-layout>
