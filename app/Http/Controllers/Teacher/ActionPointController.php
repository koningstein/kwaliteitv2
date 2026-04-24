<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ActionPointController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-action-points'),
        ];
    }

    public function index(Request $request)
    {
        $user   = auth()->user();
        $teamId = $user?->teams->first()?->id;

        $isGlobalViewer = $user?->hasRole(['ok_medewerker', 'directie']);
        $isMedewerker   = $user?->hasRole('medewerker');

        $filter   = $request->query('filter', 'all');
        $statuses = ActionPointStatus::all();

        $query = ActionPoint::with([
            'status',
            'user',
            'criterion.standard.theme',
            'evaluations' => fn ($q) => $q->orderBy('created_at', 'desc'),
        ])->orderBy('end_date');

        // Scoping
        if ($isMedewerker) {
            // Medewerker ziet alleen eigen toegewezen actiepunten
            $query->where('user_id', $user->id);
        } elseif (!$isGlobalViewer && $teamId) {
            // Kwaliteitszorg / onderwijsleider ziet alleen eigen team
            $query->where('team_id', $teamId);
        }

        if ($filter !== 'all') {
            $status = $statuses->firstWhere('id', $filter);
            if ($status) {
                $query->where('action_point_status_id', $status->id);
            }
        }

        $actionPoints = $query->get();

        return view('teacher.action-points.index', [
            'actionPoints' => $actionPoints,
            'statuses'     => $statuses,
            'filter'       => $filter,
        ]);
    }
}
