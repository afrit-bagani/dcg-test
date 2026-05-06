<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Student Registration - DCG Education</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .step-hidden { display: none; }
        .step-active { display: block; animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        /* Glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .form-input {
            width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-size: 0.875rem; transition: all 0.2s; background: #f8fafc;
        }
        .form-input:focus {
            outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2); background: #ffffff;
        }
        .form-label {
            display: block; font-size: 0.75rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 min-h-screen text-gray-800 font-sans selection:bg-purple-500 selection:text-white">

    <div class="max-w-5xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-white tracking-tight mb-2 drop-shadow-md">Online Registration</h1>
            <p class="text-purple-200">Join our academic programs by completing the form below.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-md mb-6 max-w-2xl mx-auto flex items-center">
                <i class="fas fa-check-circle mr-3 text-xl"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md mb-6 max-w-2xl mx-auto">
                <strong class="font-bold mb-2 block">Please fix the following errors:</strong>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!session('success'))
        <div class="glass-card rounded-2xl shadow-2xl overflow-hidden pb-8">
            <!-- Stepper Navigation -->
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between relative">
                    <!-- Progress Bar Background -->
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 z-0"></div>
                    <!-- Progress Bar Fill -->
                    <div id="progress-bar-fill" class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-purple-600 transition-all duration-500 z-0" style="width: 0%;"></div>
                    
                    <!-- Steps -->
                    @php
                        $steps = ['Personal', 'Address', 'Academic', 'Program', 'Finalize'];
                    @endphp

                    @foreach($steps as $index => $step)
                        <div class="relative z-10 flex flex-col items-center group cursor-pointer" onclick="goToStep({{ $index + 1 }})">
                            <div id="step-indicator-{{ $index + 1 }}" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 {{ $index == 0 ? 'bg-purple-600 text-white shadow-lg shadow-purple-500/50' : 'bg-white text-gray-400 border-2 border-gray-200' }}">
                                {{ $index + 1 }}
                            </div>
                            <span id="step-label-{{ $index + 1 }}" class="mt-2 text-xs font-bold uppercase tracking-wider {{ $index == 0 ? 'text-purple-700' : 'text-gray-400' }}">{{ $step }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Form starts -->
            <form id="registrationForm" action="{{ route('student.register.store') }}" method="POST" enctype="multipart/form-data" class="px-8 mt-8">
                @csrf

                <!-- STEP 1: Personal Details -->
                <div id="step-1" class="step-active">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-purple-100 flex items-center">
                        <i class="fas fa-user-circle text-purple-600 mr-3"></i> Personal Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div>
                            <label class="form-label">Surname <span class="text-red-500">*</span></label>
                            <input type="text" name="surname" value="{{ old('surname') }}" required class="form-input" id="inp_surname">
                        </div>
                        <div>
                            <label class="form-label">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="form-input" id="inp_first">
                        </div>
                        <div>
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-input" id="inp_middle">
                        </div>
                        <div class="md:col-span-3 xl:col-span-1">
                            <label class="form-label">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="form-input bg-gray-100 cursor-not-allowed text-gray-500 font-semibold" id="inp_full" readonly>
                        </div>

                        <div>
                            <label class="form-label">Father's Name <span class="text-red-500">*</span></label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Mother's Name <span class="text-red-500">*</span></label>
                            <input type="text" name="mother_name" value="{{ old('mother_name') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="dob" value="{{ old('dob') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Gender <span class="text-red-500">*</span></label>
                            <select name="gender" required class="form-input">
                                <option value="">Select Gender</option>
                                <option value="Male" @selected(old('gender') == 'Male')>Male</option>
                                <option value="Female" @selected(old('gender') == 'Female')>Female</option>
                                <option value="Other" @selected(old('gender') == 'Other')>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Aadhaar No <span class="text-red-500">*</span></label>
                            <input type="text" name="aadhaar_no" value="{{ old('aadhaar_no') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Mobile No <span class="text-red-500">*</span></label>
                            <input type="tel" name="mobile_no" value="{{ old('mobile_no') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">ABC / APAAR ID</label>
                            <input type="text" name="abc_id" value="{{ old('abc_id') }}" class="form-input">
                        </div>
                        
                        <div>
                            <label class="form-label">Nationality <span class="text-red-500">*</span></label>
                            <input type="text" name="nationality" value="{{ old('nationality', 'Indian') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Domicile <span class="text-red-500">*</span></label>
                            <input type="text" name="domicile" value="{{ old('domicile', 'Maharashtra') }}" required class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Category <span class="text-red-500">*</span></label>
                            <select name="category" required class="form-input">
                                <option value="">Select Category</option>
                                <option value="OPEN" @selected(old('category') == 'OPEN')>OPEN</option>
                                <option value="OBC" @selected(old('category') == 'OBC')>OBC</option>
                                <option value="SC" @selected(old('category') == 'SC')>SC</option>
                                <option value="ST" @selected(old('category') == 'ST')>ST</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Marital Status <span class="text-red-500">*</span></label>
                            <select name="marital_status" required class="form-input">
                                <option value="Single" @selected(old('marital_status') == 'Single')>Single</option>
                                <option value="Married" @selected(old('marital_status') == 'Married')>Married</option>
                            </select>
                        </div>
                    </div>

                    <!-- Additional flags row -->
                    <div class="mt-8 bg-purple-50 p-5 rounded-xl border border-purple-100 flex flex-wrap gap-6 justify-between items-center">
                        <div class="flex items-center">
                            <label class="text-sm font-bold text-gray-700 mr-2">Is Blind?</label>
                            <select name="is_blind" class="form-input py-1 w-20">
                                <option value="0" @selected(old('is_blind') == '0')>No</option>
                                <option value="1" @selected(old('is_blind') == '1')>Yes</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label class="text-sm font-bold text-gray-700 mr-2">Is PH?</label>
                            <select name="is_ph" class="form-input py-1 w-20">
                                <option value="0" @selected(old('is_ph') == '0')>No</option>
                                <option value="1" @selected(old('is_ph') == '1')>Yes</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label class="text-sm font-bold text-gray-700 mr-2">Is BPL?</label>
                            <select name="is_bpl" class="form-input py-1 w-20">
                                <option value="0" @selected(old('is_bpl') == '0')>No</option>
                                <option value="1" @selected(old('is_bpl') == '1')>Yes</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <label class="text-sm font-bold text-gray-700 mr-2">Is Minority?</label>
                            <select name="is_minority" class="form-input py-1 w-20">
                                <option value="0" @selected(old('is_minority') == '0')>No</option>
                                <option value="1" @selected(old('is_minority') == '1')>Yes</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Address -->
                <div id="step-2" class="step-hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-purple-100 flex items-center">
                        <i class="fas fa-home text-purple-600 mr-3"></i> Contact & Address Settings
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Present Address -->
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center"><i class="fas fa-map-marker-alt text-amber-500 mr-2"></i> Present Address</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">Address Line 1 <span class="text-red-500">*</span></label>
                                    <input type="text" name="present_address_1" id="p_addr1" value="{{ old('present_address_1') }}" required class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" name="present_address_2" id="p_addr2" value="{{ old('present_address_2') }}" class="form-input">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">City <span class="text-red-500">*</span></label>
                                        <input type="text" name="present_city" id="p_city" value="{{ old('present_city') }}" required class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">PIN/Zip <span class="text-red-500">*</span></label>
                                        <input type="text" name="present_pin" id="p_pin" value="{{ old('present_pin') }}" required class="form-input">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">District <span class="text-red-500">*</span></label>
                                        <input type="text" name="present_district" id="p_dist" value="{{ old('present_district') }}" required class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">State <span class="text-red-500">*</span></label>
                                        <input type="text" name="present_state" id="p_state" value="{{ old('present_state') }}" required class="form-input">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Country <span class="text-red-500">*</span></label>
                                    <input type="text" name="present_country" id="p_country" value="{{ old('present_country', 'India') }}" required class="form-input">
                                </div>
                            </div>
                        </div>

                        <!-- Permanent Address -->
                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-gray-800 flex items-center"><i class="fas fa-map text-blue-500 mr-2"></i> Permanent Address</h3>
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="sameAddressBtn" class="rounded text-purple-600 focus:ring-purple-500 mr-2 border-gray-300 w-4 h-4">
                                    <span class="text-sm font-bold text-gray-600">Same as Present</span>
                                </label>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">Address Line 1 <span class="text-red-500">*</span></label>
                                    <input type="text" name="permanent_address_1" id="perm_addr1" value="{{ old('permanent_address_1') }}" required class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Address Line 2</label>
                                    <input type="text" name="permanent_address_2" id="perm_addr2" value="{{ old('permanent_address_2') }}" class="form-input">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">City <span class="text-red-500">*</span></label>
                                        <input type="text" name="permanent_city" id="perm_city" value="{{ old('permanent_city') }}" required class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">PIN/Zip <span class="text-red-500">*</span></label>
                                        <input type="text" name="permanent_pin" id="perm_pin" value="{{ old('permanent_pin') }}" required class="form-input">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">District <span class="text-red-500">*</span></label>
                                        <input type="text" name="permanent_district" id="perm_dist" value="{{ old('permanent_district') }}" required class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">State <span class="text-red-500">*</span></label>
                                        <input type="text" name="permanent_state" id="perm_state" value="{{ old('permanent_state') }}" required class="form-input">
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Country <span class="text-red-500">*</span></label>
                                    <input type="text" name="permanent_country" id="perm_country" value="{{ old('permanent_country', 'India') }}" required class="form-input">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Previous Academic Details -->
                <div id="step-3" class="step-hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-purple-100 flex items-center">
                        <i class="fas fa-graduation-cap text-purple-600 mr-3"></i> Previous Examination Details
                    </h2>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label">Admission Type <span class="text-red-500">*</span></label>
                                <select name="admission_type" required class="form-input">
                                    <option value="Regular" @selected(old('admission_type', 'Regular') == 'Regular')>Regular</option>
                                    <option value="Lateral" @selected(old('admission_type') == 'Lateral')>Lateral</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Name Of Examination <span class="text-red-500">*</span></label>
                                <input type="text" name="exam_name" value="{{ old('exam_name') }}" required placeholder="e.g. 12th HSC" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Passing Month & Year <span class="text-red-500">*</span></label>
                                <input type="text" name="passing_month_year" value="{{ old('passing_month_year') }}" required placeholder="e.g. March 2022" class="form-input">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="form-label">Institution Attended <span class="text-red-500">*</span></label>
                                <input type="text" name="institution_name" value="{{ old('institution_name') }}" required placeholder="Name of previous School/College" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Board / University Type <span class="text-red-500">*</span></label>
                                <input type="text" name="board_type" value="{{ old('board_type') }}" required placeholder="State Board, CBSE, etc." class="form-input">
                            </div>
                            <div class="md:col-span-2">
                                <label class="form-label">Name of Board/University <span class="text-red-500">*</span></label>
                                <input type="text" name="board_university_name" value="{{ old('board_university_name') }}" required placeholder="Full name of Board/University" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Division / Class</label>
                                <input type="text" name="division_class" value="{{ old('division_class') }}" class="form-input">
                            </div>
                        </div>

                        <div class="mt-8 border-t border-gray-200 pt-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="form-label">Max Marks</label>
                                <input type="number" step="0.01" name="max_marks" id="max_marks" value="{{ old('max_marks') }}" class="form-input" oninput="calcPercent()">
                            </div>
                            <div>
                                <label class="form-label">Obtained Marks</label>
                                <input type="number" step="0.01" name="obtained_marks" id="obtained_marks" value="{{ old('obtained_marks') }}" class="form-input" oninput="calcPercent()">
                            </div>
                            <div>
                                <label class="form-label">Percentage (%)</label>
                                <input type="text" name="percentage" id="percentage" value="{{ old('percentage') }}" class="form-input bg-gray-100 font-bold" readonly>
                            </div>
                            <div>
                                <label class="form-label">Grade / CGPA</label>
                                <input type="text" name="grade_cgpa" value="{{ old('grade_cgpa') }}" class="form-input">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Program Selection -->
                <div id="step-4" class="step-hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-purple-100 flex items-center">
                        <i class="fas fa-book-open text-purple-600 mr-3"></i> Choose Your Program
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-8 border-2 border-dashed border-purple-200 rounded-2xl bg-purple-50/50">
                        <div>
                            <label class="form-label">Programme Level <span class="text-red-500">*</span></label>
                            <select name="programme_id" id="programme_sel" required class="form-input border-purple-200 shadow-sm" onchange="filterCourses()">
                                <option value="">Select Programme</option>
                                @foreach($programmes as $prog)
                                    <option value="{{ $prog->programme_id }}">{{ $prog->name }} ({{ $prog->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Select Course <span class="text-red-500">*</span></label>
                            <select name="course_id" id="course_sel" required class="form-input border-purple-200 shadow-sm disabled:bg-gray-100" disabled>
                                <option value="">Please complete programme</option>
                                <!-- Populated dynamically based on programme -->
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Academic Batch <span class="text-red-500">*</span></label>
                            <select name="batch_id" required class="form-input border-purple-200 shadow-sm">
                                <option value="">Select Batch/Year</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->batch_id }}">{{ $batch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: Finalize (Uploads & Payment) -->
                <div id="step-5" class="step-hidden">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-purple-100 flex items-center">
                        <i class="fas fa-check-double text-purple-600 mr-3"></i> Finalize Registration
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- File Uploads -->
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                            <h3 class="font-bold text-gray-800 mb-4 border-b border-gray-200 pb-2 flex items-center"><i class="fas fa-cloud-upload-alt text-indigo-500 mr-2"></i> Document Uploads</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="form-label mb-2">Passport Size Photo <span class="text-xs text-gray-400 font-normal ml-2">(Max 2MB, JPG/PNG)</span></label>
                                    <input type="file" name="photo" accept=".png, .jpg, .jpeg" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded cursor-pointer bg-white">
                                </div>
                                <div>
                                    <label class="form-label mb-2">Digital Signature <span class="text-xs text-gray-400 font-normal ml-2">(Max 2MB, JPG/PNG)</span></label>
                                    <input type="file" name="signature" accept=".png, .jpg, .jpeg" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-300 rounded cursor-pointer bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- Payment details -->
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <h3 class="font-bold text-gray-800 mb-4 border-b border-green-200 pb-2 flex items-center"><i class="fas fa-university text-green-600 mr-2"></i> Payment Information</h3>
                            <p class="text-xs text-gray-600 mb-4">Please complete your fee transfer and enter the transaction slip details here.</p>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label text-green-800">Paid Amount (₹) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required class="form-input border-green-300">
                                </div>
                                <div>
                                    <label class="form-label text-green-800">Bank Transaction ID / UTR <span class="text-red-500">*</span></label>
                                    <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" required class="form-input border-green-300 font-mono text-sm uppercase">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label text-green-800">Payment Date <span class="text-red-500">*</span></label>
                                        <input type="date" name="payment_date" value="{{ old('payment_date') }}" required class="form-input border-green-300">
                                    </div>
                                    <div>
                                        <label class="form-label text-green-800">Status <span class="text-red-500">*</span></label>
                                        <select name="payment_status" required class="form-input border-green-300">
                                            <option value="Success">Success (Paid)</option>
                                            <option value="Pending">Pending (Draft)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-10 flex justify-between bg-gray-50 -mx-8 -mb-8 px-8 py-5 border-t border-gray-200">
                    <button type="button" id="btn-prev" onclick="prevStep()" class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300 transition flex items-center hidden">
                        <i class="fas fa-arrow-left mr-2"></i> Previous
                    </button>
                    <div id="btn-prev-placeholder" class="px-6 py-3"></div>
                    
                    <div class="flex gap-4">
                        <button type="button" id="btn-next" onclick="nextStep()" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-md hover:bg-indigo-700 transition flex items-center">
                            Next Step <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="button" id="btn-submit" onclick="validateAndSubmit()" class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-extrabold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition hidden flex items-center">
                            <i class="fas fa-paper-plane mr-3"></i> Submit Application
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @else
            <!-- Success Screen -->
            <div class="text-center mt-12 mb-12">
                <a href="{{ route('student.register.create') }}" class="px-8 py-3 bg-white text-purple-700 font-bold rounded-full shadow hover:bg-gray-50 transition inline-block">
                    Return to Registration Form
                </a>
            </div>
        @endif
    </div>

    <!-- Scripts -->
    <script>
        // Store all courses for dynamic filtering
        const allCourses = @json($courses ?? []);
        
        function filterCourses() {
            const progSel = document.getElementById('programme_sel');
            const courseSel = document.getElementById('course_sel');
            const progId = progSel.value;
            
            courseSel.innerHTML = '<option value="">Select Course</option>';
            if(progId) {
                const filtered = allCourses.filter(c => c.programme_id == progId);
                filtered.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.course_id;
                    opt.textContent = c.name;
                    courseSel.appendChild(opt);
                });
                courseSel.disabled = false;
            } else {
                courseSel.disabled = true;
                courseSel.innerHTML = '<option value="">Please complete programme</option>';
            }
        }

        // Stepper functionality
        let currentStep = 1;
        const totalSteps = 5;
        
        function validateAndSubmit() {
            const form = document.getElementById('registrationForm');
            
            // Re-enable disabled fields momentarily to allow them to be submitted
            const disabledInputs = form.querySelectorAll(':disabled');
            disabledInputs.forEach(i => i.disabled = false);

            if (!form.checkValidity()) {
                // Form is invalid: find the first invalid element
                const invalidInput = form.querySelector(':invalid');
                if (invalidInput) {
                    const stepDiv = invalidInput.closest('div[id^="step-"]');
                    if (stepDiv) {
                        const stepNum = parseInt(stepDiv.id.replace('step-', ''));
                        goToStep(stepNum);
                        setTimeout(() => invalidInput.reportValidity(), 100);
                    }
                }
                
                // Re-disable what was disabled if validation failed
                disabledInputs.forEach(i => i.disabled = true);
                return;
            }
            
            // Validation passed
            form.submit();
        }

        function nextStep() {
            if(currentStep < totalSteps) {
                goToStep(currentStep + 1);
            }
        }

        function prevStep() {
            if(currentStep > 1) {
                goToStep(currentStep - 1);
            }
        }

        function goToStep(stepIndex) {
            // Check if proceeding forward and do a soft validation
            if (stepIndex > currentStep) {
                const stepDiv = document.getElementById(`step-${currentStep}`);
                const inputs = stepDiv.querySelectorAll('input[required], select[required]');
                let isValid = true;
                inputs.forEach(inp => {
                    if(!inp.checkValidity()) {
                        inp.reportValidity();
                        isValid = false;
                    }
                });
                if (!isValid) return; // Block moving forward but don't block jumping back or submitting (handled above)
            }

            // Uncheck/Hide all
            for(let i=1; i<=totalSteps; i++) {
                let el = document.getElementById(`step-${i}`);
                if(el) {
                    el.classList.add('step-hidden');
                    el.classList.remove('step-active');
                }
                
                let indicator = document.getElementById(`step-indicator-${i}`);
                let label = document.getElementById(`step-label-${i}`);
                
                if(i < stepIndex) {
                    // Completed
                    indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 bg-green-500 text-white shadow-md';
                    indicator.innerHTML = '<i class="fas fa-check"></i>';
                    label.className = `mt-2 text-xs font-bold uppercase tracking-wider text-green-600`;
                } else if (i === stepIndex) {
                    // Active
                    indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 bg-purple-600 text-white shadow-lg shadow-purple-500/50';
                    indicator.innerHTML = i;
                    label.className = `mt-2 text-xs font-bold uppercase tracking-wider text-purple-700`;
                } else {
                    // Future
                    indicator.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 bg-white text-gray-400 border-2 border-gray-200';
                    indicator.innerHTML = i;
                    label.className = `mt-2 text-xs font-bold uppercase tracking-wider text-gray-400`;
                }
            }
            
            document.getElementById(`step-${stepIndex}`).classList.remove('step-hidden');
            document.getElementById(`step-${stepIndex}`).classList.add('step-active');
            
            // Manage buttons
            const btnPrev = document.getElementById('btn-prev');
            const btnPrevPh = document.getElementById('btn-prev-placeholder');
            const btnNext = document.getElementById('btn-next');
            const btnSubmit = document.getElementById('btn-submit');
            
            if(stepIndex === 1) {
                btnPrev.classList.add('hidden');
                btnPrevPh.classList.remove('hidden');
            } else {
                btnPrev.classList.remove('hidden');
                btnPrevPh.classList.add('hidden');
            }
            
            if(stepIndex === totalSteps) {
                btnNext.classList.add('hidden');
                btnSubmit.classList.remove('hidden');
            } else {
                btnNext.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
            }
            
            // Progress bar
            const progress = ((stepIndex - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progress-bar-fill').style.width = `${progress}%`;
            
            currentStep = stepIndex;
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        // Auto calculate full name
        const n1 = document.getElementById('inp_surname');
        const n2 = document.getElementById('inp_first');
        const n3 = document.getElementById('inp_middle');
        const full = document.getElementById('inp_full');
        
        function updateFullName() {
            let parts = [n1.value.trim(), n2.value.trim(), n3.value.trim()].filter(Boolean);
            if(full) full.value = parts.join(' ').toUpperCase();
        }
        
        [n1, n2, n3].forEach(el => {
            if(el) el.addEventListener('input', updateFullName);
        });

        // Copy address
        document.getElementById('sameAddressBtn').addEventListener('change', function(e) {
            if(this.checked) {
                document.getElementById('perm_addr1').value = document.getElementById('p_addr1').value;
                document.getElementById('perm_addr2').value = document.getElementById('p_addr2').value;
                document.getElementById('perm_city').value = document.getElementById('p_city').value;
                document.getElementById('perm_dist').value = document.getElementById('p_dist').value;
                document.getElementById('perm_state').value = document.getElementById('p_state').value;
                document.getElementById('perm_pin').value = document.getElementById('p_pin').value;
                document.getElementById('perm_country').value = document.getElementById('p_country').value;
            } else {
                ['addr1','addr2','city','dist','state','pin'].forEach(f => {
                    document.getElementById(`perm_${f}`).value = '';
                });
            }
        });

        // Percent calculate
        function calcPercent() {
            let max = parseFloat(document.getElementById('max_marks').value) || 0;
            let ob = parseFloat(document.getElementById('obtained_marks').value) || 0;
            if(max > 0) {
                document.getElementById('percentage').value = ((ob / max) * 100).toFixed(2);
            }
        }
    </script>
</body>
</html>
