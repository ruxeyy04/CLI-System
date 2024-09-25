<div class="overflow-hidden card card-flush h-md-100">
    <div class="py-5 card-header">
        <h3 class="card-title align-items-start flex-column">
            <span class="text-gray-900 card-label fw-bold">GPU Graph Real-Time</span>
            <span class="mt-1 text-gray-500 fw-semibold fs-6">Shows the Graph of the GPU Real-Time</span>
        </h3>
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
