<div class="d-flex flex-column flex-root" id="kt_app_root">

    <style>
        body {
            background-image: url('../../build/assets/media/auth/bg17.jpg');
        }

        [data-bs-theme="dark"] body {
            background-image: url('../../build/assets/media/auth/bg17-dark.jpg');
        }
    </style>

    <div class="d-flex flex-column flex-center flex-column-fluid">
        <!--begin::Content-->
        <div class="d-flex flex-column flex-center text-center p-10">
            <!--begin::Wrapper-->
            <div class="card card-flush w-md-650px py-5">
                <div class="card-body py-15 py-lg-20">

                    <!--begin::Logo-->
                    <div class="mb-7">
                        <a href="../../index-2.html" class="">
                            <img alt="Logo" src="../../build/assets/media/logos/default-small.svg"
                                class="h-40px" />
                        </a>
                    </div>
                    <!--end::Logo-->

                    <!--begin::Title-->
                    <h1 class="fw-bolder text-gray-900 mb-5">
                        Welcome to CLIS </h1>
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="fw-semibold fs-6 text-gray-500 mb-7">
                        Computer Laboratory Information System
                    </div>
                    <!--end::Text-->

                    <!--begin::Illustration-->
                    <div class="mb-0">
                        <img src="../../build/assets/media/auth/welcome.png"
                            class="mw-100 mh-300px theme-light-show" alt="" />
                        <img src="../../build/assets/media/auth/welcome-dark.png"
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