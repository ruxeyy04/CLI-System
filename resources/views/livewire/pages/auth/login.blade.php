<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    public LoginForm $form;
    public bool $loading = false;

    // Define validation rules
    protected array $rules = [
        'form.email' => 'required|email',
        'form.password' => 'required|min:8',
    ];

    // Custom validation messages
    protected array $messages = [
        'form.password.required' => 'The password field is required',
        'form.email.required' => 'The email field is required',
        'form.email.email' => 'The email field must be a valid email address.',
        'form.password.min' => 'The password field must be at least 8 characters.',
    ];

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        // Start loading state
        $this->loading = true;

        // Validate the form inputs
        $this->validate();

        // Authenticate the user using the login form's method
        $this->form->authenticate();

        // Regenerate session
        Session::regenerate();

        // Redirect to the intended page
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

        // Stop loading state
        $this->loading = false;
    }
};
?>


<div>
    <div class="w-lg-500px p-10">
        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" wire:submit.prevent="login">

            <div class="text-center mb-11">
                <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
            </div>

            <div class="fv-row mb-8">
                <input type="text" placeholder="Email" name="email"
                    class="form-control bg-transparent" wire:model="form.email" />

                @error('form.email')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="fv-row mb-3">

                <input type="password" placeholder="Password" name="password" autocomplete="off"
                    class="form-control bg-transparent" wire:model="form.password" />

                @error('form.password')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @if (Route::has('password.request'))
                <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                    <div></div>

                    <!--begin::Link-->
                    <a href="{{ route('password.request') }}" class="link-primary" wire:navigate>
                        Forgot Password ?
                    </a>
                    <!--end::Link-->
                </div>
            @endif

            <div class="fv-row my-8">
                <label class="form-check form-check-inline">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input"
                        name="remember">
                    <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
                        Remember Me
                    </span>
                </label>
            </div>

            <div class="d-grid mb-10">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove class="indicator-label">Sign In</span>
                    <span wire:loading>
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
        <div class="text-gray-500 text-center fw-semibold fs-6">
            Not a Member yet?

            <a href="/register" class="link-primary" wire:navigate>
                Sign up
            </a>
        </div>
    </div>
</div>
