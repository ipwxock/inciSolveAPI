<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Issue::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'insurance_id' => 'required|exists:insurances,id',
            'subject' => 'required|string',
            'status' => 'required|in:Abierta,Cerrada,Pendiente',
        ]);

        $issue = Issue::create($validatedData);

        return response()->json($issue, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        return response()->json($issue, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Issue $issue)
    {
        $validatedData = $request->validate([
            'insurance_id' => 'exists:insurances,id',
            'subject' => 'string',
            'status' => 'in:Abierta,Cerrada,Pendiente',
        ]);

        $issue->update($validatedData);

        return response()->json($issue, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return response()->json(null, 204);
    }
}
