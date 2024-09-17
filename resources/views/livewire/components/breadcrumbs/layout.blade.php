<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3 ">
    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
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
        @else
            Welcome!
        @endif
    </h1>
    
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-muted">
            <a href="/" class="text-muted text-hover-primary" wire:navigate>
                Home
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-500 w-5px h-2px"></span>
        </li>
        
        @if (Request::segment(1) == 'account')
            <li class="breadcrumb-item text-muted">
                <a href="{{ route('account_overview') }}" class="text-muted text-hover-primary">Account</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
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
        @else
            <li class="breadcrumb-item text-muted">
                Dashboard
            </li>
        @endif
    </ul>
</div>
