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
          <h4 class="card-title">My Orders</h4>
        </div>
      </div>
      <div class="table-responsive">
        <table id="order-listing" class="table">
          <thead>
            <tr>
                <td>Order #</td>
                <td>User</td>
                <td>Kinguin Order #</td>
                <td>Transaction #</td>
                <td>Amount</td>
                <td>Status</td>
                <td>Type</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
          </thead>
          <tbody>
            @foreach($orders as $order):
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->fullName }} #{{ $order->user->id }}</td>
                        <td>{{ $order->kg_orderid }}</td>
                        <td>{{ $order->transaction_id }}</td>
                        <td>${{ $order->order_total() }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->order_type }}</td>
                        <td>{{ date('M d, Y, H:i', strtotime($order->created_at)) }}</td>
                        <td>
                            <a href="{{ route('admin.orders.view', ['order' => $order->id]) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                @endforeach
          </tbody>
        </table>
      </div>
      </div>
    </div>
  </div>
</div>

@endblock

@block("scripts")
<script>
  $(document).ready(function() {
    var table = $('#order-listing').DataTable({
      order: [0, 'desc'],
      lengthChange: false,
      buttons: [{
        extend: 'csv',
        exportOptions: {
          columns: [0, 1, 2, 3, 4]
        }
      }]
    });

    table.buttons().container()
      .appendTo('#order-listing_wrapper .col-md-6:eq(0)');
  });
</script>
@endblock