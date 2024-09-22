<?php

use Livewire\Volt\Component;

new class extends Component {
    public $searchVal;
    public function updatedSearchVal () {
        $this->dispatch('search-lab', $this->searchVal);
    }
}; ?>

<div class="card-title">
    <div class="my-1 d-flex align-items-center position-relative">
        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"><span class="path1"></span><span
                class="path2"></span></i>
        <input type="text" class="form-control form-control-solid w-250px ps-13" placeholder="Search Laboratory" name="searchVal" wire:model.live='searchVal'/>
    </div>
</div>
