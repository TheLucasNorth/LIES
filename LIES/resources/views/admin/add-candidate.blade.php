@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create candidate</div>
                    @php($election = filter_var($_GET['election'], FILTER_SANITIZE_NUMBER_INT))
                    <div class="card-body">
                        <form method="post" action="{{ route('candidates.store') }}">
                            @csrf
                            <input type="hidden" name="election_id" value="{{ $election }}">
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="image" class="col-sm-4 col-form-label">Link to picture (optional, must be full link including http(s)://)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="image" name="image">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="manifesto" class="col-sm-4 col-form-label">Manifesto</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="manifesto" name="manifesto"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Candidate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
