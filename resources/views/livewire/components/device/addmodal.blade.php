<div class="modal fade" id="add_device_modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog mw-500px">
        <div class="modal-content">
            <div class="modal-header" id="add_device_modal_header">
                <h2 class="fw-bold">Add Computer Device</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="px-5 modal-body my-7">
                <div class="px-5 d-flex flex-column px-lg-10" wire:ignore.self>
                    <form action="">
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Device ID</label>
                            <input type="text" name="device_id" class="mb-3 form-control form-control-solid mb-lg-0"
                                placeholder="UUID" wire:model.live='device_id' autocomplete="off" />
                            @error('device_id')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Device Name</label>
                            <input type="text" name="laboratory_name"
                                class="mb-3 form-control form-control-solid mb-lg-0" placeholder="Device Name"
                                wire:model.live='device_name' autocomplete="off" />
                            @error('device_name')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="fv-row mb-7" wire:ignore>
                            <label class="mb-2 required fw-semibold fs-6">Laboratory</label>
                            <select class="form-select form-select-solid" data-control="select2"
                                data-placeholder="Select an option" data-hide-search="true" name="laboratory"
                                wire:model.live="laboratory">
                                <option></option>
                                @foreach ($laboratory as $lab)
                                    <option value="{{ $lab->id }}">{{ $lab->laboratory_name }}</option>
                                @endforeach
                            </select>

                            @error('capacity')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>


                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    wire:click='discardForm'>Discard</button>
                <button type="button" class="btn btn-primary" wire:click='saveDevice' wire:loading.attr='disabled'
                    wire:target="saveDevice">
                    <span wire:loading.remove wire:target="saveDevice">Save Device</span>
                    <span wire:loading wire:target="saveDevice">
                        Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                    </span>

                </button>
            </div>
        </div>

    </div>
</div>
