<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div class="card-toolbar">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary" wire:click="$dispatch('add-laboratory-modal')">
            <i class="ki-duotone ki-plus fs-2"></i> Add Laboratory
        </button>
    </div>
</div>
