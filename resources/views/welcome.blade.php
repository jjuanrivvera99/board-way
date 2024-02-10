<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <livewire:welcome.navigation />
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
                <div class="flex justify-center">
                    <img src="/images/logo.svg" class="h-20 mr-3"
                        alt="App Logo">
                </div>

                <div class="mt-16">
                    <h1 class="text-4xl font-bold text-center text-gray-900 dark:text-white sm:text-5xl">Welcome to BoardWay</h1>
                    <p class="mt-4 text-lg text-center text-gray-600 dark:text-gray-300">The easiest way to manage your projects</p>

                    <img src="/images/board-way-view.png" alt="" class="mt-4 rounded">
                </div>
            </div>
        </div>
    </body>
</html>
