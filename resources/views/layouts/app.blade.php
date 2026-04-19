<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ $uiTheme ?? 'dark' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="app-body bg-body text-body d-flex flex-column min-vh-100">
    @include('partials.site-navbar')

    <main class="container py-4 flex-grow-1">
        @if(session('message'))
            <div class="alert alert-success py-2 mb-3">{{ session('message') }}</div>
        @endif
        @yield('content')
    </main>

    @include('partials.site-footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-password-toggle]');
            if (!btn) return;
            var sel = btn.getAttribute('data-password-toggle');
            var inp = document.querySelector(sel);
            if (!inp) return;
            inp.type = inp.type === 'password' ? 'text' : 'password';
        });
    </script>
    @stack('scripts')
</body>
</html>
