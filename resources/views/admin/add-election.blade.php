@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add new election</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('elections.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Election Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="plainName" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="description" class="col-sm-4 col-form-label">Election Description</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="seats" class="col-sm-4 col-form-label">Seats</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="seats" name="seats">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="votingStart" class="col-sm-4 col-form-label">Voting Start</label>
                                <div class="col-sm-8">
                                    <input type="datetime-local" class="form-control" id="votingStart" name="votingStart" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="votingEnd" class="col-sm-4 col-form-label">Voting End</label>
                                <div class="col-sm-8">
                                    <input type="datetime-local" class="form-control" id="votingEnd" name="votingEnd" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Create election</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
