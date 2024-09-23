<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="CLIS" />
    <meta name="keywords" content="computer, laboratory" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../../assets/media/logos/favicon.ico" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    {{-- end::Fonts --}}
    @vite(['resources/js/app.js'])

    <link href="../../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <script>
        let session_id = `{{ session()->getId() }}`
    </script>

    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
</head>
{{-- end::Head --}}

{{-- begin::Body --}}

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

    {{-- begin::Theme mode setup on page load --}}

    {{-- begin::App --}}
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        {{-- begin::Page --}}
        <div class="app-page flex-column flex-column-fluid " id="kt_app_page">


            {{-- begin::Header --}}
            <div id="kt_app_header" class="app-header ">

                {{-- begin::Header container --}}
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between "
                    id="kt_app_header_container">

                    {{-- begin::sidebar mobile toggle --}}
                    <div class="d-flex align-items-center d-lg-none ms-n3 me-2" title="Show sidebar menu">
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px"
                            id="kt_app_sidebar_mobile_toggle">
                            <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                    </div>
                    {{-- end::sidebar mobile toggle --}}


                    {{-- begin::Mobile logo --}}
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a href="../index-2.html" class="d-lg-none">
                            <img alt="Logo" src="../assets/media/logos/default-small.png"
                                class="theme-light-show h-30px" />
                            <img alt="Logo" src="../assets/media/logos/default-small-dark.png"
                                class="theme-dark-show h-30px" />
                        </a>
                    </div>
                    {{-- end::Mobile logo --}}

                    {{-- begin::Header wrapper --}}
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
                        id="kt_app_header_wrapper">


                        {{-- begin::Menu wrapper --}}
                        <div class="d-flex align-items-center">
                            <h4 class="m-0 text-muted d-none d-sm-block">Computer Laboratory Information System</h4>
                        </div>
                        {{-- end::Menu wrapper --}}


                        {{-- begin::Navbar --}}
                        <livewire:components.navbar.layout />
                        {{-- end::Navbar --}}
                    </div>
                    {{-- end::Header wrapper --}}
                </div>
                {{-- end::Header container --}}
            </div>
            {{-- end::Header --}}
            {{-- begin::Wrapper --}}
            <div class="app-wrapper flex-column flex-row-fluid " id="kt_app_wrapper">






                {{-- begin::Sidebar --}}
                <div id="kt_app_sidebar" class="app-sidebar flex-column " data-kt-drawer="true"
                    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
                    data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
                    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">


                    {{-- begin::Logo --}}
                    <div class="px-6 app-sidebar-logo" id="kt_app_sidebar_logo">
                        {{-- begin::Logo image --}}
                        <a href="{{ route('dashboard') }}">
                            <img alt="Logo" src="../assets/media/logos/default-dark.png"
                                class="h-30px app-sidebar-logo-default" />
                        </a>
                        {{-- end::Logo image --}}

                        {{-- begin::Sidebar toggle --}}
                        <div id="kt_app_sidebar_toggle"
                            class="app-sidebar-toggle btn btn-icon btn-sm h-30px w-30px rotate " data-kt-toggle="true"
                            data-kt-toggle-state="active" data-kt-toggle-target="body"
                            data-kt-toggle-name="app-sidebar-minimize">

                            <i class="rotate-180 ki-duotone ki-double-left fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </div>
                        {{-- end::Sidebar toggle --}}
                    </div>
                    {{-- end::Logo --}}
                    {{-- begin::sidebar menu --}}
                    <livewire:components.sidebar.layout />
                    {{-- end::sidebar menu --}}
                    {{-- begin::Footer --}}
                    <livewire:components.sidebar.footer />
                    {{-- end::Footer --}}
                </div>
                {{-- end::Sidebar --}}


                {{-- begin::Main --}}
                <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
                    {{-- begin::Content wrapper --}}
                    <div class="d-flex flex-column flex-column-fluid">

                        {{-- begin::Toolbar --}}
                        <div id="kt_app_toolbar" class="py-3 app-toolbar py-lg-6 ">

                            {{-- begin::Toolbar container --}}
                            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack ">



                                {{-- begin::Page title --}}
                                <livewire:components.breadcrumbs.layout />
                                {{-- end::Page title --}}
                                {{-- begin::Actions --}}
                                <livewire:components.breadcrumbs.filteraction />
                                {{-- end::Actions --}}
                            </div>
                            {{-- end::Toolbar container --}}
                        </div>
                        {{-- end::Toolbar --}}

                        {{-- begin::Content --}}
                        <div id="kt_app_content" class="app-content flex-column-fluid ">

                            {{ $slot }}
                        </div>
                        {{-- end::Content --}}

                    </div>
                    {{-- end::Content wrapper --}}


                    {{-- begin::Footer --}}
                    <livewire:components.footer />
                    {{-- end::Footer --}}
                </div>
                {{-- end:::Main --}}


            </div>
            {{-- end::Wrapper --}}


        </div>
        {{-- end::Page --}}
    </div>
    {{-- end::App --}}




    {{-- begin::Global Javascript Bundle(mandatory for all pages) --}}
    <script src="../assets/plugins/global/plugins.bundle.js"></script>
    <script src="../assets/js/scripts.bundle.js"></script>

</body>
{{-- end::Body --}}
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/channels.js') }}"></script>


</html>
