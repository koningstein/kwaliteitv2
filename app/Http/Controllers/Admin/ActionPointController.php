<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionPointStoreRequest;
use App\Http\Requests\ActionPointUpdateRequest;
use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use App\Models\Criterion;
use App\Models\Team;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ActionPointController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:edit-action-points', except: ['index']),
        ];
    }
    public function index()
    {
        return view('admin.action-points.index');
    }

    public function create()
    {
        $criteria = Criterion::with('standard.theme')->orderBy('standard_id')->orderBy('number')->get();
        $teams    = Team::orderBy('name')->get();
        $users    = User::orderBy('name')->get();
        $statuses = ActionPointStatus::orderBy('name')->get();

        return view('admin.action-points.create', compact('criteria', 'teams', 'users', 'statuses'));
    }

    public function store(ActionPointStoreRequest $request)
    {
        ActionPoint::create($request->validated());

        return redirect()->route('admin.action-points.index')
            ->with('success', 'Actiepunt succesvol aangemaakt.');
    }

    public function edit(ActionPoint $actionPoint)
    {
        $criteria = Criterion::with('standard.theme')->orderBy('standard_id')->orderBy('number')->get();
        $teams    = Team::orderBy('name')->get();
        $users    = User::orderBy('name')->get();
        $statuses = ActionPointStatus::orderBy('name')->get();

        return view('admin.action-points.edit', compact('actionPoint', 'criteria', 'teams', 'users', 'statuses'));
    }

    public function update(ActionPointUpdateRequest $request, ActionPoint $actionPoint)
    {
        $actionPoint->update($request->validated());

        return redirect()->route('admin.action-points.index')
            ->with('success', 'Actiepunt succesvol bijgewerkt.');
    }

    public function destroy(ActionPoint $actionPoint)
    {
        $actionPoint->delete();

        return redirect()->route('admin.action-points.index')
            ->with('success', 'Actiepunt succesvol verwijderd.');
    }
}
