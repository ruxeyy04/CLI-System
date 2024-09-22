<x-assistant-layout>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <!--begin::Card-->
        <div class="card">
            <div class="pt-6 border-0 card-header">
                <livewire:components.users.users-table-search />
                <livewire:components.users.card-toolbar />
            </div>
            <div class="py-4 card-body">
                <livewire:components.users.userstable>
            </div>
        </div>
    </div>
    <livewire:components.users.addusermodal>
    <livewire:components.users.editusermodal>
</x-assistant-layout>
