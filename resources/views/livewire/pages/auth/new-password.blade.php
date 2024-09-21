<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

new #[Layout('layouts.welcome')] class extends Component {
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'password' => ['required', 'min:8','string', 'confirmed', Rules\Password::defaults()],
        ]);
        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function logout(Logout $logout): void
    {
        $logout();
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
            <div class="py-5 card card-flush w-lg-600px">
                <div class="card-body py-15 py-lg-20">

                    <div class="mb-7">
                        <a href="/" class="" wire:navigate>
                            <img alt="Logo" src="../../assets/media/logos/default.png" height="100" />
                        </a>
                    </div>
                    <h1 class="mb-5 text-gray-900 fw-bolder">
                        Welcome to CLIS
                    </h1>
                    <div class="mb-8 fs-6">
                        <span class="text-gray-500 fw-semibold">
                            Welcome! Before we get started, let's set up your new password for a secure and seamless
                            experience.
                            This will only take a moment and help keep your account safe.
                        </span>
                        <span class="text-gray-500 fw-semibold">
                            If you need to log out and come back later, feel free to do so.
                        </span>
                        <br>
                        <a href="#!" wire:click='logout' class="text-danger fw-bold">Logout</a>
                    </div>

                    <div class="m-auto w-lg-400px">

                        <!--begin::Form-->
                        <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework"
                            wire:submit='updatePassword'>

                            <!--begin::Input group-->
                            <div class="mb-8 fv-row fv-plugins-icon-container" data-kt-password-meter="true">
                                <!--begin::Wrapper-->
                                <div class="mb-1">
                                    <!--begin::Input wrapper-->
                                    <div class="mb-3 position-relative">
                                        <input class="bg-transparent form-control" type="password"
                                            placeholder="Password" name="password" wire:model='password'
                                            autocomplete="off">

                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="ki-duotone ki-eye-slash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                            </i>
                                            <i class="ki-duotone ki-eye fs-2 d-none">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <!--end::Input wrapper-->

                                    <!--begin::Meter-->
                                    <div class="mb-3 d-flex align-items-center"
                                        data-kt-password-meter-control="highlight" wire:ignore>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2">
                                        </div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2">
                                        </div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2">
                                        </div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px"></div>
                                    </div>
                                    <!--end::Meter-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Hint-->
                                <div class="text-muted">
                                    Use 8 or more characters with a mix of letters, numbers &amp; symbols.
                                </div>
                                <!--end::Hint-->
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('password')
                                        {{ $message }}
                                    @enderror
                                </div>
                            </div>
                            <!--end::Input group--->

                            <!--end::Input group--->
                            <div class="mb-8 fv-row fv-plugins-icon-container">
                                <!--begin::Repeat Password-->
                                <input type="password" placeholder="Repeat Password" name="password_confirmation" wire:model='password_confirmation'
                                    autocomplete="off" class="bg-transparent form-control">
                                <!--end::Repeat Password-->
                                <div
                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    @error('password_confirmation')
                                    {{ $message }}
                                @enderror
                                </div>
                            </div>
                            <!--end::Input group--->


                            <!--begin::Action-->
                            <div class="mb-10 d-grid">
                                <button type="submit" id="kt_new_password_submit" class="btn btn-warning"
                                    wire:loading.attr='disabled' wire:target='updatePassword'>
                                    <span wire:loading.remove wire:target='updatePassword'>Update Password</span>
                                    <span wire:loading wire:target='updatePassword'>
                                        Please wait...
                                        <span class="align-middle spinner-border spinner-border-sm ms-2">
                                        </span>
                                    </span>
                                </button>
                            </div>
                            <!--end::Action-->
                        </form>
                        <!--end::Form-->
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
