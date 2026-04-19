<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetLocaleAndTheme;
use Illuminate\Http\RedirectResponse;

class UiPreferenceController extends Controller
{
    public function locale(string $locale): RedirectResponse
    {
        if (! in_array($locale, SetLocaleAndTheme::LOCALES, true)) {
            abort(404);
        }
        session(['locale' => $locale]);

        return back();
    }

    public function theme(string $theme): RedirectResponse
    {
        if (! in_array($theme, SetLocaleAndTheme::THEMES, true)) {
            abort(404);
        }
        session(['ui_theme' => $theme]);

        return back();
    }
}
