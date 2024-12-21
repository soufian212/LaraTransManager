<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LaraTransManager') }}</title>
        <!-- Tabler Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler-flags.min.css">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->

        @vite('resources/js/app.js', 'vendor/laratransmanager/build')
        <link href="{{asset('vendor/laratransmanager/build/assets/primeicons-C6QP2o4f.woff2')}}" rel="stylesheet" />
        <link href="{{asset('vendor/laratransmanager/build/assets/primeicons-WjwUDZjB.woff')}}" rel="stylesheet" />
        <link href="{{asset('vendor/laratransmanager/build/assets/primeicons-MpK4pl85.ttf')}}" rel="stylesheet" />

        @inertiaHead
        @routes
    </head>
    <body class="font-sans h-full antialiased">
        @inertia
    </body>
</html>