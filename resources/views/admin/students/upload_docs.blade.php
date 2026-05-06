<x-admin-layout title="Upload Docs | Student | Dashboard">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Edit Student Record</h2>
            <p class="text-sm text-gray-500 mt-1">Uploading mandatory documents for
                <strong>{{ $student->full_name }}</strong> ({{ $student->reg_no }})
            </p>
        </div>
        <a href="{{ route('admin.student.index') }}"
            class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded font-bold shadow-sm hover:bg-gray-50 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Back to Directory
        </a>
    </div>

    <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">

        <div class="flex border-b border-gray-200 bg-gray-50 overflow-x-auto">
            <a href="{{ route('admin.student.basic_info.edit', $student->student_id) }}"
                class="px-6 py-4 text-gray-600 font-bold text-sm hover:bg-gray-100 flex items-center gap-2 transition">
                <span
                    class="bg-green-100 text-green-700 rounded-full h-5 w-5 flex items-center justify-center text-xs"><i
                        class="fas fa-check"></i></span>
                Basic Information
            </a>

            <a href="{{ route('admin.student.paper_selection.edit', $student->student_id) }}"
                class="px-6 py-4 text-gray-600 font-bold text-sm border-l border-gray-200 hover:bg-gray-100 flex items-center gap-2 transition">
                <span
                    class="bg-green-100 text-green-700 rounded-full h-5 w-5 flex items-center justify-center text-xs"><i
                        class="fas fa-check"></i></span>
                Paper Selection
            </a>

            <div
                class="px-6 py-4 bg-white border-t-2 border-purple-600 text-purple-700 font-bold text-sm shadow-sm border-l border-gray-200 flex items-center gap-2">
                <span
                    class="bg-purple-100 text-purple-700 rounded-full h-5 w-5 flex items-center justify-center text-xs">3</span>
                Upload Document
            </div>

            <a href="{{ route('admin.student.payment_info.edit', $student->student_id) }}"
                class="px-6 py-4 text-gray-600 font-bold text-sm border-l border-gray-200 hover:bg-gray-100 flex items-center gap-2">
                <span
                    class="bg-gray-200 text-gray-600 rounded-full h-5 w-5 flex items-center justify-center text-xs">4</span>
                Payment Information
            </a>
        </div>

        <form method="POST" action="{{ route('admin.student.upload_docs.update', $student->student_id) }}"
            enctype="multipart/form-data" class="p-6 space-y-8 bg-yellow-50/40">
            @csrf
            @method('PATCH')

            <div class="border border-yellow-200 rounded-lg bg-yellow-50/70 p-6">
                <div class="flex justify-between items-center border-b border-dashed border-yellow-300 pb-3 mb-5">
                    <h3 class="text-lg font-extrabold text-gray-800">Required Documents</h3>
                    <span class="text-xs font-bold text-amber-600 bg-amber-100 px-3 py-1 rounded-full">Max File Size:
                        2MB | Format: JPG, PNG</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div
                        class="bg-white p-5 rounded border border-gray-200 shadow-sm flex flex-col items-center justify-center">
                        <label class="block text-sm font-bold text-gray-700 mb-4 text-center">Student Photograph <span
                                class="text-red-500">*</span></label>

                        <div
                            class="w-32 h-40 bg-gray-100 border-2 border-dashed border-gray-300 rounded mb-4 flex items-center justify-center text-gray-400 overflow-hidden">
                            <i class="fas fa-user text-4xl"></i>
                        </div>

                        <input type="file" name="photo" accept="image/png, image/jpeg" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                    </div>

                    <div
                        class="bg-white p-5 rounded border border-gray-200 shadow-sm flex flex-col items-center justify-center">
                        <label class="block text-sm font-bold text-gray-700 mb-4 text-center">Student Signature <span
                                class="text-red-500">*</span></label>

                        <div
                            class="w-48 h-20 bg-gray-100 border-2 border-dashed border-gray-300 rounded mb-4 flex items-center justify-center text-gray-400 overflow-hidden">
                            <i class="fas fa-signature text-2xl"></i>
                        </div>

                        <input type="file" name="signature" accept="image/png, image/jpeg" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition">
                    </div>

                </div>
            </div>

            <div class="mt-8 flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="bg-purple-600 text-white px-8 py-3 rounded font-bold shadow-lg hover:bg-purple-700 transition">
                    Upload Documents & Continue
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
