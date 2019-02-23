@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Update site data</div>

                    <div class="card-body">
                       <form method="post" action="{{ route('meta.store') }}">
                            @csrf
                           <div class="form-group row">
                               <label for="meta1" class="col-sm-3 col-form-label">Site Name</label>
                               <div class="col-sm-9">
                                   <input type="text" class="form-control" id="meta1" name="meta1" value="{{ \App\Meta::find(1)->value }}">
                               </div>
                           </div>
                           <div class="form-group row">
                               <label for="privacy" class="col-sm-3 col-form-label">URL to Privacy Policy</label>
                               <div class="col-sm-9">
                                   <input type="text" class="form-control" id="meta2" name="meta2" value="{{ \App\Meta::find(2)->value }}">
                               </div>
                           </div>
                           <div class="form-group row">
                               <label for="meta3" class="col-sm-3 col-form-label">Imprint (optional)</label>
                               <div class="col-sm-9">
                                   <input type="text" class="form-control" id="meta3" name="meta3" value="{{ \App\Meta::find(3)->value }}">
                               </div>
                           </div>
                           <div class="form-group row">
                               <label for="meta4" class="col-sm-3 col-form-label">Privacy Policy (optional, will populate "site/privacy")</label>
                               <div class="col-sm-9">
                                   <textarea class="form-control" id="meta4" name="meta4">{{ \App\Meta::find(4)->value }}</textarea>
                               </div>
                           </div>
                           <div class="form-group row">
                               <label for="meta5" class="col-sm-3 col-form-label">Shuffle candidates when voting?</label>
                               <div class="col-sm-9">
                                   <input type="checkbox" class="form-control" id="meta5" name="meta5" @if( \App\Meta::find(5)->value == '1') checked @endif>
                               </div>
                           </div>
                           <button type="submit" class="btn btn-primary">Save</button>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
