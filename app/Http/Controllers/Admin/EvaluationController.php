<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluationStoreRequest;
use App\Http\Requests\EvaluationUpdateRequest;
use App\Models\ActionPoint;
use App\Models\Evaluation;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EvaluationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:edit-action-points', except: ['index']),
        ];
    }

    public function index()
    {
        $this->authorize('viewAny', Evaluation::class);

        return view('admin.evaluations.index');
    }

    public function create()
    {
        $this->authorize('create', Evaluation::class);

        $actionPoints = ActionPoint::with(['criterion.standard', 'team'])
            ->orderBy('team_id')
            ->orderBy('criterion_id')
            ->get();

        return view('admin.evaluations.create', compact('actionPoints'));
    }

    public function store(EvaluationStoreRequest $request)
    {
        $this->authorize('create', Evaluation::class);

        Evaluation::create($request->validated());

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluatie succesvol aangemaakt.');
    }

    public function edit(Evaluation $evaluation)
    {
        $this->authorize('update', $evaluation);

        $actionPoints = ActionPoint::with(['criterion.standard', 'team'])
            ->orderBy('team_id')
            ->orderBy('criterion_id')
            ->get();

        return view('admin.evaluations.edit', compact('evaluation', 'actionPoints'));
    }

    public function update(EvaluationUpdateRequest $request, Evaluation $evaluation)
    {
        $this->authorize('update', $evaluation);

        $evaluation->update($request->validated());

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluatie succesvol bijgewerkt.');
    }

    public function destroy(Evaluation $evaluation)
    {
        $this->authorize('delete', $evaluation);

        $evaluation->delete();

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluatie succesvol verwijderd.');
    }
}
