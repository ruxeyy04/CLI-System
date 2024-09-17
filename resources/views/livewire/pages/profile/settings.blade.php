<?php
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Livewire\Actions\Logout;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Validation\Rule;
new #[Layout('layouts.assistant')] class extends Component {
    // Personal information
    public string $fname = '';
    public string $midname = '';
    public string $lname = '';
    public string $username = '';
    public string $motto = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';

    public array $originalValues = [];
    public bool $hasUnsavedChanges = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->fname = $user->fname;
        $this->midname = $user->midname ?? '';
        $this->lname = $user->lname;
        $this->username = $user->username;
        $this->motto = $user->motto ?? '';
        $this->phone = $user->phone ?? '';
        $this->email = $user->email;
        $this->address = $user->address ?? '';

        $this->originalValues = [
            'fname' => $this->fname,
            'midname' => $this->midname,
            'lname' => $this->lname,
            'username' => $this->username,
            'motto' => $this->motto,
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

        $this->hasUnsavedChanges = false;
    }
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'fname' => ['required', 'string', 'max:255'],
            'midname' => ['nullable','string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string','max:255', Rule::unique(User::class)->ignore($user->id)],
            'motto' => ['nullable','string', 'max:255'],
        ]);

        $user->fill($validated);

        $user->save();

        $this->dispatch('profile-updated', name: $user->fname);
    }

}; ?>

<div id="kt_app_content_container" class="app-container  container-xxl">
    <livewire:components.profile.usercardheader />
    <livewire:components.profile.navitems />
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('profileUpdated', () => {
                Swal.fire({
                    icon: 'success',
                    title: "Success",
                    text: "Profile details have been successfully updated",
                });
            });
        });
    </script>
    <!--begin::details View-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
            data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Profile Details</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->

        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
            <!--begin::Form-->
            <form class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit="updateProfileInformation">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <!--begin::Input group-->
                    <div class="row mb-6">
                        <!--begin::Label-->
                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                        <!--end::Label-->

                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <!--begin::Image input-->
                            <div class="image-input image-input-outline image-input-empty" data-kt-image-input="true"
                                style="background-image: url('{{ Auth::user()->profileimg ? asset('storage/profile/' . Auth::user()->id . '/' . Auth::user()->profileimg) : asset('storage/profile/default.jpg') }}')">
                                <!--begin::Preview existing avatar-->
                                <div class="image-input-wrapper w-125px h-125px" style="background-image: none;"></div>
                                <!--end::Preview existing avatar-->

                                <!--begin::Label-->
                                <label
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                    aria-label="Change avatar" data-bs-original-title="Change avatar"
                                    data-kt-initialized="1">
                                    <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span
                                            class="path2"></span></i>
                                    <!--begin::Inputs-->
                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                    <input type="hidden" name="avatar_remove" value="1">
                                    <!--end::Inputs-->
                                </label>
                                <!--end::Label-->

                                <!--begin::Cancel-->
                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                    aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                    data-kt-initialized="1">
                                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                            class="path2"></span></i> </span>
                                <!--end::Cancel-->

                                <!--begin::Remove-->
                                <span
                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                    aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                    data-kt-initialized="1">
                                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                            class="path2"></span></i> </span>
                                <!--end::Remove-->
                            </div>
                            <!--end::Image input-->

                            <!--begin::Hint-->
                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->


                        <div class="row mb-6">
                            <!-- First Name -->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">First Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="fname" class="form-control form-control-lg form-control-solid"
                                    placeholder="First Name" wire:model="fname" wire:keydown="checkForChanges">
                            </div>
                        </div>
                    
                        <div class="row mb-6">
                            <!-- Middle Name -->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Middle Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="midname" class="form-control form-control-lg form-control-solid"
                                    placeholder="Middle Name" wire:model="midname" wire:keydown="checkForChanges">
                            </div>
                        </div>
                    
                        <div class="row mb-6">
                            <!-- Last Name -->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Last Name</label>
                            <div class="col-lg-8">
                                <input type="text" name="lname" class="form-control form-control-lg form-control-solid"
                                    placeholder="Last Name" wire:model="lname" wire:keydown="checkForChanges">
                            </div>
                        </div>
                    
                        <div class="row mb-6">
                            <!-- Username -->
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Username</label>
                            <div class="col-lg-8">
                                <input type="text" name="username" class="form-control form-control-lg form-control-solid"
                                    placeholder="Username" wire:model="username" wire:keydown="checkForChanges">
                            </div>
                        </div>
                    
                        <div class="row mb-6">
                            <!-- Motto -->
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Motto</label>
                            <div class="col-lg-8">
                                <input type="text" name="motto" class="form-control form-control-lg form-control-solid"
                                    placeholder="Motto" wire:model="motto" wire:keydown="checkForChanges">
                            </div>
                        </div>
                    
                        <!-- Card Footer -->
                    
                </div>
                <!--end::Card body-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    @if($hasUnsavedChanges)
                        <button type="button" wire:loading.attr='disabled' class="btn btn-light btn-active-light-primary me-2" wire:click="discardChanges" wire:target="discardChanges">
                            <span wire:loading.remove wire:target="discardChanges">Discard</span>
                            <span wire:loading wire:target="discardChanges">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr='disabled' wire:target="updateProfileInformation">
                            <span wire:loading.remove wire:target="updateProfileInformation">Save Changes</span>
                            <span wire:loading wire:target="updateProfileInformation">
                                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    @endif
                    
                </div>
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>


</div>
