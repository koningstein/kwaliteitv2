<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionManager extends Component
{
    public array $confirmingDelete = [];

    public function togglePermission(int $permissionId, int $roleId): void
    {
        abort_unless(auth()->user()->hasPermissionTo('manage-permissions'), 403);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role       = Role::findOrFail($roleId);
        $permission = Permission::findOrFail($permissionId);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
        } else {
            $role->givePermissionTo($permission);
        }
    }

    public function startConfirmDelete(int $permissionId): void
    {
        abort_unless(auth()->user()->hasPermissionTo('manage-permissions'), 403);
        $this->confirmingDelete[$permissionId] = true;
    }

    public function cancelDelete(int $permissionId): void
    {
        unset($this->confirmingDelete[$permissionId]);
    }

    public function render()
    {
        $roles = Role::orderBy('name')->get()->load('permissions');

        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->map(function ($permission) use ($roles) {
                $permission->roleIds = $roles
                    ->filter(fn($role) => $role->permissions->contains('id', $permission->id))
                    ->pluck('id')
                    ->toArray();

                $permission->roleNames = $roles
                    ->filter(fn($role) => $role->permissions->contains('id', $permission->id))
                    ->pluck('name')
                    ->toArray();

                return $permission;
            });

        return view('livewire.admin.permission-manager', [
            'permissions' => $permissions,
            'roles'       => $roles,
        ]);
    }
}
