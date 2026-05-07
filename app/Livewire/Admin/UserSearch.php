<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UserSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedRoleFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when(strlen($this->search) >= 2, fn($q) =>
                $q->where(function ($q2) {
                    $q2->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                })
            )
            ->when($this->roleFilter !== '', fn($q) =>
                $q->whereHas('roles', fn($q2) => $q2->where('name', $this->roleFilter))
            )
            ->with(['roles', 'teams'])
            ->orderBy('name')
            ->paginate(15);

        $roles = Role::orderBy('name')->get();

        return view('livewire.admin.user-search', compact('users', 'roles'));
    }
}
