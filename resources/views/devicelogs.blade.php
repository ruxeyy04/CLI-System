<x-assistant-layout>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <div class="row gx-5 gx-xl-10 mb-xl-10">
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <div class="card">
                    <div class="pt-6 border-0 card-header">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="text-gray-900 card-label fw-bold">Cpu Temperature Logs</span>
                        </h3>
                        <livewire:components.devicelogs.cputablesearch />
                    </div>
                    <div class="py-4 card-body">
                        <livewire:components.devicelogs.cputable :device="$device">
                    </div>
                </div>
            </div>
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <div class="card">
                    <div class="pt-6 border-0 card-header">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="text-gray-900 card-label fw-bold">Cpu Utilization Logs</span>
                        </h3>
                        <livewire:components.devicelogs.cputableutilsearch />
                    </div>
                    <div class="py-4 card-body">
                        <livewire:components.devicelogs.cputableutil :device="$device">
                    </div>
                </div>
            </div>
        </div>
        <div class="row gx-5 gx-xl-10 mb-xl-10">
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <div class="card">
                    <div class="pt-6 border-0 card-header">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="text-gray-900 card-label fw-bold">Gpu Temperature Logs</span>
                        </h3>
                        <livewire:components.devicelogs.gputabletempsearch />
                    </div>
                    <div class="py-4 card-body">
                        <livewire:components.devicelogs.gputabletemp :device="$device">
                    </div>
                </div>
            </div>
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <div class="card">
                    <div class="pt-6 border-0 card-header">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="text-gray-900 card-label fw-bold">Gpu Usage Logs</span>
                        </h3>
                        <livewire:components.devicelogs.gputableutilsearch />
                    </div>
                    <div class="py-4 card-body">
                        <livewire:components.devicelogs.gputableutil :device="$device">
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-5 gx-xl-10 mb-xl-10">
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-12 mb-xl-0">
                <div class="card">
                    <div class="pt-6 border-0 card-header">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="text-gray-900 card-label fw-bold">RAM Usage Logs</span>
                        </h3>
                        <livewire:components.devicelogs.ramtablesearch />
                    </div>
                    <div class="py-4 card-body">
                        <livewire:components.devicelogs.ramtable :device="$device">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="pt-6 border-0 card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="text-gray-900 card-label fw-bold">Device Notification/Input Logs</span>
                </h3>
                <livewire:components.notifications.search />
            </div>
            <div class="py-4 card-body">
                <livewire:components.notifications.table>
            </div>
        </div>
    </div>
</x-assisant-layout>
