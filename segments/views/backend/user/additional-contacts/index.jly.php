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
          <h4 class="card-title">My Contacts</h4>
        </div>
        <div class="col">
          <a class="btn btn-md btn-primary float-right" href="{{ route('user.additional-contacts.create') }}">
            Add Contact
          </a>
        </div>
      </div>
        <table class="table">
          <thead>
            <tr>
              <th>Full Name</th>
              <th>Email</th>
              <th>Contact No.</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @if ($totalContacts > 0):
              @foreach ($additionalContacts as $contact):
              <tr>
                <td>{{ $contact->full_name }}</td>
                <td>{{ $contact->email }}</td>
                <td>{{ $contact->contact }}</td>
                <td>
                  <a href="{{ url('user/additional-contacts/edit/'.$contact->id) }}" class="btn btn-sm btn-info">
                    <span><i class="ti-pencil"></i></span>
                  </a>
                  <form method="post" action="{{ route('user.additional-contacts.delete', ['contact' => $contact->id]) }}" class="d-inline-block">
                    <input type="hidden" name="_method" value="DELETE" />
                    <button type="submit" class="btn btn-sm btn-danger">
                      <span><i class="ti-trash"></i></span>
                      </a>
                  </form>
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

@endblock

@block("scripts")
@endblock