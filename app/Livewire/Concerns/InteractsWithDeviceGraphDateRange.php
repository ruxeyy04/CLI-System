<?php

namespace App\Livewire\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait InteractsWithDeviceGraphDateRange
{
    public string $graphStartDate = '';

    public string $graphEndDate = '';

    public bool $isLoadingGraph = false;

    protected function initializeGraphDateRange(): void
    {
        $this->graphStartDate = now()->toDateString();
        $this->graphEndDate = $this->graphStartDate;
    }

    /**
     * @return array{0: \Carbon\Carbon, 1: \Carbon\Carbon}
     */
    protected function graphMetricsBetween(): array
    {
        $start = $this->graphStartDate ?: now()->toDateString();
        $end = $this->graphEndDate ?: $start;

        return [
            Carbon::parse($start)->startOfDay(),
            Carbon::parse($end)->endOfDay(),
        ];
    }

    protected function applyGraphDateBetween(Builder $query, string $column = 'created_at'): Builder
    {
        [$from, $to] = $this->graphMetricsBetween();

        return $query->whereBetween($column, [$from, $to]);
    }
}
