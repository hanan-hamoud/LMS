<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased bg-gray-100">

    <div class="p-4">
        <form method="POST" action="{{ route('language.switch') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                {{ app()->getLocale() === 'ar' ? 'English' : 'عربي' }}
            </button>
        </form>
    </div>

    {{ $slot }}

    @livewireScripts
</body>
</html>
