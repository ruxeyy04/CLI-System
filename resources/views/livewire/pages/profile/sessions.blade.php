<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.assistant')] class extends Component {};
?>
<div id="kt_app_content_container" class="app-container  container-xxl">
    <livewire:components.profile.usercardheader />
    <livewire:components.profile.navitems />


</div>
