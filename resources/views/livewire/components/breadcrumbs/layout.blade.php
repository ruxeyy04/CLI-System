<div class="flex-wrap page-title d-flex flex-column justify-content-center me-3 ">
    <h1 class="my-0 text-gray-900 page-heading d-flex fw-bold fs-3 flex-column justify-content-center">
        @if (Route::currentRouteName() == 'dashboard')
            Welcome {{ Auth::user()->fname }}!
        @elseif (Request::segment(1) == 'account')
            @if (Route::currentRouteName() == 'account_overview')
                User Profile
            @elseif (Route::currentRouteName() == 'account_settings')
                Account Settings
            @elseif (Route::currentRouteName() == 'account_changepass')
                Change Password
            @elseif (Route::currentRouteName() == 'account_sessions')
                Active Sessions
            @endif
        @elseif(Route::currentRouteName() == 'user_management')
            User Management
        @elseif(Route::currentRouteName() == 'laboratory')
            Laboratory
        @elseif(Route::currentRouteName() == 'computerdevices')
            Computer Devices
        @else
            Welcome!
        @endif
    </h1>

    <ul class="pt-1 my-0 breadcrumb breadcrumb-separatorless fw-semibold fs-7">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted text-hover-primary" wire:navigate>
                Home
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="bg-gray-500 bullet w-5px h-2px"></span>
        </li>

        @if (Request::segment(1) == 'account')
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('account_overview') }}" class="text-muted text-hover-primary">Account</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bg-gray-500 bullet w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                @if (Route::currentRouteName() == 'account_overview')
                    User Profile
                @elseif (Route::currentRouteName() == 'account_settings')
                    Account Settings
                @elseif (Route::currentRouteName() == 'account_changepass')
                    Change Password
                @elseif (Route::currentRouteName() == 'account_sessions')
                    Active Sessions
                @endif
            </li>
        @elseif(Route::currentRouteName() == 'user_management')
            <li class="breadcrumb-item text-muted">
                User Management
            </li>
        @elseif(Route::currentRouteName() == 'laboratory')
            <li class="breadcrumb-item text-muted">
                Laboratory
            </li>
        @elseif(Route::currentRouteName() == 'computerdevices')
            <li class="breadcrumb-item text-muted">
                Computer Devices
            </li>
        @else
            <li class="breadcrumb-item text-muted">
                Dashboard
            </li>
        @endif
    </ul>
</div>
