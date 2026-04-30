{{--
    ============================================================
    LMLinga - Barangay Health Center
    Layout File: resources/views/layouts/app.blade.php
    Description: Master layout used by all pages.
    ============================================================
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name1="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LMLinga – Smart Health Support</title>

    {{-- Google Fonts: Poppins (body/headings) + Protest Riot (branding accent) --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Protest+Riot&display=swap" rel="stylesheet" />

    {{-- Bootstrap 5 CSS (chosen for mobile-first responsiveness) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    {{-- Custom stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    {{-- Page-specific styles (optional override) --}}
    @stack('styles')
</head>
<body>

    {{-- Main content slot --}}
    @yield('content')

    {{-- Bootstrap 5 JS bundle (includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>
</html>
