<div class="modal fade" id="edit_device_modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog mw-500px">
        <div class="modal-content">
            <div class="modal-header" id="edit_device_modal_header">
                <h2 class="fw-bold">Update Computer Device</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="px-5 modal-body my-7">
                <div class="px-5 d-flex flex-column px-lg-10" wire:ignore.self>
                    <form action="">
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Device ID <small>(read only)</small></label>
                            <input type="text" name="device_id" class="mb-3 form-control form-control-solid mb-lg-0"
                                placeholder="UUID" wire:model.live='device_id' autocomplete="off" readonly/>
                            @error('device_id')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Serial Number</label>
                            <input type="text" name="serial_number"
                                class="mb-3 form-control form-control-solid mb-lg-0" placeholder="Serial Number"
                                wire:model.live='serial_number' autocomplete="off" />
                            @error('serial_number')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Device Name</label>
                            <input type="text" name="device_name"
                                class="mb-3 form-control form-control-solid mb-lg-0" placeholder="Device Name"
                                wire:model.live='device_name' autocomplete="off" />
                            @error('device_name')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Laboratory</label>
                            <div wire:ignore>
                                <select class="lab_select form-select form-select-solid" name="lab_id" data-control="select2"
                                    data-placeholder="Select an option" data-hide-search="true"
                                    wire:model.live="lab_id">
                                    <option></option>
                                    @foreach ($laboratory as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->laboratory_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @error('lab_id')
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
                <button type="button" class="btn btn-primary" wire:click='updateDevice' wire:loading.attr='disabled'
                    wire:target="updateDevice">
                    <span wire:loading.remove wire:target="updateDevice">Update Device</span>
                    <span wire:loading wire:target="updateDevice">
                        Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                    </span>

                </button>
            </div>
        </div>

    </div>
</div>
