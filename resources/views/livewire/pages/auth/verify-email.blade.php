<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.welcome')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(route('dashboard'), navigate: true);
            return;
        }

        // If the email is not verified, send the verification email
        Auth::user()->sendEmailVerificationNotification();

        // Flash a session message indicating the verification link was sent
        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        // Call the logout action
        $logout();

        // Redirect to the homepage after logging out
        $this->redirect('/', navigate: true);
    }
};
?>

<div class="d-flex flex-column flex-root">

    <style>
        body {
            background-image: url('../../assets/media/auth/bg16.jpg');
        }

        [data-bs-theme="dark"] body {
            background-image: url('../../assets/media/auth/bg16-dark.jpg');
        }
    </style>


    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="p-10 text-center d-flex flex-column flex-center">
            <div class="py-5 card card-flush w-lg-650px">
                <div class="card-body py-15 py-lg-20">

                    <div class="mb-7">
                        <a href="/" class="" wire:navigate>
                            <img alt="Logo" src="../../assets/media/logos/default.png"
                                height="100" />
                        </a>
                    </div>
                    <h1 class="mb-5 text-gray-900 fw-bolder">
                        Welcome to CLIS
                    </h1>
                    <div class="mb-8 fs-6">
                        <span class="text-gray-500 fw-semibold">Thanks for signing up! Before getting started, could
                            you verify your email address by clicking on the link we just emailed to you? If you
                            didn't receive the email, we will gladly send you another.</span>

                    </div>
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 fw-medium text-success">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif


                    <div class="mb-11">
                        <button class="btn btn-sm btn-warning" wire:click="sendVerification"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove class="indicator-label">Resend Verification Email</span>
                            <span wire:loading>
                                Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                            </span>
                        </button>
                        <button class="btn btn-sm btn-secondary" wire:click="logout" type="submit">Logout</button>
                    </div>
                    <div class="mb-0">
                        <img src="../../assets/media/auth/verify-email.png"
                            class="mw-100 mh-300px theme-light-show" alt="" />
                        <img src="../../assets/media/auth/verify-email-dark.png"
                            class="mw-100 mh-300px theme-dark-show" alt="" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
