<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $fname = '';
    public string $lname = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        // Validate the form data
        $validated = $this->validate([
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Register the user
        event(new Registered($user = User::create($validated)));

        // Log the user in
        Auth::login($user);

        // Redirect to the dashboard
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

};
?>

<div>
    <div class="w-lg-500px p-10">
        <form class="form w-100" wire:submit.prevent="register">

            <!-- Heading -->
            <div class="text-center mb-11">
                <h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
            </div>

            <!-- First Name Field -->
            <div class="fv-row mb-8">
                <input type="text" placeholder="First Name" name="fname" autocomplete="off"
                       class="form-control bg-transparent" wire:model.lazy="fname" />

                @error('fname')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Last Name Field -->
            <div class="fv-row mb-8">
                <input type="text" placeholder="Last Name" name="lname" autocomplete="off"
                       class="form-control bg-transparent" wire:model.lazy="lname" />

                @error('lname')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username Field -->
            <div class="fv-row mb-8">
                <input type="text" placeholder="Username" name="username" autocomplete="off"
                       class="form-control bg-transparent" wire:model.lazy="username" />

                @error('username')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="fv-row mb-8">
                <input type="text" placeholder="Email" name="email" autocomplete="off"
                       class="form-control bg-transparent" wire:model.lazy="email" />

                @error('email')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="fv-row mb-8">
                <input type="password" placeholder="Password" name="password" autocomplete="off"
                       class="form-control bg-transparent" wire:model.lazy="password" />

                @error('password')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="fv-row mb-3">
                <input type="password" placeholder="Confirm Password" name="password_confirmation" autocomplete="off"
                       class="form-control bg-transparent" wire:model.lazy="password_confirmation" />

                @error('password_confirmation')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="d-grid mb-10">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary" 
                        wire:loading.attr="disabled">
                    <!-- Loading spinner during form submission -->
                    <span wire:loading.remove class="indicator-label">Sign Up</span>
                    <span wire:loading>
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>

        <!-- Sign In Link -->
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Already have an Account?
            <a href="/login" class="link-primary" wire:navigate>
                Sign in
            </a>
        </div>
    </div>
</div>

