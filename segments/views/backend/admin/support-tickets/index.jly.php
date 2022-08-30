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
          <h4 class="card-title">Support Tickets</h4>
        </div>
      </div>
      <div class="row">
      <div class="col-md-12 grid-margin transparent">
        <div class="row">
          <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
              <div class="card-body">
                <p class="mb-4">Pending</p>
                <p class="fs-30 mb-2">{{ $pendingTickets }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
              <div class="card-body">
                <p class="mb-4">Completed</p>
                <p class="fs-30 mb-2">{{ $completedTickets }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
              <div class="card-body">
                <p class="mb-4">Answered</p>
                <p class="fs-30 mb-2">{{ $answeredTickets }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-4 stretch-card transparent">
            <div class="card card-tale">
              <div class="card-body">
                <p class="mb-4">All</p>
                <p class="fs-30 mb-2">{{ $allTickets }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table id="order-listing" class="table">
          <thead>
            <tr>
                <td>Ticket #</td>
                <td>User</td>
                <td>Title #</td>
                <td>Status</td>
                <td>Is Read?</td>
                <td>Created At</td>
                <td>Action</td>
            </tr>
          </thead>
          <tbody>
            @foreach($tickets as $ticket):
                    <tr>
                        <td>{{ $ticket->id }}</td>
                        <td>{{ $ticket->user->fullName }} #{{ $ticket->user->id }}</td>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->status }}</td>
                        <td>{{ $ticket->is_read }}</td>
                        <td>{{ date('M d, Y, H:i', strtotime($ticket->created_at)) }}</td>
                        <td>
                            <a href="{{ route('admin.support-tickets.view', ['ticket' => $ticket->id]) }}" class="btn btn-sm btn-info">View</a>
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