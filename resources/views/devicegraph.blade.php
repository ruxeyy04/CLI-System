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
                    <div class="pt-0 card-body">
                        <!--begin::Progress-->
                        <div class="mt-3 d-flex align-items-center flex-column w-100">
                            <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                                <span class="text-gray-900 fw-bolder fs-6">Local Disk (C:)</span>
                                <span class="text-gray-500 fw-bold fs-6">62%</span>
                            </div>

                            <div class="mx-3 rounded h-8px w-100 bg-light-success">
                                <div class="rounded bg-success h-8px" role="progressbar" style="width: 70%;"
                                    aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center flex-column w-100">
                            <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                                <span class="text-gray-900 fw-bolder fs-6">Local Disk (D:)</span>
                                <span class="text-gray-500 fw-bold fs-6">62%</span>
                            </div>

                            <div class="mx-3 rounded h-8px w-100 bg-light-danger">
                                <div class="rounded bg-danger h-8px" role="progressbar" style="width: 90%;"
                                    aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="mt-3 d-flex align-items-center flex-column w-100">
                            <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                                <span class="text-gray-900 fw-bolder fs-6">Local Disk (E:)</span>
                                <span class="text-gray-500 fw-bold fs-6">62%</span>
                            </div>

                            <div class="mx-3 rounded h-8px w-100 bg-light-success">
                                <div class="rounded bg-success h-8px" role="progressbar" style="width: 30%;"
                                    aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!--end::Progress-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card widget 5-->
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="mb-10 col-md-6 col-lg-6 col-xl-6 col-xxl-3">
                <!--begin::Card widget 6-->
                <div class="mb-5 card card-flush h-md-50 mb-xl-10">
                    <!--begin::Header-->
                    <div class="pt-5 card-header">
                        <!--begin::Title-->
                        <div class="card-title d-flex flex-column">
                            <!--begin::Info-->
                            <div class="d-flex align-items-center">
                                <span class="text-gray-900 fs-2hx fw-bold me-2 lh-1 ls-n2">RAM</span>

                            </div>
                            <!--end::Info-->

                            <!--begin::Subtitle-->
                            <span class="pt-1 text-gray-500 fw-semibold fs-6">Random Access Memory</span>
                            <!--end::Subtitle-->
                        </div>
                        <!--end::Title-->
                    </div>
                    <!--end::Header-->

                    <!--begin::Card body-->
                    <div class="px-0 pb-0 card-body d-flex align-items-end">

                    </div>
                </div>
                <div class="card card-flush h-md-50 mb-xl-10">
                    <div class="pt-5 card-header">
                        <div class="card-title d-flex flex-column">
                            <span class="text-gray-900 fs-2hx fw-bold me-2 lh-1 ls-n2">GPU</span>
                            <span class="pt-1 text-gray-500 fw-semibold fs-6">Graphic Processing Unit</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-end pe-0">

                    </div>
                </div>
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.cpugraph :device="$device" />
            </div>
            <!--end::Col-->
        </div>
        <!--end::Row-->
    </div>

    </x-assisant-layout>
