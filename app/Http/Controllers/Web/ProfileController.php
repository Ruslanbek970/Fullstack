<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\UserDetail; 

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('pages.account.profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'file', 'max:5120', 'mimes:jpeg,jpg,png,webp'],
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            
            if ($file && $file->isValid()) {
                
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }       
                $path = $file->store('avatars', 'public');
                $validated['avatar'] = $path;     
                UserDetail::updateOrCreate(
                    ['user_id' => $user->id], 
                    [                 
                        'avatar_filename' => basename($path), 
                        'original_name' => $file->getClientOriginalName() 
                    ]
                );
              
            }
        } else {
            unset($validated['avatar']);
        }

        $user->fill($validated);
        $user->save();

        return back()->with('message', 'Профиль обновлён.');
    }
}