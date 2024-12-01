<div>
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_gpu_temp">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-100px">#</th>
                    <th class="min-w-125px">Device Name</th>
                    <th class="min-w-125px">GPU Temperature (°C)</th>
                    <th class="min-w-125px">Logged At</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
                @forelse ($gpuTempLogs as $log)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $device->name }}</td>
                        <td>{{ $log->temp }}</td>
                        <td>{{ $log->created_at->format('M j, Y \a\t g:iA') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No GPU temperature logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $gpuTempLogs->links() }}
    </div>
</div>
