<div class="modal fade" id="updateinput_modal" tabindex="-1" aria-hidden="true" wire:ignore.self>

    <div class="modal-dialog mw-500px">
        <div class="modal-content">
            <div class="modal-header" id="updateinput_modal_header">
                <h2 class="fw-bold">
                    {{ ucfirst(explode('-', $type)[0] ?? '') }} 
                    @if(str_contains($type, '-'))
                        - {{ ucfirst(explode('-', $type)[1] ?? '') }}
                    @endif
                </h2>
                
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="px-5 modal-body my-7">
                <div class="px-5 d-flex flex-column px-lg-10" wire:ignore.self>
                    <form wire:submit.prevent="save">
                        {{-- Handle data fields --}}
                        @if(Str::contains($type, 'data'))
                            <div class="fv-row mb-7">
                                <label class="mb-2 required fw-semibold fs-6">Brand</label>
                                <input type="text" name="brand" class="mb-3 form-control form-control-solid" 
                                    placeholder="Enter brand" wire:model.defer="inputDevice.brand" />
                                @error('inputDevice.brand')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="fv-row mb-7">
                                <label class="mb-2 required fw-semibold fs-6">Model</label>
                                <input type="text" name="model" class="mb-3 form-control form-control-solid" 
                                    placeholder="Enter model" wire:model.defer="inputDevice.model" />
                                @error('inputDevice.model')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="fv-row mb-7">
                                <label class="mb-2 required fw-semibold fs-6">Serial Number</label>
                                <input type="text" name="serial_number" class="mb-3 form-control form-control-solid" 
                                    placeholder="Enter serial number" wire:model.defer="inputDevice.serial_number" />
                                @error('inputDevice.serial_number')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="fv-row mb-7">
                                <label class="mb-2 required fw-semibold fs-6">Manufacturer</label>
                                <input type="text" name="manufacturer" class="mb-3 form-control form-control-solid" 
                                    placeholder="Enter manufacturer" wire:model.defer="inputDevice.manufacturer" />
                                @error('inputDevice.manufacturer')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        {{-- Handle status fields --}}
                        @if(Str::contains($type, 'status'))
                            <div class="fv-row mb-7">
                                <label class="mb-2 required fw-semibold fs-6">Physical Status</label>
                                <input type="text" name="physical_status" class="mb-3 form-control form-control-solid" 
                                    placeholder="Enter physical status" wire:model.defer="inputDevice.physical_status" />
                                @error('inputDevice.physical_status')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif

                        {{-- Handle note fields --}}
                        @if(Str::contains($type, 'note'))
                            <div class="fv-row mb-7">
                                <label class="mb-2 fw-semibold fs-6">Note</label>
                                <textarea name="note" class="mb-3 form-control form-control-solid" 
                                    placeholder="Enter note" wire:model.defer="inputDevice.note"></textarea>
                                @error('inputDevice.note')
                                    <div class="fv-plugins-message-container invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click='discardForm'>Discard</button>
                <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr='disabled'>
                    <span wire:loading.remove>Save</span>
                    <span wire:loading>Please wait... <span class="spinner-border spinner-border-sm ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
