@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")

@endblock

@block("content")

<div class="row">
  <div class="col-md-12">
    <div class="card card-inverse-light-with-black-text flatten-border">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2">
            <h6>Settings</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
      <form method="post" action="{{ route('admin.settings.update') }}">
        {{ prevent_csrf() }}
        @foreach($settings as $setting):
          
          <div class="row mb-3">
            <div class="col-md-4">
              {{ ucwords(str_replace('_', ' ', $setting->key)) }}
              <input type="hidden" class="form-control" name="setting_ids[]" value="{{ $setting->id }}" >
              <input type="hidden" class="form-control" name="setting_keys[]" value="{{ $setting->key }}" >
            </div>

            <div class="col-md-8">
              <input type="text" class="form-control" name="setting_values[]" value="{{ $setting->value }}" >
            </div>
          </div>
          
        @endforeach 
        <div class="row">
          <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endblock


@block("scripts")
@endblock