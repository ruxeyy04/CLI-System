<div>

    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_cpu_util">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-100px">#</th>
                    <th class="min-w-125px">Device Name</th>
                    <th class="min-w-125px">CPU Utilization (%)</th>
                    <th class="min-w-125px">Logged At</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
                @forelse ($cpuUtilLogs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $device->device_name }}</td>
                        <td>{{ $log->util }}</td>
                        <td>{{ $log->created_at->format('M j, Y \a\t g:iA') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No CPU utilization logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $cpuUtilLogs->links() }}
    </div>
</div>
