<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public $showForm = false;
    /**
     * Update the password for the currently authenticated user.
     */
    public function changePassword()
    {
        $this->showForm = !$this->showForm;
        $this->resetValidation();
    }
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
        $this->showForm = !$this->showForm;
    }
}; ?>

<div class="mb-5 card mb-xl-10">
    {{-- begin::Card header --}}
    <div class="border-0 cursor-pointer card-header" role="button" data-bs-toggle="collapse"
        data-bs-target="#contactInfoContainer">
        <div class="m-0 card-title">
            <h3 class="m-0 fw-bold">Update Password</h3>
        </div>
    </div>
    {{-- end::Card header --}}

    {{-- begin::Content --}}
    <div id="contactInfoContainer" class="collapse show">
        {{-- begin::Card body --}}
        <div class="card-body border-top p-9">
            {{-- begin::change password --}}
            <div class="flex-wrap d-flex align-items-center" x-data="{ showForm: @entangle('showForm') }">
                {{-- begin::Label --}}
                <div :class="showForm ? 'd-none' : ''">
                    <div class="mb-1 fs-6 fw-bold">Password</div>
                    <div class="text-gray-600 fw-semibold">************</div>
                </div>
                {{-- end::Label --}}

                {{-- begin::Edit --}}
                <div :class="showForm ? '' : 'd-none'" class="flex-row-fluid">
                    {{-- begin::Form --}}
                    <form class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="updatePassword">
                        <div class="mb-1 row">
                            <div class="col-lg-4">
                                <div class="mb-0 fv-row fv-plugins-icon-container">
                                    <label for="current_password" class="mb-3 form-label fs-6 fw-bold">Current
                                        Password</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid "
                                        name="current_password" id="current_password"
                                        wire:model="current_password">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('current_password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-0 fv-row fv-plugins-icon-container">
                                    <label for="password" class="mb-3 form-label fs-6 fw-bold">New Password</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid "
                                        name="password" id="password" wire:model="password">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="mb-0 fv-row fv-plugins-icon-container">
                                    <label for="password_confirmation" class="mb-3 form-label fs-6 fw-bold">Confirm New
                                        Password</label>
                                    <input type="password" class="form-control form-control-lg form-control-solid "
                                        name="password_confirmation" id="password_confirmation"
                                        wire:model="password_confirmation">
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('password_confirmation')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5 form-text">Password must be at least 8 character and contain symbols</div>

                        <div class="d-flex">
                            <button id="kt_password_submit" type="submit" class="px-6 btn btn-primary me-2"
                                wire:loading.attr='disabled' wire:target="updatePassword">
                                <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                                <span wire:loading wire:target="updatePassword">
                                    Please wait... <span
                                        class="align-middle spinner-border spinner-border-sm ms-2"></span>
                            </button>
                            <button id="kt_password_cancel" type="button"
                                class="px-6 btn btn-color-gray-500 btn-active-light-primary"
                                wire:click="changePassword">Cancel</button>
                        </div>
                    </form>
                    {{-- end::Form --}}
                </div>
                {{-- end::Edit --}}

                {{-- begin::Action --}}
                <div class="ms-auto" :class="showForm ? 'd-none' : ''">
                    <button class="btn btn-light btn-active-light-primary" wire:click="changePassword">Change
                        Password</button>
                </div>
                {{-- end::Action --}}
            </div>
            {{-- end::change password --}}

            {{-- begin::Separator --}}
            <div class="my-6 separator separator-dashed"></div>
            {{-- end::Separator --}}
            <x-action-message class="text-success me-3" on="password-updated">
                {{ __('Password is updated successfully.') }}
            </x-action-message>
        </div>
        {{-- end::Card body --}}
    </div>
    {{-- end::Content --}}
</div>
