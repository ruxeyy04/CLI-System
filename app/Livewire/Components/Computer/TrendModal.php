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
    protected function getListeners() {
        return [
            'generate-trend-modal' => 'openModalTrend'
        ];
    }
    public function mount ($device) {
        $this->device_id = $device->id;
    }
    public function updatedType () {
        if($this->type = 'temp' && $this->type == 'cpu') {
            $this->min_date = CpuTemp::min('created_at');
            $this->max_date = CpuTemp::max('created_at');
    
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        } elseif($this->type = 'utilization' && $this->type == 'cpu') {
            $this->min_date = CpuUtilization::min('created_at');
            $this->max_date = CpuUtilization::max('created_at');
    
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        }

        if($this->type = 'temp' && $this->type == 'gpu') {
            $this->min_date = GpuTemp::min('created_at');
            $this->max_date = GpuTemp::max('created_at');
    
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        } elseif($this->type = 'usage' && $this->type == 'gpu') {
            $this->min_date = GpuUsage::min('created_at');
            $this->max_date = GpuUsage::max('created_at');
    
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        }
    }
    public function openModalTrend($id, $type) {
        $this->id = $id;
        $this->type = $type;

        if ($type === 'cpu') {
            $this->trend_type = 'utilization'; 
            $this->min_date = CpuUtilization::min('created_at');
            $this->max_date = CpuUtilization::max('created_at');
    
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        } elseif ($type === 'ram') {
            $this->trend_type = 'usage';
            $this->min_date = RamUsage::min('created_at');
            $this->max_date = RamUsage::max('created_at');
    
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
        } elseif ($type === 'gpu') {
            $this->trend_type = 'usage'; 
            $this->min_date = GpuUsage::min('created_at');
            $this->max_date = GpuUsage::max('created_at');
    
            $this->start_datetime = now()->format('Y-m-d H:i');
            $this->end_datetime = now()->addHour()->format('Y-m-d H:i');
            
        }

        $this->dispatch('openModalTrend');
    }
    public function generateTrend()
    {
        // Initialize trend data and logs
        $this->trend_data = [];
        $this->raw_logs = []; // This will hold the real logs
    
        // Fetch data based on type and trend type
        if ($this->type === 'cpu') {
            if ($this->trend_type === 'utilization') {
                $this->trend_data = CpuUtilization::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'util'])
                    ->map(function($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->util,
                        ];
                    });
    
                // Fetch raw logs for CPU utilization
                $this->raw_logs = CpuUtilization::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get();
            } elseif ($this->trend_type === 'temp') {
                $this->trend_data = CpuTemp::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'temp'])
                    ->map(function($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->temp,
                        ];
                    });
    
                // Fetch raw logs for CPU temperature
                $this->raw_logs = CpuTemp::where('cpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get();
            }
        } elseif ($this->type === 'gpu') {
            if ($this->trend_type === 'usage') {
                $this->trend_data = GpuUsage::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'usage'])
                    ->map(function($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->usage,
                        ];
                    });
    
                // Fetch raw logs for GPU usage
                $this->raw_logs = GpuUsage::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get();
            } elseif ($this->trend_type === 'temp') {
                $this->trend_data = GpuTemp::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get(['created_at', 'temp'])
                    ->map(function($item) {
                        return [
                            'x' => $item->created_at->format('Y-m-d H:i:s'),
                            'y' => $item->temp,
                        ];
                    });
    
                // Fetch raw logs for GPU temperature
                $this->raw_logs = GpuTemp::where('gpu_id', $this->id)
                    ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                    ->orderBy('created_at')
                    ->get();
            }
        } elseif ($this->type === 'ram') {
            $this->trend_data = RamUsage::where('ram_id', $this->id)
                ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                ->orderBy('created_at')
                ->get(['created_at', 'usage'])
                ->map(function($item) {
                    return [
                        'x' => $item->created_at->format('Y-m-d H:i:s'),
                        'y' => $item->usage,
                    ];
                });
    
            // Fetch raw logs for RAM usage
            $this->raw_logs = RamUsage::where('ram_id', $this->id)
                ->whereBetween('created_at', [$this->start_datetime, $this->end_datetime])
                ->orderBy('created_at')
                ->get();
        }
    
        // Calculate the trend line data
        $trendLine = $this->calculateTrendLine($this->trend_data);
    
        // Dispatch event to refresh the chart with the new data, including the trend line
        $this->dispatch('refreshCharts', raw_data: $this->raw_logs, trend_line: $trendLine);
    }
    
    // Helper method to calculate the trend line (linear regression)
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
    
    
    public function save() {
        TrendLog::create([
            'device_id' => $this->device_id,
            'type' => $this->trend_type,
            'component' => $this->type,
            'start_datetime' => $this->start_datetime,
            'end_datetime' => $this->end_datetime,
            'description' => $this->description,
        ]);


        $this->dispatch('refreshCharts');
        
        $this->dispatch('closeModalTrend');
    }

    public function render()
    {
        return view('livewire.components.computer.trend-modal')->with('trend_data', $this->trend_data);
    }
}
