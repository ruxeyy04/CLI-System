<x-assistant-layout>
    <style>
        .dt-empty {
            text-align: center;
        }
    </style>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <!--begin::Card-->
        <div class="card">
            <div class="pt-6 border-0 card-header">
                <livewire:components.users.users-table-search />
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#adduser_modal">
                            <i class="ki-duotone ki-plus fs-2"></i> Add User
                        </button>
                    </div>
                </div>
            </div>
            <div class="py-4 card-body">
                <livewire:components.users.userstable>
            </div>
        </div>
    </div>
    <livewire:components.users.addusermodal>
    <livewire:components.users.editusermodal>
</x-assistant-layout>
