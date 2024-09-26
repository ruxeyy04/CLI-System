<div class="row">

    <div class="col-md-6">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="text-gray-800 card-label fw-bold">Keyboard</span>
                    <span class="mt-1 text-gray-500 fw-semibold fs-6">Input Information</span>
                </h3>

            </div>
            <div class="card-body">
                <ul class="mx-0 nav nav-pills nav-pills-custom row position-relative mb-9" role="tablist">
                    <!--begin::Item-->
                    <li class="p-0 mx-0 nav-item col-4" role="presentation">
                        <!--begin::Link-->
                        <a class="border-0 nav-link d-flex justify-content-center w-100 h-100 active"
                            data-bs-toggle="pill" href="#keyboard_list_tab" aria-selected="true" role="tab">
                            <!--begin::Subtitle-->
                            <span class="mb-3 text-gray-800 nav-text fw-bold fs-6">
                                Keyboard
                            </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span
                                class="bottom-0 rounded bullet-custom position-absolute z-index-2 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->


                    <!--begin::Item-->
                    <li class="px-0 mx-0 nav-item col-4" role="presentation">
                        <!--begin::Link-->
                        <a class="border-0 nav-link d-flex justify-content-center w-100 h-100" data-bs-toggle="pill"
                            href="#keyboard_status_tab" aria-selected="false" role="tab" tabindex="-1">
                            <!--begin::Subtitle-->
                            <span class="mb-3 text-gray-800 nav-text fw-bold fs-6">
                                Status
                            </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span
                                class="bottom-0 rounded bullet-custom position-absolute z-index-2 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="px-0 mx-0 nav-item col-4" role="presentation">
                        <!--begin::Link-->
                        <a class="border-0 nav-link d-flex justify-content-center w-100 h-100" data-bs-toggle="pill"
                            href="#keyboard_note_tab" aria-selected="false" role="tab" tabindex="-1">
                            <!--begin::Subtitle-->
                            <span class="mb-3 text-gray-800 nav-text fw-bold fs-6">
                                Note
                            </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span
                                class="bottom-0 rounded bullet-custom position-absolute z-index-2 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                    <!--begin::Bullet-->
                    <span class="bottom-0 rounded position-absolute z-index-1 w-100 h-4px bg-light"></span>
                    <!--end::Bullet-->
                </ul>
                <div class="tab-content">
                   
                    <div class="tab-pane fade active show" id="keyboard_list_tab" role="tabpanel">
                        @foreach ($keyboards as $kb)
                      
                            <div class="m-0">
                                <div class="mb-5 d-flex align-items-sm-center">
                                    <div class="symbol symbol-45px me-4">
                                        <span class="symbol-label bg-primary">
                                            <i class="ki-duotone ki-keyboard text-inverse-primary fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <div class="flex-wrap d-flex align-items-center flex-row-fluid">
                                        <div class="flex-grow-1 me-2">
                                            <a href="#!"
                                                class="text-gray-500 fs-6 fw-semibold">{{ $kb->brand ? $kb->brand : 'Keyboard' }}</a>

                                            <span
                                                class="text-gray-800 fw-bold d-block fs-4">{{ $kb->model ? $kb->model : '' }}</span>
                                        </div>

                                        <div class="card-toolbar">
                                            <button
                                                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="ki-duotone ki-dots-square fs-1"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                                                <div class="px-3 menu-item">
                                                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">Quick
                                                        Actions</div>
                                                </div>
                                                <div class="mb-3 opacity-75 separator"></div>
                                                <div class="px-3 mb-3 menu-item">
                                                    
                                                    <a href="#!" class="px-3 menu-link" wire:click="$dispatch('update-data-modal-input', {'id': {{ $kb->id }}, 'type': 'keyboard-data'})">
                                                        Update Data
                                                    </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="timeline">
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Brand</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $kb->brand ? $kb->brand : 'Not Specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Model</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $kb->model ? $kb->model : 'Not Specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Serial Number</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $kb->serial_number ? $kb->serial_number : 'Not Specified' }}</span>
                                        </div>
                                    </div>

                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Manufacturer</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $kb->manufacturer ? $kb->manufacturer : 'Not Specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Description</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{ $kb->description }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center">
                                        <div class="timeline-line"></div>
                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Creation Class
                                                Name</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $kb->creation_class_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-6 separator separator-dashed"></div>
                        @endforeach

                    </div>
                    <div class="tab-pane fade " id="keyboard_status_tab" role="tabpanel">
                        @foreach ($keyboards as $kb)
                            <!--begin::Item-->
                            <div class="m-0">
                                <!--begin::Wrapper-->
                                <div class="mb-5 d-flex align-items-sm-center">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-45px me-4">
                                        <span class="symbol-label bg-primary">
                                            <i class="ki-duotone ki-keyboard text-inverse-primary fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="flex-wrap d-flex align-items-center flex-row-fluid">
                                        <div class="flex-grow-1 me-2">
                                            <a href="#!"
                                                class="text-gray-500 fs-6 fw-semibold">{{ $kb->brand ? $kb->brand : 'Keyboard' }}</a>

                                            <span
                                                class="text-gray-800 fw-bold d-block fs-4">{{ $kb->model ? $kb->model : '' }}</span>
                                        </div>

                                        <div class="card-toolbar">
                                            <button
                                                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="ki-duotone ki-dots-square fs-1"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                                                <div class="px-3 menu-item">
                                                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">
                                                        Actions
                                                    </div>
                                                </div>

                                                <div class="mb-3 opacity-75 separator"></div>
                                                <div class="px-3 mb-3 menu-item">
                                                    <a href="#!" class="px-3 menu-link" wire:click="$dispatch('update-data-modal-input', {'id': {{ $kb->id }}, 'type': 'keyboard-status'})">
                                                        Update Status
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Timeline-->
                                <div class="timeline">
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Input Status</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{ $kb->input_status }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center">
                                        <div class="timeline-line"></div>
                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Physical Status</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{ $kb->physical_status }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Timeline-->
                            </div>
                            <!--end::Item-->

                            <!--begin::Separator-->
                            <div class="my-6 separator separator-dashed"></div>
                            <!--end::Separator-->
                        @endforeach



                    </div>
                    <div class="tab-pane fade " id="keyboard_note_tab" role="tabpanel">
                        @foreach ($keyboards as $kb)
                            <div class="m-0">
                                <div class="mb-5 d-flex align-items-sm-center">
                                    <div class="symbol symbol-45px me-4">
                                        <span class="symbol-label bg-primary">
                                            <i class="ki-duotone ki-keyboard text-inverse-primary fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <div class="flex-wrap d-flex align-items-center flex-row-fluid">
                                        <div class="flex-grow-1 me-2">
                                            <a href="#!"
                                                class="text-gray-500 fs-6 fw-semibold">{{ $kb->brand ? $kb->brand : 'Keyboard' }}</a>

                                            <span
                                                class="text-gray-800 fw-bold d-block fs-4">{{ $kb->model ? $kb->model : '' }}</span>
                                        </div>

                                        <div class="card-toolbar">
                                            <button
                                                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="ki-duotone ki-dots-square fs-1"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                                                <div class="px-3 menu-item">
                                                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">
                                                        Actions
                                                    </div>
                                                </div>

                                                <div class="mb-3 opacity-75 separator"></div>
                                                <div class="px-3 mb-3 menu-item">
                                                    <a href="#!" class="px-3 menu-link" wire:click="$dispatch('update-data-modal-input', {'id': {{ $kb->id }}, 'type': 'keyboard-note'})">
                                                        Update Note
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="timeline">
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Note</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{$kb->note ? $kb->note : 'None'}}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center">
                                        <div class="timeline-line"></div>
                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Note Added</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{$kb->note_added ? $kb->note_added->format('d M Y, h:i a') : 'None'}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-6 separator separator-dashed"></div>
                        @endforeach


                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-7">
                <h3 class="card-title align-items-start flex-column">
                    <span class="text-gray-800 card-label fw-bold">Pointing Device</span>
                    <span class="mt-1 text-gray-500 fw-semibold fs-6">Input Information</span>
                </h3>

            </div>
            <div class="card-body">
                <ul class="mx-0 nav nav-pills nav-pills-custom row position-relative mb-9" role="tablist">
                    <!--begin::Item-->
                    <li class="p-0 mx-0 nav-item col-4" role="presentation">
                        <!--begin::Link-->
                        <a class="border-0 nav-link d-flex justify-content-center w-100 h-100 active"
                            data-bs-toggle="pill" href="#mouse_list_tab" aria-selected="true" role="tab">
                            <!--begin::Subtitle-->
                            <span class="mb-3 text-gray-800 nav-text fw-bold fs-6">
                                Mouse
                            </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span
                                class="bottom-0 rounded bullet-custom position-absolute z-index-2 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->


                    <!--begin::Item-->
                    <li class="px-0 mx-0 nav-item col-4" role="presentation">
                        <!--begin::Link-->
                        <a class="border-0 nav-link d-flex justify-content-center w-100 h-100" data-bs-toggle="pill"
                            href="#mouse_status_tab" aria-selected="false" role="tab" tabindex="-1">
                            <!--begin::Subtitle-->
                            <span class="mb-3 text-gray-800 nav-text fw-bold fs-6">
                                Status
                            </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span
                                class="bottom-0 rounded bullet-custom position-absolute z-index-2 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="px-0 mx-0 nav-item col-4" role="presentation">
                        <!--begin::Link-->
                        <a class="border-0 nav-link d-flex justify-content-center w-100 h-100" data-bs-toggle="pill"
                            href="#mouse_note_tab" aria-selected="false" role="tab" tabindex="-1">
                            <!--begin::Subtitle-->
                            <span class="mb-3 text-gray-800 nav-text fw-bold fs-6">
                                Note
                            </span>
                            <!--end::Subtitle-->

                            <!--begin::Bullet-->
                            <span
                                class="bottom-0 rounded bullet-custom position-absolute z-index-2 w-100 h-4px bg-primary"></span>
                            <!--end::Bullet-->
                        </a>
                        <!--end::Link-->
                    </li>
                    <!--end::Item-->
                    <!--begin::Bullet-->
                    <span class="bottom-0 rounded position-absolute z-index-1 w-100 h-4px bg-light"></span>
                    <!--end::Bullet-->
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="mouse_list_tab" role="tabpanel">
                        @foreach ($pointingDevices as $pd)
                            <div class="m-0">
                                <div class="mb-5 d-flex align-items-sm-center">
                                    <div class="symbol symbol-45px me-4">
                                        <span class="symbol-label bg-primary">
                                            <i class="ki-duotone ki-mouse text-inverse-primary fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <div class="flex-wrap d-flex align-items-center flex-row-fluid">
                                        <div class="flex-grow-1 me-2">
                                            <a href="#!"
                                                class="text-gray-500 fs-6 fw-semibold">{{ $pd->brand ? $pd->brand : 'Mouse' }}</a>

                                            <span
                                                class="text-gray-800 fw-bold d-block fs-4">{{ $pd->model ? $pd->model : '' }}</span>
                                        </div>

                                        <div class="card-toolbar">
                                            <button
                                                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="ki-duotone ki-dots-square fs-1"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                                                <div class="px-3 menu-item">
                                                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">Quick
                                                        Actions</div>
                                                </div>

                                                <div class="mb-3 opacity-75 separator"></div>
                                                <div class="px-3 mb-3 menu-item">
                                                    <a href="#!" class="px-3 menu-link" wire:click="$dispatch('update-data-modal-input', {'id': {{ $pd->id }}, 'type': 'mouse-data'})">
                                                        Update Data
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="timeline">
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Brand</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $pd->brand ? $pd->brand : 'Not Specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Model</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $pd->model ? $pd->model : 'Not Specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Serial Number</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $pd->serial_number ? $pd->serial_number : 'Not Specified' }}</span>
                                        </div>
                                    </div>

                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Manufacturer</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $pd->manufacturer ? $pd->manufacturer : 'Not Specified' }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Description</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{ $pd->description }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center">
                                        <div class="timeline-line"></div>
                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Creation Class
                                                Name</span>
                                            <span
                                                class="text-gray-800 fs-6 fw-bold">{{ $pd->creation_class_name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-6 separator separator-dashed"></div>
                        @endforeach

                    </div>
                    <div class="tab-pane fade " id="mouse_status_tab" role="tabpanel">
                        @foreach ($pointingDevices as $pd)
                            <!--begin::Item-->
                            <div class="m-0">
                                <!--begin::Wrapper-->
                                <div class="mb-5 d-flex align-items-sm-center">
                                    <!--begin::Symbol-->
                                    <div class="symbol symbol-45px me-4">
                                        <span class="symbol-label bg-primary">
                                            <i class="ki-duotone ki-mouse text-inverse-primary fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <!--end::Symbol-->

                                    <!--begin::Section-->
                                    <div class="flex-wrap d-flex align-items-center flex-row-fluid">
                                        <div class="flex-grow-1 me-2">
                                            <a href="#!"
                                                class="text-gray-500 fs-6 fw-semibold">{{ $pd->brand ? $pd->brand : 'Mouse' }}</a>

                                            <span
                                                class="text-gray-800 fw-bold d-block fs-4">{{ $pd->model ? $pd->model : '' }}</span>
                                        </div>

                                        <div class="card-toolbar">
                                            <button
                                                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="ki-duotone ki-dots-square fs-1"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                                                <div class="px-3 menu-item">
                                                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">
                                                        Actions
                                                    </div>
                                                </div>

                                                <div class="mb-3 opacity-75 separator"></div>
                                                <div class="px-3 mb-3 menu-item">
                                                    <a href="#!" class="px-3 menu-link" wire:click="$dispatch('update-data-modal-input', {'id': {{ $pd->id }}, 'type': 'mouse-status'})">
                                                        Update Status
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Timeline-->
                                <div class="timeline">
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Input Status</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{ $pd->input_status }}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center">
                                        <div class="timeline-line"></div>
                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Physical Status</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{ $pd->physical_status }}</span>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Timeline-->
                            </div>
                            <!--end::Item-->

                            <!--begin::Separator-->
                            <div class="my-6 separator separator-dashed"></div>
                            <!--end::Separator-->
                        @endforeach



                    </div>
                    <div class="tab-pane fade " id="mouse_note_tab" role="tabpanel">
                        @foreach ($pointingDevices as $pd)
                            <div class="m-0">
                                <div class="mb-5 d-flex align-items-sm-center">
                                    <div class="symbol symbol-45px me-4">
                                        <span class="symbol-label bg-primary">
                                            <i class="ki-duotone ki-mouse text-inverse-primary fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </span>
                                    </div>
                                    <div class="flex-wrap d-flex align-items-center flex-row-fluid">
                                        <div class="flex-grow-1 me-2">
                                            <a href="#!"
                                                class="text-gray-500 fs-6 fw-semibold">{{ $pd->brand ? $pd->brand : 'Mouse' }}</a>

                                            <span
                                                class="text-gray-800 fw-bold d-block fs-4">{{ $pd->model ? $pd->model : '' }}</span>
                                        </div>

                                        <div class="card-toolbar">
                                            <button
                                                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                                                data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="ki-duotone ki-dots-square fs-1"><span
                                                        class="path1"></span><span class="path2"></span><span
                                                        class="path3"></span><span class="path4"></span></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                                                <div class="px-3 menu-item">
                                                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">
                                                        Actions
                                                    </div>
                                                </div>

                                                <div class="mb-3 opacity-75 separator"></div>
                                                <div class="px-3 mb-3 menu-item">
                                                    <a href="#!" class="px-3 menu-link" wire:click="$dispatch('update-data-modal-input', {'id': {{ $pd->id }}, 'type': 'mouse-note'})">
                                                        Update Note
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="timeline">
                                    <div class="timeline-item align-items-center mb-7">
                                        <div class="mt-1 timeline-line mb-n6 mb-sm-n7"></div>

                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Note</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{$pd->note ? $pd->note : 'None'}}</span>
                                        </div>
                                    </div>
                                    <div class="timeline-item align-items-center">
                                        <div class="timeline-line"></div>
                                        <div class="timeline-icon">
                                            <i class="ki-duotone ki-information-2 text-success-emphasis fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </div>
                                        <div class="m-0 timeline-content">
                                            <span class="text-gray-500 fs-6 fw-semibold d-block">Note Added</span>
                                            <span class="text-gray-800 fs-6 fw-bold">{{$pd->note_added ? $pd->note_added->format('d M Y, h:i a') : 'None'}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-6 separator separator-dashed"></div>
                        @endforeach


                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
