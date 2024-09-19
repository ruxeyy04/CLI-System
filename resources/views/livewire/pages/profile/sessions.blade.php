<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Events\SessionLogout;
new #[Layout('layouts.assistant')] class extends Component {
    public $selectedTime = 0;
    public $sessions;

    public function mount()
    {
        // Load all sessions on first mount
        $this->loadSessions(false);
    }

    public function updateSelectedTime()
    {
        // Apply time filter when user changes the selected time
        if ($this->selectedTime == 0) {
            $this->loadSessions(false);
        } else {
            $this->loadSessions(true);
        }
    }

    public function loadSessions($applyFilter = false)
    {
        $query = DB::table('sessions')->where('user_id', Auth::id());

        // Only apply the time filter if specified
        if ($applyFilter) {
            $query->where('last_activity', '>=', now()->subHours($this->selectedTime)->timestamp);
        }

        $this->sessions = $query->get();
    }

    public function loadAll()
    {
        // Load all sessions without any filter
        $this->sessions = DB::table('sessions')->where('user_id', Auth::id())->get();
        $this->selectedTime = 0;
    }

    public function logoutSession($sessionId)
    {
        if ($sessionId !== session()->getId()) {
            DB::table('sessions')->where('id', $sessionId)->delete();
            $this->loadSessions(); // Reload sessions after logout

            SessionLogout::dispatch($sessionId);
        }
    }
};
?>

<div id="kt_app_content_container" class="app-container container-xxl">
    <livewire:components.profile.usercardheader />
    <livewire:components.profile.navitems />

    <!--begin::Login sessions-->
    <div class="mb-5 card mb-lg-10">
        <!--begin::Card header-->
        <div class="card-header">
            <div class="card-title">
                <h3>Login Sessions</h3>
            </div>

            <div class="card-toolbar">
                <div class="my-1 me-4">
                    <!-- Time selection dropdown -->
                    <select wire:model="selectedTime" wire:change='updateSelectedTime'
                        class="form-select form-select-sm form-select-solid w-125px">
                        <option value="0">Select Time</option>
                        <option value="1">1 Hour</option>
                        <option value="6">6 Hours</option>
                        <option value="12">12 Hours</option>
                        <option value="24">24 Hours</option>
                    </select>
                </div>
                <button wire:click="loadAll" class="my-1 btn btn-sm btn-primary">View All</button>
            </div>
        </div>

        <!--begin::Card body-->
        <div class="p-0 card-body">
            <div class="table-responsive">
                <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                    <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                        <tr>
                            <th class="min-w-150px">Device</th>
                            <th class="min-w-100px">IP Address</th>
                            <th class="min-w-100px">Platform Name</th>
                            <th class="min-w-100px">Browser</th>
                            <th class="min-w-100px">User-Agent</th>
                            <th class="min-w-150px">Last Activity</th>
                            <th class="min-w-150px">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-6 fw-semibold">
                        @foreach ($sessions as $session)
                            <tr>
                                <td>{{ $session->devicefamily ?? 'None' }} - {{ $session->devicemodel ?? 'None' }}</td>
                                <td><span
                                        class="badge badge-light-primary fs-7 fw-bold">{{ $session->ip_address }}</span>
                                </td>
                                <td>{{ $session->platformname }}</td>
                                <td>{{ $session->browsername }}</td>
                                <td>{{ $session->user_agent ?? 'Unknown' }}</td>
                                <td>{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </td>
                                <td>
                                    @if ($session->id !== session()->getId())
                                        <button wire:click="logoutSession('{{ $session->id }}')"
                                            class="btn btn-sm btn-danger" wire:loading.attr="disabled" wire:target="logoutSession('{{ $session->id }}')">

                                            <span  wire:loading.remove wire:target="logoutSession('{{ $session->id }}')">
                                                Logout
                                            </span>
                                            <span wire:loading wire:target="logoutSession('{{ $session->id }}')"><span class="align-middle spinner-border spinner-border-sm ms-2"></span> Loading...
                                            </span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
