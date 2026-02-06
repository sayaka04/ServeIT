<section class="mb-4">
    <header>
        <h2 class="h5 fw-semibold">
            {{ __('Profile Information') }}
        </h2>
        <p class="text-muted small">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <!-- Form to resend verification email -->
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="first_name" class="form-label">{{ __('First Name') }}</label>
            <input
                type="text"
                class="form-control @error('first_name') is-invalid @enderror"
                id="first_name"
                name="first_name"
                value="{{ old('first_name', $user->first_name) }}"
                required
                autofocus
                autocomplete="given-name">
            @error('first_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="middle_name" class="form-label">{{ __('Middle Name') }}</label>
            <input
                type="text"
                class="form-control @error('middle_name') is-invalid @enderror"
                id="middle_name"
                name="middle_name"
                value="{{ old('middle_name', $user->middle_name) }}"
                autocomplete="additional-name">
            @error('middle_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
            <input
                type="text"
                class="form-control @error('last_name') is-invalid @enderror"
                id="last_name"
                name="last_name"
                value="{{ old('last_name', $user->last_name) }}"
                required
                autocomplete="family-name">
            @error('last_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2">
                <p class="small text-warning">
                    {{ __('Your email address is unverified.') }}

                    <button
                        form="send-verification"
                        type="submit"
                        class="btn btn-link p-0 align-baseline">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 small text-success">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
            <div id="profileSavedMsg" class="text-success small">
                {{ __('Saved.') }}
            </div>

            <script>
                setTimeout(() => {
                    const msg = document.getElementById('profileSavedMsg');
                    if (msg) msg.style.display = 'none';
                }, 2000);
            </script>
            @endif
        </div>
    </form>
</section>