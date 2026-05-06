@props(['title'])

<x-app :$title>

    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <main class="flex-1 p-8 overflow-y-auto bg-gray-50">

            @if (session('success'))
                <div
                    class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded shadow-sm mb-6 flex items-center">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded shadow-sm mb-6">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}

        </main>
    </div>

</x-app>
