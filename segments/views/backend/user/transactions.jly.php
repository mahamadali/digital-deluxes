@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
@endblock

@block("content")

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      <div class="row">
        <div class="col-md-2">
          <h4 class="card-title">My Transactions</h4>
        </div>
      </div>
        <table class="table">
          <thead>
            <tr>
              <th>TX ID</th>
              <th>Subscriber Id</th>
              <th>Payment Method</th>
              <th>Payment Status</th>
              <th>Plan</th>
              <th>Interval</th>
              <th>Start</th>
              <th>End</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @if ($totalTransactions > 0):
              @foreach ($transactions->get() as $transaction):
              <tr>
                <td>{{ $transaction->txn_id }}</td>
                <td>{{ $transaction->paypal_subscr_id }}</td>
                <td>{{ $transaction->payment_method }}</td>
                <td>{{ $transaction->payment_status }}</td>
                <td>{{ $transaction->plan()->title }}</td>
                <td>{{ $transaction->subscr_interval_count }} {{ $transaction->subscr_interval }}</td>
                <td>{{ date('M d, Y', strtotime($transaction->valid_from)) }}</td>
                <td>{{ date('M d, Y', strtotime($transaction->valid_to)) }}</td>
                <td>{{ $transaction->plan()->price }} {{ $transaction->currency_code }}</td>
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="9" class="text-center text-muted">No data found</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

@endblock

@block("scripts")
@endblock