@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $election->plainName }}</div>

                    <div class="card-body">
                        <h6>{{ $election->description }}</h6><br>

                        @if($election->candidates()->count() >= 1)
                        The candidates are as follows:

                        @foreach($candidates as $candidate)
                            <div class="col-sm-12" style="justify-content: center; -webkit-justify-content: center; display: flex; display: -webkit-flex">
                                <div class="card" style="width: 90%; ">
                                    <div class="row no-gutters">
                                        @if($candidate->image)
                                        <div class="col-md-4">
                                            <img src="{{ $candidate->image }}" class="card-img" alt="Candidate photo">
                                        </div>
                                        <div class="col-md-8">
                                        @else
                                           <div class="col-md-12">
                                        @endif
                                                <div class="card-body">
                                                <h5 class="card-title">{{ $candidate->name }}</h5>
                                                <p class="card-text" style="white-space: pre-line">Manifesto:<br>{{ $candidate->manifesto }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                        @endforeach
                                @if(\App\Vote::where('votingCode', Auth::user()->votingCode)->where('election_id', $election->id)->count() != 0)
                                    <br><h5>You've already voted in this election!</h5>
                                    You can update your vote by voting again, below. You should place candidates so that the candidate you want to win the most is ranked as number 1, and rank candidates in descending order of preference.
                                @else
                                    <br>You may cast your vote by ranking the candidates in order of preference below. You should place candidates so that the candidate you want to win the most is ranked as number 1, and rank candidates in descending order of preference.
                                @endif


                                <br><br><div id="election" style="display: -webkit-flex; display: flex; -webkit-justify-content: center; justify-content: center; justify-items: center">
                                    {{--<p>Please note that candidates are shown in a random order in each dropdown box, so as to discourage 'donkey voting'.</p>--}}
                                    <form method="post" action="{{ route('vote.store') }}" class="col-md-6" id="voteForm">
                                        @csrf
                                        <input type="hidden" name="election_id" value="{{ $election->id }}">
                                        <input type="hidden" name="votingCode" value="{{ \Illuminate\Support\Facades\Auth::user()->votingCode }}">
                                        @foreach($candidates as $candidate)
                                            <div class="row no-gutters">
                                                <label class="col-sm-2" for="voteSelect{{ $loop->iteration }}">{{ $loop->iteration }}</label>
                                                <select class="form-control col-sm-10 test" id="voteSelect{{ $loop->iteration }}" form="voteForm" name="{{ $loop->iteration }}">
                                                    <option value="">No preference selected</option>
                                                    @if(\App\Meta::find(5)->value == '1')
                                                        @foreach($candidates->shuffle() as $candidate)
                                                            <option value="{{$candidate->id}}">{{$candidate->name}}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach($candidates as $candidate)
                                                            <option value="{{$candidate->id}}">{{$candidate->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        @endforeach
                                        <br>
                                        <button type="submit" class="btn btn-primary btn-block">Cast Vote</button>
                                    </form>
                        @else
                            There are no candidates in this election. Please contact your returning officer for assistance.
                        @endif

                    </div>
                            </div>
                </div>
            </div>
        </div>
    </div>
    </div>
        @verbatim
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $(".test").each(function () {
                    var $self = $(this);
                    $self.data("previous_value", $self.val());
                });

                $(".test").on("change", function () {
                    var $self = $(this);
                    var prev_value = $self.data("previous_value");
                    var cur_value = $self.val();

                    $(".test").not($self).find("option").filter(function () {
                        return $(this).val() == prev_value;
                    }).prop("disabled", false);

                    if (cur_value != "") {
                        $(".test").not($self).find("option").filter(function () {
                            return $(this).val() == cur_value;
                        }).prop("disabled", true);

                        $self.data("previous_value", cur_value);
                    }
                });
            });
            </script>
        @endverbatim
@endsection