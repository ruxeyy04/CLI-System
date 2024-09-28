<div class="modal fade" id="view_trend_logs" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog @if ($hardware_type === 'ram') modal-lg @else modal-fullscreen p-9 @endif">
        <div class="modal-content">
            <div class="modal-header" id="generate_trend_modal_header">
                <h2 class="fw-bold">Saved Trend Analysis for {{ strtoupper($hardware_type) }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="px-5 modal-body">
                <div class="row">
                    @if ($hardware_type === 'cpu')
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-6 card card-flush mb-xl-9">
                                        <div class="pt-5 card-header">
                                            <div class="card-title">
                                                <h2 class="d-flex align-items-center">CPU Utilization Trend
                                                    {{ Str::plural('Log', $trend_data1->count()) }}<span
                                                        class="text-gray-600 fs-6 ms-1">({{ $trend_data1->count() }})</span>
                                                </h2>
                                            </div>

                                            <div class="card-toolbar">
                                                <div class="my-1 d-flex align-items-center position-relative">
                                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                    <input type="text" wire:model.live="searchValTrend1"
                                                        class="form-control form-control-solid w-250px ps-15"
                                                        placeholder="Search Trend">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-0 card-body">
                                            <div id="kt_roles_view_table_wrapper"
                                                class="dt-container dt-bootstrap5 dt-empty-footer">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table mb-0 align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                        id="kt_roles_view_table" style="width: 100%;">
                                                        <thead>
                                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                                role="row">
                                                                <th class="min-w-75px">Type</th>
                                                                <th class="min-w-100px">Start Date & Time</th>
                                                                <th class="min-w-75px">End Date & Time</th>
                                                                <th class="min-w-125px">Description</th>
                                                                <th class="min-w-125px">Created At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold mh-250px scroll-y">
                                                            @forelse ($trend_data1 as $trend)
                                                                <tr>
                                                                    <td>{{ ucfirst($trend->type) }}</td>
                                                                    <td>{{ $trend->start_datetime ? $trend->start_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->end_datetime ? $trend->end_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->description }}</td>
                                                                    <td>{{ $trend->created_at ? $trend->created_at->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-success"
                                                                                wire:click="$dispatch('view-trend', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-eye fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                </i>
                                                                            </button>
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-danger "
                                                                                wire:click="$dispatch('delete-trend-confirmation', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-trash fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center">No Saved
                                                                        Trend
                                                                        found.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div
                                                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                                                    </div>
                                                    <div
                                                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                                        <div class="dt-paging paging_simple_numbers">
                                                            {{ $trend_data1->links() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-6 card card-flush mb-xl-9">
                                        <div class="pt-5 card-header">
                                            <div class="card-title">
                                                <h2 class="d-flex align-items-center">CPU Temperature Trend
                                                    {{ Str::plural('Log', $trend_data2->count()) }}<span
                                                        class="text-gray-600 fs-6 ms-1">({{ $trend_data2->count() }})</span>
                                                </h2>
                                            </div>

                                            <div class="card-toolbar">
                                                <div class="my-1 d-flex align-items-center position-relative">
                                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                    <input type="text" wire:model.live="searchValTrend2"
                                                        class="form-control form-control-solid w-250px ps-15"
                                                        placeholder="Search Trend">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-0 card-body">
                                            <div id="kt_roles_view_table_wrapper"
                                                class="dt-container dt-bootstrap5 dt-empty-footer">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table mb-0 align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                        id="kt_roles_view_table" style="width: 100%;">
                                                        <thead>
                                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                                role="row">
                                                                <th class="min-w-75px">Type</th>
                                                                <th class="min-w-100px">Start Date & Time</th>
                                                                <th class="min-w-75px">End Date & Time</th>
                                                                <th class="min-w-125px">Description</th>
                                                                <th class="min-w-125px">Created At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            @forelse ($trend_data2 as $trend)
                                                                <tr>
                                                                    <td>Temperature</td>
                                                                    <td>{{ $trend->start_datetime ? $trend->start_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->end_datetime ? $trend->end_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->description }}</td>
                                                                    <td>{{ $trend->created_at ? $trend->created_at->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-success"
                                                                                wire:click="$dispatch('view-trend', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-eye fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                </i>
                                                                            </button>
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-danger "
                                                                                wire:click="$dispatch('delete-trend-confirmation', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-trash fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center">No Saved
                                                                        Trend
                                                                        found.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div
                                                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                                                    </div>
                                                    <div
                                                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                                        <div class="dt-paging paging_simple_numbers">
                                                            {{ $trend_data2->links() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($hardware_type === 'ram')
                        <div class="col-sm-12">
                            <div class="mb-6 card card-flush mb-xl-9">
                                <div class="pt-5 card-header">
                                    <div class="card-title">
                                        <h2 class="d-flex align-items-center">RAM Usage Trend
                                            {{ Str::plural('Log', $trend_data1->count()) }}<span
                                                class="text-gray-600 fs-6 ms-1">({{ $trend_data1->count() }})</span>
                                        </h2>
                                    </div>

                                    <div class="card-toolbar">
                                        <div class="my-1 d-flex align-items-center position-relative">
                                            <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                    class="path1"></span><span class="path2"></span></i>
                                            <input type="text" wire:model.live="searchValTrend1"
                                                class="form-control form-control-solid w-250px ps-15"
                                                placeholder="Search Trend">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-0 card-body">
                                    <div id="kt_roles_view_table_wrapper"
                                        class="dt-container dt-bootstrap5 dt-empty-footer">
                                        <div class="table-responsive">
                                            <table class="table mb-0 align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                id="kt_roles_view_table" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                        role="row">
                                                        <th class="min-w-75px">Type</th>
                                                        <th class="min-w-100px">Start Date & Time</th>
                                                        <th class="min-w-75px">End Date & Time</th>
                                                        <th class="min-w-125px">Description</th>
                                                        <th class="min-w-125px">Created At</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="text-gray-600 fw-semibold">
                                                    @forelse ($trend_data1 as $trend)
                                                        <tr>
                                                            <td>{{ ucfirst($trend->type) }}</td>
                                                            <td>{{ $trend->start_datetime ? $trend->start_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                            </td>
                                                            <td>{{ $trend->end_datetime ? $trend->end_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                            </td>
                                                            <td>{{ $trend->description }}</td>
                                                            <td>{{ $trend->created_at ? $trend->created_at->format('d M Y, h:i a') : 'N/A' }}
                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <button
                                                                        class="flex-shrink-0 btn btn-icon btn-sm btn-light-success"
                                                                        wire:click="$dispatch('view-trend', {id: {{ $trend->id }}})">
                                                                        <i class="ki-duotone ki-eye fs-2">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                            <span class="path3"></span>
                                                                        </i>
                                                                    </button>
                                                                    <button
                                                                        class="flex-shrink-0 btn btn-icon btn-sm btn-light-danger "
                                                                        wire:click="$dispatch('delete-trend-confirmation', {id: {{ $trend->id }}})">
                                                                        <i class="ki-duotone ki-trash fs-2">
                                                                            <span class="path1"></span>
                                                                            <span class="path2"></span>
                                                                            <span class="path3"></span>
                                                                            <span class="path4"></span>
                                                                            <span class="path5"></span>
                                                                        </i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center">No Saved Trend
                                                                found.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div
                                                class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                                            </div>
                                            <div
                                                class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                                <div class="dt-paging paging_simple_numbers">
                                                    {{ $trend_data1->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($hardware_type === 'gpu')
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="mb-6 card card-flush mb-xl-9">
                                        <div class="pt-5 card-header">
                                            <div class="card-title">
                                                <h2 class="d-flex align-items-center">GPU Usage Trend
                                                    {{ Str::plural('Log', $trend_data1->count()) }}<span
                                                        class="text-gray-600 fs-6 ms-1">({{ $trend_data1->count() }})</span>
                                                </h2>
                                            </div>

                                            <div class="card-toolbar">
                                                <div class="my-1 d-flex align-items-center position-relative">
                                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                    <input type="text" wire:model.live="searchValTrend1"
                                                        class="form-control form-control-solid w-250px ps-15"
                                                        placeholder="Search Trend">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-0 card-body">
                                            <div id="kt_roles_view_table_wrapper"
                                                class="dt-container dt-bootstrap5 dt-empty-footer">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table mb-0 align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                        id="kt_roles_view_table" style="width: 100%;">
                                                        <thead>
                                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                                role="row">
                                                                <th class="min-w-75px">Type</th>
                                                                <th class="min-w-100px">Start Date & Time</th>
                                                                <th class="min-w-75px">End Date & Time</th>
                                                                <th class="min-w-125px">Description</th>
                                                                <th class="min-w-125px">Created At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            @forelse ($trend_data1 as $trend)
                                                                <tr>
                                                                    <td>{{ ucfirst($trend->type) }}</td>
                                                                    <td>{{ $trend->start_datetime ? $trend->start_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->end_datetime ? $trend->end_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->description }}</td>
                                                                    <td>{{ $trend->created_at ? $trend->created_at->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-success"
                                                                                wire:click="$dispatch('view-trend', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-eye fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                </i>
                                                                            </button>
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-danger "
                                                                                wire:click="$dispatch('delete-trend-confirmation', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-trash fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center">No Saved
                                                                        Trend
                                                                        found.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div
                                                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                                                    </div>
                                                    <div
                                                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                                        <div class="dt-paging paging_simple_numbers">
                                                            {{ $trend_data1->links() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="mb-6 card card-flush mb-xl-9">
                                        <div class="pt-5 card-header">
                                            <div class="card-title">
                                                <h2 class="d-flex align-items-center">GPU Temperature Trend
                                                    {{ Str::plural('Log', $trend_data2->count()) }}<span
                                                        class="text-gray-600 fs-6 ms-1">({{ $trend_data2->count() }})</span>
                                                </h2>
                                            </div>

                                            <div class="card-toolbar">
                                                <div class="my-1 d-flex align-items-center position-relative">
                                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                            class="path1"></span><span class="path2"></span></i>
                                                    <input type="text" wire:model.live="searchValTrend2"
                                                        class="form-control form-control-solid w-250px ps-15"
                                                        placeholder="Search Trend">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-0 card-body">
                                            <div id="kt_roles_view_table_wrapper"
                                                class="dt-container dt-bootstrap5 dt-empty-footer">
                                                <div class="table-responsive">
                                                    <table
                                                        class="table mb-0 align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                        id="kt_roles_view_table" style="width: 100%;">
                                                        <thead>
                                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                                role="row">
                                                                <th class="min-w-75px">Type</th>
                                                                <th class="min-w-100px">Start Date & Time</th>
                                                                <th class="min-w-75px">End Date & Time</th>
                                                                <th class="min-w-125px">Description</th>
                                                                <th class="min-w-125px">Created At</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="text-gray-600 fw-semibold">
                                                            @forelse ($trend_data2 as $trend)
                                                                <tr>
                                                                    <td>Temperature</td>
                                                                    <td>{{ $trend->start_datetime ? $trend->start_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->end_datetime ? $trend->end_datetime->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>{{ $trend->description }}</td>
                                                                    <td>{{ $trend->created_at ? $trend->created_at->format('d M Y, h:i a') : 'N/A' }}
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-success"
                                                                                wire:click="$dispatch('view-trend', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-eye fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                </i>
                                                                            </button>
                                                                            <button
                                                                                class="flex-shrink-0 btn btn-icon btn-sm btn-light-danger "
                                                                                wire:click="$dispatch('delete-trend-confirmation', {id: {{ $trend->id }}})">
                                                                                <i class="ki-duotone ki-trash fs-2">
                                                                                    <span class="path1"></span>
                                                                                    <span class="path2"></span>
                                                                                    <span class="path3"></span>
                                                                                    <span class="path4"></span>
                                                                                    <span class="path5"></span>
                                                                                </i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center">No Saved
                                                                        Trend
                                                                        found.</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="row">
                                                    <div
                                                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                                                    </div>
                                                    <div
                                                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                                                        <div class="dt-paging paging_simple_numbers">
                                                            {{ $trend_data2->links() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="@if ($show_chart == false) d-none @endif">
                    <div id="view_trend_graph" class=" ps-4 pe-6" style="height: 500px !impoertant" wire:ignore></div>
                    <h3 class="mt-4 text-center">{{ $description }}</h3>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    wire:click='hideTrendChart'>Close</button>
            </div>
        </div>
    </div>

</div>
