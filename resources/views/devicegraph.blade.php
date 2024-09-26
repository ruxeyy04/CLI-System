<x-assistant-layout>
    <div id="kt_app_content_container" class="app-container container-xxl ">

        <div class="row gx-5 gx-xl-10 mb-xl-10">
            <!--begin::Col-->
            <div class="mb-10 col-md-6 col-lg-6 col-xl-6 col-xxl-3">
                <livewire:components.computer.cpu-card :device="$device" />
                <div class="card card-flush h-md-50 mb-xl-10">
                    <!--begin::Header-->
                    <div class="pt-5 card-header">
                        <!--begin::Title-->
                        <div class="card-title d-flex flex-column">
                            <!--begin::Info-->
                            <div class="d-flex align-items-center">
                                <!--begin::Amount-->
                                <span class="text-gray-900 fs-2hx fw-bold me-2 lh-1 ls-n2">Storage</span>
                                <!--end::Amount-->
                            </div>
                            <!--end::Info-->

                            <!--begin::Subtitle-->
                            <span class="pt-1 text-gray-500 fw-semibold fs-6">Disk Information</span>
                            <!--end::Subtitle-->
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Header-->

                    <!--begin::Card body-->
                    <livewire:components.computer.disk-card-summary :device="$device" />
                    <!--end::Card body-->
                </div>
                <!--end::Card widget 5-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="mb-10 col-md-6 col-lg-6 col-xl-6 col-xxl-3">
                <!--begin::Card widget 6-->
                <livewire:components.computer.ram-card :device="$device" />
                <livewire:components.computer.gpu-card :device="$device" />

            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.cpugraph :device="$device" />
            </div>
            <!--end::Col-->
        </div>
        <div class="row gx-5 gx-xl-10 mb-xl-10">
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.ramgraph :device="$device" />
            </div>
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.gpugraph :device="$device" />
            </div>
        </div>
        <div class="row gx-5 gx-xl-10 mb-xl-10">
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.disk-cards :device="$device" />
            </div>
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.input-devices :device="$device" />
            </div>
        </div>
        <!--end::Row-->
    </div>
    <livewire:components.computer.modal />
</x-assisant-layout>
