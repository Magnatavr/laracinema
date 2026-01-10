<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'LaraCinema'))</title>

    {{-- Vite --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- MAIN CSS --}}
    <link rel="stylesheet" href="{{ asset('dist/css/main.css') }}">

    {{-- ICONS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    @stack('styles')
</head>
<body>

{{-- HEADER --}}
@include('components.header')

{{-- MAIN --}}
<main class="main">
    @yield('content')
</main>

{{-- FOOTER --}}
@include('components.footer')


@include('components.search-modal')

@stack('scripts')

</body>
</html>
