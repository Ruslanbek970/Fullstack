<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeRegisteredMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthSessionController extends Controller
{
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:6'],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole('member');

            try {
                Mail::to($user->email)->send(new WelcomeRegisteredMail($user));
                Log::info('Welcome mail sent', ['to' => $user->email]);
            } catch (\Throwable $e) {
                Log::error('Welcome mail failed', ['to' => $user->email, 'error' => $e->getMessage()]);
                report($e);
            }
#тут
            Auth::login($user);

            return redirect()->route('home')->with('message', 'Аккаунт создан, вы вошли как участник (member).');
        }

        return view('pages.auth.register');
    }

    public function login(Request $request)
    {
        $message = session('message', '');

        if ($request->isMethod('post')) {
            $email = $request->input('login', '');
            $password = $request->input('password', '');

            $user = User::where('email', $email)->first();

            if ($user && Hash::check($password, $user->password)) {
                if ($user->isBanned()) {
                    $message = 'Аккаунт заблокирован.';
                } else {
                    Auth::login($user, $request->boolean('remember'));

                    return redirect()->intended(route('home'));
                }
            }

            $message = 'Неверный email или пароль (в поле Username укажи email).';
        }

        return view('pages.auth.login', compact('message'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
