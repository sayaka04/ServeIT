<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use App\Models\ExpertiseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Technician;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

use App\Models\UserRating;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    protected $emailController;

    // Inject the EmailController into the constructor
    public function __construct(EmailController $emailController)
    {
        $this->emailController = $emailController;
    }


    public function registerClient(Request $request): RedirectResponse
    {

        // Check if email already exists BEFORE validation
        if (User::where('email', $request->email)->exists()) {
            return back()
                ->withInput() // keep the filled fields
                ->with('error', 'The email is already taken. Please use a different email.');
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['nullable', 'string', 'max:20', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);




        $user = User::create([
            'first_name'  => ucwords($request->first_name),
            'middle_name' => ucwords($request->middle_name),
            'last_name'   => ucwords($request->last_name),
            'email' => $request->email,
            'phone_number' => Crypt::encrypt($request->phone_number),
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

        // ✅ If authenticated, resend verification email manually
        if (Auth::check()) {
            $this->resendEmailVerification();
        }


        if ($request->is_technician) {
            return redirect(route('dashboard.index', absolute: false));
        } else {
            return redirect(route('dashboard.index', absolute: false));
        }
    }





    public function showRegistrationForm()
    {
        // Fetch active categories for the view
        $expertiseCategories = ExpertiseCategory::where('is_archived', false)->get();

        return view('authentication.register-technician', compact('expertiseCategories'));
    }

    public function registerTechnician(Request $request): RedirectResponse
    {
        // Check if email already exists BEFORE validation (Your custom logic)
        if (User::where('email', $request->email)->exists()) {
            return back()
                ->withInput() // keep the filled fields
                ->with('error', 'The email is already taken. Please use a different email.');
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['nullable', 'string', 'max:20', 'unique:' . User::class],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['required', 'string', 'max:255'],
            'home_service' => ['nullable'],

            // [NEW] Expertise Validation
            'expertise_ids' => ['required', 'array', 'min:1'], // Require at least 1
            'expertise_ids.*' => ['exists:expertise_categories,id'], // Ensure IDs exist
        ]);

        // var_dump($request->all()); // Commented out for production

        $user = User::create([
            'first_name'  => ucwords($request->first_name),
            'middle_name' => ucwords($request->middle_name),
            'last_name'   => ucwords($request->last_name),
            'email' => $request->email,
            'phone_number' => Crypt::encrypt($request->phone_number), // Your encryption logic
            'password' => Hash::make($request->password),
            'is_technician' => true,
            'is_admin' => false,
            'is_disabled' => false,
            'is_banned' => false,
        ]);

        // Your specific TESDA data handling
        if (!empty($request->tesda_first_four) && !empty($request->tesda_last_four)) {
            $request->merge([
                'tesda_first_four' => '0000',
                'tesda_last_four'  => '0000',
            ]);
        }
        $request->merge([
            'tesda_first_four' => $request->tesda_first_four ?? '0000',
            'tesda_last_four'  => $request->tesda_last_four  ?? '0000',
        ]);

        // Your specific TESDA verification URL
        $url = 'https://t2mis.tesda.gov.ph/Rwac/IndexProc2?' .
            'searchLName=' . urlencode($request->last_name) .
            '&searchFName=' . urlencode($request->first_name) .
            '&firstFour=' . urlencode($request->tesda_first_four) .
            '&lastFour=' . urlencode($request->tesda_last_four);

        Log::info('TESDA verification URL: ' . $url);

        // Basic HTML fetch
        $html = @file_get_contents($url);
        $is_verified = false;

        if ($html === false) {
            // Handle error if needed
        } else {
            if (stripos($html, 'Verified') !== false) {
                $is_verified = true;
            }
        }

        $technician = Technician::create([
            'technician_user_id'      => $user->id,
            'technician_code'         => 'TEMP', // placeholder to satisfy NOT NULL
            'shop_id'                 => null,
            'availability_start'      => null,
            'availability_end'        => null,
            'address' => ucwords($request->address),
            'longitude'               => $request->longitude,
            'latitude'                => $request->latitude,
            'tesda_verified'          => $is_verified,
            'tesda_first_four'        => $request->tesda_first_four,
            'tesda_last_four'         => $request->tesda_last_four,
            'jobs_completed'          => 0,
            'weighted_score_rating'   => 0.0,
            'success_rate'            => 0.0,
            'home_service'            => $request->has('home_service') ? 1 : 0,
        ]);

        // [NEW] Save the Expertise Categories
        if ($request->has('expertise_ids')) {
            $technician->expertiseCategories()->attach($request->expertise_ids);
        }

        // Now update with real code (Your custom Hashids logic)
        $timestamp = strtotime($technician->created_at); // integer timestamp
        $code = Hashids::encode($technician->id)
            . Str::upper(Str::random(4))
            . Hashids::encode($timestamp);
        $technician->update(['technician_code' => $code]);

        event(new Registered($user));

        Auth::login($user);

        // ✅ If authenticated, resend verification email manually (Your logic)
        if (Auth::check()) {
            $this->resendEmailVerification();
        }

        if ($request->is_technician) {
            return redirect(route('dashboard.index', absolute: false));
        } else {
            return redirect(route('dashboard.index', absolute: false));
        }
    }

    // public function registerTechnician(Request $request): RedirectResponse
    // {

    //     // Check if email already exists BEFORE validation
    //     if (User::where('email', $request->email)->exists()) {
    //         return back()
    //             ->withInput() // keep the filled fields
    //             ->with('error', 'The email is already taken. Please use a different email.');
    //     }


    //     $request->validate([
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'middle_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
    //         'phone_number' => ['nullable', 'string', 'max:20', 'unique:' . User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //         'latitude' => ['required', 'numeric', 'between:-90,90'],
    //         'longitude' => ['required', 'numeric', 'between:-180,180'],
    //         'address' => ['required', 'string', 'max:255'],
    //         'home_service' => ['nullable'],

    //     ]);



    //     var_dump($request->all());

    //     $user = User::create([
    //         'first_name'  => ucwords($request->first_name),
    //         'middle_name' => ucwords($request->middle_name),
    //         'last_name'   => ucwords($request->last_name),
    //         'email' => $request->email,
    //         'phone_number' => Crypt::encrypt($request->phone_number),
    //         'password' => Hash::make($request->password),
    //         'is_technician' => true,
    //         'is_admin' => false,
    //         'is_disabled' => false,
    //         'is_banned' => false,
    //     ]);
    //     if (!empty($request->tesda_first_four) && !empty($request->tesda_last_four)) {
    //         $request->merge([
    //             'tesda_first_four' => '0000',
    //             'tesda_last_four'  => '0000',
    //         ]);
    //     }
    //     $request->merge([
    //         'tesda_first_four' => $request->tesda_first_four ?? '0000',
    //         'tesda_last_four'  => $request->tesda_last_four  ?? '0000',
    //     ]);



    //     // $url = 'https://t2mis.tesda.gov.ph/Rwac/IndexProc2?searchLName=' . $request->last_name . '&searchFName=' . $request->first_name . '&firstFour=' . $request->tesda_first_four . '&lastFour=' . $request->tesda_last_four . ''; // Change this to your target URL
    //     $url = 'https://t2mis.tesda.gov.ph/Rwac/IndexProc2?' .
    //         'searchLName=' . urlencode($request->last_name) .
    //         '&searchFName=' . urlencode($request->first_name) .
    //         '&firstFour=' . urlencode($request->tesda_first_four) .
    //         '&lastFour=' . urlencode($request->tesda_last_four);

    //     Log::info('TESDA verification URL: ' . $url);

    //     // Basic HTML fetch
    //     $html = @file_get_contents($url);
    //     $is_verified = false;

    //     if ($html === false) {
    //         //
    //     } else {
    //         if (stripos($html, 'Verified') !== false) {
    //             $is_verified = true;
    //         }
    //     }

    //     $technician = Technician::create([
    //         'technician_user_id'      => $user->id,
    //         'technician_code'         => 'TEMP', // placeholder to satisfy NOT NULL
    //         'shop_id'                 => null,
    //         'availability_start'      => null,
    //         'availability_end'        => null,
    //         'address' => ucwords($request->address),
    //         'longitude'               => $request->longitude,
    //         'latitude'                => $request->latitude,
    //         'tesda_verified'          => $is_verified,
    //         'tesda_first_four'        => $request->tesda_first_four,
    //         'tesda_last_four'         => $request->tesda_last_four,
    //         'jobs_completed'          => 0,
    //         'weighted_score_rating'   => 0.0,
    //         'success_rate'            => 0.0,
    //         'home_service'            => $request->has('home_service') ? 1 : 0,
    //     ]);

    //     // Now update with real code
    //     $timestamp = strtotime($technician->created_at); // integer timestamp
    //     $code = Hashids::encode($technician->id)
    //         . Str::upper(Str::random(4))
    //         . Hashids::encode($timestamp);
    //     $technician->update(['technician_code' => $code]);







    //     event(new Registered($user));

    //     Auth::login($user);

    //     // ✅ If authenticated, resend verification email manually
    //     if (Auth::check()) {
    //         $this->resendEmailVerification();
    //     }



    //     if ($request->is_technician) {
    //         return redirect(route('dashboard.index', absolute: false));
    //     } else {
    //         return redirect(route('dashboard.index', absolute: false));
    //     }
    // }



    public function resendEmailVerification()
    {


        $email = Auth::user()->email;

        $payload = [
            'email' => $email,
            'expires_at' => now()->addHours(24)->toDateTimeString(), // expires in 24 hours
        ];

        $encrypted = Crypt::encryptString(json_encode($payload));

        $verifyUrl = route('verify.email', ['token' => $encrypted]);



        // Send email to the client
        if (config('custom.enable_email_sender')) {
            $this->emailController->emailSendNotification(
                Auth::user()->email,
                'Verify Your Email Address',
                'Verify Your Email Address 2',
                [
                    'verification_link' => $verifyUrl
                ],
                'mail.verify-email' // Specify the view for the email template
            );
        }

        return back()->with('status', 'verification-link-sent');
    }

    public function verifyEmail($token)
    {
        try {
            $decrypted = Crypt::decryptString($token);
            $data = json_decode($decrypted, true);
        } catch (\Exception $e) {
            return response()->view('auth.verify-failed', [], 403);
        }

        // Validate payload structure
        if (!isset($data['email'], $data['expires_at'])) {
            return response()->view('auth.verify-failed', [], 403);
        }

        // Check expiration
        if (now()->greaterThan($data['expires_at'])) {
            return response()->view('auth.verify-expired', [], 403);
        }

        // Find user by email
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            return response()->view('auth.verify-failed', [], 403);
        }

        // Mark verified if not already
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }

        Log::info('User email verified: ' . $user->email);

        return redirect()->route('dashboard')->with('status', 'email-verified');
    }
}
