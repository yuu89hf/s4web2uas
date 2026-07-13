<!-- resources/views/partials/navbar.blade.php -->
<nav class="bg-white px-8 py-4 flex justify-between items-center shadow-sm">
    <!-- Logo / Brand -->
    <div class="text-xl font-bold text-[#0f172a]">
        Home
    </div>

    <!-- Dynamic Auth Navigation (Laravel Breeze Logic) -->
    <div class="flex items-center space-x-6 text-sm font-medium text-gray-600">
        @if (Route::has('login'))
            @auth
                <!-- Jika User Sudah Login -->
                <a href="{{ url('/dashboard') }}" class="hover:text-gray-900 transition">Dashboard</a>
                <a href="#" class="hover:text-gray-900 transition">App</a>
                <a href="#" class="hover:text-gray-900 transition">Profile</a>
                <!-- Avatar Lingkaran Potret User -->
                <div class="w-8 h-8 rounded-full bg-gray-200 border border-gray-300 flex items-center justify-center text-xs text-gray-500">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @else
                <!-- Jika User Belum Login -->
                <a href="{{ route('login') }}" class="hover:text-gray-900 transition">Home</a>
                <a href="{{ route('login') }}" class="hover:text-gray-900 transition">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="hover:text-gray-900 transition">Register</a>
                @endif
                <!-- Placeholder Lingkaran Kosong saat Tamu -->
                <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200"></div>
            @endif
        @endif
    </div>
</nav>