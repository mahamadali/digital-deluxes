@extends('backend/app')

@block("title") {{ setting('app.title') }} @endblock

@block("styles")
@endblock

@block("content")

  <div class="card card-inverse-light-with-black-text flatten-border">
    <div class="card-header">
      Create Practice Area
    </div>
    <div class="card-body">
      <form method="post" action="{{ route('admin.practice_area.store') }}">
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label> Title</label>
              <input type="text" class="form-control" name="title" value="{{ old('title') }}" />
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