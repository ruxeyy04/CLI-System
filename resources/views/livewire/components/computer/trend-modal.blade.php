<div class="modal fade" id="generate_trend_modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog mw-1000px">
        <div class="modal-content">
            <div class="modal-header" id="generate_trend_modal_header">
                <h2 class="fw-bold">Generate Trend for {{ strtoupper($type) }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="px-5 modal-body my-7">
                <div class="px-5 " wire:ignore.self>
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="trend_type" class="form-label">Trend Type</label>
                            <select id="trend_type" class="form-select" wire:model.live="trend_type">
                                @if($type === 'cpu')
                                    <option value="utilization">Utilization</option>
                                    <option value="temp">Temperature</option>
                                @elseif($type === 'ram')
                                    <option value="usage">Usage</option>
                                @elseif($type === 'gpu')
                                    <option value="usage">Usage</option>
                                    <option value="temp">Temperature</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_datetime" class="form-label">Start Date & Time</label>
                            <input type="datetime-local" class="form-control" id="start_datetime" wire:model="start_datetime" min="{{ $min_date }}" max="{{ $max_date }}">
                        </div>
                        <div class="mb-3">
                            <label for="end_datetime" class="form-label">End Date & Time</label>
                            <input type="datetime-local" class="form-control" id="end_datetime" wire:model="end_datetime" min="{{ $min_date }}" max="{{ $max_date }}">
                        </div>

                    </form>
                    <button type="button" class="btn btn-primary" wire:click="generateTrend" wire:loading.attr='disabled' wire:target='generateTrend'>
                        <span wire:loading.remove wire:target='generateTrend'>Generate Trend</span>
                        <span wire:loading wire:target='generateTrend'>Please wait... <span class="spinner-border spinner-border-sm ms-2"></span></span>
                    </button>
                </div>
                <h4 class="text-center">Trend Analysis</h4>
                <div id="trend_graph" class="min-h-auto ps-4 pe-6" style="height: 350px" wire:ignore></div>
                <h3 class="mt-4 text-center">{{$description}}</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                <button type="button" class="btn btn-primary" wire:click="save" wire:loading.attr='disabled' wire:target='save'>
                    <span wire:loading.remove wire:target='save'>Save</span>
                    <span wire:loading wire:target='save'>Please wait... <span class="spinner-border spinner-border-sm ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
 
</div>
