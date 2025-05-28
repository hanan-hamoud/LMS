<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }} - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @livewireStyles
</head>
<body class="filament-body">
    <header>
        {{-- هنا ممكن تحط الهيدر إذا حابب --}}
    </header>

    <main>
        @livewire('language-switcher')
        {{ $slot }}
    </main>

    <footer>
        {{-- الفوتر --}}
    </footer>

    @livewireScripts
    @stack('scripts')
</body>
</html>
