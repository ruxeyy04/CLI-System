<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
new class extends Component {
    #[Validate('required|max:255|string')]
    public $phone_number;
    public $originalPhone;

    #[Validate('required|max:255|email|string|lowercase')]
    public $email;
    public $originalEmail;

    #[Validate('required|max:255|string')]
    public $address;
    public $originalAddress;

    #[Validate('required|current_password|string')]
    public $current_password;

    public bool $hasUnsavedChanges = false;
    public $showForm = false;
    public $showFormPhone = false;
    public $showFormAddress = false;
    public function mount(): void
    {
        $user = Auth::user();

        $this->phone = $user->phone ?? '';
        $this->originalEmail = auth()->user()->email;
        $this->email = $this->originalEmail;
        $this->originalPhone = auth()->user()->phone;
        $this->phone_number = $this->originalPhone;
        $this->originalAddress = auth()->user()->address;
        $this->address = $this->originalAddress;
        $this->originalValues = [
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
        ];
    }

    public function checkForChanges(): void
    {
        $this->hasUnsavedChanges = false;

        foreach ($this->originalValues as $key => $value) {
            if ($this->$key !== $value) {
                $this->hasUnsavedChanges = true;
                break;
            }
        }
    }
    public function discardChanges(): void
    {
        foreach ($this->originalValues as $key => $value) {
            $this->$key = $value;
        }
        $this->resetValidation();
        $this->hasUnsavedChanges = false;
    }
    public function updateEmailSubmit()
    {
        $user = Auth::user();
        $validated = $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $this->dispatch('email-update', email: $user->email);

        $user->save();
        $this->showForm = !$this->showForm;
    }
    public function updatePhoneSubmit()
    {
        $user = Auth::user();
        $validated = $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'phone_number' => ['required', 'string', 'max:20', Rule::unique(User::class, 'phone')->ignore($user->id)],
        ]);
        $user->phone = $validated['phone_number'];
        $this->dispatch('phone-update', phone: $user->phone);
        $user->save();
        $this->showFormPhone = !$this->showFormPhone;
    }
    public function updateAddressSubmit()
    {
        $user = Auth::user();
        $validated = $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'address' => ['required', 'string', 'max:255'],
        ]);
        $user->fill($validated);

        $this->dispatch('address-update', address: $user->address);

        $user->save();
        $this->showFormAddress = !$this->showFormAddress;
    }
    public function changeEmail()
    {
        $this->showForm = !$this->showForm;

        if (!$this->showForm) {
            $this->email = $this->originalEmail;
        }
        $this->resetValidation();
    }
    public function changePhoneNumber()
    {
        $this->showFormPhone = !$this->showFormPhone;

        if (!$this->showFormPhone) {
            $this->phone_number = $this->originalPhone;
        }
        $this->resetValidation();
    }
    public function changeAddress()
    {
        $this->showFormAddress = !$this->showFormAddress;

        if (!$this->showFormAddress) {
            $this->address = $this->originalAddress;
        }
        $this->resetValidation();
    }
    public function sendVerification(): void
    {
        // If the email is not verified, send the verification email
        Auth::user()->sendEmailVerificationNotification();

        // Flash a session message indicating the verification link was sent
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div class="card  mb-5 mb-xl-10">
    {{-- begin::Card header --}}
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
        data-bs-target="#contactInfoContainer">
        <div class="card-title m-0">
            <h3 class="fw-bold m-0">Contact Information</h3>
        </div>
    </div>
    {{-- end::Card header --}}

    {{-- begin::Content --}}
    <div id="contactInfoContainer" class="collapse show">
        {{-- begin::Card body --}}
        <div class="card-body border-top p-9">
            {{-- begin::Email Address --}}
            <div class="d-flex flex-wrap align-items-center" x-data="{ showForm: @entangle('showForm') }">
                {{-- begin::Label --}}
                <div :class="showForm ? 'd-none' : ''">
                    <div class="fs-6 fw-bold mb-1">Email Address</div>
                    <div class="fw-semibold text-gray-600" x-data="{{ json_encode(['email' => auth()->user()->email]) }}"
                        x-on:email-update.window="email = $event.detail.email;" x-text="email"></div>
                </div>
                {{-- end::Label --}}

                {{-- begin::Edit --}}
                <div :class="showForm ? '' : 'd-none'" class="flex-row-fluid">
                    {{-- begin::Form --}}
                    <form class="form" wire:submit="updateEmailSubmit">
                        <div class="row mb-6">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="fv-row mb-0">
                                    <label for="emailaddress" class="form-label fs-6 fw-bold mb-3">Enter New
                                        Email
                                        Address</label>
                                    <input type="text" class="form-control form-control-lg form-control-solid"
                                        placeholder="Email Address" name="email" wire:model.live="email" />
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="fv-row mb-0">
                                    <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">Confirm
                                        Password</label>
                                    <input type="password" name="current_password"
                                        class="form-control form-control-lg form-control-solid"
                                        wire:model.live="current_password" />
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('current_password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary  me-2 px-6" wire:loading.attr='disabled'
                                wire:target="updateEmailSubmit">
                                <span wire:loading.remove wire:target="updateEmailSubmit">Update Email</span>
                                <span wire:loading wire:target="updateEmailSubmit">
                                    Please wait... <span
                                        class="align-middle spinner-border spinner-border-sm ms-2"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-color-gray-500 btn-active-light-primary px-6"
                                wire:click="changeEmail">Cancel</button>
                        </div>
                    </form>
                    {{-- end::Form --}}
                </div>
                {{-- end::Edit --}}

                {{-- begin::Action --}}
                <div class="ms-auto" :class="showForm ? 'd-none' : ''">
                    <button class="btn btn-light btn-active-light-primary" wire:click="changeEmail">Change
                        Email</button>
                </div>
                {{-- end::Action --}}
            </div>
            {{-- end::Email Address --}}

            {{-- begin::Separator --}}
            <div class="separator separator-dashed my-6"></div>
            {{-- end::Separator --}}

            {{-- begin::phone number --}}
            <div class="d-flex flex-wrap align-items-center" x-data="{ showFormPhone: @entangle('showFormPhone') }">
                {{-- begin::Label --}}
                <div :class="showFormPhone ? 'd-none' : ''">
                    <div class="fs-6 fw-bold mb-1">Phone Number</div>
                    <div class="fw-semibold text-gray-600" x-data="{ phone: '{{ Auth::user()->phone ? Auth::user()->phone : 'None' }}' }"
                        x-on:phone-update.window="phone = $event.detail.phone;" x-text="phone"></div>
                </div>
                {{-- end::Label --}}

                {{-- begin::Edit --}}
                <div :class="showFormPhone ? '' : 'd-none'" class="flex-row-fluid">
                    {{-- begin::Form --}}
                    <form class="form" wire:submit="updatePhoneSubmit">
                        <div class="row mb-6">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="fv-row mb-0">
                                    <label for="phone_number" class="form-label fs-6 fw-bold mb-3">Enter New Phone
                                        Number</label>
                                    <input type="text" class="form-control form-control-lg form-control-solid"
                                        placeholder="Phone Number" name="phone_number" wire:model.live="phone_number" />
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('phone_number')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="fv-row mb-0">
                                    <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">Confirm
                                        Password</label>
                                    <input type="password" name="current_password"
                                        class="form-control form-control-lg form-control-solid"
                                        wire:model.live="current_password" />
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('current_password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary  me-2 px-6" wire:loading.attr='disabled'
                                wire:target="updatePhoneSubmit">
                                <span wire:loading.remove wire:target="updatePhoneSubmit">Update Phone Number</span>
                                <span wire:loading wire:target="updatePhoneSubmit">
                                    Please wait... <span
                                        class="align-middle spinner-border spinner-border-sm ms-2"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-color-gray-500 btn-active-light-primary px-6"
                                wire:click="changePhoneNumber">Cancel</button>
                        </div>
                    </form>
                    {{-- end::Form --}}
                </div>
                {{-- end::Edit --}}

                {{-- begin::Action --}}
                <div class="ms-auto" :class="showFormPhone ? 'd-none' : ''">
                    <button class="btn btn-light btn-active-light-primary" wire:click="changePhoneNumber">Change
                        Phone Number</button>
                </div>
                {{-- end::Action --}}
            </div>
            {{-- end::phone number --}}
            {{-- begin::Separator --}}
            <div class="separator separator-dashed my-6"></div>
            {{-- end::Separator --}}
            {{-- begin::address --}}
            <div class="d-flex flex-wrap align-items-center" x-data="{ showFormAddress: @entangle('showFormAddress') }">
                {{-- begin::Label --}}
                <div :class="showFormAddress ? 'd-none' : ''">
                    <div class="fs-6 fw-bold mb-1">Address</div>
                    <div class="fw-semibold text-gray-600" x-data="{ address: '{{ Auth::user()->address ? Auth::user()->address : 'None' }}' }"
                        x-on:address-update.window="address = $event.detail.address;" x-text="address"></div>
                </div>
                {{-- end::Label --}}

                {{-- begin::Edit --}}
                <div :class="showFormAddress ? '' : 'd-none'" class="flex-row-fluid">
                    {{-- begin::Form --}}
                    <form class="form" wire:submit="updateAddressSubmit">
                        <div class="row mb-6">
                            <div class="col-lg-6 mb-4 mb-lg-0">
                                <div class="fv-row mb-0">
                                    <label for="address" class="form-label fs-6 fw-bold mb-3">Enter New
                                        Address</label>
                                    <input type="text" class="form-control form-control-lg form-control-solid"
                                        placeholder="Address" name="address" wire:model.live="address" />
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('address')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="fv-row mb-0">
                                    <label for="confirmemailpassword" class="form-label fs-6 fw-bold mb-3">Confirm
                                        Password</label>
                                    <input type="password" name="current_password"
                                        class="form-control form-control-lg form-control-solid"
                                        wire:model.live="current_password" />
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        @error('current_password')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary  me-2 px-6" wire:loading.attr='disabled'
                                wire:target="updateAddressSubmit">
                                <span wire:loading.remove wire:target="updateAddressSubmit">Update Address</span>
                                <span wire:loading wire:target="updateAddressSubmit">
                                    Please wait... <span
                                        class="align-middle spinner-border spinner-border-sm ms-2"></span>
                                </span>
                            </button>
                            <button type="button" class="btn btn-color-gray-500 btn-active-light-primary px-6"
                                wire:click="changeAddress">Cancel</button>
                        </div>
                    </form>
                    {{-- end::Form --}}
                </div>
                {{-- end::Edit --}}

                {{-- begin::Action --}}
                <div class="ms-auto" :class="showFormAddress ? 'd-none' : ''">
                    <button class="btn btn-light btn-active-light-primary" wire:click="changeAddress">Change
                        Address</button>
                </div>
                {{-- end::Action --}}
            </div>
            {{-- end::address --}}
            @if (!Auth::user()->hasVerifiedEmail())
                {{-- begin::Notice --}}
                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
                    <i class="ki-duotone ki-information fs-2tx text-warning me-4"><span class="path1"></span><span
                            class="path2"></span><span class="path3"></span></i>

                    {{-- begin::Wrapper --}}
                    <div class="d-flex flex-stack flex-grow-1 flex-wrap flex-md-nowrap">
                        {{-- begin::Content --}}
                        <div class="mb-3 mb-md-0 fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Email Not Verified!</h4>

                            <div class="fs-6 text-gray-700 pe-7">Your email is not verified. To verify, please click
                                the
                                verify button
                            </div>
                        </div>
                        {{-- end::Content --}}

                        {{-- begin::Action --}}
                        <button class="btn btn-primary px-6 align-self-center text-nowrap"
                            wire:click="sendVerification" wire:loading.attr='disabled'
                            wire:target="sendVerification">
                            <span wire:loading.remove wire:target="sendVerification">Verify</span>
                            <span wire:loading wire:target="sendVerification">
                                Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                            </span>

                        </button>
                        {{-- end::Action --}}
                    </div>
                    {{-- end::Wrapper --}}
                </div>
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 fw-medium text-success text-center mt-3">
                        {{ __('A new verification link has been sent to the email address') }}
                    </div>
                @endif
                {{-- end::Notice --}}
            @endif
        </div>
        {{-- end::Card body --}}
    </div>
    {{-- end::Content --}}
</div>
