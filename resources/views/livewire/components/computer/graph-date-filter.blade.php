<div class="mb-5 card card-flush">
    <div class="py-4 card-body">
        <div class="flex-wrap gap-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-gray-900 fw-bold fs-6">Graph date range</span>
                @if ($this->rangeLabel)
                    <span class="text-gray-500 fs-7 d-block">{{ $this->rangeLabel }}</span>
                @endif
            </div>

            <div class="flex-wrap gap-2 d-flex align-items-center">
                <div class="btn-group" role="group" aria-label="Graph date presets">
                    <button type="button"
                        class="btn btn-sm {{ $preset === 'today' ? 'btn-primary' : 'btn-light-primary' }}"
                        wire:click="setPreset('today')" @disabled($isApplying)>
                        Today
                    </button>
                    <button type="button"
                        class="btn btn-sm {{ $preset === 'yesterday' ? 'btn-primary' : 'btn-light-primary' }}"
                        wire:click="setPreset('yesterday')" @disabled($isApplying)>
                        Yesterday
                    </button>
                    <button type="button"
                        class="btn btn-sm {{ $preset === 'last7' ? 'btn-primary' : 'btn-light-primary' }}"
                        wire:click="setPreset('last7')" @disabled($isApplying)>
                        Last 7 days
                    </button>
                    <button type="button"
                        class="btn btn-sm {{ $preset === 'custom' ? 'btn-primary' : 'btn-light-primary' }}"
                        wire:click="setPreset('custom')" @disabled($isApplying)>
                        Custom
                    </button>
                </div>
                @if ($isApplying)
                    <div class="gap-2 d-flex align-items-center text-primary">
                        <span class="spinner-border spinner-border-sm" role="status"></span>
                        <span class="fs-7 fw-semibold">Updating graphs...</span>
                    </div>
                @endif
            </div>
        </div>

        @if ($preset === 'custom')
            <div class="flex-wrap gap-3 mt-4 d-flex align-items-end">
                <div>
                    <label class="form-label fs-7 fw-semibold text-gray-600">Start date</label>
                    <input type="date" class="form-control form-control-sm form-control-solid w-175px"
                        wire:model="startDate" max="{{ now()->toDateString() }}" @disabled($isApplying)>
                    @error('startDate')
                        <div class="mt-1 text-danger fs-7">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="form-label fs-7 fw-semibold text-gray-600">End date</label>
                    <input type="date" class="form-control form-control-sm form-control-solid w-175px"
                        wire:model="endDate" max="{{ now()->toDateString() }}" @disabled($isApplying)>
                    @error('endDate')
                        <div class="mt-1 text-danger fs-7">{{ $message }}</div>
                    @enderror
                </div>
                <button type="button" class="btn btn-sm btn-primary" wire:click="applyCustomRange"
                    @disabled($isApplying)>
                    @if ($isApplying)
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                        Applying...
                    @else
                        Apply range
                    @endif
                </button>
            </div>
        @endif
    </div>
</div>
