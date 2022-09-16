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
          <h4 class="card-title">Transactions</h4>
        </div>
      </div>
      <div class="row">
          <div class="col-md-4">
            
          </div>
          <div class="col-md-8 text-right">
            <a href="#" class="btn btn-primary delete_transactions_btn mb-1" style="display:none;"><i class="ti-trash"></i>Delete</a>
          </div>

        </div>
      <div class="table-responsive">
        <table id="transaction-listing" class="table">
          <thead>
            <tr>
                <td>#</td>
                <td>ID</td>
                <td>User</td>
                <td>TX ID</td>
                <td>Currency</td>
                <td>Type</td>
                <td>Amount</td>
                <td>Payment Method</td>
                <td>Kind of Tx</td>
                <td>Created At</td>
            </tr>
          </thead>
          <tbody>
            @foreach($transactions as $transaction):
                    <tr>
                        <td><input type="checkbox" name="delete_transaction" id="delete_transaction" class="delete_transaction" value="{{$transaction->id}}"></td>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->user->fullName }} #{{ $transaction->user->id ?? '' }}</td>
                        <td>{{ $transaction->tx_id }}</td>
                        <td>{{ $transaction->currency }}</td>
                        <td>${{ $transaction->type }}</td>
                        <td>${{ $transaction->amount }}</td>
                        <td>{{ $transaction->payment_method }}</td>
                        <td>{{ $transaction->kind_of_tx }}</td>
                        <td>{{ date('M d, Y, H:i', strtotime($transaction->created_at)) }}</td>
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
    var table = $('#transaction-listing').DataTable({
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
      .appendTo('#transaction-listing_wrapper .col-md-6:eq(0)');
  });

  $(document).ready(function() {
    $("#transaction-listing tbody").on("change", ".delete_transaction", function() {
      if($('.delete_transaction:checked').length > 0) {
        $('.delete_transactions_btn').show();
      } else {
        $('.delete_transactions_btn').hide();
      }
    });

    $('.delete_transactions_btn').on('click', function() {
      var transactionIds = [];
      $(".delete_transaction:checked").each(function ()
      {
        transactionIds.push(parseInt($(this).val()));
      });
      
      $.ajax({
        url : '{{ route("admin.transactions.delete") }}',
        type : 'POST',
        data: {
          'prevent_csrf_token': '{{ prevent_csrf_token() }}',
          'transactionIds': transactionIds,
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