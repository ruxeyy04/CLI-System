<x-welcome-layout>

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
                <div class="py-5 card card-flush w-lg-500px">
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
                            <span class="text-gray-500 fw-semibold">Before we get started, please setup your new password</span>

                        </div>
                        <div class="m-auto w-lg-300px">

                            <!--begin::Form-->
                            <form class="form w-100 fv-plugins-bootstrap5 fv-plugins-framework" >

                                <!--begin::Input group-->
                                <div class="mb-8 fv-row fv-plugins-icon-container" data-kt-password-meter="true">
                                    <!--begin::Wrapper-->
                                    <div class="mb-1">
                                        <!--begin::Input wrapper-->
                                        <div class="mb-3 position-relative">
                                            <input class="bg-transparent form-control" type="password"
                                                placeholder="Password" name="password" autocomplete="off">

                                            <span
                                                class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                data-kt-password-meter-control="visibility">
                                                <i class="ki-duotone ki-eye-slash fs-2"></i> <i
                                                    class="ki-duotone ki-eye fs-2 d-none"></i> </span>
                                        </div>
                                        <!--end::Input wrapper-->

                                        <!--begin::Meter-->
                                        <div class="mb-3 d-flex align-items-center"
                                            data-kt-password-meter-control="highlight">
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
                                    </div>
                                </div>
                                <!--end::Input group--->

                                <!--end::Input group--->
                                <div class="mb-8 fv-row fv-plugins-icon-container">
                                    <!--begin::Repeat Password-->
                                    <input type="password" placeholder="Repeat Password" name="confirm-password"
                                        autocomplete="off" class="bg-transparent form-control">
                                    <!--end::Repeat Password-->
                                    <div
                                        class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                    </div>
                                </div>
                                <!--end::Input group--->


                                <!--begin::Action-->
                                <div class="mb-10 d-grid">
                                    <button type="button" id="kt_new_password_submit" class="btn btn-primary">

                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">
                                            Submit</span>
                                        <!--end::Indicator label-->

                                        <!--begin::Indicator progress-->
                                        <span class="indicator-progress">
                                            Please wait... <span
                                                class="align-middle spinner-border spinner-border-sm ms-2"></span>
                                        </span>
                                        <!--end::Indicator progress--> </button>
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
</x-welcome-layout>
