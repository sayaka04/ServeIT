<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Repair;
use App\Models\RepairRating;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Attempt to authenticate the user
            $request->authenticate();

            if (Auth::user()->is_disabled) {
                Auth::logout();
                abort(403, 'Your account was disabled.');
            } elseif (Auth::user()->is_banned) {
                Auth::logout();
                abort(403, 'Your account was been banned.');
            }

            // Regenerate the session on successful login
            $request->session()->regenerate();



            // Redirect based on user role
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('dashboard.index', absolute: false));
            } elseif (Auth::user()->is_technician) {

                $technician = Auth::user()->technician;
                Log::info($technician);

                $ratings = RepairRating::where('technician_id', $technician->id)
                    ->where('user_weighted_score', '!=', null)
                    ->get();
                $repairs = Repair::where('technician_id', $technician->id)->get();

                if ($ratings->isNotEmpty()) {

                    $weighted_score_rating = $ratings->avg('user_weighted_score');
                    $jobs_completed = $ratings->count();

                    $total_jobs = $repairs->where('is_completed', 1)->count();

                    Log::info('weighted_score_rating: ' . $weighted_score_rating);
                    Log::info('jobs_completed: ' . $jobs_completed);


                    $technician->update([
                        'weighted_score_rating' => $weighted_score_rating,
                        'jobs_completed' => $jobs_completed,
                        'success_rate' => ($jobs_completed / $total_jobs) * 100

                    ]);
                }


                // if ($ratings->isNotEmpty()) {
                //     $weighted_score_rating = $ratings->where('completion_confirmed', true)->avg('user_weighted_score');
                //     $job_completed = $ratings->where('completion_confirmed', true)->count();

                //     if (!isEmpty($weighted_score_rating) || !isEmpty($job_completed)) {
                //         Log::info($weighted_score_rating);

                //         $technician->update([
                //             'weighted_score_rating' => $weighted_score_rating,
                //             'job_completed' => $job_completed

                //         ]);
                //     }
                // }


                return redirect()->intended(route('dashboard.index', absolute: false));
            } else {
                return redirect()->intended(route('dashboard.index', absolute: false));
            }
        } catch (AuthenticationException $e) {
            // Handle the error and give feedback
            return back()
                ->withErrors(['email' => 'The provided credentials are incorrect.'])
                ->withInput(); // Keep the user input in the form
        }
    }

    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     // return redirect()->intended(route('dashboard', absolute: false));
    //     if (Auth::user()->is_admin) {
    //         return redirect()->intended(route('dashboard.index', absolute: false));
    //     } elseif (Auth::user()->is_technician) {
    //         return redirect()->intended(route('dashboard.index', absolute: false));
    //     } else {
    //         return redirect()->intended(route('dashboard.index', absolute: false));
    //     }
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
