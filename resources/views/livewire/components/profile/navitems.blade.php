<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div id="kt_user_profile_nav" class="rounded bg-gray-200 d-flex flex-stack flex-wrap mb-9 p-2"
    data-kt-page-scroll-position="400" data-kt-sticky="true" data-kt-sticky-name="sticky-profile-navs"
    data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{target: '#kt_user_profile_panel'}"
    data-kt-sticky-left="auto" data-kt-sticky-top="70px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
    <!--begin::Nav-->
    <ul class="nav flex-wrap border-transparent">
        <!--begin::Nav item-->
        <li class="nav-item my-1">
            <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    {{ request()->routeIs('account_overview') ? 'active' : '' }}"
                href="{{ route('account_overview') }}" wire:navigate>

                Overview </a>
        </li>
        <!--end::Nav item-->
        <!--begin::Nav item-->
        <li class="nav-item my-1">
            <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    {{ request()->routeIs('account_settings') ? 'active' : '' }}"
                href="{{ route('account_settings') }}" wire:navigate>

                Settings </a>
        </li>
        <!--end::Nav item-->
        <!--begin::Nav item-->
        <li class="nav-item my-1">
            <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    {{ request()->routeIs('account_changepass') ? 'active' : '' }}"
                href="{{ route('account_changepass') }}" wire:navigate>

                Security </a>
        </li>
        <!--end::Nav item-->

        <!--begin::Nav item-->
        <li class="nav-item my-1">
            <a class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1  
                    {{ request()->routeIs('account_sessions') ? 'active' : '' }}"
                href="{{ route('account_sessions') }}" wire:navigate>

                Sessions </a>
        </li>
        <!--end::Nav item-->
    </ul>
    <!--end::Nav-->
</div>
