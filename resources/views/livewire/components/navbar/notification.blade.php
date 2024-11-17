<!--begin::Menu-->
<div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px" data-kt-menu="true" id="kt_menu_notifications">
    <!--begin::Heading-->
    <div class="d-flex flex-column bgi-no-repeat rounded-top" style="background-image:url('../assets/media/misc/menu-header-bg.jpg')">
        <!--begin::Title-->
        <h3 class="mt-10 mb-6 text-white fw-semibold px-9">
            Notifications <span class="opacity-75 fs-8 ps-3">{{ $notificationCount }} alerts</span>
        </h3>
        <!--end::Title-->

        <!--begin::Tabs-->
        <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
            <li class="nav-item">
                <a class="pb-4 text-white opacity-75 nav-link opacity-state-100 active" data-bs-toggle="tab" href="#kt_topbar_notifications_1">Alerts</a>
            </li>
        </ul>
        <!--end::Tabs-->
    </div>
    <!--end::Heading-->

    <!--begin::Tab content-->
    <div class="tab-content">
        <!--begin::Tab panel-->
        <div class="tab-pane fade show active" id="kt_topbar_notifications_1" role="tabpanel">
            <!--begin::Items-->
            <div class="px-8 my-5 scroll-y mh-325px">
                @forelse ($notifications as $notification)
                    <!--begin::Item-->
                    <div class="py-4 d-flex flex-stack">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center">
                            <!--begin::Symbol-->
                            <div class="symbol symbol-35px me-4">
                                <span class="symbol-label bg-light-{{ 
                                    $notification->type === 'CPU' ? 'danger' : 
                                    ($notification->type === 'GPU' ? 'warning' : 
                                    ($notification->type === 'RAM' ? 'info' : 
                                    ($notification->type === 'Storage' ? 'primary' : 'secondary'))) 
                                }}">
                                    <i class="fa-solid {{ 
                                        $notification->type === 'CPU' ? 'fa-microchip' : 
                                        ($notification->type === 'GPU' ? 'fa-vr-cardboard' : 
                                        ($notification->type === 'RAM' ? 'fa-memory' : 
                                        ($notification->type === 'Storage' ? 'fa-hard-drive' : 
                                        ($notification->type === 'Input Device' ? 'fa-brands fa-usb' : 'fa-question-circle')))) 
                                    }}"></i>
                                </span>
                            </div>
                            <!--end::Symbol-->

                            <!--begin::Title-->
                            <div class="mb-0 me-2">
                                <a href="#" class="text-gray-800 fs-6 text-hover-primary fw-bold">
                                    {{ $notification->title }}
                                </a>
                                <div class="text-gray-500 fs-7">
                                    {{ $notification->device->device_name ?? 'Unknown Device' }}: {{ $notification->message }}
                                </div>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Section-->

                        <!--begin::Label-->
                        <span class="badge badge-light fs-8">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                        <!--end::Label-->
                    </div>
                    <!--end::Item-->
                @empty
                    <div class="py-4 text-center text-gray-500">
                        No notifications found.
                    </div>
                @endforelse
            </div>
            <!--end::Items-->

            <!--begin::View more-->
            <div class="py-3 text-center border-top">
                <a href="{{ route('notifications') }}" class="btn btn-color-gray-600 btn-active-color-primary" wire:navigate>
                    View All
                    <i class="ki-duotone ki-arrow-right fs-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
            </div>
            <!--end::View more-->
        </div>
        <!--end::Tab panel-->
    </div>
    <!--end::Tab content-->
</div>
<!--end::Menu-->
