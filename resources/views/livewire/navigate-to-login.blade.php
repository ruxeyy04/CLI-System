<div>
    <button class="btn btn-primary" wire:click="login" wire:loading.attr="disabled">
        <span wire:loading.remove>Login</span>
        <span wire:loading>
            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
</div>
