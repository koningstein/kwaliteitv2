<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionPointStatusStoreRequest;
use App\Http\Requests\ActionPointStatusUpdateRequest;
use App\Models\ActionPointStatus;

class ActionPointStatusController extends Controller
{
    public function index()
    {
        return view('admin.action-point-statuses.index');
    }

    public function create()
    {
        return view('admin.action-point-statuses.create');
    }

    public function store(ActionPointStatusStoreRequest $request)
    {
        ActionPointStatus::create($request->validated());

        return redirect()->route('admin.action-point-statuses.index')
            ->with('success', 'Status succesvol aangemaakt.');
    }

    public function edit(ActionPointStatus $actionPointStatus)
    {
        return view('admin.action-point-statuses.edit', compact('actionPointStatus'));
    }

    public function update(ActionPointStatusUpdateRequest $request, ActionPointStatus $actionPointStatus)
    {
        $actionPointStatus->update($request->validated());

        return redirect()->route('admin.action-point-statuses.index')
            ->with('success', 'Status succesvol bijgewerkt.');
    }

    public function destroy(ActionPointStatus $actionPointStatus)
    {
        $actionPointStatus->delete();

        return redirect()->route('admin.action-point-statuses.index')
            ->with('success', 'Status succesvol verwijderd.');
    }
}
