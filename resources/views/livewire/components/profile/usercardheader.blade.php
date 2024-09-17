<?php
use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="card card-flush mb-9" id="kt_user_profile_panel">

    <div class="card-header rounded-top bgi-size-cover h-200px"
        style="background-position: 100% 80%; background-image:url('../build/assets/media/misc/profile-head-bg.png')">
    </div>

    <div class="card-body mt-n19">
        <div class="m-0">
            <div class="d-flex flex-stack align-items-end pb-4 mt-n19">
                <div class="symbol symbol-125px symbol-lg-150px symbol-fixed position-relative mt-n3">
                    <img 
                    x-data="{ profileimg: '{{ Auth::user()->profileimg ? asset('storage/profile/' . Auth::user()->id . '/' . Auth::user()->profileimg) : asset('storage/profile/default.jpg') }}' }"
                    :src="profileimg"
                    alt="Profile Image"
                    style="border-radius: 20px; border: 4px solid #f1f1f4;"
                    x-on:profile-updated.window="profileimg = $event.detail.profileimg">
                

                </div>
            </div>
            <div class="d-flex flex-stack flex-wrap align-items-end">

                <div class="d-flex flex-column">

                    <div class="d-flex align-items-center mb-2">
                        <a href="#" class="text-gray-800 text-hover-primary fs-2 fw-bolder me-1"
                            x-data="{{ json_encode(['fname' => auth()->user()->fname, 'lname' => auth()->user()->lname]) }}" x-text="`${fname} ${lname}`"
                            x-on:profile-updated.window="fname = $event.detail.fname; lname = $event.detail.lname">
                        </a>

                    </div>

                    <span class="fw-bold text-gray-600 fs-6 mb-2 d-block" x-data="{{ json_encode(['motto' => auth()->user()->motto]) }}" x-text="motto"
                        x-on:profile-updated.window="motto = $event.detail.motto">
                    </span>

                    <div class="d-flex align-items-center flex-wrap fw-semibold fs-7 pe-2">
                        <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary">
                            {{ Str::ucfirst(Auth::user()->role) }}
                        </a>
                    </div>
                </div>
                <div class="d-flex">
                    <button class="btn btn-sm btn-light" id="kt_user_follow_button" wire:click='logout'
                        wire:loading.attr='disabled' wire:target="logout">
                        <i class="ki-duotone ki-check fs-2 d-none"></i>

                        <span class="indicator-label" wire:loading.remove wire:target="logout">
                            Logout</span>

                        <span wire:loading wire:target="logout">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
