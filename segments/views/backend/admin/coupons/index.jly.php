@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<style>
  .toggle-group .btn {
    font-size: 14px;
  }
  .toggle-off.btn {
      padding-left: 1rem;
  }
</style>
@endblock

@block("content")

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
      <div class="row">
        <div class="col-md-2">
          <h4 class="card-title">Coupons</h4>
        </div>
      </div>
      <div class="row">
          <div class="col-md-4">
            
          </div>
          <div class="col-md-8 text-right">
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-info mb-1" style="background-color: #4B49AC;"><i class="ti-plus"></i> Create Coupon</a>
          </div>

        </div>
      <div class="table-responsive">
        <table id="coupon-listing" class="table">
          <thead>
            <tr>
                <td>#</td>
                <td>CODE</td>
                <td>Condition</td>
                <td>Price Limit</td>
                <td>Percentage</td>
                <td>No of activatation</td>
                <td>Status</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
          </thead>
          <tbody>
            @foreach($coupons as $coupon):
                    <tr>
                        <td>{{ $coupon->id }}</td>
                        <td>{{ $coupon->code }}</td>
                        <td>{{ $coupon->condition }}</td>
                        <td>{{ $coupon->price_limit }}</td>
                        <td>{{ $coupon->percentage }}</td>
                        <td>{{ $coupon->activation_count }}</td>
                        <td>
                        <input type="checkbox" class="coupon-status" data-id="{{ $coupon->id }}" data-href="{{ route('admin.coupons.change-status', ['coupon' => $coupon->id]) }}" {{ $coupon->status == 'ACTIVE' ? 'checked' : '' }} data-toggle="toggle" data-on="ACTIVE" data-off="INACTIVE" data-onstyle="success" data-offstyle="danger">
                        </td>
                        <td>{{ date('M d, Y, H:i', strtotime($coupon->created_at)) }}</td>
                        <td>
                          <a href="{{ route('admin.coupons.edit', ['coupon' => $coupon->id]) }}" class="btn btn-sm btn-info"><i class="ti-pencil"></i></a>
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
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>
  $(document).ready(function() {
    // var table = $('#coupon-listing').DataTable({
    //   order: [0, 'desc'],
    //   lengthChange: false,
    //   buttons: [{
    //     extend: 'csv',
    //     exportOptions: {
    //       columns: [0, 1, 2, 3, 4]
    //     }
    //   }]
    // });

    // table.buttons().container()
    //   .appendTo('#coupon-listing_wrapper .col-md-6:eq(0)');

      $('.coupon-status').change(function() {
        if(this.checked) {
          var status = 'ACTIVE';
        } else {
          var status = 'INACTIVE';
        }
        $.ajax({
            url : $(this).data('href'),
            type : 'POST',
            data: {
                'prevent_csrf_token': '{{ prevent_csrf_token() }}',
                'status': status,
            },
            dataType: 'json',
            success: function(response) {
                toastr.success(response.message);
            }
        });
      });
  });
</script>
@endblock