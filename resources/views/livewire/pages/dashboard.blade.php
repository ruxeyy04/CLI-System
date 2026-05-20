<?php

use App\Models\ComputerDevice;
use App\Models\Laboratory;
use App\Services\DeviceHealthService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('layouts.assistant')] class extends Component {
    public array $labsOverview = [];
    public array $labDevices = [];
    public int $totalDevices = 0;
    public int $healthyDevices = 0;
    public int $unhealthyDevices = 0;
    public int $unknownDevices = 0;
    public ?int $selectedLabId = null;
    public ?string $selectedLabName = null;
    public ?string $selectedDeviceId = null;
    public ?array $selectedDeviceDetails = null;

    public function mount(): void
    {
        $this->loadOverview();
    }

    #[On('echo:device-updates,.patch.saved')]
    public function refreshDashboard(): void
    {
        $this->loadOverview();

        if ($this->selectedLabId) {
            $this->loadLabDevices($this->selectedLabId);
        }

        if ($this->selectedDeviceId) {
            $this->loadDeviceDetails($this->selectedDeviceId);
        }
    }

    public function loadOverview(): void
    {
        $health = app(DeviceHealthService::class);
        $user = auth()->user();
        $isAssistant = ucfirst($user->role) === 'Assistant';

        $labsQuery = Laboratory::with(['computerDevices.cpuInfo', 'computerDevices.gpuInfo', 'computerDevices.ramInfo']);

        if ($isAssistant && $user->laboratory_id) {
            $labsQuery->where('id', $user->laboratory_id);
        } elseif ($isAssistant) {
            $this->labsOverview = [];
            $this->resetCounts();

            return;
        }

        $labs = $labsQuery->get();
        $this->labsOverview = [];
        $this->resetCounts();

        foreach ($labs as $lab) {
            $devices = $lab->computerDevices;
            $deviceStatuses = [];

            foreach ($devices as $device) {
                $status = $health->getDeviceStatus($device);
                $deviceStatuses[] = $status;
                $this->totalDevices++;
                $this->incrementStatusCount($status);
            }

            $this->labsOverview[] = [
                'id' => $lab->id,
                'name' => $lab->laboratory_name,
                'device_count' => $devices->count(),
                'status' => $health->getLabStatus($devices),
            ];
        }

        if ($isAssistant && count($this->labsOverview) === 1 && !$this->selectedLabId) {
            $this->selectLab($this->labsOverview[0]['id']);
        }
    }

    public function selectLab(int $labId): void
    {
        $this->selectedLabId = $labId;
        $this->selectedDeviceId = null;
        $this->selectedDeviceDetails = null;
        $this->selectedLabName = Laboratory::find($labId)?->laboratory_name;
        $this->loadLabDevices($labId);
        $this->dispatch('dashboard-scroll-to', target: 'workstations-section');
    }

    public function selectDevice(string $deviceId): void
    {
        $this->selectedDeviceId = $deviceId;
        $this->loadDeviceDetails($deviceId);
        $this->dispatch('dashboard-scroll-to', target: 'device-details-section');
    }

    public function clearLabSelection(): void
    {
        $this->selectedLabId = null;
        $this->selectedLabName = null;
        $this->selectedDeviceId = null;
        $this->selectedDeviceDetails = null;
        $this->labDevices = [];
    }

    public function clearDeviceSelection(): void
    {
        $this->selectedDeviceId = null;
        $this->selectedDeviceDetails = null;
    }

    protected function loadDeviceDetails(string $deviceId): void
    {
        $device = ComputerDevice::with(['laboratory', 'cpuInfo', 'gpuInfo', 'ramInfo', 'diskInfo', 'inputDevices'])->find($deviceId);

        if (!$device) {
            $this->selectedDeviceDetails = null;

            return;
        }

        $this->selectedDeviceDetails = app(DeviceHealthService::class)->getDeviceSnapshot($device);
    }

    protected function loadLabDevices(int $labId): void
    {
        $health = app(DeviceHealthService::class);
        $devices = ComputerDevice::with(['cpuInfo', 'gpuInfo', 'ramInfo'])
            ->where('laboratory_id', $labId)
            ->orderBy('device_name')
            ->get();

        $this->labDevices = $devices->map(fn ($device) => [
            'id' => $device->id,
            'name' => $device->device_name,
            'serial_number' => $device->serial_number,
            'status' => $health->getDeviceStatus($device),
            'patch_status' => $device->patch_id ? 'Patched' : 'Not patched',
        ])->all();
    }

    protected function resetCounts(): void
    {
        $this->totalDevices = 0;
        $this->healthyDevices = 0;
        $this->unhealthyDevices = 0;
        $this->unknownDevices = 0;
    }

    protected function incrementStatusCount(string $status): void
    {
        match ($status) {
            'healthy' => $this->healthyDevices++,
            'unhealthy' => $this->unhealthyDevices++,
            default => $this->unknownDevices++,
        };
    }
}; ?>

<div id="kt_app_content_container" class="app-container container-xxl">
    <style>
        .monitor-tile {
            min-height: 110px;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            border: 2px solid transparent;
        }

        .monitor-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08);
        }

        .monitor-tile.is-selected {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.2);
        }

        .monitor-tile--healthy {
            background: linear-gradient(135deg, #e8fff3 0%, #d1fae5 100%);
            color: #065f46;
        }

        .monitor-tile--unhealthy {
            background: linear-gradient(135deg, #ffeef0 0%, #fecdd3 100%);
            color: #9f1239;
        }

        .monitor-tile--unknown {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            color: #4b5563;
        }

        .lab-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .device-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 0.75rem;
        }
    </style>

    {{-- Date & time header --}}
    <div class="row g-5 g-xl-8 mb-6">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center py-6">
                    <span class="text-gray-500 fw-semibold fs-7 mb-1">Today's Date</span>
                    <span class="text-gray-900 fw-bold fs-2x" id="current-date"></span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center py-6">
                    <span class="text-gray-500 fw-semibold fs-7 mb-1">Current Time</span>
                    <span class="text-gray-900 fw-bold fs-2x" id="current-time"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Laboratory overview grid --}}
    <div class="card mb-6">
        <div class="card-header border-0 pt-6">
            <div class="card-title flex-column align-items-start">
                <h2 class="fw-bold mb-1">Laboratory Overview</h2>
                <span class="text-muted fs-7">
                    Click a laboratory to list all workstations. Tile color reflects RAM, GPU, and CPU health
                    (RAM ≥85%, GPU/CPU temp ≥80°C, GPU/CPU usage ≥90%).
                </span>
            </div>
        </div>
        <div class="card-body pt-2">
            @if (count($labsOverview) > 0)
                <div class="lab-grid mb-6">
                    @foreach ($labsOverview as $lab)
                        <div wire:click="selectLab({{ $lab['id'] }})"
                            class="monitor-tile monitor-tile--{{ $lab['status'] }} d-flex flex-column justify-content-center align-items-center p-4 text-center {{ $selectedLabId === $lab['id'] ? 'is-selected' : '' }}"
                            role="button" tabindex="0">
                            <span class="fw-bold fs-4 mb-1">{{ $lab['name'] }}</span>
                            <span class="fs-8 opacity-75">{{ $lab['device_count'] }}
                                {{ Str::plural('device', $lab['device_count']) }}</span>
                            <span class="badge mt-2 badge-light-{{ $lab['status'] === 'healthy' ? 'success' : ($lab['status'] === 'unhealthy' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($lab['status']) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex flex-wrap align-items-center gap-4 p-4 rounded bg-light">
                    <span class="fw-bold fs-4 text-gray-900">{{ $totalDevices }} Devices</span>
                    <span class="text-muted">|</span>
                    <span class="fw-semibold text-danger">{{ $unhealthyDevices }} Red</span>
                    <span class="fw-semibold text-success">{{ $healthyDevices }} Green</span>
                    @if ($unknownDevices > 0)
                        <span class="fw-semibold text-gray-600">{{ $unknownDevices }} No data / offline</span>
                    @endif
                </div>
            @else
                <div class="text-center text-muted py-10">
                    No laboratories available for your account.
                </div>
            @endif
        </div>
    </div>

    {{-- Drill-down: workstations in selected lab --}}
    @if ($selectedLabId)
        <div class="card mb-6" id="workstations-section">
            <div class="card-header border-0 pt-6">
                <div class="card-title flex-column align-items-start">
                    <h2 class="fw-bold mb-1">{{ $selectedLabName }} — Workstations</h2>
                    <span class="text-muted fs-7">
                        All devices in this laboratory. Click a workstation to open its full information table.
                    </span>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light" wire:click="clearLabSelection">
                        Back to all labs
                    </button>
                </div>
            </div>
            <div class="card-body pt-2">
                @if (count($labDevices) > 0)
                    <div class="device-grid mb-6">
                        @foreach ($labDevices as $device)
                            <div wire:click="selectDevice('{{ $device['id'] }}')"
                                class="monitor-tile monitor-tile--{{ $device['status'] }} d-flex flex-column justify-content-center align-items-center p-3 text-center {{ $selectedDeviceId === $device['id'] ? 'is-selected' : '' }}"
                                role="button" tabindex="0">
                                <span class="fw-bold fs-6">{{ $device['name'] }}</span>
                                <span class="badge mt-2 badge-light-{{ $device['status'] === 'healthy' ? 'success' : ($device['status'] === 'unhealthy' ? 'danger' : 'secondary') }}">
                                    {{ $device['status'] === 'healthy' ? 'Green' : ($device['status'] === 'unhealthy' ? 'Red' : 'No data') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <h3 class="fw-semibold fs-5 text-gray-800 mb-3">List of devices in {{ $selectedLabName }}</h3>
                    <div class="table-responsive mb-4">
                        <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted text-uppercase fs-7">
                                    <th>Workstation</th>
                                    <th>Serial number</th>
                                    <th>Patch</th>
                                    <th>Health</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($labDevices as $device)
                                    <tr class="{{ $selectedDeviceId === $device['id'] ? 'table-active' : '' }}">
                                        <td class="fw-bold text-gray-900">{{ $device['name'] }}</td>
                                        <td>{{ $device['serial_number'] }}</td>
                                        <td>
                                            <span class="badge badge-light-{{ $device['patch_status'] === 'Patched' ? 'success' : 'danger' }}">
                                                {{ $device['patch_status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $device['status'] === 'healthy' ? 'success' : ($device['status'] === 'unhealthy' ? 'danger' : 'secondary') }}">
                                                {{ $device['status'] === 'healthy' ? 'Green' : ($device['status'] === 'unhealthy' ? 'Red' : 'No data') }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light-primary"
                                                wire:click="selectDevice('{{ $device['id'] }}')">
                                                View details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($selectedDeviceId)
                        <button type="button" class="btn btn-sm btn-light-primary" wire:click="clearDeviceSelection">
                            Show all devices in {{ $selectedLabName }}
                        </button>
                    @endif
                @else
                    <div class="text-muted py-6">No devices registered in this laboratory yet.</div>
                @endif
            </div>
        </div>
    @endif

    {{-- Full workstation information (table mode) --}}
    @if ($selectedDeviceDetails)
        @php $d = $selectedDeviceDetails; @endphp
        <div class="card mb-6" id="device-details-section" wire:key="device-details-{{ $d['id'] }}">
            <div class="card-header border-0 pt-6">
                <div class="card-title flex-column align-items-start">
                    <h2 class="fw-bold mb-1">{{ $d['name'] }} — Complete workstation information</h2>
                    <span class="text-muted fs-7">
                        All data reported by the diagnostic patch: system, hardware, storage, keyboards, and mice.
                    </span>
                </div>
                <div class="card-toolbar gap-2">
                    @if ($d['patch_status'] === 'Patched')
                        <a href="{{ route('devicegraph', $d['id']) }}" class="btn btn-sm btn-primary" wire:navigate>
                            Open real-time graphs
                        </a>
                    @endif
                    <button type="button" class="btn btn-sm btn-light" wire:click="clearDeviceSelection">
                        Close details
                    </button>
                </div>
            </div>
            <div class="card-body pt-2">
                @include('livewire.pages.partials.dashboard-device-tables')
            </div>
        </div>
    @elseif ($selectedLabId)
        <div class="card mb-6">
            <div class="card-body py-8 text-center text-muted">
                Select a workstation above to view its full information table (CPU, RAM, GPU, storage, keyboard, and mouse).
            </div>
        </div>
    @endif
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const dateEl = document.getElementById('current-date');
        const timeEl = document.getElementById('current-time');
        if (dateEl) {
            dateEl.textContent = now.toLocaleDateString();
        }
        if (timeEl) {
            timeEl.textContent = now.toLocaleTimeString();
        }
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

@script
<script>
    $wire.on('dashboard-scroll-to', ({ target }) => {
        $nextTick(() => {
            document.getElementById(target)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endscript
