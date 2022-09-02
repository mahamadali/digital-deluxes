@extends('backend/app')

@block("title") {{ setting('app.title') }} @endblock

@block("styles")
@endblock

@block("content")

  <div class="card card-inverse-light-with-black-text flatten-border">
    <div class="card-header">
      Edit City
    </div>
    <div class="card-body">
      <form method="post" action="{{ route('admin.cities.update') }}">
        <input type="hidden" name="id" value="{{ $city->id }}" />
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label>Name</label>
              <input type="text" class="form-control" name="name" value="{{ $city->name }}" />
            </div>
          </div>
        </div>
        
        <div class="row mt-2">
          <div class="col">
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg">Save</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

@endblock

@block("scripts")
@endblock