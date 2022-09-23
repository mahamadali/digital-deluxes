@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
@endblock

@block("content")

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2">
            <h6>Edit Coupon</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
      <form method="post" action="{{ route('admin.coupons.update', ['coupon' => $coupon->id]) }}" enctype="multipart/form-data">
        {{ prevent_csrf() }}
        
        <div class="form-group">
            <label>CODE <small>Should be unique</small></label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $coupon->code }}">
        </div>

        <div class="form-group">
            <label>Condition <small>Leave empty if no condition</small></label>
            <select class="form-control" name="condition" id="condition">
              <option value="">None</option>
              <option {{ $coupon->condition == '>=' ? 'selected' : '' }} value=">=">Greater than or equal to</option>
              <option {{ $coupon->condition == '<=' ? 'selected' : '' }} value="<=">Lower than or equal to</option>
            </select>
        </div>

        <div class="form-group">
            <label>Price Limit <small>IN EUR</small></label>
            <input type="text" name="price_limit" id="price_limit" class="form-control" value="{{ $coupon->price_limit }}">
        </div>

        <div class="form-group">
            <label>Percentage</label>
            <input type="number" name="percentage" id="percentage" min="1" max="100"  class="form-control" value="{{ $coupon->percentage }}">
        </div>

        <div class="form-group">
            <label>Total Activation Usage <small>Leave empty if no usage limit</small></label>
            <input type="number" name="activation_count" id="activation_count" class="form-control" value="{{ $coupon->activation_count }}">
        </div>

        <div class="row">
          <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Submit</button>
              <a href="{{ route('admin.coupons.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endblock