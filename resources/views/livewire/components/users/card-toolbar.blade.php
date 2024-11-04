<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="card-toolbar">
    @if (Route::currentRouteName() != 'dashboard')
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" wire:click="$dispatch('add-user-modal')">
                <i class="ki-duotone ki-plus fs-2"></i> Add User
            </button>
        </div>
    @endif


</div>
