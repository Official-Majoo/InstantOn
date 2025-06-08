<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FNBB Online Registration') }} - @yield('title')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    <!-- Unified Fonts for All FNBB Templates -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap"
        
        rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/fnbb-styles.css') }}">

    <!-- Additional Styles -->
    @stack('styles')

    <style>
        /* FNBB Global Font System */
        :root {
            --fnbb-primary: #002F6C;
            --fnbb-secondary: #FF6720;
            --fnbb-success: #28a745;
            --fnbb-info: #17a2b8;
            --fnbb-warning: #ffc107;
            --fnbb-danger: #dc3545;

            /* Typography variables */
            --heading-font: 'Montserrat', sans-serif;
            --body-font: 'Inter', sans-serif;
        }

        body {
            font-family: var(--body-font);
            line-height: 1.6;
        }

        /* Typography System */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            font-family: var(--heading-font);
            font-weight: 600;
            color: var(--fnbb-primary);
        }

        h1,
        .h1 {
            font-size: 2.5rem;
            letter-spacing: -0.5px;
        }

        h2,
        .h2 {
            font-size: 2rem;
            letter-spacing: -0.25px;
        }

        h3,
        .h3 {
            font-size: 1.75rem;
        }

        h4,
        .h4 {
            font-size: 1.5rem;
        }

        h5,
        .h5 {
            font-size: 1.25rem;
        }

        h6,
        .h6 {
            font-size: 1rem;
        }

        .lead {
            font-size: 1.25rem;
            font-weight: 400;
            font-family: var(--body-font);
        }

        /* UI Elements */
        .btn {
            font-family: var(--heading-font);
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .form-label {
            font-family: var(--heading-font);
            font-weight: 500;
        }

        .form-control,
        .form-select {
            font-family: var(--body-font);
        }

        /* Navbar/Navigation */
        .navbar,
        .sidebar,
        .nav-link,
        .topbar {
            font-family: var(--heading-font);
        }

        .navbar-brand,
        .brand-text {
            font-family: var(--heading-font);
            font-weight: 700;
        }

        /* Feature Items */
        .feature-item {
            font-family: var(--body-font);
        }

        /* Footer Styles */
        .fnbb-brand-footer p,
        .app-footer {
            font-family: var(--body-font);
            font-size: 0.85rem;
        }

        /* Card Elements */
        .card-title,
        .stat-value,
        .verification-score,
        .verification-status {
            font-family: var(--heading-font);
            font-weight: 600;
        }

        .card-text,
        .stat-label,
        .user-email,
        .timeline-time {
            font-family: var(--body-font);
        }

        .fnbb-brand-content {
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .fnbb-brand-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/path/to/your/background.jpg') no-repeat center center;
            background-size: cover;
            filter: blur(8px) brightness(0.7);
            z-index: -1;
        }
    </style>

    <!-- Livewire Styles -->
    @livewireStyles
    <script src="{{ asset('js/camera.js') }}"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-light">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Flash Messages -->
        @include('partials.flash-messages')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (required for some components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- App Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/camera.js') }}"></script>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>
