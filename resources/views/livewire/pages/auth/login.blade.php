<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;
use hisorange\BrowserDetect\Parser as Browser;
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

        // Update session with device information before regenerating session ID
        $sessionId = session()->getId();
        DB::table('sessions')
            ->where('id', $sessionId)
            ->update([
                'devicefamily' => Browser::deviceFamily(),
                'devicemodel' => Browser::deviceModel(),
                'platformname' => Browser::platformName(),
            ]);

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
    <div class="p-10 w-lg-500px">
        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" wire:submit.prevent="login">

            <div class="text-center mb-11">
                <h1 class="mb-3 text-gray-900 fw-bolder">Sign In</h1>
            </div>

            <div class="mb-8 fv-row">
                <input type="text" placeholder="Email" name="email" class="bg-transparent form-control"
                    wire:model="form.email" />

                @error('form.email')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 fv-row">

                <input type="password" placeholder="Password" name="password" autocomplete="off"
                    class="bg-transparent form-control" wire:model="form.password" />

                @error('form.password')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @if (Route::has('password.request'))
                <div class="flex-wrap gap-3 mb-8 d-flex flex-stack fs-base fw-semibold">
                    <div></div>

                    <!--begin::Link-->
                    <a href="{{ route('password.request') }}" class="link-primary" wire:navigate>
                        Forgot Password ?
                    </a>
                    <!--end::Link-->
                </div>
            @endif

            <div class="my-8 fv-row">
                <label class="form-check form-check-inline">
                    <input wire:model="form.remember" id="remember" type="checkbox" class="form-check-input"
                        name="remember">
                    <span class="text-gray-700 form-check-label fw-semibold fs-base ms-1">
                        Remember Me
                    </span>
                </label>
            </div>

            <div class="mb-10 d-grid">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove class="indicator-label">Sign In</span>
                    <span wire:loading>
                        Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
        <div class="text-center text-gray-500 fw-semibold fs-6">
            Not a Member yet?

            <a href="/register" class="link-primary" wire:navigate>
                Sign up
            </a>
        </div>
    </div>
</div>
