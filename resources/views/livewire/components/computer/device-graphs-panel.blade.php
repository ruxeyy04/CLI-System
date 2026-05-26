<div>
    @include('livewire.components.computer.partials.graph-date-filter-inline')

    <div class="row gx-5 gx-xl-10 mb-xl-10">
        <div class="mb-10 col-md-6 col-lg-6 col-xl-6 col-xxl-3">
            <livewire:components.computer.cpu-card :device="$device" :key="'cpu-card-'.$device->id" />
            <div class="card card-flush h-md-50 mb-xl-10">
                <div class="pt-5 card-header">
                    <div class="card-title d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <span class="text-gray-900 fs-2hx fw-bold me-2 lh-1 ls-n2">Storage</span>
                        </div>
                        <span class="pt-1 text-gray-500 fw-semibold fs-6">Disk Information</span>
                    </div>
                </div>
                <livewire:components.computer.disk-card-summary :device="$device"
                    :key="'disk-summary-'.$device->id" />
            </div>
        </div>

        <div class="mb-10 col-md-6 col-lg-6 col-xl-6 col-xxl-3">
            <livewire:components.computer.ram-card :device="$device" :key="'ram-card-'.$device->id" />
            <livewire:components.computer.gpu-card :device="$device" :key="'gpu-card-'.$device->id" />
        </div>

        <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
            @include('livewire.components.computer.partials.cpu-graph-card')
        </div>
    </div>

    <div class="row gx-5 gx-xl-10 mb-xl-10">
        <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
            @include('livewire.components.computer.partials.ram-graph-card')
        </div>
        <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
            @include('livewire.components.computer.partials.gpu-graph-card')
        </div>
    </div>

    @script
    <script>
        const applyDeviceGraphPayloads = (detail) => {
            if (!detail) {
                return;
            }

            window.updateCpuGraphFromLivewire?.(detail.cpu);
            window.updateRamGraphFromLivewire?.(detail.ram);
            window.updateGpuGraphFromLivewire?.(detail.gpu);
        };

        $wire.on('device-graphs-refreshed', () => {
            applyDeviceGraphPayloads(event.detail);
        });

        applyDeviceGraphPayloads({
            cpu: @js($this->cpuChartPayload()),
            ram: @js($this->ramChartPayload()),
            gpu: @js($this->gpuChartPayload()),
        });
    </script>
    @endscript
</div>
