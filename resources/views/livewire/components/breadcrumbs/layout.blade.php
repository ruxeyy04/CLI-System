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
        @elseif(Route::currentRouteName() == 'notifications')
            Notifications
        @elseif(Route::currentRouteName() == 'devicegraph')
            Computer Device Real-Time Information
        @elseif(Route::currentRouteName() == 'devicelogs')
            Computer Device Logs
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

        @if (Route::currentRouteName() == 'devicegraph')
            @php
                $deviceId = request()->route('id');
                $device = \App\Models\ComputerDevice::find($deviceId);
                $laboratory = $device ? $device->laboratory : null;
            @endphp
            @if ($laboratory)
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('laboratory') }}"
                        class="text-muted text-hover-primary">{{ $laboratory->laboratory_name }}</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bg-gray-500 bullet w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    {{ $device->device_name }}
                </li>
            @endif
            @elseif (Route::currentRouteName() == 'devicelogs')
            @php
                $deviceId = request()->route('id');
                $device = \App\Models\ComputerDevice::find($deviceId);
                $laboratory = $device ? $device->laboratory : null;
            @endphp
            @if ($laboratory)
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('laboratory') }}"
                        class="text-muted text-hover-primary">{{ $laboratory->laboratory_name }}</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bg-gray-500 bullet w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    {{ $device->device_name }}
                </li>
            @endif       
        @elseif (Request::segment(1) == 'account')
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
            
        @elseif(Route::currentRouteName() == 'notifications')
            <li class="breadcrumb-item text-muted">
                Notifications
            </li>
        @else
            <li class="breadcrumb-item text-muted">
                Dashboard
            </li>
        @endif
    </ul>
</div>
