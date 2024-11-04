<div>
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_data_users" wire:ignore.self>
            <thead wire:ignore>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-100px">Device ID</th>
                    <th class="min-w-125px">Name</th>
                    <th class="min-w-125px">Serial Number</th>
                    <th class="min-w-125px">Laboratory</th>
                    <th class="min-w-125px">Patch</th>
                    <th class="min-w-125px">Date Patched</th>
                    <th class="min-w-125px">Added on </th>
                    @if (Route::currentRouteName() != 'dashboard')
                        <th class="text-end min-w-100px">Actions</th>
                    @endif

                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
                @foreach ($devices as $dev)
                    @if (auth()->user()->laboratory_id != null && ucfirst(auth()->user()->role) === 'Assistant')
                        @if ($dev->laboratory != null)
                            @if (auth()->user()->laboratory_id == $dev->laboratory->id)
                                <tr>
                                    <td>{{ $dev->id }}</td>
                                    <td>{{ $dev->device_name }}</td>
                                    <td>{{ $dev->serial_number }}</td>
                                    <td>{{ $dev->laboratory->laboratory_name ?? 'Not Assigned' }}</td>
                                    <td>
                                        <div
                                            class="badge badge-light-{{ $dev->patch_id ? 'success' : 'danger' }} fw-bold">
                                            {{ $dev->patch_id ? 'Patched' : 'Not Patch' }}</div>
                                    </td>
                                    <td>{{ $dev->patched_date ? ($dev->patched_date ? $dev->patched_date->format('d M Y, h:i a') : 'None') : 'None' }}
                                    </td>
                                    <td>
                                        {{ $dev->created_at ? $dev->created_at->format('d M Y, h:i a') : 'None' }}
                                    </td>
                                    @if (Route::currentRouteName() != 'dashboard')
                                        <td class="text-end">
                                            <button
                                                class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                wire:key="user-{{ $dev->id }}">
                                                Actions
                                                <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                            </button>
                                            <div
                                                class="py-4 dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px">
                                                @if ($dev->patch_id)
                                                    <div class="px-3 menu-item">
                                                        <a href="#" class="px-3 menu-link"
                                                            wire:click="$dispatch('removePatchConfirmation', {'dev_id': '{{ $dev->id }}'})">
                                                            Remove Patch
                                                        </a>
                                                    </div>
                                                @endif

                                                <div class="px-3 menu-item">
                                                    <a href="#" class="px-3 menu-link"
                                                        wire:click="$dispatch('openEditDeviceModal', {'dev_id': '{{ $dev->id }}'})">
                                                        Edit
                                                    </a>
                                                </div>
                                                <div class="px-3 menu-item">
                                                    <a href="#" class="px-3 menu-link"
                                                        wire:click="$dispatch('deleteDeviceConfirmation', {'dev_id': '{{ $dev->id }}'})">
                                                        Delete
                                                    </a>
                                                </div>
                                            </div>

                                        </td>
                                    @endif

                                </tr>
                            @endif
                        @endif
                    @else
                        <tr>
                            <td>{{ $dev->id }}</td>
                            <td>{{ $dev->device_name }}</td>
                            <td>{{ $dev->serial_number }}</td>
                            <td>{{ $dev->laboratory->laboratory_name ?? 'Not Assigned' }}</td>
                            <td>
                                <div class="badge badge-light-{{ $dev->patch_id ? 'success' : 'danger' }} fw-bold">
                                    {{ $dev->patch_id ? 'Patched' : 'Not Patch' }}</div>
                            </td>
                            <td>{{ $dev->patched_date ? ($dev->patched_date ? $dev->patched_date->format('d M Y, h:i a') : 'None') : 'None' }}
                            </td>
                            <td>
                                {{ $dev->created_at ? $dev->created_at->format('d M Y, h:i a') : 'None' }}
                            </td>
                            @if (Route::currentRouteName() != 'dashboard')
                                <td class="text-end">
                                    <button class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false"
                                        wire:key="user-{{ $dev->id }}">
                                        Actions
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                    </button>
                                    <div
                                        class="py-4 dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px">
                                        @if ($dev->patch_id)
                                            <div class="px-3 menu-item">
                                                <a href="#" class="px-3 menu-link"
                                                    wire:click="$dispatch('removePatchConfirmation', {'dev_id': '{{ $dev->id }}'})">
                                                    Remove Patch
                                                </a>
                                            </div>
                                        @endif

                                        <div class="px-3 menu-item">
                                            <a href="#" class="px-3 menu-link"
                                                wire:click="$dispatch('openEditDeviceModal', {'dev_id': '{{ $dev->id }}'})">
                                                Edit
                                            </a>
                                        </div>
                                        <div class="px-3 menu-item">
                                            <a href="#" class="px-3 menu-link"
                                                wire:click="$dispatch('deleteDeviceConfirmation', {'dev_id': '{{ $dev->id }}'})">
                                                Delete
                                            </a>
                                        </div>
                                    </div>

                                </td>
                            @endif

                        </tr>
                    @endif

                @endforeach
            </tbody>
        </table>
    </div>

    <div class="row">
        <div
            class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
            <!-- Add any toolbar items here -->
        </div>
        <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
            <div class="dt-paging paging_simple_numbers">
                {{ $devices->links() }}
            </div>
        </div>
    </div>
</div>
