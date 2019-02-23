@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Admin Dashboard</div>

                <div class="card-body">
                    @if (\Session::has('adminStatus'))
                        <div class="alert alert-primary" role="alert">
                            {{ \Session::get('adminStatus') }}
                        </div>
                    @endif
                    <h4>Update your details</h4>
                    <a href="{{ route('users.edit', ['user' => \Illuminate\Support\Facades\Auth::user()]) }}"><h5>Change your password, name, or login code</h5></a>
                        <br>
                    <h4>Update system details and users</h4>
                    <a href="admin/meta"><h5>Update system data</h5></a>
                    <a href="admin/users"><h5>View voters</h5></a>
                    <a href="{{ route('users.create') }}"><h5>Add admin user</h5></a>
                        <br>
                    <h4>Update elections and candidates, or download results</h4>
                        <a href="{{ route('elections.create') }}"><h5>Add new election</h5></a>
                    @foreach($elections as $election)
                        <a href="{{ route('elections.edit', ['election' => $election->id]) }}"><h5>{{ $election->plainName }}</h5></a>
                    @endforeach
                    <br><h4>Stats and system info</h4>
                    <h5>{{ \App\Vote::count() }} votes have been cast, by {{ \App\User::where('hasVoted', '1')->count() }} voters</h5>
                    <h5>There are {{ \App\User::count() }} total registered users, and {{ \App\User::where('admin', 1)->count() }} users with admin rights</h5>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
