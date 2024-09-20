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
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
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
        $this->redirect(route('dashboard'));
    }

};
?>

<div>
    <div class="p-10 w-lg-500px">
        <form class="form w-100" wire:submit.prevent="register">

            <!-- Heading -->
            <div class="text-center mb-11">
                <h1 class="mb-3 text-gray-900 fw-bolder">Sign Up</h1>
            </div>

            <!-- First Name Field -->
            <div class="mb-8 fv-row">
                <input type="text" placeholder="First Name" name="fname" autocomplete="off"
                       class="bg-transparent form-control" wire:model="fname" />

                @error('fname')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Last Name Field -->
            <div class="mb-8 fv-row">
                <input type="text" placeholder="Last Name" name="lname" autocomplete="off"
                       class="bg-transparent form-control" wire:model="lname" />

                @error('lname')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username Field -->
            <div class="mb-8 fv-row">
                <input type="text" placeholder="Username" name="username" autocomplete="off"
                       class="bg-transparent form-control" wire:model="username" />

                @error('username')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="mb-8 fv-row">
                <input type="text" placeholder="Email" name="email" autocomplete="off"
                       class="bg-transparent form-control" wire:model="email" />

                @error('email')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="mb-8 fv-row">
                <input type="password" placeholder="Password" name="password" autocomplete="off"
                       class="bg-transparent form-control" wire:model="password" />

                @error('password')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-3 fv-row">
                <input type="password" placeholder="Confirm Password" name="password_confirmation" autocomplete="off"
                       class="bg-transparent form-control" wire:model="password_confirmation" />

                @error('password_confirmation')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="mb-10 d-grid">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary" 
                        wire:loading.attr="disabled">
                    <!-- Loading spinner during form submission -->
                    <span wire:loading.remove class="indicator-label">Sign Up</span>
                    <span wire:loading>
                        Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                    </span>
                </button>
            </div>
        </form>

        <!-- Sign In Link -->
        <div class="text-center text-gray-500 fw-semibold fs-6">
            Already have an Account?
            <a href="/login" class="link-primary" wire:navigate>
                Sign in
            </a>
        </div>
    </div>
</div>

