<?php
use App\Livewire\Actions\Logout;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.assistant')] class extends Component {
    public function sendVerification(): void
    {
        // If the email is not verified, send the verification email
        Auth::user()->sendEmailVerificationNotification();

        // Flash a session message indicating the verification link was sent
        Session::flash('status', 'verification-link-sent');
    }
    

}; ?>

<div id="kt_app_content_container" class="app-container  container-xxl">
    <livewire:components.profile.usercardheader />
    <livewire:components.profile.navitems />

    <!--begin::details View-->
    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <!--begin::Card header-->
        <div class="card-header cursor-pointer">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Profile Details</h3>
            </div>
            <!--end::Card title-->

            <!--begin::Action-->
            <a href="{{ route('account_settings') }}" class="btn btn-sm btn-primary align-self-center"
                wire:navigate>Edit Profile</a>
            <!--end::Action-->
        </div>
        <!--begin::Card header-->

        <!--begin::Card body-->
        <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Full Name</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Role</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ Str::ucfirst(Auth::user()->role) }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">
                    Contact Phone

                </label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8 d-flex align-items-center">
                    <span class="fw-bold fs-6 text-gray-800 me-2">{{ Auth::user()->phone ?? 'None' }}</span>

                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Address</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ Auth::user()->address ?? 'None' }}</span>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="row mb-7">
                <!--begin::Label-->
                <label class="col-lg-4 fw-semibold text-muted">Email Address</label>
                <!--end::Label-->

                <!--begin::Col-->
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ Auth::user()->email }}</span>
                    @if (Auth::user()->hasVerifiedEmail())
                        <span class="badge badge-success">Verified</span>
                    @else
                        <span class="badge badge-danger">Not Verified</span>
                    @endif

                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 fw-medium text-success">
                    {{ __('A new verification link has been sent to the email address') }}
                </div>
            @endif


            @if (!Auth::user()->hasVerifiedEmail())
                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed  p-6">
         
                    <i class="ki-duotone ki-information fs-2tx text-warning me-4"><span class="path1"></span><span
                            class="path2"></span><span class="path3"></span></i> <!--end::Icon-->

                    <div class="d-flex flex-stack flex-grow-1 ">
                 
                        <div class=" fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Email Not Verified!</h4>

                            <div class="fs-6 text-gray-700 ">Your email is not verified. To verify, please <a
                                    href="#!" wire:click='sendVerification'  class="fw-bold"><span wire:loading.remove wire:target="sendVerification">Send an Email
                                        Verification.</span><span wire:loading wire:target="sendVerification"> <span class="spinner-border spinner-border-sm align-middle ms-2"></span> Please Wait...</span></a></div>
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </div>


</div>
