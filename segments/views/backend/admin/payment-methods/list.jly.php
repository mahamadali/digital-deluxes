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
    <div class="card card-inverse-light-with-black-text flatten-border">
      <div class="card-body">
        <div class="row"> 
          <div class="col-md-2">
            <h6>Payment Methods</h6>
          </div>
        </div>
        <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if (count($payment_methods) > 0):
              @foreach ($payment_methods as $payment_method):
              <tr>
                <td>{{ $payment_method->title }}</td>
                <td>{{ $payment_method->type }}</td>
                <td>
                  <input type="checkbox" class="pm-status" data-id="{{ $payment_method->id }}" {{ $payment_method->status == 'ACTIVE' ? 'checked' : '' }} data-toggle="toggle" data-on="ACTIVE" data-off="INACTIVE" data-onstyle="success" data-offstyle="danger">
                </td>
              </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="text-center text-muted">No data found</td>
              </tr>
            @endif
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
    $('.pm-status').change(function() {
      if(this.checked) {
        var status = 'ACTIVE';
      } else {
        var status = 'INACTIVE';
      }
      $.ajax({
                url : '{{ route("admin.settings.payment-methods.change-status") }}',
                type : 'POST',
                data: {
                    'prevent_csrf_token': '{{ prevent_csrf_token() }}',
                    'status': status,
                    'id': $(this).data('id')
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