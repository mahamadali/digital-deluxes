@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")

@endblock

@block("content")

<div class="row">
  <div class="col-md-12">
    <div class="card card-inverse-light-with-black-text flatten-border">
      <div class="card-header">
        <div class="row">
          <div class="col-md-2">
            <h6>Members</h6>
          </div>
        </div>
      </div>
      <div class="card-body">


        <div class="table-responsive">
          <table id="user-listing" class="table no-footer">
            <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Create At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              @if ($totalUsers > 0):
              @foreach ($users as $user):
              <tr>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->email }}</td>

                <td>{{ date('M d, Y H:i', strtotime($user->created_at)) }}</td>

                <td>
                  <a href="{{ url('admin/users/view/'.$user->id) }}" class="btn btn-sm btn-success">
                    <span><i class="ti-eye"></i></span>
                  </a>
                </td>
              </tr>
              @endforeach
              @else
              <tr>
                <td colspan="4" class="text-center text-muted">No data found</td>
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
<script>
  $(document).ready(function() {
    var table = $('#user-listing').DataTable({
      lengthChange: false,
      buttons: [{
        extend: 'csv',
        exportOptions: {
          columns: [0, 1, 2, 3, 4]
        }
      }]
    });

    table.buttons().container()
      .appendTo('#user-listing_wrapper .col-md-6:eq(0)');
  });
</script>
@endblock