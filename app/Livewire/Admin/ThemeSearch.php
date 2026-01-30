<?php

namespace App\Livewire\Admin;

use App\Models\Theme;
use Livewire\Component;
use Livewire\WithPagination;

class ThemeSearch extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $themes = Theme::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->withCount('standards')
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.admin.theme-search', compact('themes'));
    }
}
