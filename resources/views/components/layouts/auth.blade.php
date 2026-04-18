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
            <x-app-logo />
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
