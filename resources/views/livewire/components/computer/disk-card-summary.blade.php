<div class="pt-0 card-body">
    <!--begin::Progress-->
    @if (isset($disks))
        @foreach ($disks as $disk)
            @php
                // Calculate disk usage percentage
                $usagePercentage = round(($disk->used / $disk->total) * 100);
                $barColor = $usagePercentage > 90 ? 'danger' : 'success'; // Change color based on usage
            @endphp

            <div class="mt-3 d-flex align-items-center flex-column w-100">
                <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                    <span class="text-gray-900 fw-bolder fs-6">{{ $disk->volume_label }} {{ $disk->mountpoint }}\</span>
                    <span class="text-gray-500 fw-bold fs-6">{{ $usagePercentage }}%</span>
                </div>

                <div class="mx-3 rounded h-8px w-100 bg-light-{{ $barColor }}">
                    <div class="rounded bg-{{ $barColor }} h-8px" role="progressbar"
                        style="width: {{ $usagePercentage }}%;" aria-valuenow="{{ $usagePercentage }}" aria-valuemin="0"
                        aria-valuemax="100"></div>
                </div>
            </div>
        @endforeach
    @else
        <div class="d-flex flex-column content-justify-center w-100">
            <h4>No Data</h4>
        </div>
    @endif

    <!--end::Progress-->
</div>
