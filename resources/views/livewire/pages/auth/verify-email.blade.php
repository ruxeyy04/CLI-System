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
            background-image: url('../../build/assets/media/auth/bg16.jpg');
        }

        [data-bs-theme="dark"] body {
            background-image: url('../../build/assets/media/auth/bg16-dark.jpg');
        }
    </style>


    <div class="d-flex flex-column flex-center flex-column-fluid">
        <div class="d-flex flex-column flex-center text-center p-10">
            <div class="card card-flush w-lg-650px py-5">
                <div class="card-body py-15 py-lg-20">

                    <div class="mb-14">
                        <a href="../../index-2.html" class="">
                            <img alt="Logo" src="../../build/assets/media/logos/default-small.svg" class="h-40px" />
                        </a>
                    </div>
                    <h1 class="fw-bolder text-gray-900 mb-5">
                        Welcome to CLIS
                    </h1>
                    <div class="fs-6 mb-8">
                        <span class="fw-semibold text-gray-500">Thanks for signing up! Before getting started, could
                            you verify your email address by clicking on the link we just emailed to you? If you
                            didn't receive the email, we will gladly send you another.</span>

                    </div>
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 fw-medium text-success">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif


                    <div class="mb-11">
                        <button class="btn btn-sm btn-primary" wire:click="sendVerification"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove class="indicator-label">Resend Verification Email</span>
                            <span wire:loading>
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button class="btn btn-sm btn-secondary" wire:click="logout" type="submit">Logout</button>
                    </div>
                    <div class="mb-0">
                        <img src="../../build/assets/media/auth/verify-email.png"
                            class="mw-100 mh-300px theme-light-show" alt="" />
                        <img src="../../build/assets/media/auth/verify-email-dark.png"
                            class="mw-100 mh-300px theme-dark-show" alt="" />
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
