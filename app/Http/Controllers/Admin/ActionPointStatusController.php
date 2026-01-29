<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActionPointStatusStoreRequest;
use App\Http\Requests\ActionPointStatusUpdateRequest;
use App\Models\ActionPointStatus;

class ActionPointStatusController extends Controller
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
    public function store(ActionPointStatusStoreRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ActionPointStatus $actionPointStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActionPointStatus $actionPointStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActionPointStatusUpdateRequest $request, ActionPointStatus $actionPointStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActionPointStatus $actionPointStatus)
    {
        //
    }
}
