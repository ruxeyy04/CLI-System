<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }
    public function getPasswordStrength(): int
    {
        $length = strlen($this->password);
        $strength = 0;

        if (preg_match('/[a-z]/', $this->password)) {
            $strength++;
        }
        if (preg_match('/[A-Z]/', $this->password)) {
            $strength++;
        }
        if (preg_match('/[0-9]/', $this->password)) {
            $strength++;
        }
        if (preg_match('/[\W]/', $this->password)) {
            $strength++;
        }

        return $strength + ($length >= 8 ? 1 : 0);
    }
    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset($this->only('email', 'password', 'password_confirmation', 'token'), function ($user) {
            $user
                ->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])
                ->save();

            event(new PasswordReset($user));
        });

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <div class="w-lg-500px p-10">
        <form class="form w-100" wire:submit.prevent="resetPassword">
            <div class="text-center mb-10">
                <h1 class="text-gray-900 fw-bolder mb-3">Setup New Password</h1>
                <div class="text-gray-500 fw-semibold fs-6">
                    Already reset the password?
                    <a href="{{ route('login') }}" class="link-primary fw-bold" wire:navigate>Sign in</a>
                </div>
            </div>

            <!-- Email (readonly) -->
            <div class="fv-row mb-8">
                <input type="email" placeholder="Email" name="email" autocomplete="off"
                    class="form-control bg-transparent" wire:model="email" required readonly>
                @error('email')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="fv-row mb-8">
                <input type="password" placeholder="Password" name="password" autocomplete="off"
                    class="form-control bg-transparent" wire:model="password" oninput="updateStrengthMeter()">

                <!-- Password Strength Meter -->
                <div class="d-flex align-items-center my-3">
                    <div id="strength-1" class="flex-grow-1 bg-secondary rounded h-5px me-2"></div>
                    <div id="strength-2" class="flex-grow-1 bg-secondary rounded h-5px me-2"></div>
                    <div id="strength-3" class="flex-grow-1 bg-secondary rounded h-5px me-2"></div>
                    <div id="strength-4" class="flex-grow-1 bg-secondary rounded h-5px"></div>
                </div>

                <div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
                @error('password')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="fv-row mb-8">
                <input type="password" placeholder="Repeat Password" name="password_confirmation" autocomplete="off"
                    class="form-control bg-transparent" wire:model="password_confirmation">
            </div>

            <div class="d-grid mb-10">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove class="indicator-label">Submit</span>
                    <span wire:loading>
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateStrengthMeter() {
        const password = document.querySelector('input[name="password"]').value;
        const meterBars = [1, 2, 3, 4].map(num => document.getElementById(`strength-${num}`));

        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[\W]/.test(password)) strength++;

        meterBars.forEach((bar, index) => {
            bar.classList.toggle('bg-success', index < strength);
            bar.classList.toggle('bg-secondary', index >= strength);
        });
    }
</script>
