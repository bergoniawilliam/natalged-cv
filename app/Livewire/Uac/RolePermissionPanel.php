<?php

namespace App\Livewire\Uac;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionPanel extends Component
{
    public $roles = [];
    public $permissions = [];

    public $selectedRole = null;
    public $rolePermissions = [];

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
        $this->permissions = Permission::orderBy('name')->get();
    }

    public function updatedSelectedRole()
    {
        if (!$this->selectedRole) {
            $this->rolePermissions = [];
            return;
        }

        $role = Role::with('permissions')
            ->find($this->selectedRole);

        $this->rolePermissions = $role
            ? $role->permissions->pluck('name')->values()->toArray()
            : [];
    }

    public function save()
    {
        if (!$this->selectedRole) return;

        $role = Role::findById($this->selectedRole);

        $role->syncPermissions($this->rolePermissions);

        session()->flash('success', 'Permissions updated successfully!');
    }

    public function render()
    {
        return view('livewire.uac.role-permission-panel');
    }
}