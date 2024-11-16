<div>
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_data_users">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-100px">#</th>
                    <th class="min-w-125px">Device Name</th>
                    <th class="min-w-125px">Title</th>
                    <th class="min-w-125px">Message</th>
                    <th class="min-w-125px">Type</th>
                    <th class="min-w-125px">Notified On</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
                @forelse ($notifications as $index => $notification)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $notification->device->device_name ?? 'N/A' }}</td>
                        <td>{{ $notification->title }}</td>
                        <td>{{ $notification->message }}</td>
                        <td>{{ $notification->type }}</td>
                        <td>{{ $notification->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No notifications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
