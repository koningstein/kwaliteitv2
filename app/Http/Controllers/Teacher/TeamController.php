<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        $users = User::with([
            'actionPoints.status',
            'actionPoints.criterion.standard.theme',
        ])->get()->sortBy('name');

        return view('teacher.team.index', [
            'users' => $users,
        ]);
    }
}
