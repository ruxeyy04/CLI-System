<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />{{--  /Added by HTTrack  --}}

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="CLIS" />
    <meta name="keywords" content="computer, laboratory" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="../../assets/media/logos/favicon.ico" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" /> {{-- end::Fonts --}}


    <link href="../../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />


    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>


</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    {{-- begin::Theme mode setup on page load --}}
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
    {{-- end::Theme mode setup on page load --}}
    {{-- Begin::Google Tag Manager (noscript)  --}}
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    {{-- End::Google Tag Manager (noscript)  --}}

    {{-- begin::Root --}}
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        {{-- begin::Page bg image --}}
        <style>
            body {
                background-image: url('../../assets/media/auth/bg13.png');
            }

            [data-bs-theme="dark"] body {
                background-image: url('../../assets/media/auth/bg13-dark.png');
            }
        </style>
        {{-- end::Page bg image --}}


        {{-- begin::Authentication - Signup Welcome Message  --}}
        <div class="d-flex flex-column flex-center flex-column-fluid">
            {{-- begin::Content --}}
            <div class="p-10 text-center d-flex flex-column flex-center">
                {{-- begin::Wrapper --}}
                <div class="py-5 card card-flush w-lg-650px">
                    <div class="card-body py-15 py-lg-20">

                        {{-- begin::Title --}}
                        <h1 class="mb-4 text-gray-900 fw-bolder fs-2hx">
                            Oops!
                        </h1>
                        {{-- end::Title --}}

                        {{-- begin::Text --}}
                        <div class="text-gray-500 fw-semibold fs-6 mb-7">
                            We can't find that page.
                        </div>
                        {{-- end::Text --}}

                        {{-- begin::Illustration --}}
                        <div class="mb-3">
                            <img src="../../assets/media/auth/404.png" class="mw-100 mh-300px theme-light-show"
                                alt="" />
                            <img src="../../assets/media/auth/404-dark.png"
                                class="mw-100 mh-300px theme-dark-show" alt="" />
                        </div>
                        {{-- end::Illustration --}}

                        {{-- begin::Link --}}
                        <div class="mb-0">
                            <a href="/" class="btn btn-sm btn-primary">Return Home</a>
                        </div>
                        {{-- end::Link --}}

                    </div>
                </div>
                {{-- end::Wrapper --}}
            </div>
            {{-- end::Content --}}
        </div>
        {{-- end::Authentication - Signup Welcome Message --}}
    </div>
    {{-- end::Root --}}

    {{-- begin::Javascript --}}

    {{-- begin::Global Javascript Bundle(mandatory for all pages) --}}
    <script src="../../assets/plugins/global/plugins.bundle.js"></script>
    <script src="../../assets/js/scripts.bundle.js"></script>
    {{-- end::Global Javascript Bundle --}}


    {{-- end::Javascript --}}
</body>

</html>
