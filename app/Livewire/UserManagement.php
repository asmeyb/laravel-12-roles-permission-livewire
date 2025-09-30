<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserManagement extends Component
{
    use WithPagination;

    public $userId, $name, $email, $password;
    public $selectedRoles = [];

    public $isEditing = false;

    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'selectedRoles' => 'required|array',
    ];

    public function create()
    {
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('user.edit');
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.user-management');
    }
}
