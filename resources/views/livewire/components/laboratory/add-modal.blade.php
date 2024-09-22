<?php

use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Laboratory;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
new class extends Component {
    #[Validate]
    public $laboratory_name;
    #[Validate]
    public $capacity;
    public $assistants;
    public $searchVal;
    #[Validate]
    public $selectedUsers = [];

    public function rules()
    {
        return [
            'laboratory_name' => ['required', 'string', 'max:255', 'unique:' . Laboratory::class],
            'capacity' => ['required', 'integer', 'max:255'],
            'selectedUsers' => ['required', 'array', 'min:1'],
        ];
    }
    public function messages()
    {
        return [
            'selectedUsers.required' => 'You should assign a laboratory assistant.',
            'selectedUsers.min' => 'You should assign at least one laboratory assistant.',
        ];
    }
    public function mount()
    {
        $this->assistants = User::where('role', 'assistant')->get();
    }
    public function discardForm(): void
    {
        $this->searchVal = '';
        $this->capacity = '';
        $this->laboratory_name = '';
        $this->selectedUsers = [];
        $this->resetValidation();
    }
    public function updatedSearchVal()
    {
        $searchTerms = explode(' ', $this->searchVal);

        $users = User::where('role', 'assistant');

        foreach ($searchTerms as $term) {
            $users->where(function ($query) use ($term) {
                $query
                    ->where('fname', 'like', '%' . $term . '%')
                    ->orWhere('lname', 'like', '%' . $term . '%')
                    ->orWhere('username', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%');
            });
        }

        $this->assistants = $users->get();
    }
    public function saveLaboratory()
    {
        $validated = $this->validate();

        $laboratory = Laboratory::create([
            'laboratory_name' => $this->laboratory_name,
            'capacity' => $this->capacity,
        ]);

        foreach ($this->selectedUsers as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->laboratory_id = $laboratory->id;
                $user->save();
            }
        }
        $this->dispatch('add-laboratory-success');
        $this->discardForm();
    }
};
?>

<div class="modal fade" id="addlab_modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog mw-500px">
        <div class="modal-content">
            <div class="modal-header" id="addlab_modal_header">
                <h2 class="fw-bold">Add Laboratory</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <div class="px-5 modal-body my-7">
                <div class="px-5 d-flex flex-column px-lg-10" wire:ignore.self>
                    <form action="">
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Laboratory Name</label>
                            <input type="text" name="laboratory_name"
                                class="mb-3 form-control form-control-solid mb-lg-0" placeholder="Laboratory Name"
                                wire:model.live='laboratory_name' />
                            @error('laboratory_name')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Capacity</label>
                            <input type="text" name="capacity" class="mb-3 form-control form-control-solid mb-lg-0"
                                placeholder="Capacity" wire:model.live='capacity' />
                            @error('capacity')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-10">
                            <div class="mb-2 fs-6 fw-semibold required">Select Computer Lab Assistant</div>
                            <input type="text" autocomplete="off"
                                class="mb-4 form-control form-control-solid flex-grow-1" name="search"
                                placeholder="Search User" wire:model.live='searchVal'>
                            @error('selectedUsers')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div class="mh-250px scroll-y">
                                @php
                                    $colors = [
                                        ['bg-light-danger', 'text-danger'],
                                        ['bg-light-success', 'text-success'],
                                        ['bg-light-info', 'text-info'],
                                        ['bg-light-warning', 'text-warning'],
                                        ['bg-light-primary', 'text-primary'],
                                    ];
                                @endphp
                                @foreach ($assistants as $assistant)
                                    @php
                                        $randomColor = $colors[array_rand($colors)];
                                    @endphp
                                    <div
                                        class="py-4 border-gray-300 d-flex flex-stack border-bottom border-bottom-dashed">
                                        <!--begin::Details-->
                                        <div class="d-flex align-items-center">
                                            <!--begin::Avatar-->
                                            <div class="symbol symbol-35px symbol-circle">
                                                @if ($assistant->profileimg)
                                                    <img alt="{{ $assistant->fname }} {{ $assistant->lname }}"
                                                        src="{{ asset('storage/profile/' . $assistant->id . '/' . $assistant->profileimg) }}">
                                                @else
                                                    <div
                                                        class="symbol-label fs-3 {{ $randomColor[0] }} {{ $randomColor[1] }}">
                                                        {{ strtoupper($assistant->fname[0]) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <!--end::Avatar-->

                                            <!--begin::Details-->
                                            <div class="ms-5">
                                                <a href="#"
                                                    class="mb-2 text-gray-900 fs-5 fw-bold text-hover-primary">
                                                    {{ $assistant->fname }} {{ $assistant->lname }}
                                                </a>
                                                <div class="fw-semibold text-muted">{{ $assistant->email }}</div>
                                            </div>
                                        </div>
                                        <div class="me-3">
                                            <input wire:key="assistant-{{ $assistant->id }}"
                                                class="form-check-input h-20px w-20px" type="checkbox"
                                                name="selecteduser[]" value="{{ $assistant->id }}"
                                                wire:model.live="selectedUsers"
                                                @if (in_array($assistant->id, $selectedUsers)) checked @else @endif>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>



                    </form>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    wire:click='discardForm'>Discard</button>
                <button type="button" class="btn btn-primary" wire:click='saveLaboratory' wire:loading.attr='disabled'
                    wire:target="saveLaboratory">
                    <span wire:loading.remove wire:target="saveLaboratory">Save Laboratory</span>
                    <span wire:loading wire:target="saveLaboratory">
                        Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                    </span>

                </button>
            </div>
        </div>

    </div>
</div>
