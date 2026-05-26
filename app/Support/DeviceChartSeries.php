<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class DeviceChartSeries
{
    /**
     * Format for ApexCharts: wall-clock string without timezone suffix.
     */
    public static function formatTimestamp(CarbonInterface|string $value): string
    {
        if ($value instanceof CarbonInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    /**
     * @param  Collection<int, object>  $primary
     * @param  Collection<int, object>  $secondary
     * @return array{timestamps: list<string>, primary: list<float|int|string|null>, secondary: list<float|int|string|null>}
     */
    public static function alignDualSeries(
        Collection $primary,
        Collection $secondary,
        string $primaryKey,
        string $secondaryKey,
    ): array {
        $primary = $primary->sortBy('created_at')->values();
        $secondary = $secondary->sortBy('created_at')->values();

        $primaryByTime = $primary->keyBy(
            fn (object $row) => self::formatTimestamp($row->created_at)
        );
        $secondaryByTime = $secondary->keyBy(
            fn (object $row) => self::formatTimestamp($row->created_at)
        );

        $times = $primaryByTime->keys()
            ->merge($secondaryByTime->keys())
            ->unique()
            ->sort()
            ->values();

        $primaryValues = [];
        $secondaryValues = [];
        $lastPrimary = null;
        $lastSecondary = null;

        foreach ($times as $time) {
            if ($primaryByTime->has($time)) {
                $lastPrimary = $primaryByTime[$time]->{$primaryKey};
            }

            if ($secondaryByTime->has($time)) {
                $lastSecondary = $secondaryByTime[$time]->{$secondaryKey};
            }

            $primaryValues[] = $lastPrimary;
            $secondaryValues[] = $lastSecondary;
        }

        return [
            'timestamps' => $times->all(),
            'primary' => $primaryValues,
            'secondary' => $secondaryValues,
        ];
    }

    /**
     * @param  Collection<int, object>  $records
     * @return array{timestamps: list<string>, values: list<float|int|string>}
     */
    public static function singleSeries(Collection $records, string $valueKey): array
    {
        $records = $records->sortBy('created_at')->values();

        return [
            'timestamps' => $records
                ->map(fn (object $row) => self::formatTimestamp($row->created_at))
                ->all(),
            'values' => $records->pluck($valueKey)->all(),
        ];
    }

    /**
     * @param  iterable<int, object>  $logs
     * @return list<array{data: mixed, created_at: string}>
     */
    public static function formatLogsForChart(iterable $logs): array
    {
        return collect($logs)->map(function (object $row) {
            $createdAt = $row->created_at;

            return [
                'data' => $row->data,
                'created_at' => $createdAt instanceof CarbonInterface
                    ? self::formatTimestamp($createdAt)
                    : (string) $createdAt,
            ];
        })->values()->all();
    }

    /**
     * @param  list<array{x: string, y: float|int|string}>  $trendLine
     * @return list<array{x: string, y: float|int|string}>
     */
    public static function formatTrendLineForChart(array $trendLine): array
    {
        return collect($trendLine)->map(function (array $point) {
            return [
                'x' => self::formatTimestamp($point['x']),
                'y' => $point['y'],
            ];
        })->values()->all();
    }
}
