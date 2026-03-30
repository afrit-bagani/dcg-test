<x-admin-layout>

    @if (request()->routeIs('admin.batch.*'))
        @include('admin.modules.batch')
    @elseif(request()->routeIs('admin.programme.*'))
        @include('admin.modules.programme')
    @elseif(request()->routeIs('admin.course.*'))
        @include('admin.modules.course')
    @elseif(request()->routeIs('admin.subject.*'))
        @include('admin.modules.subject')
    @elseif(request()->routeIs('admin.student.*'))
        {{--        @include('admin.modules.student')--}}
        @include('admin.students.index')
    @endif

</x-admin-layout>
