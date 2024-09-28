<?php

use Livewire\Volt\Component;
use App\Models\Laboratory;
new class extends Component {
    public $labs;
    
    protected function getListeners()
    {
        return [
            'delete-laboratory' => 'deleteLab',
            'search-lab' => 'searchLab',
            'add-laboratory-success' => 'reloadData',
            'update-laboratory-success' => 'reloadData',
        ];
    }
    public function mount()
    {
        $this->labs = Laboratory::withCount('users')->withCount('computerDevices')->get();
    }
    public function reloadData()
    {
        $this->labs = Laboratory::withCount('users')->withCount('computerDevices')->get();
    }
    public function deleteLab($lab_id)
    {
        $lab = Laboratory::find($lab_id);

        if ($lab) {
            $lab->delete();
            $this->dispatch('delete-laboratory-alert', status: 'success');
            $this->labs = Laboratory::withCount('users')->get();
        } else {
            $this->dispatch('delete-laboratory-alert', status: 'fail');
        }
    }
    public function searchLab($searchVal)
    {
        $this->labs = Laboratory::withCount('users')
            ->where(function ($query) use ($searchVal) {
                $query->where('laboratory_name', 'like', '%' . $searchVal . '%')->orWhere('capacity', 'like', '%' . $searchVal . '%');
            })
            ->get();
    }
}; ?>

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
    @foreach ($labs as $lab)
        <div class="col-md-4">
            <div class="card card-flush h-md-100">
                <!--begin::Card header-->
                <div class="card-header">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h2>{{ $lab->laboratory_name }}</h2>
                    </div>
                    <!--end::Card title-->
                </div>
                <div class="pt-1 card-body">
                    <div class="mb-5 text-gray-600 fw-bold">Total assistant assign this lab: {{ $lab->users_count }}
                    </div>
                    <div class="text-gray-600 d-flex flex-column">
                        <div class="py-2 d-flex align-items-center">
                            <span class="bullet bg-primary me-3"></span>
                            {{ $lab->computer_devices_count }} 
                            {{ Str::plural('Computer', $lab->computer_devices_count) }}
                        </div>
                        
                        <div class="py-2 d-flex align-items-center">
                            <span class="bullet bg-primary me-3"></span> {{ $lab->capacity }} Capacity
                        </div>
                        <div class="py-2 d-flex align-items-center">
                            <span class="bullet bg-primary me-3"></span> Created at
                            {{ $lab->created_at ? $lab->created_at->format('d M Y, h:i a') : 'None' }}
                        </div>
                        <div class="py-2 d-flex align-items-center">
                            <span
                                class="bullet bg-primary me-3"></span>{{ $lab->updated_at != $lab->created_at ? 'Updated at ' . $lab->updated_at->format('d M Y, h:i a') : 'Not Yet Updated' }}
                        </div>
                    </div>
                </div>
                <div class="flex-wrap pt-0 card-footer">
                    <button class="my-1 btn btn-light btn-active-primary"
                        wire:click="$dispatch('viewlab_modal', {'lab_id': {{ $lab->id }}})">View</button>
                    <button type="button" class="my-1 btn btn-light btn-active-light-primary"
                        wire:click="$dispatch('editlab_modal', {'lab_id': {{ $lab->id }}})">Edit</button>
                    <button type="button" class="my-1 btn btn-light btn-active-light-danger"
                        wire:click="$dispatch('delete-lab-confirmation', {'lab_id': {{ $lab->id }}})">Delete</button>

                </div>
            </div>
        </div>
    @endforeach


</div>
