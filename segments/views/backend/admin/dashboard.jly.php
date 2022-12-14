@extends('backend/app')

@block("title") {{ setting('app.title') }} @endblock

@block("styles")

@endblock

@block("content")

<div class="row">
  <div class="col-md-12 grid-margin">
    <div class="row">
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-bold">Welcome {{ auth()->full_name }}</h3>
        <h6 class="font-weight-normal mb-0">All systems are running smoothly!
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 grid-margin transparent">
    <div class="row">
      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Total Users</p>
            <p class="fs-30 mb-2">{{ $total_users }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Kinguin Balance</p>
            <p class="fs-30 mb-2">€{{ number_format($kinguin_balance, 2) }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Total Products</p>
            <p class="fs-30 mb-2">{{ $products }}</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Total Orders</p>
            <p class="fs-30 mb-2">{{ $orders }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Total Users Balance</p>
            <p class="fs-30 mb-2">€{{ !empty($total_wallet_amount) ? $total_wallet_amount : 0.00 }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Today Profit</p>
            <p class="fs-30 mb-2">€{{ !empty($today_profit) ? $today_profit : 0.00 }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Monthly Profit</p>
            <p class="fs-30 mb-2">€{{ !empty($monthly_profit) ? $monthly_profit : 0.00 }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-tale">
          <div class="card-body">
            <p class="mb-4">Total Profit</p>
            <p class="fs-30 mb-2">€{{ !empty($total_profit) ? $total_profit : 0.00 }}</p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
@endblock

@block("scripts")

@endblock