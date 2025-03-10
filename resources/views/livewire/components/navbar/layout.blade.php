<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>


<div class="flex-shrink-0 app-navbar">
    {{-- <!--begin::Search-->
    <div class="app-navbar-item align-items-stretch ms-1 ms-lg-3">

        <!--begin::Search-->
        <div id="kt_header_search" class="header-search d-flex align-items-stretch" data-kt-search-keypress="true"
            data-kt-search-min-length="2" data-kt-search-enter="enter" data-kt-search-layout="menu"
            data-kt-menu-trigger="auto" data-kt-menu-overflow="false" data-kt-menu-permanent="true"
            data-kt-menu-placement="bottom-end">

            <!--begin::Search toggle-->
            <div class="d-flex align-items-center" data-kt-search-element="toggle" id="kt_header_search_toggle">
                <div
                    class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px">
                    <i class="ki-duotone ki-magnifier fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <!--end::Search toggle-->

            <!--begin::Menu-->
            <livewire:components.navbar.search />

            <!--end::Menu-->
        </div>
        <!--end::Search-->
    </div>
    <!--end::Search-->
--}}


    <!--begin::Notifications-->
    <div class="app-navbar-item ms-1 ms-lg-3">
        <!--begin::Menu- wrapper-->
        <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"
            data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-notification-status fs-1"><span class="path1"></span><span class="path2"></span><span
                    class="path3"></span><span class="path4"></span></i>
        </div>
        <livewire:components.navbar.notification />
        <!--end::Menu wrapper-->
    </div>
    <!--end::Notifications-->



    <!--begin::Theme mode-->
    <div class="app-navbar-item ms-1 ms-lg-3">

        <!--begin::Menu toggle-->
        <a href="#"
            class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px w-md-40px h-md-40px"
            data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-night-day theme-light-show fs-1"><span class="path1"></span><span
                    class="path2"></span><span class="path3"></span><span class="path4"></span><span
                    class="path5"></span><span class="path6"></span><span class="path7"></span><span
                    class="path8"></span><span class="path9"></span><span class="path10"></span></i> <i
                class="ki-duotone ki-moon theme-dark-show fs-1"><span class="path1"></span><span
                    class="path2"></span></i></a>
        <!--begin::Menu toggle-->


        <!--begin::Menu-->
        <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold fs-base w-150px"
            data-kt-menu="true" data-kt-element="theme-mode-menu">
            <!--begin::Menu item-->
            <div class="px-3 my-0 menu-item" data-kt-element="mode" data-kt-value="light">
                <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="light">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-duotone ki-night-day fs-2"><span class="path1"></span><span
                                class="path2"></span><span class="path3"></span><span class="path4"></span><span
                                class="path5"></span><span class="path6"></span><span class="path7"></span><span
                                class="path8"></span><span class="path9"></span><span class="path10"></span></i>
                    </span>
                    <span class="menu-title">
                        Light
                    </span>
                </a>
            </div>
            <!--end::Menu item-->

            <!--begin::Menu item-->
            <div class="px-3 my-0 menu-item">
                <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="dark">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-duotone ki-moon fs-2"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                    <span class="menu-title">
                        Dark
                    </span>
                </a>
            </div>
            <!--end::Menu item-->

            <!--begin::Menu item-->
            <div class="px-3 my-0 menu-item">
                <a href="#" class="px-3 py-2 menu-link" data-kt-element="mode" data-kt-value="system">
                    <span class="menu-icon" data-kt-element="icon">
                        <i class="ki-duotone ki-screen fs-2"><span class="path1"></span><span
                                class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                    </span>
                    <span class="menu-title">
                        System
                    </span>
                </a>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::Menu-->

    </div>
    <!--end::Theme mode-->

    <!--begin::User menu-->
    <div class="app-navbar-item ms-2 ms-lg-3" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <div class="cursor-pointer symbol symbol-35px symbol-md-40px"
            data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            <img x-data="{ profileimg: '{{ Auth::user()->profileimg ? asset('storage/profile/' . Auth::user()->id . '/' . Auth::user()->profileimg) : asset('storage/profile/default.jpg') }}' }" :src="profileimg" alt="Profile Image"
                x-on:profile-updated.window="profileimg = $event.detail.profileimg">
        </div>

        <!--begin::User account menu-->
        <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold fs-6 w-275px"
            data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="px-3 menu-item">
                <div class="px-3 menu-content d-flex align-items-center">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-50px me-5">
                        <img x-data="{ profileimg: '{{ Auth::user()->profileimg ? asset('storage/profile/' . Auth::user()->id . '/' . Auth::user()->profileimg) : asset('storage/profile/default.jpg') }}' }" :src="profileimg" alt="Profile Image"
                            x-on:profile-updated.window="profileimg = $event.detail.profileimg">
                    </div>
                    <!--end::Avatar-->

                    <!--begin::Username-->
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-5" x-data="{{ json_encode(['fname' => auth()->user()->fname, 'lname' => auth()->user()->lname]) }}"
                            x-on:profile-updated.window="fname = $event.detail.fname; lname = $event.detail.lname">
                            <span x-text="`${fname} ${lname}`"></span> <!-- Display fname and lname dynamically -->

                            @php
                                $role = ucfirst(auth()->user()->role); // Capitalize the first letter
                                $badgeClass = $role === 'Incharge' ? 'badge-light-warning' : 'badge-light-success';
                            @endphp

                            <span class="badge {{ $badgeClass }} fw-bold fs-8 px-2 py-1 ms-2">
                                {{ $role }}
                            </span>
                        </div>

                        <a href="#" class="fw-semibold text-muted text-hover-primary fs-7"
                            x-data="{{ json_encode(['email' => auth()->user()->email]) }}" x-on:email-update.window="email = $event.detail.email;"
                            x-text="email"></a>
                    </div>
                    <!--end::Username-->
                </div>
            </div>
            <!--end::Menu item-->

            <!--begin::Menu separator-->
            <div class="my-2 separator"></div>
            <!--end::Menu separator-->

            <!--begin::Menu item-->
            <div class="px-5 menu-item">
                <a href="{{ route('account_overview') }}" class="px-5 menu-link" wire:navigate>
                    My Profile
                </a>
            </div>
            <!--end::Menu item-->


            <!--begin::Menu separator-->
            <div class="my-2 separator"></div>
            <!--end::Menu separator-->



            <!--begin::Menu item-->
            <div class="px-5 my-1 menu-item">
                <a href="{{ route('account_settings') }}" class="px-5 menu-link" wire:navigate>
                    Account Settings
                </a>
            </div>
            <!--end::Menu item-->

            <!--begin::Menu item-->
            <div class="px-5 menu-item">
                <a class="px-5 menu-link" wire:click='logout'>
                    Logout
                </a>
            </div>
            <!--end::Menu item-->
        </div>
        <!--end::User account menu-->
        <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->

</div>
