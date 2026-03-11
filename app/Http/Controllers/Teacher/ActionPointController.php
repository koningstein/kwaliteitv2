<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActionPoint;
use App\Models\ActionPointStatus;
use Illuminate\Http\Request;

class ActionPointController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $statuses = ActionPointStatus::all();

        $query = ActionPoint::with([
            'status',
            'user',
            'criterion.standard.theme',
            'evaluations' => fn ($q) => $q->orderBy('created_at', 'desc'),
        ])->orderBy('end_date');

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
