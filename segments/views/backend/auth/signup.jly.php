@extends('backend/auth-master')

@block("title") {{ 'Setup Your Portal '.setting('app.title', 'Quotations') }} @endblock

@block("styles")
        
@endblock

@block("content")
		<div class="container-scroller">
		    <div class="container-fluid page-body-wrapper full-page-wrapper">
		      <div class="content-wrapper d-flex align-items-center auth px-0">
		        <div class="row w-100 mx-0">
		          <div class="col-lg-4 mx-auto">
				  <center>
					<img src="{{ url(company()->logo) }}" class="mr-2 mb-2" height="100" alt="{{ setting('app.title') }}" title="{{ setting('app.title') }}" />
					</center>
		            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
		              <div class="brand-logo">
					  <h5>{{ setting('app.title', 'Quotations') }}</h5>
		              </div>
		              <h4>Hello! let's get started</h4>
		              <h6 class="font-weight-light">Setup your portal.</h6>
					
					  @if (session()->hasFlash('error')):
						<div class="alert alert-danger">
							<span>{{ session()->flash('error') }}</span>
						</div>
					  @endif

		              <form class="pt-3" method="post" action="{{ route('auth.signup-post', ['plan_request' => $planRequest->id]) }}">
						{{ prevent_csrf() }}
                        <div class="form-group">
		                  <input type="first_name" name="first_name" class="form-control form-control-lg" id="first_name" value="{{ old('first_name') }}" placeholder="First Name">
		                </div>
                        <div class="form-group">
		                  <input type="last_name" name="last_name" class="form-control form-control-lg" id="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
		                </div>
                        <div class="form-group">
		                  <input type="contact_number" name="contact_number" class="form-control form-control-lg" id="contact_number" value="{{ old('contact_number') }}" placeholder="Contact Number">
		                </div>
		                <div class="form-group">
		                  <input type="email" class="form-control form-control-lg" id="email" placeholder="Email" value="{{ $planRequest->email }}" readonly>
		                </div>
                        <div class="form-group">
		                  <input type="text" name="username" class="form-control form-control-lg" id="username" value="{{ old('username') }}" placeholder="Username" autocomplete="off">
		                </div>
		                <div class="form-group">
		                  <input type="password" name="password" class="form-control form-control-lg" id="password" value="{{ old('password') }}" placeholder="Password" autocomplete="off">
		                </div>
                        <div class="form-group">
		                  <input type="password" name="cpassword" class="form-control form-control-lg" id="cpassword" value="{{ old('cpassword') }}" placeholder="Confirm Password">
		                </div>
		                <div class="mt-3">
		                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SETUP PORTAL</button>
		                </div>
		              </form>

                      <center><a href="{{ route('auth.login') }}">Already have an account?</a></center>
		            </div>
		          </div>
		        </div>
		      </div>
		      <!-- content-wrapper ends -->
		    </div>
		    <!-- page-body-wrapper ends -->
		  </div>
@endblock

@block("scripts")
    
@endblock