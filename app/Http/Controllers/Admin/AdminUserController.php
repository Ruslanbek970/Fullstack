<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()->orderBy('id')->paginate(30);

        return view('pages.admin.users', compact('users'));
    }

    public function ban(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('message', 'Нельзя забанить себя.');
        }

        $user->update(['banned_at' => now()]);

        return back()->with('message', 'Пользователь забанен.');
    }

    public function unban(Request $request, User $user)
    {
        $user->update(['banned_at' => null]);

        return back()->with('message', 'Бан снят.');
    }
}
