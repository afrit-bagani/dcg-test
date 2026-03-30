<x-admin-layout>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Edit Student Record</h2>
            <p class="text-sm text-gray-500 mt-1">Finalizing registration for <strong>{{ $student->full_name }}</strong>
                ({{ $student->reg_no }})</p>
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
                <span class="bg-green-100 text-green-700 rounded-full h-5 w-5 flex items-center justify-center text-xs"><i
                        class="fas fa-check"></i></span> Basic Information
            </a>
            <a href="{{ route('admin.student.paper_selection.edit', $student->student_id) }}"
               class="px-6 py-4 text-gray-600 font-bold text-sm border-l border-gray-200 hover:bg-gray-100 flex items-center gap-2 transition">
                <span class="bg-green-100 text-green-700 rounded-full h-5 w-5 flex items-center justify-center text-xs"><i
                        class="fas fa-check"></i></span> Paper Selection
            </a>
            <a href="{{ route('admin.student.upload_docs.edit', $student->student_id) }}"
               class="px-6 py-4 text-gray-600 font-bold text-sm border-l border-gray-200 hover:bg-gray-100 flex items-center gap-2 transition">
                <span class="bg-green-100 text-green-700 rounded-full h-5 w-5 flex items-center justify-center text-xs"><i
                        class="fas fa-check"></i></span> Upload Document
            </a>

            <div
                class="px-6 py-4 bg-white border-t-2 border-purple-600 text-purple-700 font-bold text-sm shadow-sm border-l border-gray-200 flex items-center gap-2">
                <span
                    class="bg-purple-100 text-purple-700 rounded-full h-5 w-5 flex items-center justify-center text-xs">4</span>
                Payment Information
            </div>
        </div>

        <form method="POST" action="{{ route('admin.student.payment_info.update', $student->student_id) }}"
              class="p-6 space-y-8 bg-yellow-50/40">
            @csrf
            @method('PATCH')

            <div class="border border-yellow-200 rounded-lg bg-yellow-50/70 p-6">
                <div class="flex justify-between items-center border-b border-dashed border-yellow-300 pb-3 mb-5">
                    <h3 class="text-lg font-extrabold text-gray-800">Fee Collection Details</h3>
                    <i class="fas fa-file-invoice-dollar text-2xl text-gray-400"></i>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Fee Amount (₹) <span
                                class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="amount" required placeholder="0.00"
                               class="border border-yellow-400 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-purple-500 bg-white shadow-sm font-mono text-lg">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Transaction ID / Ref No. <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="transaction_id" required placeholder="e.g. TXN987654321"
                               class="border border-yellow-400 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-purple-500 bg-white shadow-sm font-mono">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Payment Date <span
                                class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}"
                               class="border border-yellow-400 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-purple-500 bg-white shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Payment Status <span
                                class="text-red-500">*</span></label>
                        <select name="payment_status" required
                                class="border border-yellow-400 w-full p-2.5 rounded outline-none focus:ring-2 focus:ring-purple-500 bg-white shadow-sm">
                            <option value="Success" class="text-green-600 font-bold">Success</option>
                            <option value="Pending" class="text-amber-600 font-bold">Pending</option>
                            <option value="Failed" class="text-red-600 font-bold">Failed</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="mt-8 flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                        class="bg-green-600 text-white px-8 py-3 rounded font-bold shadow-lg hover:bg-green-700 transition flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Complete Registration
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
