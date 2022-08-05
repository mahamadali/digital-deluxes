@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
@endblock

@block("content")

  <div class="card card-inverse-light-with-black-text flatten-border">
    <div class="card-header">
      Edit Contact
    </div>
    <div class="card-body">
      <form method="post" action="{{ route('user.additional-contacts.update', ['contact' => $contact->id]) }}" id="create-additional-contact-form" enctype="multipart/form-data">
      <div class="row">
          <div class="col">
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" name="full_name" class="form-control" value="{{ $contact->full_name }}" required/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control" value="{{ $contact->email }}" required/>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="form-group">
              <label>Contact</label>
              <input type="text" name="contact" class="form-control" value="{{ $contact->contact }}" required/>
            </div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col">
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg">Save</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

@endblock

@block("scripts")
@endblock