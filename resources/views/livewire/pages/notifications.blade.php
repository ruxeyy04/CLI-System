<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use App\Models\Laboratory;
use App\Models\ComputerDevice;

new #[Layout('layouts.assistant')] class extends Component {
    //
}; ?>

<div>
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <!--begin::Card-->
        <div class="card">
            <div class="pt-6 border-0 card-header">
                <livewire:components.notifications.search />
            </div>
            <div class="py-4 card-body">
                <livewire:components.notifications.table>
            </div>
        </div>
    </div>
</div>
