<div class="row">
    @foreach ($disks as $index => $disk)
        @php
            // Calculate disk usage percentage
            $usagePercentage = ($disk->used / $disk->total) * 100;
            $barColor = $usagePercentage > 80 ? 'danger' : 'success';

            // Determine column class for the last disk only
            $colClass = $loop->last && $disks->count() % 2 == 0 ? 'col-md-6 mb-5' : 'col-md-12';
        @endphp
        <div class="{{ $loop->last ? $colClass : 'col-md-6 mb-5' }}">
            <div class="card card-flush">
                <!--begin::Header-->
                <div class="pt-5 card-header">
                    <div class="card-title d-flex flex-column">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-45px w-45px bg-light me-5">
                                @if ($disk->drive_type == 'Internal Drive')
                                    <img src="../../assets/media/svg/hard-disk-svgrepo-com.svg" alt="image"
                                        class="p-3">
                                @else
                                    <img src="../../assets/media/svg/pendrive-svgrepo-com.svg" alt="image"
                                        class="p-3">
                                @endif

                            </div>
                            <span class="text-gray-900 fs-1hx fw-bold me-2 lh-1 ls-n2 ">
                                {{ $disk->drive_type }}
                            </span>
                        </div>
                        <span class="pt-1 text-gray-500 cursor-pointer fw-semibold fs-6">
                            <span class="disk-model" title="{{ $disk->model }}" data-bs-toggle="tooltip" wire:ignore>
                                {{ Str::limit($disk->model, 22, '...') }}
                            </span>
                        </span>
                    </div>
                </div>
                <!--begin::Card body-->
                <div class="pt-0 card-body">
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">Total</div>
                        <div class="text-gray-700 fw-bolder text-xxl-end">{{ $disk->total }} GB</div>
                    </div>
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">Used</div>
                        <div class="text-gray-700 fw-bolder text-xxl-end">{{ $disk->used }} GB
                            ({{ round(($disk->used / $disk->total) * 100, 1) }}%)
                        </div>
                    </div>
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">Free</div>
                        <div class="text-gray-700 fw-bolder text-xxl-end">{{ $disk->free }} GB</div>
                    </div>
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">Temperature</div>
                        <div class="text-gray-700 fw-bolder text-xxl-end">{{ $disk->temperature }} °C</div>
                    </div>
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">Health</div>
                        <div class="text-gray-700 fw-bolder text-xxl-end">{{ $disk->health }}</div>
                    </div>
                    <div class="d-flex fs-6 fw-semibold align-items-center">
                        <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                        <div class="text-gray-500 flex-grow-1 me-4">Drive Type</div>
                        <div class="text-gray-700 fw-bolder text-xxl-end">{{ $disk->drive_type }}</div>
                    </div>
                    <div class="mt-3 d-flex align-items-center flex-column w-100">
                        <div class="mt-auto mb-2 d-flex justify-content-between w-100">
                            <span class="text-gray-900 fw-bolder fs-6">{{ $disk->volume_label }}
                                {{ $disk->mountpoint }}\</span>
                            <span
                                class="text-gray-500 fw-bold fs-6">{{ round(($disk->used / $disk->total) * 100) }}%</span>
                        </div>
                        @php
                            $usedPercentage = round(($disk->used / $disk->total) * 100);
                            $progressBarClass = $usedPercentage > 90 ? 'bg-danger' : 'bg-success';
                            $progressBarClassDiv = $usedPercentage > 90 ? 'bg-light-danger' : 'bg-light-success';
                        @endphp
                        <div class="mx-3 rounded h-8px w-100 {{ $progressBarClassDiv }}">

                            <div class="rounded {{ $progressBarClass }} h-8px" role="progressbar"
                                style="width: {{ $usedPercentage }}%;" aria-valuenow="{{ $usedPercentage }}"
                                aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>

                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    @endforeach
</div>
