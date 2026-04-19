<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleAndTheme
{
    public const LOCALES = ['ru', 'kk', 'en'];

    public const THEMES = ['light', 'dark'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'ru'));
        if (! in_array($locale, self::LOCALES, true)) {
            $locale = 'ru';
        }
        app()->setLocale($locale);

        $theme = session('ui_theme', 'dark');
        if (! in_array($theme, self::THEMES, true)) {
            $theme = 'dark';
        }

        View::share('uiTheme', $theme);
        View::share('uiLocale', $locale);

        return $next($request);
    }
}
