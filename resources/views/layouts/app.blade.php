<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Sistema de Gestão Operacional - Hospital Santa Luzia">
        <meta name="theme-color" content="#006D87">

        <title>{{ config('app.name', 'Hospital Santa Luzia') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @if(Auth::user()->is_admin)
                    <!-- Menu e cards de todos os módulos -->
                @elseif(Auth::user()->sector->name == 'Recepção')
                    <!-- Menu e cards de recepção -->
                @elseif(Auth::user()->sector->name == 'Farmácia')
                    <!-- Menu e cards de farmácia -->
                @endif

                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
