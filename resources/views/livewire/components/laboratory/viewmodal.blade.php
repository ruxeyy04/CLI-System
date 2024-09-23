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
        $this->laboratory = null;
        $this->assistants = null;
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
    <div class="modal-dialog mw-75">
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
                                    <h2 class="mb-0">{{ $laboratory->laboratory_name ??  null}}</h2>
                                </div>
                            </div>

                            <div class="pt-1 card-body">
                                <div class="mb-5 text-gray-600 fw-bold">Total assistant assign this lab:
                                    {{ $laboratory->users_count ?? null  }}
                                </div>
                                <div class="text-gray-600 d-flex flex-column">
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span> 24 Computers
                                    </div>
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span> {{ $laboratory->capacity ?? null}} Capacity
                                    </div>
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span> Created at
                                        {{ isset($laboratory->created_at) ? $laboratory->created_at ? $laboratory->created_at->format('d M Y, h:i a') : 'None' : ''}}
                                    </div>
                                    <div class="py-2 d-flex align-items-center">
                                        <span
                                            class="bullet bg-primary me-3"></span>{{ isset($laboratory->created_at) ? $laboratory->updated_at != $laboratory->created_at ? 'Updated at ' . $laboratory->updated_at->format('d M Y, h:i a') : 'Not Yet Updated' : '' }}
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
                                                    class="text-gray-600 fs-6 ms-1">(14)</span></h2>
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
                                                    <colgroup>
                                                        <col data-dt-column="2" style="width: 197.391px;">
                                                        <col data-dt-column="3" style="width: 213.25px;">
                                                        <col data-dt-column="4" style="width: 132.203px;">
                                                    </colgroup>
                                                    <thead>
                                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                            role="row">
                                                            <th class="min-w-50px">Assistant</th>
                                                            <th class="min-w-150px ">Username</th>
                                                            <th class="min-w-125px ">Contact Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-semibold">
                                                        <tr>
                                                            <td class="d-flex align-items-center">
                                                                <!--begin:: Avatar -->
                                                                <div
                                                                    class="overflow-hidden symbol symbol-circle symbol-50px me-3">
                                                                    <a href="../users/view.html">
                                                                        <div class="symbol-label">
                                                                            <img src="../storage/profile/1/2x2%20Pic.jpg"
                                                                                alt="Emma Smith" class="w-100">
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                                <!--end::Avatar-->

                                                                <!--begin::User details-->
                                                                <div class="d-flex flex-column">
                                                                    <a href="../users/view.html"
                                                                        class="mb-1 text-gray-800 text-hover-primary">Emma
                                                                        Smith</a>
                                                                    <span>smith@kpmg.com</span>
                                                                </div>
                                                                <!--begin::User details-->
                                                            </td>
                                                            <td data-order="2024-12-20T17:30:00+08:00">
                                                                20 Dec 2024, 5:30 pm </td>
                                                            <td class="text-end">
                                                                <a href="#"
                                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                                    data-kt-menu-trigger="click"
                                                                    data-kt-menu-placement="bottom-end">
                                                                    Actions
                                                                    <i class="m-0 ki-duotone ki-down fs-5"></i> </a>
                                                                <!--begin::Menu-->
                                                                <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px"
                                                                    data-kt-menu="true">
                                                                    <!--begin::Menu item-->
                                                                    <div class="px-3 menu-item">
                                                                        <a href="../users/view.html"
                                                                            class="px-3 menu-link">
                                                                            View
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="px-3 menu-item">
                                                                        <a href="#" class="px-3 menu-link"
                                                                            data-kt-roles-table-filter="delete_row">
                                                                            Delete
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu-->
                                                            </td>
                                                        </tr>
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
                                                    class="text-gray-600 fs-6 ms-1">(14)</span></h2>
                                        </div>
                                        <!--end::Card title-->

                                        <!--begin::Card toolbar-->
                                        <div class="card-toolbar">
                                            <!--begin::Search-->
                                            <div class="my-1 d-flex align-items-center position-relative">
                                                <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span
                                                        class="path1"></span><span class="path2"></span></i> <input
                                                    type="text"
                                                    class="form-control form-control-solid w-250px ps-15"
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
                                                    <colgroup>
                                                        <col data-dt-column="2" style="width: 197.391px;">
                                                        <col data-dt-column="3" style="width: 213.25px;">
                                                        <col data-dt-column="4" style="width: 132.203px;">
                                                    </colgroup>
                                                    <thead>
                                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0"
                                                            role="row">
                                                            <th class="min-w-50px">Assistant</th>
                                                            <th class="min-w-150px ">Username</th>
                                                            <th class="min-w-125px ">Contact Number</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-gray-600 fw-semibold">
                                                        <tr>
                                                            <td class="d-flex align-items-center">
                                                                <!--begin:: Avatar -->
                                                                <div
                                                                    class="overflow-hidden symbol symbol-circle symbol-50px me-3">
                                                                    <a href="../users/view.html">
                                                                        <div class="symbol-label">
                                                                            <img src="../storage/profile/1/2x2%20Pic.jpg"
                                                                                alt="Emma Smith" class="w-100">
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                                <!--end::Avatar-->

                                                                <!--begin::User details-->
                                                                <div class="d-flex flex-column">
                                                                    <a href="../users/view.html"
                                                                        class="mb-1 text-gray-800 text-hover-primary">Emma
                                                                        Smith</a>
                                                                    <span>smith@kpmg.com</span>
                                                                </div>
                                                                <!--begin::User details-->
                                                            </td>
                                                            <td data-order="2024-12-20T17:30:00+08:00">
                                                                20 Dec 2024, 5:30 pm </td>
                                                            <td class="text-end">
                                                                <a href="#"
                                                                    class="btn btn-sm btn-light btn-active-light-primary"
                                                                    data-kt-menu-trigger="click"
                                                                    data-kt-menu-placement="bottom-end">
                                                                    Actions
                                                                    <i class="m-0 ki-duotone ki-down fs-5"></i> </a>
                                                                <!--begin::Menu-->
                                                                <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px"
                                                                    data-kt-menu="true">
                                                                    <!--begin::Menu item-->
                                                                    <div class="px-3 menu-item">
                                                                        <a href="../users/view.html"
                                                                            class="px-3 menu-link">
                                                                            View
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="px-3 menu-item">
                                                                        <a href="#" class="px-3 menu-link"
                                                                            data-kt-roles-table-filter="delete_row">
                                                                            Delete
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div>
                                                                <!--end::Menu-->
                                                            </td>
                                                        </tr>
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
