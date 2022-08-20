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
            <h6>Members</h6>
          </div>
        </div>
        <div class="table-responsive">
          <table id="user-listing" class="table no-footer">
            <thead>
              <tr>
                <th>Profile</th>
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
                <td><img src="{{ url($user->profile_image) }}" height="50"></td>
                <td>{{ $user->full_name }}</td>
                <td>{{ $user->email }}</td>

                <td>{{ date('M d, Y H:i', strtotime($user->created_at)) }}</td>

                <td>
                  <a href="{{ url('admin/users/view/'.$user->id) }}" class="btn btn-sm btn-success">
                    <span><i class="ti-eye"></i></span>
                  </a>
                  <a href="{{ url('admin/users/edit/'.$user->id) }}" class="btn btn-sm btn-success">
                    <span><i class="ti-pencil"></i></span>
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