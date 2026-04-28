<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Middleware\SetLocaleAndTheme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UiPreferenceController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:'.implode(',', SetLocaleAndTheme::LOCALES)],
            'theme' => ['required', 'string', 'in:'.implode(',', SetLocaleAndTheme::THEMES)],
        ]);

        session([
            'locale' => $validated['locale'],
            'ui_theme' => $validated['theme'],
        ]);

        return back();
    }
}
