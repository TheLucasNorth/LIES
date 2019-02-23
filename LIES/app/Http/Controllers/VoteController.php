<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Election;
use App\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->except('store');
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       /*  */

       $vote = $_POST;
       unset($vote['_token']); unset($vote['election_id']); unset($vote['votingCode']);
       $voteStorage = implode(" ", $vote);

        if($saveVote = Vote::updateOrCreate(
            ['votingCode' => $request->votingCode, 'election_id' => $request->election_id],
            ['vote' => $voteStorage]
        )) {
            $updateVoted = \App\User::where('votingCode', $request->votingCode);
            $updateVoted->update(['hasVoted' => '1']);
            return redirect('elections')->with(['voteStatus' => 'Vote cast! You can update your vote by voting again in the same election.']);
        }
        else {
            return redirect('elections')->with(['voteStatus' => 'Vote could not be recorded. Please try again or contact the returning officer for assistance.']);
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vote  $vote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vote $vote)
    {
        //
    }

    public function export(Election $election)
    {
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=LIES result export - $election->plainName.blt");
       $candidates = $election->candidates()->select('name', 'id', 'withdrawn')->get()->keyBy('id');
       $votes = $election->votes()->select('vote', 'id')->get();
       $keyedCandidates = $candidates->mapWithKeys(function ($keyedCandidates) {
        return [$keyedCandidates['id'] => $keyedCandidates['name']];
    });
       $candidateIDs[0] = '';
        foreach ($keyedCandidates as $key => $value) {
            $candidateIDs[] = $key;
       }
        $keyVotes = $votes->mapWithKeys(function ($keyVotes) {
            return [$keyVotes['id'] => $keyVotes['vote']];
        });
        $numberCandidates = $election->candidates()->count();
        $implosion[] = "$numberCandidates $election->seats # Number of candidates in the election and number of seats to be elected";
        $withdrawn = $election->candidates()->select('id')->where('withdrawn', 1)->get()->keyBy('id');
        if ($withdrawn->count() > 0) {
            $keyedWithdrawn = $withdrawn->mapWithKeys(function ($keyedWithdrawn) {
                return [$keyedWithdrawn['id'] => $keyedWithdrawn['name']];
            })->toArray();
            foreach ($keyedWithdrawn as $key => $value) {
                $displayWithdrawn[] = "-$key";
            }
            $implosion[] = "# Withdrawn candidates (the number corresponds to where they are in the list at the end of the file, and is the order the candidates were added to the database)";
            $implosion[] = implode(" ", $displayWithdrawn);
        }
        $implosion[] = "# Votes, expressed as a '1' to start each line, the candidates in preference order, and a '0' to finish each line.";
        $voteFinal = $keyVotes->toArray();
        foreach ($voteFinal as $key => $value) {
            $exploded[$key] = explode(" ", $value);
            foreach ($exploded[$key] as $item) {
                    $newVote = array_search($item, $candidateIDs);
                    if ($newVote !== 0) {
                        $voteReferenced[$key][] = $newVote;
                    }
            }
            $voteSaved[$key] = implode(" ", $voteReferenced[$key]);
            $implosion[] = "1 $voteSaved[$key] 0";
        }
        $implosion[] = "# End of votes is marked with a line containing a single 0";
        $implosion[] = 0;
        $implosion[] = "# Candidate names, in the order that candidates are listed in the database";
        foreach ($keyedCandidates as $candidate) {
            $implosion[] = "\"$candidate\"";
        }
        $implosion[] = "# The plaintext name of the election, as set on the election setup page";
        $implosion[] = "\"$election->plainName\"";
        $display = implode("\n", $implosion);
        echo $display;


    }
}
