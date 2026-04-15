<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Scriptoria') }} | Autentikasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="auth-bg relative min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Branding Header --}}
        <div class="text-center mb-8">
            <x-lucide-book-open class="w-10 h-10 text-ink mx-auto mb-3 stroke-[1.5]" />
            <h1 class="font-serif text-2xl font-black tracking-[0.2em] text-ink uppercase">Scriptoria</h1>
            <p class="font-mono text-[10px] uppercase tracking-[0.3em] text-coffee mt-2">Digital Archive System</p>
        </div>

        {{-- Auth Card --}}
        <div class="bg-surface border border-ink shadow-[var(--elevation-2)]">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs font-mono text-muted mt-6">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>

</html>
