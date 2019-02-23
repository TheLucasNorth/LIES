<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Election;
use Illuminate\Http\Request;
use vendor\project\StatusTest;

class CandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add-candidate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $candidate = new Candidate();
        $candidate->name = $request->name;
        $candidate->image = $request->image;
        $candidate->manifesto = $request->manifesto;
        $candidate->election_id = $request->election_id;
        $candidate->withdrawn = 0;
        $candidate->save();
        return redirect(route('elections.edit', ['election' => $candidate->election_id]))->with(['adminStatus' => 'Candidate created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show(Candidate $candidate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidate)
    {
        return view('admin.edit-candidate', compact('candidate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        if($request->withdrawn === 'on'){$withdrawn = 1;}else{$withdrawn = 0;};
        $candidate = Candidate::find($candidate->id);
        $candidate->name = $request->name;
        $candidate->image = $request->image;
        $candidate->manifesto = $request->manifesto;
        $candidate->withdrawn = $withdrawn;
        $candidate->save();
        return redirect(route('elections.edit', ['election' => $candidate->election_id]))->with(['adminStatus' => 'Candidate updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        //
    }
}
