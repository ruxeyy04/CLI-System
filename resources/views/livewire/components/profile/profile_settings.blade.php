<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
new class extends Component {
    use WithFileUploads;

    #[Validate('image|max:2048')]
    public $profileimg;
    // Personal information
    #[Validate('required|max:255|string')] 
    public $fname;
    #[Validate('max:255|string')] 
    public $midname;
    #[Validate('required|max:255|string')] 
    public $lname;
    #[Validate('required|max:255|string')] 
    public $username;
    #[Validate('max:255|string')] 
    public $motto;
  
    public $existProfileImg = '';
    public array $originalValues = [];
    public bool $hasUnsavedChanges = false;
    public bool $removeProfileImg = false;
    public function mount(): void
    {
        $user = Auth::user();
        $this->fname = $user->fname;
        $this->midname = $user->midname ?? '';
        $this->lname = $user->lname;
        $this->username = $user->username;
        $this->existProfileImg = $user->profileimg ?? '';
        $this->motto = $user->motto ?? '';
        $this->originalValues = [
            'fname' => $this->fname,
            'midname' => $this->midname,
            'lname' => $this->lname,
            'username' => $this->username,
            'motto' => $this->motto,
            'profileimg' => $this->profileimg,
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
    public function cancelImage()
    {
        $this->profileimg = null;
        $this->hasUnsavedChanges = true;
    }
    public function discardChanges(): void
    {
        $user = Auth::user();
        foreach ($this->originalValues as $key => $value) {
            $this->$key = $value;
        }

        $this->hasUnsavedChanges = false;
        if ($user->profileimg) {
            $this->dispatch('backToDefault');
        } else {
            $this->dispatch('discardImageCol');
        }
        $this->resetValidation();
    }
    public function removeProfileImage()
    {
        $user = Auth::user();
        $this->hasUnsavedChanges = true;
        if ($user->profileimg) {
            $this->dispatch('discardImageCol');
            $this->removeProfileImg = true;
        } else {
            $this->dispatch('backToDefault');
        }
    }

    public function updatedProfileimg()
    {
        $this->hasUnsavedChanges = true;
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();
        if ($this->profileimg) {
            $this->validate([
                'profileimg' => 'image|max:2048', // Validate profile image size
            ]);
        }
        if ($this->removeProfileImg) {
            Storage::disk('public')->delete('profile/' . $user->id . '/' . $user->profileimg);
            $user->profileimg = null;
        }

        $validated = $this->validate([
            'fname' => ['required', 'string', 'max:255'],
            'midname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'motto' => ['nullable', 'string', 'max:255'],
        ]);
        $user->fill($validated);
        if ($this->profileimg) {
            $path = $this->profileimg->storeAs('profile/' . $user->id, $this->profileimg->getClientOriginalName(), 'public');
            $user->profileimg = $this->profileimg->getClientOriginalName();
            $this->profileimg = null;
        }

        $user->save();

        $this->dispatch('profile-updated', motto: $user->motto, fname: $user->fname, lname: $user->lname, profileimg: $user->profileimg ? '/storage/profile/' . $user->id . '/' . $user->profileimg : '/storage/profile/default.jpg');

        $this->originalValues = [
            'fname' => $user->fname,
            'midname' => $user->midname ?? '',
            'lname' => $user->lname,
            'username' => $user->username,
            'motto' => $user->motto ?? '',
        ];

        $this->hasUnsavedChanges = false;
    }
}; ?>

<div class="mb-5 card mb-xl-10">
    <!--begin::Card header-->
    <div class="border-0 cursor-pointer card-header" role="button" data-bs-toggle="collapse"
        data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
        <!--begin::Card title-->
        <div class="m-0 card-title">
            <h3 class="m-0 fw-bold">Profile Details</h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div class="collapse show" id="kt_account_profile_details">
        <!--begin::Form-->
        <form class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="updateProfileInformation"
            enctype="multipart/form-data">
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
                <!--begin::Input group-->
                <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                    <!--end::Label-->

                    <!--begin::Col-->
                    <div class="col-lg-8">
                        <!--begin::Image input-->
                        <div class="image-input image-input-outline {{ $existProfileImg ? '' : 'image-input-empty' }}"
                            data-kt-image-input="true" wire:ignore
                            style="background-image: url('{{ asset('storage/profile/default.jpg') }}')">
                            <!--begin::Preview existing avatar-->
                            <div class="image-input-wrapper w-125px h-125px"
                                style="background-image: {{ $existProfileImg ? "url('" . asset('storage/profile/' . Auth::user()->id . '/' . $existProfileImg) . "')" : 'none' }};');">
                            </div>
                            <!--end::Preview existing avatar-->

                            <!--begin::Label-->
                            <label class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span
                                        class="path2"></span></i>
                                <!--begin::Inputs-->
                                <input type="file" wire:model="profileimg" name="profileimg"
                                    accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                                <!--end::Inputs-->
                            </label>
                            <!--end::Label-->

                            <!--begin::Cancel-->
                            <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar"
                                wire:click='cancelImage'>
                                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                        class="path2"></span></i> </span>
                            <!--end::Cancel-->


                            <!--begin::Remove-->
                            <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar"
                                wire:click='removeProfileImage'>
                                <i class="ki-duotone ki-trash-square fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i> </span>
                            <!--end::Remove-->
                        </div>
                        <!--end::Image input-->

                        <!--begin::Hint-->
                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                        <!--end::Hint-->

                        <div
                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                            @error('profileimg')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->


                <div class="mb-6 row">
                    <!-- First Name -->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">First Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="fname" class="form-control form-control-lg form-control-solid"
                            placeholder="First Name" wire:model="fname" wire:keydown="checkForChanges">
                        @error('fname')
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">

                                {{ $message }}

                            </div>
                        @enderror
                    </div>

                </div>

                <div class="mb-6 row">
                    <!-- Middle Name -->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Middle Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="midname" class="form-control form-control-lg form-control-solid"
                            placeholder="Middle Name" wire:model="midname" wire:keydown="checkForChanges">
                        @error('midname')
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">

                                {{ $message }}

                            </div>
                        @enderror
                    </div>

                </div>

                <div class="mb-6 row">
                    <!-- Last Name -->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Last Name</label>
                    <div class="col-lg-8">
                        <input type="text" name="lname" class="form-control form-control-lg form-control-solid"
                            placeholder="Last Name" wire:model="lname" wire:keydown="checkForChanges">
                        @error('lname')
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">

                                {{ $message }}

                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-6 row">
                    <!-- Username -->
                    <label class="col-lg-4 col-form-label required fw-semibold fs-6">Username</label>
                    <div class="col-lg-8">
                        <input type="text" name="username" class="form-control form-control-lg form-control-solid"
                            placeholder="Username" wire:model="username" wire:keydown="checkForChanges">
                        @error('username')
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">

                                {{ $message }}

                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-6 row">
                    <!-- Motto -->
                    <label class="col-lg-4 col-form-label fw-semibold fs-6">Motto</label>
                    <div class="col-lg-8">
                        <input type="text" name="motto" class="form-control form-control-lg form-control-solid"
                            placeholder="Motto" wire:model="motto" wire:keydown="checkForChanges">
                        @error('motto')
                            <div
                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">

                                {{ $message }}

                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Card Footer -->

            </div>
            <!--end::Card body-->
            <div class="py-6 card-footer d-flex justify-content-end px-9">
                @if ($hasUnsavedChanges)
                    <button type="button" wire:loading.attr='disabled'
                        class="btn btn-light btn-active-light-primary me-2" wire:click="discardChanges"
                        wire:target="discardChanges">
                        <span wire:loading.remove wire:target="discardChanges">Discard</span>
                        <span wire:loading wire:target="discardChanges">
                            Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                        </span>
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr='disabled'
                        wire:target="updateProfileInformation">
                        <span wire:loading.remove wire:target="updateProfileInformation">Save Changes</span>
                        <span wire:loading wire:target="updateProfileInformation">
                            Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                        </span>
                    </button>
                @endif

            </div>
        </form>
        <!--end::Form-->

    </div>
    <!--end::Content-->
</div>
