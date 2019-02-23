@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">View voters</div>

                    <div class="card-body">
                        <h5>Users List</h5>
                        Here you can view all registered voting codes. Please scroll down to add more users. If you would like to export a CSV of all voting codes and security codes, <a href="export-users">click here.</a> Please note that admin codes are not included.
                        <table class="table">
                            <tr>
                                <th>Voting Code</th>
                                <th>Security Code</th>
                                <th>Has Voted?</th>
                            </tr>
                            @foreach($users as $voter)
                                <tr>
                                    <td>{{$voter->votingCode}}</td>
                                    <td>{{$voter->passwordPlain}}</td>
                                    <td>{{$voter->hasVoted}}</td>
                                </tr>
                            @endforeach
                        </table>


                        {{ $users->links() }}
                        <h5>Create new users:</h5>
                        <form method="post" action="{{ route('users.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="usersCreate" class="col-sm-6 col-form-label">Number of users to create</label>
                                <div class="col-sm-6">
                                    <input type="number" class="form-control" id="usersCreate" name="numberUsers">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
