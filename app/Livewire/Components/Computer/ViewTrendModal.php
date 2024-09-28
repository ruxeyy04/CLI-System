<?php 
namespace App\Livewire\Components\Computer;

use App\Models\CpuTemp;
use App\Models\CpuUtilization;
use App\Models\GpuTemp;
use App\Models\GpuUsage;
use App\Models\RamUsage;
use App\Models\TrendLog;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class ViewTrendModal extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $id;
    public $hardware_type;
    public $start_datetime;
    public $end_datetime;
    public $trend_type;
    public $description = '';
    public $show_chart;
    public $searchValTrend1 = '';
    public $searchValTrend2 = '';

    public $trend_data;
    public $raw_logs;
    public $raw_label;
    public $trend_start_date;
    public $trend_end_date;
    protected function getListeners()
    {
        return [
            'view-saved-trend' => 'loadSavedTrend',
            'delete-trend' => 'deleteTrend',
            'view-trend' => 'loadTrend'
        ];
    }
    public function loadTrend($id) 
    {
        $trend = TrendLog::find($id);
    
        if ($trend) {
            $this->start_datetime = $trend->start_datetime;
            $this->end_datetime = $trend->end_datetime;
            $this->trend_type= $trend->type;
            $this->description = $trend->description;
        }
        $this->generateTrend();
    }
    public function generateTrend()
    {   

        $this->trend_data = [];
        $this->raw_logs = [];

        if ($this->hardware_type === 'cpu') {
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
        } elseif ($this->hardware_type === 'gpu') {
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
        } elseif ($this->hardware_type === 'ram') {
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
        $this->show_chart = true;
        $trendLine = $this->calculateTrendLine($this->trend_data);
        $this->dispatch('viewTrendChart', raw_data: $this->raw_logs, trend_line: $trendLine, raw_data_label: $this->raw_label);
    }
    public function hideTrendChart() {
        $this->show_chart = false;
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

    public function deleteTrend($id) {
        $trend = TrendLog::find($id);

        if ($trend) {
            $trend->delete();
            $this->dispatch('delete-trend-alert', status: 'success');
            $this->resetPage();
        } else {
            $this->dispatch('delete-trend-alert', status: 'fail');
        }
        $this->show_chart = false;
    }
    public function loadSavedTrend($id, $type) 
    {
        $this->id = $id;
        $this->hardware_type = $type;
        $this->description = '';
    
        $this->resetPage('page1');
        $this->resetPage('page2');
        
        $this->dispatch('openSavedTrendModal');
    }
    

    public function render()
    {
        if ($this->hardware_type === 'cpu') {
            $trend_data1 = TrendLog::where('component', $this->hardware_type)
                ->where('type', 'utilization')
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'page1'); 
    
            $trend_data2 = TrendLog::where('component', $this->hardware_type)
                ->where('type', 'temp')
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'page2');
        } elseif ($this->hardware_type === 'ram') {
            $trend_data1 = TrendLog::where('component', $this->hardware_type)
                ->where('type', 'usage')
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'page1');
    
            $trend_data2 = null; 
        } elseif ($this->hardware_type === 'gpu') {
            $trend_data1 = TrendLog::where('component', $this->hardware_type)
                ->where('type', 'usage')
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'page1');
    
            $trend_data2 = TrendLog::where('component', $this->hardware_type)
                ->where('type', 'temp')
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'page2');
        } else {
            $trend_data1 = collect(); 
            $trend_data2 = collect(); 
        }
    
        return view('livewire.components.computer.view-trend-modal', [
            'trend_data1' => $trend_data1,
            'trend_data2' => $trend_data2,
        ]);
    }
    
    
}
