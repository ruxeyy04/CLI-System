<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Laboratory;

new class extends Component {
    public $laboratoryId;
    public $laboratory;
    public $assistants;
    public $searchVal;

    public $selectedUsers = [];
    protected function getListeners()
    {
        return [
            'view_lab' => 'loadLaboratory',
        ];
    }
    public function mount()
    {
        $this->laboratory = [];
        $this->assistants = [];
    }
    public function loadLaboratory($lab_id)
    {
        $this->laboratory = Laboratory::withCount('users')->where('id', $lab_id)->first();

        $this->loadAssistant($lab_id);
        $this->dispatch('view_lab_done');
    }

    public function loadAssistant($laboratoryId)
    {
        $this->laboratoryId = $laboratoryId;

        $this->assistants = User::where('role', 'assistant')
            ->where('laboratory_id', $this->laboratoryId)
            ->get();
    }
}; ?>

<div class="modal fade" id="viewlab_modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" id="viewlab_modal_header">
                <h2 class="fw-bold">View Laboratory</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="px-5 modal-body" style="background-color: #f9f9f9;">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card card-flush">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2 class="mb-0">{{ $laboratory->laboratory_name ?? null }}</h2>
                                </div>
                            </div>

                            <div class="pt-1 card-body">
                                <div class="mb-5 text-gray-600 fw-bold">Total assistant assign this lab:
                                    {{ $laboratory->users_count ?? null }}
                                </div>
                                <div class="text-gray-600 d-flex flex-column">
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span> 24 Computers
                                    </div>
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span> {{ $laboratory->capacity ?? null }}
                                        Capacity
                                    </div>
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span> Created at
                                        {{ isset($laboratory->created_at) ? ($laboratory->created_at ? $laboratory->created_at->format('d M Y, h:i a') : 'None') : '' }}
                                    </div>
                                    <div class="py-2 d-flex align-items-center">
                                        <span
                                            class="bullet bg-primary me-3"></span>{{ isset($laboratory->created_at) ? ($laboratory->updated_at != $laboratory->created_at ? 'Updated at ' . $laboratory->updated_at->format('d M Y, h:i a') : 'Not Yet Updated') : '' }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="mb-6 card card-flush mb-xl-9">
                                    <!--begin::Card header-->
                                    <div class="pt-5 card-header">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2 class="d-flex align-items-center"> Users Assigned<span
                                                    class="text-gray-600 fs-6 ms-1">({{ $laboratory->users_count ?? null }})</span>
                                            </h2>
                                        </div>
                                        <!--end::Card title-->

                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Search-->
                                            <div class="my-1 d-flex align-items-center position-relative">
                                                <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                        class="path1"></span><span class="path2"></span></i> <input
                                                    type="text" class="form-control form-control-solid w-250px ps-15"
                                                    placeholder="Search user" name="searchVal"
                                                    wire:model.live="searchVal">
                                            </div>
                                            <!--end::Search-->

                                        </div>
                                        <!--end::Card toolbar-->
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="pt-0 card-body">
                                        <!--begin::Table-->
                                        <div id="kt_roles_view_table_wrapper"
                                            class="dt-container dt-bootstrap5 dt-empty-footer">
                                            <div id="" class="table-responsive">
                                                <table
                                                    class="table mb-0 align-middle table-row-dashed fs-6 gy-5 dataTable" style="width: 100%;">
                                                    <thead>
                                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                            role="row">
                                                            <th class="min-w-50px">Assistant</th>
                                                            <th class="min-w-150px ">Role</th>
                                                            <th class="min-w-125px ">Last Login</th>
                                                            <th class="min-w-125px ">Verified Email</th>
                                                            <th class="min-w-125px ">Contact Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-semibold">
                                                        @php
                                                            $colors = [
                                                                ['bg-light-danger', 'text-danger'],
                                                                ['bg-light-success', 'text-success'],
                                                                ['bg-light-info', 'text-info'],
                                                                ['bg-light-warning', 'text-warning'],
                                                                ['bg-light-primary', 'text-primary'],
                                                            ];
                                                        @endphp
                                                        @foreach ($assistants as $user)
                                                            @php
                                                                $randomColor = $colors[array_rand($colors)];
                                                            @endphp
                                                            <tr wire:key="user-{{ $user->id }}">
                                                                <td class="d-flex align-items-center">
                                                                    <div
                                                                        class="overflow-hidden symbol symbol-circle symbol-50px me-3">
                                                                        <a href="#!">
                                                                            @if ($user->profileimg)
                                                                                <div class="symbol-label">
                                                                                    <img src="{{ asset('storage/profile/' . $user->id . '/' . $user->profileimg) }}"
                                                                                        alt="{{ $user->fname }} {{ $user->lname }}"
                                                                                        class="w-100" />
                                                                                </div>
                                                                            @else
                                                                                <div
                                                                                    class="symbol-label fs-3 {{ $randomColor[0] }} {{ $randomColor[1] }}">
                                                                                    {{ strtoupper($user->fname[0]) }}
                                                                                </div>
                                                                            @endif
                                                                        </a>
                                                                    </div>
                                                                    <div class="d-flex flex-column">
                                                                        <a href="#!"
                                                                            class="mb-1 text-gray-800 text-hover-primary">{{ $user->fname }}
                                                                            {{ $user->lname }}</a>
                                                                        <span>{{ $user->email }}</span>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ ucfirst($user->role) }}
                                                                </td>
                                                                <td>
                                                                    <div class="badge badge-light fw-bold">
                                                                        {{ $user->last_login ? $user->last_login->diffForHumans() : 'Never' }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        class="badge badge-light-{{ $user->email_verified_at ? 'success' : 'danger' }} fw-bold">
                                                                        {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ $user->phone ?? 'None' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-6 card card-flush mb-xl-9">
                                    <!--begin::Card header-->
                                    <div class="pt-5 card-header">
                                        <!--begin::Card title-->
                                        <div class="card-title">
                                            <h2 class="d-flex align-items-center">Computer Devices<span
                                                    class="text-gray-600 fs-6 ms-1">(0)</span></h2>
                                        </div>
                                        <!--end::Card title-->

                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Search-->
                                            <div class="my-1 d-flex align-items-center position-relative">
                                                <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                        class="path1"></span><span class="path2"></span></i> <input
                                                    type="text" class="form-control form-control-solid w-250px ps-15"
                                                    placeholder="Search Users">
                                            </div>
                                            <!--end::Search-->

                                        </div>
                                        <!--end::Card toolbar-->
                                    </div>
                                    <!--end::Card header-->

                                    <!--begin::Card body-->
                                    <div class="pt-0 card-body">
                                        <!--begin::Table-->
                                        <div id="kt_roles_view_table_wrapper"
                                            class="dt-container dt-bootstrap5 dt-empty-footer">
                                            <div id="" class="table-responsive">
                                                <table
                                                    class="table mb-0 align-middle table-row-dashed fs-6 gy-5 dataTable"
                                                    id="kt_roles_view_table" style="width: 100%;">
                                                    <thead>
                                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                            role="row">
                                                            <th class="min-w-50px">Patch ID</th>
                                                            <th class="min-w-150px ">Profile Name</th>
                                                            <th class="min-w-125px ">Total Logs</th>
                                                            <th class="min-w-125px ">Date Added</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-semibold">

                                                    </tbody>
                                                    <tfoot></tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Card body-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>

    </div>
</div>
