<div>
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="table_data_users" wire:ignore.self>
            <thead wire:ignore>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-125px">User</th>
                    <th class="min-w-125px">Role</th>
                    <th class="min-w-125px">Last login</th>
                    <th class="min-w-125px">Verified Email </th>
                    <th class="min-w-125px">Joined Date</th>
                    <th class="text-end min-w-100px">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
                @php
                    // Define an array of color classes
                    $colors = [
                        ['bg-light-danger', 'text-danger'],
                        ['bg-light-success', 'text-success'],
                        ['bg-light-info', 'text-info'],
                        ['bg-light-warning', 'text-warning'],
                        ['bg-light-primary', 'text-primary'],
                    ];
                @endphp
                @foreach ($users as $user)
                    @php
                        // Randomly pick a color class from the array for each user
                        $randomColor = $colors[array_rand($colors)];
                    @endphp
                    <tr>
                        <td class="d-flex align-items-center">
                            <div class="overflow-hidden symbol symbol-circle symbol-50px me-3">
                                <a href="#!">
                                    @if ($user->profileimg)
                                        <div class="symbol-label">
                                            <img src="{{ asset('storage/profile/' . $user->id . '/' . $user->profileimg) }}"
                                                alt="{{ $user->fname }} {{ $user->lname }}" class="w-100" />
                                        </div>
                                    @else
                                        <div class="symbol-label fs-3 {{ $randomColor[0] }} {{ $randomColor[1] }}">
                                            {{ strtoupper($user->fname[0]) }}
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="d-flex flex-column">
                                <a href="#!" class="mb-1 text-gray-800 text-hover-primary">{{ $user->fname }}
                                    {{ $user->lname }}</a>
                                <span>{{ $user->email }}</span>
                            </div>
                        </td>
                        <td>
                            {{ ucfirst($user->role) }}
                        </td>
                        <td>
                            <div class="badge badge-light fw-bold">
                                {{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}</div>
                        </td>
                        <td>
                            <div
                                class="badge badge-light-{{ $user->email_verified_at ? 'success' : 'danger' }} fw-bold">
                                {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}</div>
                        </td>
                        <td>
                            {{ $user->created_at ? $user->created_at->format('d M Y, h:i a') : 'N/A' }}
                        </td>
                        <td class="text-end">
                            @if ($user->id != Auth::id())
                                <a href="#"
                                    class="dropdown-btn btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                    <i class="ki-duotone ki-down fs-5 ms-1"></i>
                                </a>
                                <div class="py-4 dropdown-menu dropdown-menu-end menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px" wire:ignore>             
                                    <div class="px-3 menu-item">
                                        <a href="#" class="px-3 menu-link" wire:click='resetPasswordConfirmation({{$user->id}})'>
                                            Reset Password
                                        </a>
                                    </div>
                                    <div class="px-3 menu-item">
                                        <a href="#" class="px-3 menu-link" wire:click='openEditModal({{$user->id}})'>
                                            Edit
                                        </a>
                                    </div>
                                    <div class="px-3 menu-item">
                                        <a href="#" class="px-3 menu-link" wire:click='deleteUserConfirmation({{$user->id}})'>
                                            Delete
                                        </a>
                                    </div>
                                </div>
                            @endif

                        </td>
                    </tr>
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
