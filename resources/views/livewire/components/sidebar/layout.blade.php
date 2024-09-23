<div class="overflow-hidden app-sidebar-menu flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll" class="mx-3 my-5 hover-scroll-y" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class=" menu menu-column menu-rounded menu-sub-indention fw-semibold"
                id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}" wire:navigate>
                        <span class="menu-icon">
                            <i class="ki-duotone ki-category fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>
                <!--begin:Menu item-->
                <div class="pt-5 menu-item">
                    <!--begin:Menu content-->
                    <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Menu</span></div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::segment(1) == 'account' ? 'show' : '' }}">
                    <!--begin:Menu link--><span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-profile-circle fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title">Account</span><span class="menu-arrow"></span></span>

                    <div class="menu-sub menu-sub-accordion">
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('account_overview') ? 'active' : '' }}" href="{{ route('account_overview') }}" wire:navigate>
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot">
                                    </span>
                                </span>
                                <span class="menu-title">Overview</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('account_settings') ? 'active' : '' }}" href="{{ route('account_settings') }}" wire:navigate>
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot">
                                    </span>
                                </span>
                                <span class="menu-title">Settings</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('account_changepass') ? 'active' : '' }}" href="{{ route('account_changepass') }}" wire:navigate>
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot">
                                    </span>
                                </span>
                                <span class="menu-title">Update Password</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('account_sessions') ? 'active' : '' }}" href="{{ route('account_sessions') }}" wire:navigate>
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot">
                                    </span>
                                </span>
                                <span class="menu-title">Sessions</span>
                            </a>
                        </div>
                        <!--end:Menu item-->
                    </div>
                    <!--end:Menu sub-->
                </div>
                <!--end:Menu item-->

                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="pt-5 menu-item">
                    <!--begin:Menu content-->
                    <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Management</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link--><a class="menu-link {{ request()->routeIs('laboratory') ? 'active' : '' }}" href="{{ route('laboratory')}}" wire:navigate><span class="menu-icon"><i
                                class="ki-duotone ki-abstract-13 fs-2"><span class="path1"></span><span
                                    class="path2"></span></i></span><span class="menu-title">Laboratory</span></a>
                    <!--end:Menu link-->
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('computerdevices') ? 'active' : '' }}" href="{{route('computerdevices')}}" wire:navigate>
                        <span class="menu-icon">
                            <i class="ki-duotone ki-monitor-mobile fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title">Computer Devices</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('user_management') ? 'active' : '' }}" href="{{ route('user_management') }}" wire:navigate>
                        <span class="menu-icon">
                            <i class="ki-duotone ki-people fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title">Users</span>
                    </a>
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="pt-5 menu-item">
                    <!--begin:Menu content-->
                    <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Help</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link--><a class="menu-link" href="https://preview.keenthemes.com/html/keen/docs"
                        target="_blank"><span class="menu-icon"><i class="ki-duotone ki-abstract-26 fs-2"><span
                                    class="path1"></span><span class="path2"></span></i></span><span
                            class="menu-title">System Guide</span></a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
</div>
