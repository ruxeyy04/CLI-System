
<div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
    <button  class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100" wire:click="logout" wire:loading.attr="disabled">

        <span class="btn-label" wire:loading.remove>
            Logout
        </span>
        <span class="btn-label" wire:loading>
            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
</div>
