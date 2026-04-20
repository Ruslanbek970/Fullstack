<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()->orderBy('id')->paginate(30);
        $roles = Role::query()->orderBy('name')->get();

        return view('pages.admin.users', compact('users', 'roles'));
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

    public function setRoles(Request $request, User $user)
    {
        if ($user->id === $request->user()->id) {
            return back()->with('message', 'Нельзя менять роли самому себе.');
        }

        $validated = $request->validate([
            'roles' => ['array'],
            'roles.*' => ['string', 'max:255'],
        ]);

        $roleNames = collect($validated['roles'] ?? [])
            ->filter(fn ($r) => is_string($r) && $r !== '')
            ->values()
            ->all();

        $user->syncRoles($roleNames);

        return back()->with('message', 'Роли обновлены.');
    }
}
