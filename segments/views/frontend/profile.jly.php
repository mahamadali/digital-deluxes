@extends('app')

@block("title") {{ setting('app.title', 'Ali Rocks!') }} @endblock
<style>
    .alert-success{
        color:green;
    }

    .alert-danger{
        color:red;
    }
</style>
@block("content")
<main class="page-main">

                <div class="uk-grid" data-uk-grid>
                    <div class="uk-width-2-3@l">
                        <div class="widjet --profile">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Profile</h3>
                            </div>
                            <div class="widjet__body">
                                <div class="user-info">
                                    @if(!empty($user->profile_image)):
                                    <div class="user-info__avatar"><img src="{{ url($user->profile_image) }}" alt="profile"></div>
                                    @endif
                                    
                                    <div class="user-info__box">
                                        <div class="user-info__title">{{ $user->getFullNameProperty() }}</div>
                                        <div class="user-info__text">Member since {{  date("M Y", strtotime($user->created_at))  }}</div>
                                    </div>
                                </div>
                                <!-- <a class="uk-button uk-button-danger" href="04_profile.html"><i class="ico_edit"></i><span class="uk-margin-small-left">Edit Profile</span></a> -->
                            </div>
                        </div>
                        <div class="widjet --bio">
                            <div class="widjet__head">
                                <h3 class="uk-text-lead">Edit Profile</h3>
                            </div>
                            <div class="widjet__body">
                                <form method="post" action="{{ route('frontend.profile.update') }}"  enctype="multipart/form-data">
                                {{ prevent_csrf() }}
                                <div class="uk-margin"><input type="text" class="uk-input" name="first_name" placeholder="First Name" value="{{ $user->first_name }}"></div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="last_name" placeholder="Last Name" value="{{ $user->last_name }}"></div>
                                <div class="uk-margin"><input type="text" class="uk-input" name="phone" placeholder="Phone Number" value="{{ $user->phone }}"></div>
                                <div class="uk-margin"><input type="password" class="uk-input" name="password" placeholder="Password"></div>
                                <div class="uk-margin"><input type="password" class="uk-input" name="confirm_password" placeholder="Confirm Password"></div>
                                <div class="uk-margin">
                                    Upload Profile Image: 
                                    <input type="file" class="uk-input-file" name="profile_image">
                                    
                                </div>
                                <div class="uk-margin"><button class="uk-button uk-button-danger uk-width-1-1" type="submit">Save</button></div>
                                </form>

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
                            </div>
                        </div>
                       
                    </div>
                </div>
            </main>
@endblock