<?php

namespace App\Livewire\Components\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Userstable extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $searchVal = ''; 
    protected $paginationTheme = 'bootstrap';
    public $useridForPassword = '';
    protected function getListeners()
    {
        return [
            'saved-user' => 'reloadUsers',
            'updated-user' => 'reloadUsers',
            'search-user' => 'searchUser',
            'reset-password' => 'resetPassword',
            'delete-user' => 'deleteUser'
        ];
    }

    public function searchUser($searchVal)
    {
        $this->searchVal = $searchVal;
        $this->resetPage();
    }


    public function reloadUsers()
    {
        $this->resetPage();
    }
    // Reset Password
    public function resetPasswordConfirmation($userId)
    {
        $this->dispatch('confirm-reset-password', userId: $userId);
    }
    public function resetPassword($userId)
    {
        $this->useridForPassword = $userId;
        $user = User::find($this->useridForPassword);
        if ($user) {
            $user->password = Hash::make('12345678');;
            $user->save();

            $this->dispatch('reset-password-success');
        }
    }
    // Delete User
    public function deleteUserConfirmation($userId)
    {
        $this->dispatch('confirm-delete-user', userId: $userId);
    }
    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->delete();
            $this->dispatch('delete-user-alert', status: 'success');
        } else {
            $this->dispatch('delete-user-alert', status: 'fail');
        }

    }
// Open Edit Modal
    public function openEditModal($userId) {
        $this->dispatch('openEditUserModal', userId: $userId);
    }
    public function render()
    {
        // Split searchVal into individual words
        $searchTerms = explode(' ', $this->searchVal);

        // Start building the query
        $users = User::query();

        // Apply filters for each search term
        foreach ($searchTerms as $term) {
            $users->where(function ($query) use ($term) {
                $query->where('fname', 'like', '%' . $term . '%')
                    ->orWhere('lname', 'like', '%' . $term . '%')
                    ->orWhere('midname', 'like', '%' . $term . '%')
                    ->orWhere('username', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    ->orWhere('phone', 'like', '%' . $term . '%')
                    ->orWhere('address', 'like', '%' . $term . '%');
            });
        }

        // Paginate the result
        $users = $users->paginate(5);

        return view('livewire.components.users.userstable', [
            'users' => $users
        ]);
    }
}
