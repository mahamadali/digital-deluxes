@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
<style>
    .alert-success{
        color:green;
    }

    .alert-danger{
        color:red;
    }
    .iti--separate-dial-code
    {
        width: 100% !important;
    }
</style>
@block("content")
<main class="page-main">

                <div class="uk-grid" data-uk-grid>
                    <div class="uk-width-2-3@l">
                        <div class="widjet --profile">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">{{ trans('profile.profile') }}</h3>
                            </div>
                            <div class="widjet__body">
                                <div class="user-info">
                                    @if(!empty($user->profile_image)):
                                    <div class="user-info__avatar"><img src="{{ url($user->profile_image) }}" alt="profile"></div>
                                    @endif
                                    
                                    <div class="user-info__box">
                                        <div class="user-info__title">{{ $user->getFullNameProperty() }}</div>
                                        <div class="user-info__text">{{ trans('profile.member_since') }} {{  date("M Y", strtotime($user->created_at))  }}</div>
                                    </div>
                                </div>
                                <!-- <a class="uk-button uk-button-danger" href="04_profile.html"><i class="ico_edit"></i><span class="uk-margin-small-left">Edit Profile</span></a> -->
                            </div>
                        </div>
                        <div class="widjet --bio">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">{{ trans('profile.edit_profile') }}</h3>
                            </div>
                            <div class="widjet__body">
                                @if (session()->hasFlash('error')):
                                <div class="alert-danger">
                                    <span>{{ session()->flash('error') }}</span>
                                </div>
                                @endif

                                @if (session()->hasFlash('success')):
                                <div class="alert-success text-center">
                                    <span>{{ session()->flash('success') }}</span>
                                </div>
                                @endif
                                <form method="post" action="{{ route('frontend.profile.update') }}"  enctype="multipart/form-data">
                                {{ prevent_csrf() }}
                                <div class="uk-margin"><input type="email" class="uk-input" name="email" placeholder="Email" value="{{ $user->email }}" required></div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="first_name" placeholder="First Name" value="{{ $user->first_name }}" required></div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}" required></div>
                                <div class="uk-margin"><input type="hidden" name="country_code" id="country_code" value="{{ $user->country_code }}"><input type="text" class="uk-input" name="phone" id="phone" placeholder="Phone Number" value="{{ $user->phone }}" required></div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="age" placeholder="Age" value="{{ $user->age }}" required></div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="address" placeholder="Address" value="{{ $user->address }}" required></div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="national_identification_id" placeholder="National Identification ID" value="{{ $user->national_identification_id }}" required></div>
                                <div class="uk-margin">
                                    <select class="uk-input" name="country" required>
                                        <option value="">Select</option>
                                        @foreach($countries as $country):
                                            <option value="{{ $country->id }}" {{ $user->country == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    
                                </div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="city" placeholder="City" value="{{ $user->city }}" required></div>
                                <div class="uk-margin"><input type="password" class="uk-input" name="password" placeholder="Password"></div>
                                <div class="uk-margin"><input type="password" class="uk-input" name="confirm_password" placeholder="Confirm Password"></div>
                                <div class="uk-margin">
                                    {{ trans('profile.upload_profile_image') }}: 
                                    <input type="file" class="uk-input-file" name="profile_image">
                                    
                                </div>
                                <div class="uk-margin"><button class="uk-button uk-button-danger uk-width-1-1" type="submit">{{ trans('profile.save') }}</button></div>
                                </form>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </main>
@endblock

@block("scripts")
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