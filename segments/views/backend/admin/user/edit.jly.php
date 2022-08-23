@extends('backend/app')

@block("title") {{ setting('app.title', 'Quotations') }} @endblock

@block("styles")
<style>
  .card {
    border: 0px solid rgba(0, 0, 0, 0.125) !important;
  }
  .form-group label {
    margin-bottom: 0px !important;
  }
  .iti--separate-dial-code
  {
      width: 100% !important;
  }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
@endblock

@block("content")

<div class="row">
  <div class="col-md-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.users.update', ['user' => $user->id]) }}">
        {{ prevent_csrf() }}
        <h4 class="card-title">
          <img src="{{ url($user->profile_image) }}" height="100">
          <input type="file" class="form-control" id="profile_image" name="profile_image">
        </h4>
        <p class="card-description">
          
        </p>
          <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="{{ $user->first_name }}">
          </div>
          <div class="form-group">
            <label for="exampleInputUsername1">Last Name</label>
            <input type="text" class="form-control" id="exampleInputUsername2" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}">
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $user->email }}">
          </div>
          <div class="form-group">
            <label for="phone">Phone</label>
            <input type="hidden" name="country_code" id="country_code" value="{{ $user->country_code }}">
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" value="{{ $user->phone }}">
          </div>
          <div class="form-group">
            <label for="age">Age</label>
            <input type="text" class="form-control" id="age" name="age" placeholder="Age" value="{{ $user->age }}">
          </div>
          <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ $user->address }}">
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-4">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="status" id="status1" value="Active" {{ ($user->status == 'Active') ? 'checked' : '' }}>
                  Active
                <i class="input-helper"></i></label>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="status" id="status2" value="Deactivate" {{ ($user->status == 'Deactivate') ? 'checked' : '' }}>
                  Deactive
                <i class="input-helper"></i></label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary mr-2">Submit</button>
          <a href="{{ route('admin.users.list') }}" class="btn btn-light">Cancel</a>
      </div>
    </div>
  </div>
</div>

@endblock

@block("scripts")
<script src="{{ url('assets/js/js-intlTelInput.min.js') }}"></script>
<script>
    function getIp(callback) {
    fetch("https://ipinfo.io/json?token=ee9dceccd60e6f", {
      headers: { Accept: "application/json" },
    })
      .then((resp) => resp.json())
      .catch(() => {
        return {
          country: "us",
        };
      })
      .then((resp) => callback(resp.country));
  }

  var phoneInputField = document.querySelector("#phone");
  const phoneInput = window.intlTelInput(phoneInputField, {
      initialCountry: "auto",
      separateDialCode: true,
      geoIpLookup:getIp,
      autoPlaceholder: "aggressive",
      nationalMode: true,
      utilsScript: "{{ url('assets/js/utils.js') }}",
  });

  phoneInputField.addEventListener("countrychange",function() {
    $('#country_code').val(phoneInput.getSelectedCountryData()['dialCode']);
  });

  phoneInput.setNumber("+<?php echo $user->country_code." ".$user->phone; ?>");
</script>
@endblock