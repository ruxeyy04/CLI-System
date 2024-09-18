<div class="d-flex flex-column flex-root" id="kt_app_root">

    <style>
        body {
            background-image: url('../../assets/media/auth/welcome-bg.png');
        }

        [data-bs-theme="dark"] body {
            background-image: url('../../assets/media/auth/welcome-bg.png');
        }
    </style>

    <div class="d-flex flex-column flex-center flex-column-fluid">
        <!--begin::Content-->
        <div class="p-10 text-center d-flex flex-column flex-center">
            <!--begin::Wrapper-->
            <div class="py-5 card card-flush w-md-650px">
                <div class="card-body py-15 py-lg-20">

                    <!--begin::Logo-->
                    <div class="mb-7">
                        <a href="/" class="" wire:navigate>
                            <img alt="Logo" src="../../assets/media/logos/default.png"
                                height="100" />
                        </a>
                    </div>
                    <!--end::Logo-->

                    <!--begin::Title-->
                    <h1 class="mb-5 text-gray-900 fw-bolder">
                        Welcome to CLIS </h1>
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="text-gray-500 fw-semibold fs-6 mb-7">
                        Computer Laboratory Information System
                    </div>
                    <!--end::Text-->

                    <!--begin::Illustration-->
                    <div class="mb-0">
                        <img src="../../assets/media/auth/welcome.png"
                            class="mw-100 mh-300px theme-light-show" alt="" />
                        <img src="../../assets/media/auth/welcome-dark.png"
                            class="mw-100 mh-300px theme-dark-show" alt="" />
                    </div>
                    <!--end::Illustration-->

                    <!--begin::Link-->
                    <div class="mb-0">
                        <livewire:navigate-to-login>
                    </div>
                    
                    <!--end::Link-->

                </div>
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Content-->
    </div>
</div>