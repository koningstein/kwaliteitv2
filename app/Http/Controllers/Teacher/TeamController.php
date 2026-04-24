<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeamController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-team-dashboard'),
        ];
    }

    public function index()
    {
        $user   = auth()->user();
        $teamId = $user?->teams->first()?->id;
        $isGlobalViewer = $user?->hasRole(['ok_medewerker', 'directie']);

        $query = User::with([
            'actionPoints.status',
            'actionPoints.criterion.standard.theme',
            'teams',
        ])->withoutTrashed();

        if (!$isGlobalViewer && $teamId) {
            $query->whereHas('teams', fn ($q) => $q->where('teams.id', $teamId));
        }

        $users = $query->get()->sortBy('name');

        return view('teacher.team.index', [
            'users' => $users,
        ]);
    }
}
