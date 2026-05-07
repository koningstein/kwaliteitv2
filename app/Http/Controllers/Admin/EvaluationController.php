<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluationStoreRequest;
use App\Http\Requests\EvaluationUpdateRequest;
use App\Models\ActionPoint;
use App\Models\Evaluation;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EvaluationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('view-action-points'), only: ['index']),
            new Middleware(PermissionMiddleware::using('edit-action-points'), only: ['create', 'store', 'edit', 'update']),
            new Middleware(PermissionMiddleware::using('delete-action-points'), only: ['destroy']),
        ];
    }

    public function index()
    {
        return view('admin.evaluations.index');
    }

    public function create()
    {
        $actionPoints = ActionPoint::with(['criterion.standard', 'team'])
            ->orderBy('team_id')
            ->orderBy('criterion_id')
            ->get();

        return view('admin.evaluations.create', compact('actionPoints'));
    }

    public function store(EvaluationStoreRequest $request)
    {
        Evaluation::create($request->validated());

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluatie succesvol aangemaakt.');
    }

    public function edit(Evaluation $evaluation)
    {
        $actionPoints = ActionPoint::with(['criterion.standard', 'team'])
            ->orderBy('team_id')
            ->orderBy('criterion_id')
            ->get();

        return view('admin.evaluations.edit', compact('evaluation', 'actionPoints'));
    }

    public function update(EvaluationUpdateRequest $request, Evaluation $evaluation)
    {
        $evaluation->update($request->validated());

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluatie succesvol bijgewerkt.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluatie succesvol verwijderd.');
    }
}
