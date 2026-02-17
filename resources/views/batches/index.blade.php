<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batch Master</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="bg-white shadow p-4 mb-6">
        <div class="max-w-7xl mx-auto flex justify-between">
            <h1 class="text-xl font-bold text-gray-800">Batch Management</h1>
            <a href="/" class="text-blue-600 hover:underline">Home</a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('status') }}</p>
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

            <form action="{{ route('batches.store') }}" method="POST" class="flex gap-2 w-full md:w-auto">
                @csrf
                <input type="text" name="code" placeholder="Batch Code" required
                    class="border rounded px-3 py-2 text-sm w-32 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="name" placeholder="Batch Name" required
                    class="border rounded px-3 py-2 text-sm w-48 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-1"></i> Add
                </button>
            </form>

            <form method="GET" action="{{ route('batches.index') }}" class="w-full md:w-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search batch..."
                        class="w-full md:w-64 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="absolute right-3 top-2 text-gray-400">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <form action="{{ route('batches.bulk') }}" method="POST" id="bulkForm">
            @csrf

            <div class="bg-white p-4 rounded-t-lg border-b flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <select name="action" required
                        class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Select Action</option>
                        <option value="Active">Mark as Active</option>
                        <option value="Inactive">Mark as Inactive</option>
                        <option value="Delete">Delete Selected</option>
                    </select>
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600 transition">
                        <i class="fas fa-paper-plane mr-1"></i> Submit
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    Showing {{ $batches->count() }} records
                </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-b-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAll"
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Code
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($batches as $batch)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="checkbox" name="selected_ids[]" value="{{ $batch->batch_id }}"
                                        class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-gray-400 hover:text-blue-600 mr-3 transition"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="#" class="text-gray-400 hover:text-green-600 mr-3 transition"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button"
                                        onclick="openStatusModal('{{ $batch->batch_id }}', '{{ $batch->status }}')"
                                        class="text-gray-400 hover:text-yellow-600 transition" title="Update Status">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $batch->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $batch->status }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $batch->code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $batch->name }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No batches found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t">
                    {{ $batches->withQueryString()->links() }}
                </div>
            </div>
        </form>
    </div>

    <div id="statusModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeStatusModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <form id="statusForm" method="POST" action="">
                    @csrf
                    @method('PATCH') <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">

                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exchange-alt text-yellow-600"></i>
                            </div>

                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Update Batch Status
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Select the new status for this record.
                                    </p>

                                    <label for="modalStatus" class="block text-sm font-medium text-gray-700 mb-1">New
                                        Status</label>
                                    <select name="status" id="modalStatus"
                                        class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:w-auto sm:text-sm">
                            Update
                        </button>
                        <button type="button" onclick="closeStatusModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Select All Checkbox Logic
        document.getElementById('selectAll').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = e.target.checked);
        });

        // Modal Logic
        function openStatusModal(batchId, currentStatus) {
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            const select = document.getElementById('modalStatus');

            // Set the Action URL dynamically
            form.action = `/batches/${batchId}/status`;

            // Set the dropdown to the current status
            select.value = currentStatus;

            // Show Modal
            modal.classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        // Close on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeStatusModal();
            }
        });
    </script>

</body>

</html>
