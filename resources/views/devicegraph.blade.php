<x-assistant-layout>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        @if ($device->patch_id)
        <script>
             currentDeviceId = '{{ $device->id }}';
        </script>
        @else
        <script>
             currentDeviceId = null;
        </script>
        @endif

        <livewire:components.computer.device-graphs-panel :device="$device" />

        <div class="row gx-5 gx-xl-10 mb-xl-10">
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.disk-cards :device="$device" />
            </div>
            <div class="mb-5 col-lg-12 col-xl-12 col-xxl-6 mb-xl-0">
                <livewire:components.computer.input-devices :device="$device" />
            </div>
        </div>
    </div>
    <livewire:components.computer.modal />
    <livewire:components.computer.trend-modal :device="$device"/>
    <livewire:components.computer.view-trend-modal />

</x-assistant-layout>
