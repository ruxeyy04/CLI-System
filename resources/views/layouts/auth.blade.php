<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="CLIS" />
    <meta name="keywords" content="computer, laboratory" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../../assets/media/logos/favicon.ico" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> <!--end::Fonts-->


    <link href="../../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />


    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>


</head>

<!--begin::Body-->

<body id="kt_body" class="app-blank">
    <!--begin::Theme mode setup on page load-->
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
    <!--end::Theme mode setup on page load-->
    <!--Begin::Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!--End::Google Tag Manager (noscript) -->

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <!--begin::Aside-->
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center"
                style="background-image: url(../../../assets/media/misc/auth-bg.png)">
                <!--begin::Content-->
                <div class="p-6 d-flex flex-column flex-center p-lg-10 w-100">
                    <!--begin::Logo-->
                    <a href="/" class="mb-0 mb-lg-20" wire:navigate>
                        <img alt="Logo" src="../../../assets/media/logos/default-white.png"
                           height="100" />
                    </a>
                    <!--end::Logo-->


                    <!--begin::Title-->
                    <h1 class="text-center text-white d-none d-lg-block fs-2qx fw-bold mb-7">
                        Proactive, Data-Driven Lab Management for Optimal Performance
                    </h1>
                    
                    <!--end::Title-->

                    <!--begin::Text-->
                    <div class="text-center text-white d-none d-lg-block fs-base">
                        A Computer Laboratory Information System with Trend Analysis for Computer Maintenance is designed to streamline the management and upkeep of computer labs by integrating real-time monitoring and data analytics. This system tracks the performance, usage, and maintenance needs of all lab computers, providing insights through trend analysis.
                    </div>
                    <!--end::Text-->
                </div>
                <!--end::Content-->
            </div>
            <!--begin::Aside-->

            <!--begin::Body-->
            <div class="p-10 d-flex flex-column flex-lg-row-fluid w-lg-50">
                <!--begin::Form-->
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <!--begin::Wrapper-->
                    {{$slot}}
                    <!--end::Wrapper-->
                </div>
                <!--end::Form-->

            </div>
            <!--end::Body-->
        </div>


    </div>



</body>

</html>
