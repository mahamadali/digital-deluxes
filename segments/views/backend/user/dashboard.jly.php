@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

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
            <p class="mb-4">My Items</p>
            <p class="fs-30 mb-2">{{ $items }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-light-blue">
          <div class="card-body">
            <p class="mb-4">Total Transactions</p>
            <p class="fs-30 mb-2">{{ $transactions }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-dark-blue">
          <div class="card-body">
            <p class="mb-4">Current Plans</p>
            <p class="fs-30 mb-2">{{ $userPlans }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4 stretch-card transparent">
        <div class="card card-light-blue">
          <div class="card-body">
            <p class="mb-4">Additional Contacts</p>
            <p class="fs-30 mb-2">{{ $contacts }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endblock

@block("scripts")

@endblock