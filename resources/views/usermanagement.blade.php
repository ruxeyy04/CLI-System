<x-assistant-layout>
    <style>
        .dt-empty {
            text-align: center;
        }
    </style>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="pt-6 border-0 card-header">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="my-1 d-flex align-items-center position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                                class="path2"></span></i>
                        <input type="text" data-kt-user-table-filter="search"
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search user" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->

                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">


                        <!--begin::Add user-->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#adduser_modal">
                            <i class="ki-duotone ki-plus fs-2"></i> Add User
                        </button>
                        <!--end::Add user-->
                    </div>
                    <!--end::Toolbar-->


                    <!--begin::Modal - Adjust Balance-->
                    <livewire:components.users.exportusersmodal>
                        <!--end::Modal - New Card-->

                        <!--begin::Modal - Add task-->
                        <livewire:components.users.addusermodal>
                            <!--end::Modal - Add task-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="py-4 card-body">

                <!--begin::Table-->
                <livewire:components.users.userstable>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</x-assistant-layout>
