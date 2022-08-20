@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
<style>
  .card {
    border: 0px solid rgba(0, 0, 0, 0.125) !important;
  }
  .form-group label {
    margin-bottom: 0px !important;
  }
</style>
@endblock

@block("content")

<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title"><img src="{{ url($user->profile_image) }}" height="100"></h4>
        <p class="card-description">
          
        </p>
          <div class="form-group">
            <label for="exampleInputUsername1">Full Name</label>
            <h3 class="text-dark fs-20 font-weight-medium">{{ $user->FullName }}</h3>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <h3 class="text-dark fs-20 font-weight-medium">{{ $user->email }}</h3>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Phone</label>
            <h3 class="text-dark fs-20 font-weight-medium">{{ !empty($user->phone) ? $user->country_code." ".$user->phone : 'N/A' }}</h3>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Age</label>
            <h3 class="text-dark fs-20 font-weight-medium">{{ !empty($user->age) ? $user->age : 'N/A' }}</h3>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Address</label>
            <h3 class="text-dark fs-20 font-weight-medium">{{ !empty($user->address) ? $user->address : 'N/A' }}</h3>
          </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
      <div class="row">
        <div class="col" style="width: 100%;">
          <div class="card card-tale">
            <div class="card-body">
              <p class="mb-4">Total Orders</p>
              <p class="fs-30 mb-2">{{ count($user->orders) }}</p>
            </div>
          </div>
        </div>

        <div class="col" style="width: 100%;">
          <div class="card card-tale">
            <div class="card-body">
              <p class="mb-4">Wishlist Items</p>
              <p class="fs-30 mb-2">{{ count($user->wishlists) }}</p>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>

@endblock

@block("scripts")

@endblock