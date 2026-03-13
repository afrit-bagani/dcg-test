<aside class="w-64 bg-slate-900 text-white flex flex-col shadow-lg z-10">
    <div class="p-6 text-xl font-bold border-b border-slate-800 tracking-wider">UMS Admin</div>
    <nav class="flex-1 p-4 space-y-2">

        <a href="{{ route('admin.batch.index') }}"
            class="block px-4 py-3 rounded transition-colors {{ request()->routeIs('admin.batch.*') ? 'bg-blue-600 shadow' : 'hover:bg-slate-800' }}">
            <i class="fas fa-layer-group w-6"></i> Batch
        </a>

        <a href="{{ route('admin.programme.index') }}"
            class="block px-4 py-3 rounded transition-colors {{ request()->routeIs('admin.programme.*') ? 'bg-blue-600 shadow' : 'hover:bg-slate-800' }}">
            <i class="fas fa-graduation-cap w-6"></i> Programme
        </a>

        <a href="{{ route('admin.course.index') }}"
            class="block px-4 py-3 rounded transition-colors {{ request()->routeIs('admin.course.*') ? 'bg-blue-600 shadow' : 'hover:bg-slate-800' }}">
            <i class="fas fa-book w-6"></i> Course
        </a>

        <a href="{{ route('admin.subject.index') }}"
            class="block px-4 py-3 rounded transition-colors {{ request()->routeIs('admin.subject.*') ? 'bg-blue-600 shadow' : 'hover:bg-slate-800' }}">
            <i class="fas fa-book-open w-6"></i> Subject
        </a>

        <a href="{{ route('admin.student.index') }}"
            class="block px-4 py-3 rounded transition-colors {{ request()->routeIs('admin.student.*') ? 'bg-blue-600 shadow' : 'hover:bg-slate-800' }}">
            <i class="fa-solid fa-user-graduate w-6"></i> Student
        </a>

    </nav>
</aside>
