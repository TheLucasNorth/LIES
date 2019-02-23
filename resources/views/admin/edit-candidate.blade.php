@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit candidate</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('candidates.update', ['candidate' => $candidate->id]) }}">
                            @csrf
                            @method('put')
                            <div class="form-group row">
                                <label for="name" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="name" name="name" value="{{ $candidate->name }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="image" class="col-sm-4 col-form-label">Link to picture (optional, must be full link including http(s)://)</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="image" name="image" value="{{ $candidate->image }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="manifesto" class="col-sm-4 col-form-label">Manifesto</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" id="manifesto" name="manifesto">{{ $candidate->manifesto }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="withdrawn" class="col-sm-4 col-form-label">Check box if candidate has withdrawn</label>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="form-control" id="withdrawn" name="withdrawn" @if($candidate->withdrawn === 1)checked @endif >
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Candidate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
