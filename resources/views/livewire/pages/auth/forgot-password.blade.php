<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
     public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }

};
?>

<div>
    <div class="w-lg-500px p-10">
        <!--begin::Form-->
        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" wire:submit.prevent="sendPasswordResetLink">
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <h1 class="text-gray-900 fw-bolder mb-3">
                    Forgot Password ?
                </h1>
                <div class="text-gray-500 fw-semibold fs-6">
                    Enter your email to reset your password.
                </div>
            </div>
            <!--end::Heading-->

            <!--begin::Input group--->
            <div class="fv-row mb-8 fv-plugins-icon-container">
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <!--begin::Email-->
                <input type="text" placeholder="Email" name="email" autocomplete="off"
                    class="form-control bg-transparent" wire:model.debounce.300ms="email">
                <!--end::Email-->

                @error('email')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!--begin::Actions-->
            <div class="d-flex flex-wrap justify-content-center pb-lg-0">
                <button type="submit" id="kt_password_reset_submit" class="btn btn-primary me-4" wire:loading.attr="disabled">
                    <span wire:loading.remove class="indicator-label">Submit</span>
                    <span wire:loading>Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <a href="/login" class="btn btn-light" wire:navigate>Cancel</a>
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
</div>
