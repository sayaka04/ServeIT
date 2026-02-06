<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\User;
use App\Models\UserRating;

class LoginController extends Controller
{


    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // $isTechnician = $request->has('is_technician') ? 1 : 0;



        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_technician' => false,
            'is_admin' => false,
            'is_disabled' => false,
            'is_banned' => false,
        ]);


        if ($request->is_technician) {
            $rating = UserRating::create([
                'user_id' => $user->id,
                'weighted_score' => 00.00,
            ]);
        }



        event(new Registered($user));

        Auth::login($user);
        if ($request->is_technician) {
            return redirect(route('dashboard.index', absolute: false));
        } else {
            return redirect(route('dashboard.index', absolute: false));
        }
    }
}
