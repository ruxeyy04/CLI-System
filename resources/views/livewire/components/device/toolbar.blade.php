<div class="card-toolbar">
    @if (Route::currentRouteName() != 'dashboard')
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" wire:click="$dispatch('add-device-modal')">
                <i class="ki-duotone ki-plus fs-2"></i> Add Device
            </button>
        </div>
    @endif

</div>
