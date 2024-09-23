<x-assistant-layout>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <!--begin::Card-->
        <div class="card">
            <div class="pt-6 border-0 card-header">
                <livewire:components.device.search />
                <livewire:components.device.toolbar />
            </div>
            <div class="py-4 card-body">
                <livewire:components.device.table>
            </div>
        </div>
    </div>
    <livewire:components.device.addmodal>
</x-assistant-layout>