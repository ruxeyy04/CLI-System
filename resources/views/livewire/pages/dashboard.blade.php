<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Laboratory;
use App\Models\ComputerDevice;

new #[Layout('layouts.assistant')] class extends Component {
    public int $totalDevices = 0;
    public int $totalLaboratories = 0;
    public int $totalDevicesInLab = 0;
    public ?string $assignedLabName = null;
    public ?int $assignedLabCapacity = null;

    public function mount()
    {
        // Initialize properties with computed values
        $this->totalDevices = $this->getTotalDevicesProperty();
        $this->totalLaboratories = $this->getTotalLaboratoriesProperty();
        $this->totalDevicesInLab = $this->getTotalDevicesInLabProperty();
        $this->assignedLabName = $this->getAssignedLabNameProperty();
        $this->assignedLabCapacity = $this->getAssignedLabCapacityProperty();
    }

    // Computed properties
    public function getTotalDevicesProperty(): int
    {
        return ucfirst(auth()->user()->role) === 'Incharge' ? ComputerDevice::count() : 0;
    }

    public function getTotalLaboratoriesProperty(): int
    {
        return ucfirst(auth()->user()->role)=== 'Incharge' ? Laboratory::count() : 0;
    }

    public function getTotalDevicesInLabProperty(): int
    {
        return ucfirst(auth()->user()->role) === 'Assistant' && auth()->user()->laboratory_id
            ? ComputerDevice::where('laboratory_id', auth()->user()->laboratory_id)->count()
            : 0;
    }

    public function getAssignedLabNameProperty(): ?string
    {
        if (ucfirst(auth()->user()->role) === 'Assistant' && auth()->user()->laboratory_id) {
            return Laboratory::find(auth()->user()->laboratory_id)?->laboratory_name;
        }
        return null;
    }

    public function getAssignedLabCapacityProperty(): ?int
    {
        if (ucfirst(auth()->user()->role) === 'Assistant' && auth()->user()->laboratory_id) {
            return Laboratory::find(auth()->user()->laboratory_id)?->capacity;
        }
        return null;
    }
};

?>
<div id="kt_app_content_container" class="app-container container-xxl">
    <div class="row">
        @if (ucfirst(auth()->user()->role) === 'Incharge')
            <div class="col-md-3">
                <div class="card h-lg-100">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <div class="card-title d-flex flex-column">
                            <span class="text-gray-900 fs-2hx fw-bold lh-1 ls-n2">Total Devices</span>
                        </div>
                        <div class="d-flex flex-column my-7">
                            <span class="text-gray-800 fw-semibold fs-3x lh-1 ls-n2">{{ $this->totalDevices }}</span>
                            <span class="text-gray-500 fw-semibold fs-6">Devices</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card h-lg-100">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <div class="card-title d-flex flex-column">
                            <span class="text-gray-900 fs-2hx fw-bold lh-1 ls-n2">Total Laboratories</span>
                        </div>
                        <div class="d-flex flex-column my-7">
                            <span class="text-gray-800 fw-semibold fs-3x lh-1 ls-n2">{{ $totalLaboratories }}</span>
                            <span class="text-gray-500 fw-semibold fs-6">Laboratories</span>
                        </div>
                    </div>
                </div>
            </div>
        @elseif (ucfirst(auth()->user()->role) === 'Assistant')
            <div class="col-md-3">
                <div class="card h-lg-100">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <div class="card-title d-flex flex-column">
                            <span class="text-gray-900 fs-2hx fw-bold lh-1 ls-n2">Total Devices</span>
                        </div>
                        <div class="d-flex flex-column my-7">
                            <span class="text-gray-800 fw-semibold fs-3x lh-1 ls-n2">{{ $this->totalDevicesInLab }}</span>
                            <span class="text-gray-500 fw-semibold fs-6">Devices in Your Laboratory</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card h-lg-100">
                    <div class="card-body d-flex justify-content-between align-items-start flex-column">
                        <div class="card-title d-flex flex-column">
                            <span class="text-gray-900 fs-2hx fw-bold lh-1 ls-n2">Laboratory Info</span>
                        </div>
                        <div class="d-flex flex-column my-7">
                            <span class="text-gray-800 fw-semibold fs-3x lh-1 ls-n2">{{ $this->assignedLabName }}</span>
                            <span class="text-gray-500 fw-semibold fs-6">Lab Name</span>
                            <span class="text-gray-800 fw-semibold fs-4 lh-1 ls-n2">Capacity: {{ $this->assignedLabCapacity }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-3">
            <div class="card h-lg-100">
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <div class="card-title d-flex flex-column">
                        <span class="text-gray-900 fs-2hx fw-bold lh-1 ls-n2">Date</span>
                    </div>
                    <div class="d-flex flex-column my-7">
                        <span class="text-gray-800 fw-semibold fs-3x lh-1 ls-n2" id="current-date"></span>
                        <span class="text-gray-500 fw-semibold fs-6">Today's Date</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card h-lg-100">
                <div class="card-body d-flex justify-content-between align-items-start flex-column">
                    <div class="card-title d-flex flex-column">
                        <span class="text-gray-900 fs-2hx fw-bold lh-1 ls-n2">Time</span>
                    </div>
                    <div class="d-flex flex-column my-7">
                        <span class="text-gray-800 fw-semibold fs-3x lh-1 ls-n2" id="current-time"></span>
                        <span class="text-gray-500 fw-semibold fs-6">Current Time</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Livewire Components for Device Management -->
    <h1 class="mt-5">List of Device</h1>
    <div class="card mt-7">
        <div class="pt-6 border-0 card-header">
            <livewire:components.device.search />
            <livewire:components.device.toolbar />
        </div>
        <div class="py-4 card-body">
            <livewire:components.device.table />
        </div>
    </div>

    @if (ucfirst(auth()->user()->role) === 'Incharge')
        <!-- Livewire Components for Device Management -->
        <h1 class="mt-5">List of Users</h1>
        
        <div class="card mt-7">
            <div class="pt-6 border-0 card-header">
                <livewire:components.users.users-table-search />
                <livewire:components.users.card-toolbar />
            </div>
            <div class="py-4 card-body">
                <livewire:components.users.userstable />
            </div>
        </div>
        <h1 class="mt-5">List of Laboratories</h1>
        <div class="mb-4 card">
            <div class="py-3 border-0 card-header">
                <livewire:components.laboratory.search />
            </div>
        </div>
        <livewire:components.laboratory.card-list>
    @endif
</div>


<script>
    // JavaScript to display the current date and time
    function updateDateTime() {
        const now = new Date();
        document.getElementById('current-date').textContent = now.toLocaleDateString();
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
    }
    setInterval(updateDateTime, 1000); // Update every second
    updateDateTime(); // Initial call
</script>
