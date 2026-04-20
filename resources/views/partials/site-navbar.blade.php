@php
    $loc = $uiLocale ?? app()->getLocale();
    $th = $uiTheme ?? 'dark';
@endphp
<nav class="navbar navbar-expand-lg border-bottom sticky-top bg-body shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-warning" href="{{ route('home') }}">{{ __('site.nav_brand') }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="{{ __('site.nav_menu') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">{{ __('site.nav_home') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('pages.about') }}">{{ __('site.nav_about') }}</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('memes.mine') }}">{{ __('site.nav_my_memes') }}</a></li>
                    @can('meme.create')
                        <li class="nav-item"><a class="nav-link" href="{{ route('memes.create') }}">{{ __('site.nav_create_meme') }}</a></li>
                    @endcan
                    @can('meme.publish')
                        <li class="nav-item"><a class="nav-link" href="{{ route('memes.moderation') }}">{{ __('site.nav_moderation') }}</a></li>
                    @endcan
                    @can('analytics.view')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.statistics') }}">{{ __('site.nav_statistics') }}</a></li>
                    @endcan
                    @can('role.manage')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.roles') }}">{{ __('site.nav_roles') }}</a></li>
                    @endcan
                    @can('user.view')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.users') }}">{{ __('site.nav_users') }}</a></li>
                    @endcan
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 flex-wrap">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ __('site.ui_language') }}: <span class="fw-semibold text-warning">{{ strtoupper($loc) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item @if($loc === 'ru') active @endif" href="{{ route('locale.switch', 'ru') }}">Русский (RU)</a></li>
                        <li><a class="dropdown-item @if($loc === 'en') active @endif" href="{{ route('locale.switch', 'en') }}">English (EN)</a></li>
                        <li><a class="dropdown-item @if($loc === 'kk') active @endif" href="{{ route('locale.switch', 'kk') }}">Қазақша (KZ)</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    @if($th === 'dark')
                        <a class="nav-link py-1 fs-5" href="{{ route('theme.switch', 'light') }}" title="{{ __('site.ui_theme_light') }}">☀️</a>
                    @else
                        <a class="nav-link py-1 fs-5" href="{{ route('theme.switch', 'dark') }}" title="{{ __('site.ui_theme_dark') }}">🌙</a>
                    @endif
                </li>
                @auth
                    <li class="nav-item d-flex align-items-center gap-2">
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="" width="28" height="28" class="rounded-circle border border-secondary d-none d-lg-inline-block">
                    </li>
                    <li class="nav-item"><a class="nav-link small" href="{{ route('account.profile') }}">{{ __('site.nav_profile') }}</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('site.nav_logout') }}</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="btn btn-sm btn-outline-warning" href="{{ route('register') }}">{{ __('site.nav_register') }}</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-warning" href="{{ route('login') }}">{{ __('site.nav_login') }}</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
