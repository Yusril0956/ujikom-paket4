<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background text-ink antialiased">
    <div class="flex min-h-screen overflow-hidden lg:h-screen">
        <div class="fixed inset-0 z-40 hidden bg-ink/40 lg:hidden" data-sidebar-backdrop data-sidebar-close></div>
        {{-- Sidebar Component --}}
        <x-layouts.partials.sidebar />

        <div class="content-shell flex flex-1 flex-col overflow-hidden">
            {{-- Header Component --}}
            <x-layouts.partials.header />

            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
