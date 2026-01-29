<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionPointStoreRequest;
use App\Http\Requests\ActionPointUpdateRequest;
use App\Models\ActionPoint;

class ActionPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActionPointStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ActionPoint $actionPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActionPoint $actionPoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActionPointUpdateRequest $request, ActionPoint $actionPoint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActionPoint $actionPoint)
    {
        //
    }
}
