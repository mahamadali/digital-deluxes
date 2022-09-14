@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")

@endblock

@block("content")

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-2">
            <h6>Members</h6>
          </div>
          <div class="col-md-10 text-right">
            <a href="#" class="btn btn-danger delete_users_btn mb-1" style="display:none;"><i class="ti-trash"></i>Delete</a>
          </div>
        </div>
        <div class="table-responsive">
          <table id="user-listing" class="table no-footer">
            <thead>
              <tr>
                <th></th>
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
                <td><input type="checkbox" name="delete_user" id="delete_user" class="delete_user" value="{{$user->id}}"></td>
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

  $(document).ready(function() {
    $("#user-listing tbody").on("change", ".delete_user", function() {
      if($('.delete_user:checked').length > 0) {
        $('.delete_users_btn').show();
      } else {
        $('.delete_users_btn').hide();
      }
    });

    $('.delete_users_btn').on('click', function() {
      
      if(confirm('Are you sure?')) {
        var userIds = [];
        $(".delete_user:checked").each(function ()
        {
          userIds.push(parseInt($(this).val()));
        });
        
        $.ajax({
          url : '{{ route("admin.users.delete-multiple") }}',
          type : 'POST',
          data: {
            'prevent_csrf_token': '{{ prevent_csrf_token() }}',
            'userIds': userIds,
          },
          dataType: 'json',
          success: function(response) {
            toastr.success(response.msg);
            location.reload();
          }
        }); 
      }

    })
  });
</script>
@endblock