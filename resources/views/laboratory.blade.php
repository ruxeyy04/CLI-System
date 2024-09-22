<x-assistant-layout>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <!--begin::Card-->
        <div class="mb-4 card">
            <div class="py-3 border-0 card-header">
                <livewire:components.laboratory.search />
                <div class="card-toolbar">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" >
                            <i class="ki-duotone ki-plus fs-2"></i> Add Laboratory
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <livewire:components.laboratory.table>
    </div>
</x-assistant-layout>