@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Elections Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (\Session::has('voteStatus'))
                            <div class="alert alert-primary" role="alert">
                                {{ \Session::get('voteStatus') }}
                            </div>
                    @endif

                    @if(count($elections) > 0)

                    You're all set to start voting! Click on the links below to get started.
                    <ul style="padding-top: 20px">
                    @foreach($elections as $election)
                            <li><a class="h4" style="color: #1d68a7" href="/elections/{{ $election->id }}">{{ $election->plainName }}</a><p>{{ $election->description }}</p></li>
                    @endforeach
                    </ul>
                    @else
                        There aren't any open elections for you to vote in right now.<br>If you think this is a mistake, please contact your returning officer who will be able to advise you.
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
