<?php

namespace App\Livewire\Components\Computer;

use Livewire\Component;
use App\Models\TrendLog;
use App\Models\CpuTemp;
use App\Models\CpuUtilization;
use App\Models\GpuTemp;
use App\Models\GpuUsage;
use App\Models\RamUsage;

class TrendModal extends Component
{
    public $id;
    public $type;
    public $start_datetime;
    public $end_datetime;
    public $min_date;
    public $max_date;
    public $trend_type;
    public $trend_data = [];
    public $device_id;
    public $description;
    public $raw_logs;
    public $raw_label;

    public $trend_start_date;
    public $trend_end_date;
    protected function getListeners()
    {
        return [
            'generate-trend-modal' => 'openModalTrend'
        ];
    }
    public function mount($device)
    {
        $this->device_id = $device->id;
    }
    public function openModalTrend($id, $type)
    {
        $this->id = $id;
        $this->type = $type;
        $this->description = '';
        if ($type === 'cpu') {
            $this->trend_type = 'utilization';
            $this->min_date = CpuUtilization::min('created_at');
            $this->max_date = CpuUtilization::max('created_at');
            $this->raw_label = "CPU Utilization";
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        } elseif ($type === 'ram') {
            $this->trend_type = 'usage';
            $this->min_date = RamUsage::min('created_at');
            $this->max_date = RamUsage::max('created_at');
            $this->raw_label = "RAM Usage";
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        } elseif ($type === 'gpu') {
            $this->trend_type = 'usage';
            $this->min_date = GpuUsage::min('created_at');
            $this->max_date = GpuUsage::max('created_at');
            $this->raw_label = "GPU Usage";
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        }

        $this->dispatch('openModalTrend');
    }
    public function generateTrend()
    {
        $this->trend_data = [];
        $this->raw_logs = [];

        if ($this->type === 'cpu') {
            if ($this->trend_type === 'utilization') {
                $this->trend_data = CpuUtilization::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'util'])
                    ->map(function ($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->util,
                        ];
                    });

                $this->raw_logs = CpuUtilization::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->select('util AS data', 'created_at')
                    ->get();
                $this->raw_label = "CPU Utilization";
            } elseif ($this->trend_type === 'temp') {
                $this->trend_data = CpuTemp::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'temp'])
                    ->map(function ($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->temp,
                        ];
                    });

                $this->raw_logs = CpuTemp::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->select('temp AS data', 'created_at')
                    ->get();
                $this->raw_label = "CPU Temperature";
            }
        } elseif ($this->type === 'gpu') {
            if ($this->trend_type === 'usage') {
                $this->trend_data = GpuUsage::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'usage'])
                    ->map(function ($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->usage,
                        ];
                    });

                $this->raw_logs = GpuUsage::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->select('usage AS data', 'created_at')
                    ->get();
                $this->raw_label = "GPU Usage";
            } elseif ($this->trend_type === 'temp') {
                $this->trend_data = GpuTemp::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'temp'])
                    ->map(function ($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->temp,
                        ];
                    });

                // Fetch raw logs for GPU temperature
                $this->raw_logs = GpuTemp::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->select('temp AS data', 'created_at')
                    ->get();
                $this->raw_label = "GPU Temperature";
            }
        } elseif ($this->type === 'ram') {
            $this->trend_data = RamUsage::where('ram_id', $this->id)
                ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                ->orderBy('created_at')
                ->get(['created_at', 'usage'])
                ->map(function ($item) {
                    return [
                        'x' => $item->created_at->format('Y-m-d H:i:s'),
                        'y' => $item->usage,
                    ];
                });

            $this->raw_logs = RamUsage::where('ram_id', $this->id)
                ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                ->orderBy('created_at')
                ->select('usage AS data', 'created_at')
                ->get();
            $this->raw_label = "RAM Usage";
        }

        $trendLine = $this->calculateTrendLine($this->trend_data);
        $this->description = $this->generateTrendDescription($trendLine);
        $this->dispatch('refreshCharts', raw_data: $this->raw_logs, trend_line: $trendLine, raw_data_label: $this->raw_label);
    }

    private function calculateTrendLine($data)
    {
        $n = count($data);
        if ($n < 2) {
            return []; // Not enough data points to calculate trend line
        }

        // Calculate the means of x and y
        $sumX = 0;
        $sumY = 0;
        foreach ($data as $point) {
            $sumX += strtotime($point['x']);
            $sumY += $point['y'];
        }

        $meanX = $sumX / $n;
        $meanY = $sumY / $n;

        // Calculate the slope (m) and intercept (b) for y = mx + b
        $numerator = 0;
        $denominator = 0;
        foreach ($data as $point) {
            $x = strtotime($point['x']);
            $y = $point['y'];
            $numerator += ($x - $meanX) * ($y - $meanY);
            $denominator += pow($x - $meanX, 2);
        }

        $slope = $denominator != 0 ? $numerator / $denominator : 0;
        $intercept = $meanY - $slope * $meanX;

        // Generate trend line data based on the calculated slope and intercept
        $trendLine = [];
        foreach ($data as $point) {
            $x = strtotime($point['x']);
            $y = $slope * $x + $intercept;
            $trendLine[] = [
                'x' => date('Y-m-d H:i:s', $x),
                'y' => $y,
            ];
        }

        return $trendLine;
    }
    private function generateTrendDescription($trendLine)
    {
        if (count($trendLine) < 2) {
            return "No sufficient data to determine trend.";
        }
    
        $firstPoint = reset($trendLine);
        $lastPoint = end($trendLine);
    
        $startDate = date('M d, Y \a\t h:iA', strtotime($firstPoint['x']));
        $endDate = date('M d, Y \a\t h:iA', strtotime($lastPoint['x']));
        $this->trend_start_date = $startDate;
        $this->trend_end_date = $endDate;
    
        $change = $lastPoint['y'] - $firstPoint['y'];
        $threshold = 10; // Define what constitutes a 'slight' change
    
        if ($change > $threshold) {
            return "The {$this->raw_label} is showing an increase from $startDate to $endDate.";
        } elseif ($change > 0 && $change <= $threshold) {
            return "The {$this->raw_label} is showing a slight increase from $startDate to $endDate.";
        } elseif ($change < 0 && $change >= -$threshold) {
            return "The {$this->raw_label} is showing a slight decrease from $startDate to $endDate.";
        } elseif ($change < -$threshold) {
            return "The {$this->raw_label} is showing a decrease from $startDate to $endDate.";
        } else {
            return "The {$this->raw_label} shows no significant change from $startDate to $endDate.";
        }
    }
    
    

    public function save()
    {
        TrendLog::create([
            'device_id' => $this->device_id,
            'type' => $this->trend_type,
            'component' => $this->type,
            'start_datetime' =>$this->trend_start_date,
            'end_datetime' => $this->trend_end_date,
            'description' => $this->description,
        ]);


        $this->dispatch('closeModalTrend');
    }

    public function render()
    {
        return view('livewire.components.computer.trend-modal')->with('trend_data', $this->trend_data);
    }
}
