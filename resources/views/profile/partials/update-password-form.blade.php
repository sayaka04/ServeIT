<section class="mb-4">
    <header>
        <h2 class="h5 fw-semibold">
            {{ __('Update Password') }}
        </h2>
        <p class="text-muted small">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
            <input
                type="password"
                class="form-control @error('current_password') is-invalid @enderror"
                id="current_password"
                name="current_password"
                autocomplete="current-password"
                required>
            @error('current_password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('New Password') }}</label>
            <input
                type="password"
                class="form-control @error('password') is-invalid @enderror"
                id="password"
                name="password"
                autocomplete="new-password"
                required>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input
                type="password"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                id="password_confirmation"
                name="password_confirmation"
                autocomplete="new-password"
                required>
            @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
            <div class="text-success small" id="passwordSavedMsg">
                {{ __('Saved.') }}
            </div>
            <script>
                setTimeout(() => {
                    const msg = document.getElementById('passwordSavedMsg');
                    if (msg) msg.style.display = 'none';
                }, 2000);
            </script>
            @endif
        </div>
    </form>
</section>