@php $d = $selectedDeviceDetails; @endphp

<div class="d-flex flex-wrap align-items-center gap-3 mb-6">
    <span class="badge badge-light-{{ $d['status'] === 'healthy' ? 'success' : ($d['status'] === 'unhealthy' ? 'danger' : 'secondary') }} fs-7">
        {{ $d['status'] === 'healthy' ? 'Healthy' : ($d['status'] === 'unhealthy' ? 'Needs attention' : 'No live data') }}
    </span>
    <span class="badge badge-light-{{ $d['patch_status'] === 'Patched' ? 'success' : 'warning' }} fs-7">
        {{ $d['patch_status'] }}
    </span>
    @foreach ($d['issues'] as $issue)
        <span class="badge badge-light-danger fs-8">{{ $issue }}</span>
    @endforeach
</div>

<h3 class="fw-semibold fs-6 text-gray-800 mb-3">System information</h3>
<div class="table-responsive mb-8">
    <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-3">
        <thead>
            <tr class="fw-bold text-muted text-uppercase fs-7">
                <th>Device ID</th>
                <th>Name</th>
                <th>Serial number</th>
                <th>Laboratory</th>
                <th>Patch</th>
                <th>Health</th>
                <th>Date patched</th>
                <th>Added on</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-break" style="max-width: 220px;">{{ $d['id'] }}</td>
                <td class="fw-bold">{{ $d['name'] }}</td>
                <td>{{ $d['serial_number'] }}</td>
                <td>{{ $d['laboratory'] }}</td>
                <td>
                    <span class="badge badge-light-{{ $d['patch_status'] === 'Patched' ? 'success' : 'danger' }}">
                        {{ $d['patch_status'] }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-light-{{ $d['status'] === 'healthy' ? 'success' : ($d['status'] === 'unhealthy' ? 'danger' : 'secondary') }}">
                        {{ ucfirst($d['status']) }}
                    </span>
                </td>
                <td>{{ $d['patched_date'] ?? '—' }}</td>
                <td>{{ $d['added_on'] ?? '—' }}</td>
            </tr>
        </tbody>
    </table>
</div>

<h3 class="fw-semibold fs-6 text-gray-800 mb-3">CPU, RAM & GPU</h3>
<div class="table-responsive mb-8">
    <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-3">
        <thead>
            <tr class="fw-bold text-muted text-uppercase fs-7">
                <th>Component</th>
                <th>Brand / Total</th>
                <th>Details</th>
                <th>Live reading</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="fw-bold">CPU</td>
                <td>{{ $d['cpu']['has_data'] ? ($d['cpu']['brand'] ?? '—') : '—' }}</td>
                <td>
                    @if ($d['cpu']['has_data'])
                        {{ $d['cpu']['cores'] ?? '—' }} cores / {{ $d['cpu']['threads'] ?? '—' }} threads,
                        {{ isset($d['cpu']['base_speed']) ? number_format((float) $d['cpu']['base_speed'], 2).' GHz' : '—' }}
                    @else
                        No data
                    @endif
                </td>
                <td>
                    @if ($d['cpu']['has_data'])
                        {{ $d['cpu']['temperature'] !== null ? $d['cpu']['temperature'].'°C' : '—' }} temp,
                        {{ $d['cpu']['utilization'] !== null ? $d['cpu']['utilization'].'%' : '—' }} util
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if ($d['cpu']['has_data'])
                        <span class="{{ ($d['cpu']['temperature'] ?? 0) >= 80 || ($d['cpu']['utilization'] ?? 0) >= 90 ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ ($d['cpu']['temperature'] ?? 0) >= 80 || ($d['cpu']['utilization'] ?? 0) >= 90 ? 'Alert' : 'OK' }}
                        </span>
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold">RAM</td>
                <td>{{ $d['ram']['has_data'] ? $d['ram']['total_gb'].' GB total' : '—' }}</td>
                <td>
                    @if ($d['ram']['has_data'])
                        Used {{ $d['ram']['used_gb'] }} GB, available {{ $d['ram']['available_gb'] }} GB,
                        {{ $d['ram']['speed'] }} MT/s
                    @else
                        No data
                    @endif
                </td>
                <td class="{{ ($d['ram']['usage_percent'] ?? 0) >= 85 ? 'text-danger fw-bold' : '' }}">
                    {{ $d['ram']['usage_percent'] !== null ? $d['ram']['usage_percent'].'% used' : '—' }}
                </td>
                <td>
                    @if ($d['ram']['has_data'])
                        <span class="{{ ($d['ram']['usage_percent'] ?? 0) >= 85 ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ ($d['ram']['usage_percent'] ?? 0) >= 85 ? 'Alert' : 'OK' }}
                        </span>
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <td class="fw-bold">GPU</td>
                <td>{{ $d['gpu']['has_data'] ? Str::limit($d['gpu']['brand'] ?? '—', 50) : '—' }}</td>
                <td>
                    @if ($d['gpu']['has_data'])
                        {{ $d['gpu']['memory'] ?? '—' }} MB memory,
                        {{ is_numeric($d['gpu']['power'] ?? null) ? round((float) $d['gpu']['power'], 2).' W' : '—' }} power
                    @else
                        No data
                    @endif
                </td>
                <td>
                    @if ($d['gpu']['has_data'])
                        {{ $d['gpu']['temperature'] !== null ? $d['gpu']['temperature'].'°C' : '—' }} temp,
                        {{ $d['gpu']['usage'] !== null ? $d['gpu']['usage'].'%' : '—' }} usage
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if ($d['gpu']['has_data'])
                        <span class="{{ ($d['gpu']['temperature'] ?? 0) >= 80 || ($d['gpu']['usage'] ?? 0) >= 90 ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ ($d['gpu']['temperature'] ?? 0) >= 80 || ($d['gpu']['usage'] ?? 0) >= 90 ? 'Alert' : 'OK' }}
                        </span>
                    @else
                        —
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>

<h3 class="fw-semibold fs-6 text-gray-800 mb-3">Storage drives</h3>
<div class="table-responsive mb-8">
    <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-3">
        <thead>
            <tr class="fw-bold text-muted text-uppercase fs-7">
                <th>Volume</th>
                <th>Mount</th>
                <th>Model</th>
                <th>Serial</th>
                <th>Total</th>
                <th>Used</th>
                <th>Free</th>
                <th>Health</th>
                <th>Temp</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($d['disks'] as $disk)
                <tr>
                    <td>{{ $disk['volume_label'] ?? '—' }}</td>
                    <td>{{ $disk['mountpoint'] ?? '—' }}</td>
                    <td>{{ $disk['model'] ?? '—' }}</td>
                    <td>{{ $disk['serial_number'] ?? '—' }}</td>
                    <td>{{ $disk['total'] ?? '—' }}</td>
                    <td>{{ $disk['used'] ?? '—' }}</td>
                    <td>{{ $disk['free'] ?? '—' }}</td>
                    <td>{{ $disk['health'] ?? '—' }}</td>
                    <td>{{ $disk['temperature'] ?? '—' }}</td>
                    <td>{{ $disk['status'] ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-muted text-center py-6">No storage data reported by the patch.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3 class="fw-semibold fs-6 text-gray-800 mb-3">Keyboards</h3>
<div class="table-responsive mb-8">
    <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-3">
        <thead>
            <tr class="fw-bold text-muted text-uppercase fs-7">
                <th>Brand</th>
                <th>Model</th>
                <th>Serial</th>
                <th>Manufacturer</th>
                <th>Description</th>
                <th>Input status</th>
                <th>Physical status</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($d['keyboards'] as $kb)
                <tr>
                    <td>{{ $kb['brand'] ?? '—' }}</td>
                    <td>{{ $kb['model'] ?? '—' }}</td>
                    <td>{{ $kb['serial_number'] ?? '—' }}</td>
                    <td>{{ $kb['manufacturer'] ?? '—' }}</td>
                    <td>{{ $kb['description'] ?? '—' }}</td>
                    <td>{{ $kb['input_status'] ?? '—' }}</td>
                    <td>{{ $kb['physical_status'] ?? '—' }}</td>
                    <td>{{ $kb['note'] ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-muted text-center py-6">No keyboards detected.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3 class="fw-semibold fs-6 text-gray-800 mb-3">Mice & pointing devices</h3>
<div class="table-responsive">
    <table class="table table-row-bordered table-row-gray-200 align-middle gs-0 gy-3">
        <thead>
            <tr class="fw-bold text-muted text-uppercase fs-7">
                <th>Brand</th>
                <th>Model</th>
                <th>Serial</th>
                <th>Manufacturer</th>
                <th>Description</th>
                <th>Input status</th>
                <th>Physical status</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($d['mice'] as $mouse)
                <tr>
                    <td>{{ $mouse['brand'] ?? '—' }}</td>
                    <td>{{ $mouse['model'] ?? '—' }}</td>
                    <td>{{ $mouse['serial_number'] ?? '—' }}</td>
                    <td>{{ $mouse['manufacturer'] ?? '—' }}</td>
                    <td>{{ $mouse['description'] ?? '—' }}</td>
                    <td>{{ $mouse['input_status'] ?? '—' }}</td>
                    <td>{{ $mouse['physical_status'] ?? '—' }}</td>
                    <td>{{ $mouse['note'] ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-muted text-center py-6">No mice or pointing devices detected.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
