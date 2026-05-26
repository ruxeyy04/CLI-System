<div class="overflow-hidden card card-flush h-md-100">
    <div class="py-5 card-header">
        <h3 class="card-title align-items-start flex-column">
            <span class="text-gray-900 card-label fw-bold">CPU Graph Real-Time</span>
            <span class="mt-1 text-gray-500 fw-semibold fs-6">Shows the Graph of the CPU Real-Time</span>
        </h3>
        @if ($cpu_id)
        <div class="card-toolbar">
            <button
                class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ki-duotone ki-dots-square fs-1"><span class="path1"></span><span class="path2"></span><span
                        class="path3"></span><span class="path4"></span></i>
            </button>

            <div
                class="py-4 dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                <div class="px-3 menu-item">
                    <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">Quick Actions</div>
                </div>
                <div class="mb-3 opacity-75 separator"></div>
                <div class="px-3 mb-3 menu-item">
                    <a href="#!" class="px-3 menu-link"
                        wire:click="$dispatch('generate-trend-modal', {id: {{ $cpu_id }}, type: 'cpu'})">
                        Generate Trend Analysis
                    </a>
                    <a href="#!" class="px-3 menu-link"
                        wire:click="$dispatch('view-saved-trend', {id: {{ $cpu_id }}, type: 'cpu'})">
                        View Saved Trend Data
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
    <div class="px-0 pb-1 card-body d-flex justify-content-between flex-column">
        <div class="mb-5 px-9">
            <div class="mb-2 d-flex">
                <span class="text-gray-800 fs-2hx fw-bold me-2 lh-1 ls-n2">Utilization and Temperature</span>
            </div>
            <span class="text-gray-500 fs-6 fw-semibold">Real-Time Data</span>
        </div>
        <div class="position-relative px-4 pe-6" style="min-height: 350px">
            @include('livewire.components.computer.partials.graph-loading-overlay')
            <div id="cpu_temp_utilGraph" class="min-h-auto" style="height: 350px" wire:ignore></div>
        </div>
    </div>

    @script
    <script>
        window.updateCpuGraphFromLivewire?.({
            util: @js($cpu_util_data),
            temp: @js($cpu_temp_data),
            timestamps: @js($timestamps),
            startDate: @js($graphStartDate),
            endDate: @js($graphEndDate),
        });
    </script>
    @endscript
</div>
