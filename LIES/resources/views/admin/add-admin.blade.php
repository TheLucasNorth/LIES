@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create admin user</div>

                    <div class="card-body">
                        <form method="post" action="{{ route('users.storeAdmin') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="votingCode" class="col-sm-4 col-form-label">Username<br>(will be used to log in, must be unique)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="votingCode" name="votingCode" value="{{ $code }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-4 col-form-label">Password<br>(can be changed later)</label>
                                <div class="col-sm-8">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Admin</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
