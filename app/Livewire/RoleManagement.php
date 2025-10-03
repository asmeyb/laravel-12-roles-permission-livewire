<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $roleId;
    public $name;
    public $selectedPermissions = [];
    public $isEditing = false;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'selectedPermissions' => 'array',
    ];
    public function create()
    {
        $this->authorize('role.create');
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->authorize('role.edit');
        $role = Role::with('permissions')->findOrFail($id);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->roleId = null;
        $this->name = '';
        $this->selectedPermissions = [];
        $this->isEditing = false;
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $this->authorize('role.view');
        return view('livewire.role-management', [
            'roles' => Role::withCount('users')->paginate(10),
            'permissions' => Permission::all()->groupBy(function($permission) {
                return explode('.', $permission->name)[0]; // Group by the first part of the permission name
            }),
        ]);
    }
}
