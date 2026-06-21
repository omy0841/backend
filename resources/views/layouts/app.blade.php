<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="stylesheet" href="{{ asset('css/modern.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-sA+e2H0k0a2gF2b8s6O8gqj6QbYk5Q5T1m0w3pJTn8M=" crossorigin="" />
    </head>
    <body>
        <header>
            <div class="header-content">
                <a href="{{ route('home') }}" class="brand" data-i18n="brand">Seif Sea Fresh Zanzibar (from Pemba)</a>
                <div class="lang-toggle" style="display:inline-flex; gap:.5rem; margin-left:1rem;">
                    <button id="lang-en" class="btn btn-small" style="padding:.35rem .6rem;">EN</button>
                    <button id="lang-sw" class="btn btn-small" style="padding:.35rem .6rem;">SW</button>
                </div>
                <nav class="nav-links">
                    <a href="{{ route('home') }}">Home</a>
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('order.create') }}">Order</a>
                        <a href="{{ route('order.history') }}">History</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary text-sm px-4 py-2">Admin</a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary text-sm px-4 py-2">Orders</a>
                        @endif
                    @endauth
                </nav>
                <style>
                    .nav-links a { margin-right: 1rem; color: inherit; text-decoration: none; }
                    .nav-links a:hover { color: #0f172a; }
                </style>
                <div class="nav-auth">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-secondary">Log in</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    @endauth
                </div>
            </div>
        </header>

        <main>
            <div class="container">
                @if(session('status'))
                    <div class="success-message" style="margin-top: 2rem;">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="error-message" style="margin-top: 2rem;">
                        <ul style="margin: 0;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            @yield('content')
        </main>

        <footer>
            <div class="container">
                <p><strong data-i18n="footer_brand">Seif Sea Fresh Zanzibar © 2026</strong></p>
                <p data-i18n="footer_note">Fresh seafood delivery for hotels and restaurants across Zanzibar</p>
            </div>
        </footer>

        <script src="{{ asset('js/app.js') }}?v=3"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-o9N1j8kGQ6s3G1b6b1k4y1QvWQ2sB9d7vZ8+3G4Hh2I=" crossorigin=""></script>
    </body>
</html>
