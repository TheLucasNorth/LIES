@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit your details</div>

                    <div class="card-body">
                        <form method="post" action="{{ route('users.update', ['user' => \Illuminate\Support\Facades\Auth::user()]) }}">
                            @csrf
                            @method('patch')
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="plainName" value="{{\Illuminate\Support\Facades\Auth::user()->name}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="votingCode" class="col-sm-4 col-form-label">Username</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="votingCode" name="username" value="{{\Illuminate\Support\Facades\Auth::user()->votingCode}}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-4 col-form-label">Password<br>(you must enter a password, if you do not wish to change your password then you should enter your current password)</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update your details</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
