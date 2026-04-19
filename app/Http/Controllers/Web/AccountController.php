<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function permissions(): View
    {
        return view('pages.account.permissions');
    }
}
