<?php
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
new class extends Component {
    #[Validate] 
    public $first_name;
    #[Validate] 
    public $last_name;
    #[Validate] 
    public $username;
    #[Validate] 
    public $email;
    #[Validate] 
    public $user_role;
    
    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'user_role' => ['required', 'string', 'in:assistant,incharge'],
        ];
    }
    public function discardForm (): void
    {
        $this->reset();
    }
    public function saveUser(): void
    {
        $user = new User;
        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'user_role' => ['required', 'string', 'in:assistant,incharge'],
        ]);
        $user->fname = $validated['first_name'];
        $user->lname = $validated['last_name'];
        $user->role = $validated['user_role'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = Hash::make('12345678');

        $user->save();
        $this->dispatch('saved-user');
        $this->reset(); 
    }
}; ?>

<div class="modal fade" id="adduser_modal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="adduser_modal_header">
                <h2 class="fw-bold">Add User</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
                <div class="px-5 modal-body my-7">
                    <div class="px-5 d-flex flex-column scroll-y px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
                    data-kt-scroll-offset="300px" wire:ignore.self>
                    <form action="">
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">First Name</label>
                            <input type="text" name="first_name" class="mb-3 form-control form-control-solid mb-lg-0"
                                placeholder="First Name" wire:model.live='first_name' />
                            @error('first_name')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Last Name</label>
                            <input type="text" name="last_name" class="mb-3 form-control form-control-solid mb-lg-0"
                                placeholder="Last Name" wire:model.live='last_name' />
                            @error('last_name')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Username</label>
                            <input type="text" name="username" class="mb-3 form-control form-control-solid mb-lg-0"
                                placeholder="Username" wire:model.live='username' />
                            @error('username')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="mb-2 required fw-semibold fs-6">Email</label>
                            <input type="email" name="email" class="mb-3 form-control form-control-solid mb-lg-0"
                                placeholder="Email Address" wire:model.live='email' />
                            @error('email')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <label class="mb-5 required fw-semibold fs-6">Role</label>
                            <div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input me-3" name="user_role" wire:model.live='user_role'
                                        type="radio" value="incharge" id="kt_modal_update_role_option_0" />
                                    <label class="form-check-label" for="kt_modal_update_role_option_0">
                                        <div class="text-gray-800 fw-bold">Incharge</div>
                                        <div class="text-gray-600">The individual responsible for overseeing the
                                            computer lab and its operations.</div>
                                    </label>
                                </div>
                            </div>
                            <div class='my-5 separator separator-dashed'></div>
                            <div class="d-flex fv-row">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input me-3" name="user_role" wire:model.live='user_role'
                                        type="radio" value="assistant" id="kt_modal_update_role_option_1" />
                                    <label class="form-check-label" for="kt_modal_update_role_option_1">
                                        <div class="text-gray-800 fw-bold">Laboratory Assistant</div>
                                        <div class="text-gray-600">The laboratory assistant provides hands-on support in
                                            the lab.</div>
                                    </label>
                                </div>
                            </div>
                            <div class='my-5 separator separator-dashed'></div>
                            @error('user_role')
                                <div class="fv-plugins-message-container invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                    </form>
                       
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click='discardForm'>Discard</button>
                    <button type="button" class="btn btn-primary" wire:click='saveUser' wire:loading.attr='disabled' wire:target="saveUser">
                        <span wire:loading.remove wire:target="saveUser">Save User</span>
                        <span wire:loading wire:target="saveUser">
                            Please wait... <span class="align-middle spinner-border spinner-border-sm ms-2"></span>
                        </span>

                    </button>
                </div>
        </div>
    </div>
</div>
