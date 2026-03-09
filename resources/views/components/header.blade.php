<div class="flex justify-between items-center p-4 border-b border-gray-200">

    <!-- LOGO -->
    <div class="flex items-center gap-2">
        <img src="{{ asset('images/logo-lms.png') }}" alt="Logo" class="w-12 h-12">

        <div>
            <h1 class="text-red-600 font-bold text-lg">Citra Husada</h1>
            <p class="text-green-600 text-sm">Learning Management System</p>
        </div>
    </div>

    <!-- NOTIF + USER -->
    <div class="flex items-center gap-4">

        @include('components.notif')

        <div class="text-right">
            <p class="font-medium">Agung Sunaryo</p>
            <p class="text-sm text-gray-500">TIK Unit</p>
        </div>
    </div>
</div>