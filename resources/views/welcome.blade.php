<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GAME</title>

    <!-- Fonts & Tailwind (Laravel Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[#e9eff6] min-h-screen flex flex-col font-sans">

    <!-- Memanggil Komponen Navbar yang Sudah Dipisah -->
    @include('partials.navbar')

    <!-- MAIN CONTENT (CENTERED CARD) -->
    <main class="flex-1 flex items-center justify-center p-4">
        <div class="w-64 h-64 bg-gradient-to-tr from-[#cfe3fc] via-[#e2eafc] to-[#fbcfe8] rounded-3xl shadow-md flex flex-col items-center justify-center transition-transform hover:scale-105 duration-300 cursor-pointer">
            <div class="flex items-center space-x-3 bg-white/40 backdrop-blur-sm px-4 py-3 rounded-2xl border border-white/20">
                <img id="music-icon" class="w-5 h-5 opacity-70" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3'/></svg>" alt="Icon">
                <span class="text-sm font-semibold text-gray-800">Play Music</span>
            </div>
        </div>
    </main>

</body>
</html>