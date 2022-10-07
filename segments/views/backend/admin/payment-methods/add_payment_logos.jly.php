@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
<style>
  img {
    max-width: 100%;
    max-height: 100%;
  }
</style>
@endblock

@block("content")

<div class="row">
  <div class="col-md-12">
    <div class="card card-inverse-light-with-black-text flatten-border">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2">
            <h6>Add Logos</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
      <form method="post" action="{{ route('admin.settings.payment-methods.add-payment-logo-post') }}" enctype="multipart/form-data">
        {{ prevent_csrf() }}
        <input type="hidden" name="payment_id" value="{{$payment->id}}"> 
        <div class="form-group">
            <label>Upload Main Logo</label>
            <input type="file" name="main_logo" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Upload little Logos</label>
            <input type="file" name="logo[]" class="form-control" multiple required>
        </div>

        <div class="form-group">
            <label>Uploaded Logos</label>
            <div class="row">
              @if($logos->count() > 0):
              @foreach($logos as $logo):
                <div class="col-md-3">
                    <img src="{{ url($logo->logo) }}"><br>
                    <a href="{{ route('admin.settings.payment-methods.remove-payment-logo', ['logo' => $logo->id]) }}" class="text-danger">Remove</a>
                </div>
              @endforeach
              @else
                <div class="col-md-12">No Logos</div>
              @endif
            </div>
        </div>
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
