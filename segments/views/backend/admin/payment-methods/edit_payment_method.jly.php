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
            <h6>Edit Payment Method</h6>
          </div>
        </div>
      </div>
      <div class="card-body">
      <form method="post" action="{{ route('admin.settings.payment-methods.edit-payment-method-post') }}" enctype="multipart/form-data">
        {{ prevent_csrf() }}
        <input type="hidden" name="payment_id" value="{{$payment->id}}"> 
        <div class="form-group">
            <label>New Users</label>
            <select class="form-control" name="new_users" id="new_users" required>
              <option value="">Select</option>
              <option {{(!empty($paymentMethod->new_users) && $paymentMethod->new_users == 1) ? 'selected' : '' }} value="1">Allowed</option>
              <option {{(isset($paymentMethod->new_users) && $paymentMethod->new_users == 0) ? 'selected' : '' }} value="0">Not Allowed</option>
            </select>
        </div>

        <div class="form-group">
            <label>Transaction Fee</label>
            <input type="text" name="transaction_fee" id="transaction_fee" class="form-control" required value="{{!empty($paymentMethod->transaction_fee) ? $paymentMethod->transaction_fee : ''}}">
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
