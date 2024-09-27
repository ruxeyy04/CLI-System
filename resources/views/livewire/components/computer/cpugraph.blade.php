<?php

use Livewire\Volt\Component;
use App\Models\CpuInfo;
use App\Models\CpuTemp;
use App\Models\CpuUtilization;

new class extends Component {
    public $device;
    public $cpu_temp_data = [];
    public $cpu_util_data = [];
    public $timestamps = [];
    public $cpu_id;
    public function mount($device)
    {
        $this->device = $device;

        $cpuInfo = CpuInfo::where('device_id', $this->device->id)->first();

        if ($cpuInfo) {
            
            $cpuId = $cpuInfo->id;
            $this->cpu_id = $cpuId;
            $cpuTempRecords = CpuTemp::where('cpu_id', $cpuId)
                ->whereDate('created_at', now()->toDateString())
                ->get(['temp', 'created_at']);

            $cpuUtilRecords = CpuUtilization::where('cpu_id', $cpuId)
                ->whereDate('created_at', now()->toDateString())
                ->get(['util', 'created_at']);

            $this->cpu_temp_data = $cpuTempRecords->pluck('temp')->toArray();
            $cpu_temp_timestamps = $cpuTempRecords->pluck('created_at')->toArray();

            $this->cpu_util_data = $cpuUtilRecords->pluck('util')->toArray();
            $cpu_util_timestamps = $cpuUtilRecords->pluck('created_at')->toArray();

            $this->timestamps = count($cpu_temp_timestamps) > count($cpu_util_timestamps) ? $cpu_temp_timestamps : $cpu_util_timestamps;

            $this->equalizeDataArrays();
        }
    }

    private function equalizeDataArrays()
    {
        $lastTempValue = end($this->cpu_temp_data);
        $lastUtilValue = end($this->cpu_util_data);

        // Adjust lengths
        while (count($this->cpu_temp_data) < count($this->cpu_util_data)) {
            $this->cpu_temp_data[] = $lastTempValue;
        }

        while (count($this->cpu_util_data) < count($this->cpu_temp_data)) {
            $this->cpu_util_data[] = $lastUtilValue;
        }
    }
};

?>

<div class="overflow-hidden card card-flush h-md-100">
    <div class="py-5 card-header">
        <h3 class="card-title align-items-start flex-column">
            <span class="text-gray-900 card-label fw-bold">CPU Graph Real-Time</span>
            <span class="mt-1 text-gray-500 fw-semibold fs-6">Shows the Graph of the CPU Real-Time</span>
        </h3>
        <div class="card-toolbar">
            <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                data-bs-toggle="dropdown" aria-expanded="false">

                <i class="ki-duotone ki-dots-square fs-1"><span class="path1"></span><span class="path2"></span><span
                        class="path3"></span><span class="path4"></span></i>
            </button>

            <div
                class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                <div class="px-3 menu-item">
                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">Quick
                        Actions</div>
                </div>
                <div class="mb-3 opacity-75 separator"></div>
                <div class="px-3 mb-3 menu-item">

                    <a href="#!" class="px-3 menu-link" wire:click="$dispatch('generate-trend-modal', {id: {{$cpu_id}}, type: 'cpu'})">
                        Generate Trend Analysis
                    </a>
                </div>

            </div>
        </div>
    </div>
    <div class="px-0 pb-1 card-body d-flex justify-content-between flex-column">
        <div class="mb-5 px-9">
            <div class="mb-2 d-flex">
                <span class="text-gray-800 fs-2hx fw-bold me-2 lh-1 ls-n2">Utilization and Temperature</span>
            </div>
            <span class="text-gray-500 fs-6 fw-semibold">Real-Time Data</span>
        </div>
        <div id="cpu_temp_utilGraph" class="min-h-auto ps-4 pe-6" style="height: 350px"></div>
    </div>

    <script>
        cpu_util = {
            name: "Utilization",
            data: {!! json_encode($cpu_util_data) !!}
        };
        cpu_temp = {
            name: "Temperature",
            data: {!! json_encode($cpu_temp_data) !!}
        };
        timestamps = {!! json_encode($timestamps) !!};

        cpu_temp.data = {!! json_encode($cpu_temp_data) !!};
        cpu_util.data = {!! json_encode($cpu_util_data) !!};
    </script>

    <script>
        currentDeviceId = '{{ $device->id }}';
    </script>
</div>
