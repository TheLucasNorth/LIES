<?php

namespace App\Http\Controllers;

use App\Election;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $elections = Election::where([
            ['voting_start', '<=', now()],
            ['voting_end', '>', now()],
        ])->get();
        return view('home', ['elections' => $elections]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add-election');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $election = new Election();
        $election->plainName = $request->plainName;
        $election->description = $request->description;
        $election->seats = $request->seats;
        $election->voting_start = Carbon::parse($request->votingStart);
        $election->voting_end = Carbon::parse($request->votingEnd);
        $election->save();
        return redirect('/admin')->with(['adminStatus' => 'Election created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function show(Election $election)
    {
        return view('election')->withElection($election)->withCandidates($election->candidates()->where('withdrawn', '=', 0)->get());
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function edit(Election $election)
    {
        return view('admin.edit-election')->withElection($election);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Election $election)
    {
        $election = Election::find($election->id);
        $election->plainName = $request->plainName;
        $election->description = $request->description;
        $election->seats = $request->seats;
        $election->voting_start = Carbon::parse($request->votingStart);
        $election->voting_end = Carbon::parse($request->votingEnd);
        $election->save();
        return redirect('/admin')->with(['adminStatus' => 'Election updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function destroy(Election $election)
    {
        //
    }
}
