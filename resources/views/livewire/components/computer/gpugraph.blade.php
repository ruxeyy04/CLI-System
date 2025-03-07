<div class="overflow-hidden card card-flush h-md-100">
    <div class="py-5 card-header">
        <h3 class="card-title align-items-start flex-column">
            <span class="text-gray-900 card-label fw-bold">GPU Graph Real-Time</span>
            <span class="mt-1 text-gray-500 fw-semibold fs-6">Shows the Graph of the GPU Real-Time</span>
        </h3>
        @if ($gpu_id)
            <div class="card-toolbar">
                <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end"
                    data-bs-toggle="dropdown" aria-expanded="false">

                    <i class="ki-duotone ki-dots-square fs-1"><span class="path1"></span><span
                            class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                </button>

                <div
                    class="dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px">
                    <div class="px-3 menu-item">
                        <div class="px-3 py-4 text-gray-900 menu-content fs-6 fw-bold">Quick
                            Actions</div>
                    </div>
                    <div class="mb-3 opacity-75 separator"></div>
                    <div class="px-3 mb-3 menu-item">

                        <a href="#!" class="px-3 menu-link"
                            wire:click="$dispatch('generate-trend-modal', {id: {{ $gpu_id }}, type: 'gpu'})">
                            Generate Trend Analysis
                        </a>
                        <a href="#!" class="px-3 menu-link"
                            wire:click="$dispatch('view-saved-trend', {id: {{ $gpu_id }}, type: 'gpu'})">
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
                <span class="text-gray-800 fs-2hx fw-bold me-2 lh-1 ls-n2">Temperature and Usage</span>
            </div>
            <span class="text-gray-500 fs-6 fw-semibold">Real-Time Data</span>
        </div>
        <div id="gpu_usage_graph" class="min-h-auto ps-4 pe-6" style="height: 350px"></div>
    </div>
    <script>
        gpu_usage = {
            name: "Usage",
            data: {!! json_encode($gpu_usage_data) !!}
        };
        gpu_temp = {
            name: "Temperature",
            data: {!! json_encode($gpu_temp_data) !!}
        };
        gpu_timestamps = {!! json_encode($timestamps) !!};

        gpu_usage.data = {!! json_encode($gpu_usage_data) !!};
        gpu_temp.data = {!! json_encode($gpu_temp_data) !!};
    </script>
</div>
