<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\User;
use App\Models\UserRating;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_technician' => ['nullable', 'boolean'],
        ]);

        $isTechnician = $request->has('is_technician') ? 1 : 0;



        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_technician' => $isTechnician,
            'is_admin' => false,
            'is_disabled' => false,
            'is_banned' => false,
        ]);


        if ($request->is_technician) {
            $rating = UserRating::create([
                'user_id' => $user->id,
                'weighted_score' => 99.99,
            ]);
        }



        event(new Registered($user));

        Auth::login($user);
        if ($request->is_technician) {
            return redirect(route('technician-dashboard', absolute: false));
        } else {
            return redirect(route('client-dashboard', absolute: false));
        }
    }
}
