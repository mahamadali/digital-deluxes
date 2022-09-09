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
      <div class="row">
          <div class="col-md-4">
            
          </div>
          <div class="col-md-8 text-right">
            <a href="#" class="btn btn-primary delete_orders_btn mb-1" style="display:none;"><i class="ti-trash"></i>Delete</a>
          </div>

        </div>
      <div class="table-responsive">
        <table id="order-listing" class="table">
          <thead>
            <tr>
                <td>#</td>
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
                        <td><input type="checkbox" name="delete_order" id="delete_order" class="delete_order" value="{{$order->id}}"></td>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->fullName }} #{{ $order->user->id }}</td>
                        <td>{{ $order->kg_orderid }}</td>
                        <td>{{ $order->transaction_id }}</td>
                        <td>${{ number_format($order->order_amount, 2) }}</td>
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

  $(document).ready(function() {
    $('.delete_order').on('change',function(){
      if($('.delete_order:checked').length > 0) {
        $('.delete_orders_btn').show();
      } else {
        $('.delete_orders_btn').hide();
      }
    });

    $('.delete_orders_btn').on('click', function() {
      var orderIds = [];
      $(".delete_order:checked").each(function ()
      {
        orderIds.push(parseInt($(this).val()));
      });
      
      $.ajax({
        url : '{{ route("admin.orders.delete") }}',
        type : 'POST',
        data: {
          'prevent_csrf_token': '{{ prevent_csrf_token() }}',
          'orderIds': orderIds,
        },
        dataType: 'json',
        success: function(response) {
          toastr.success(response.msg);
          location.reload();
        }
      });  

    })
  });
</script>
@endblock