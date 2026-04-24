<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThemeStoreRequest;
use App\Http\Requests\ThemeUpdateRequest;
use App\Models\Theme;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ThemeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:manage-themes', except: ['index']),
        ];
    }

    public function index()
    {
        return view('admin.themes.index');
    }

    public function create()
    {
        return view('admin.themes.create');
    }

    public function store(ThemeStoreRequest $request)
    {
        Theme::create($request->validated());

        return redirect()->route('admin.themes.index')
            ->with('success', 'Thema succesvol aangemaakt.');
    }

    public function edit(Theme $theme)
    {
        return view('admin.themes.edit', compact('theme'));
    }

    public function update(ThemeUpdateRequest $request, Theme $theme)
    {
        $theme->update($request->validated());

        return redirect()->route('admin.themes.index')
            ->with('success', 'Thema succesvol bijgewerkt.');
    }

    public function destroy(Theme $theme)
    {
        if (!$theme->is_deletable) {
            return redirect()->route('admin.themes.index')
                ->with('error', 'Dit thema kan niet verwijderd worden.');
        }

        $theme->delete();

        return redirect()->route('admin.themes.index')
            ->with('success', 'Thema succesvol verwijderd.');
    }
}
