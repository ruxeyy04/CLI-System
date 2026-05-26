<?php

namespace App\Livewire\Components\Computer;

use Carbon\Carbon;
use Livewire\Component;

class GraphDateFilter extends Component
{
    public string $preset = 'today';

    public string $startDate = '';

    public string $endDate = '';

    public bool $isApplying = false;

    public function mount(): void
    {
        $this->applyPreset('today', dispatch: false);
    }

    public function setPreset(string $preset): void
    {
        if (! in_array($preset, ['today', 'yesterday', 'last7', 'custom'], true)) {
            return;
        }

        $this->preset = $preset;

        if ($preset === 'custom') {
            return;
        }

        $this->isApplying = true;

        try {
            $this->applyPreset($preset);
        } finally {
            $this->isApplying = false;
        }
    }

    public function applyCustomRange(): void
    {
        $this->validate([
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
        ], [], [
            'startDate' => 'start date',
            'endDate' => 'end date',
        ]);

        $this->preset = 'custom';
        $this->isApplying = true;

        try {
            $this->dispatchRange();
        } finally {
            $this->isApplying = false;
        }
    }

    public function getRangeLabelProperty(): string
    {
        if ($this->startDate === '' || $this->endDate === '') {
            return '';
        }

        if ($this->startDate === $this->endDate) {
            return Carbon::parse($this->startDate)->format('M d, Y');
        }

        return Carbon::parse($this->startDate)->format('M d, Y')
            .' – '
            .Carbon::parse($this->endDate)->format('M d, Y');
    }

    protected function applyPreset(string $preset, bool $dispatch = true): void
    {
        [$this->startDate, $this->endDate] = match ($preset) {
            'yesterday' => [
                now()->subDay()->toDateString(),
                now()->subDay()->toDateString(),
            ],
            'last7' => [
                now()->subDays(6)->toDateString(),
                now()->toDateString(),
            ],
            default => [
                now()->toDateString(),
                now()->toDateString(),
            ],
        };

        if ($dispatch) {
            $this->dispatchRange();
        }
    }

    protected function dispatchRange(): void
    {
        $this->dispatch(
            'graph-date-range-changed',
            startDate: $this->startDate,
            endDate: $this->endDate,
        );
    }

    public function render()
    {
        return view('livewire.components.computer.graph-date-filter');
    }
}
