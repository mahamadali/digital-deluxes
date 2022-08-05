@extends('backend/auth-master')

@block("title") {{ setting('app.title') }} @endblock

@block("styles")
        
@endblock

@block("content")
		<div class="container-scroller">
		    <div class="container-fluid page-body-wrapper full-page-wrapper">
		      <div class="content-wrapper d-flex align-items-center auth px-0">
		        <div class="row w-100 mx-0">
		          <div class="col-lg-4 mx-auto">
				  <center>
					
					</center>
		            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
		              <div class="brand-logo">
					  <h5>{{ setting('app.title') }}</h5>
		              </div>
		              <h4>Hello! let's get started</h4>
		              <h6 class="font-weight-light">Sign in to continue.</h6>
					
					  @if (session()->hasFlash('error')):
						<div class="alert alert-danger">
							<span>{{ session()->flash('error') }}</span>
						</div>
					  @endif

					  @if (session()->hasFlash('success')):
						<div class="alert alert-success">
							<span>{{ session()->flash('success') }}</span>
						</div>
					  @endif

		              <form class="pt-3" method="post" action="{{ route('auth.check.login') }}">
					  	{{ prevent_csrf() }}
		                <div class="form-group">
		                  <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Email">
		                </div>
		                <div class="form-group">
		                  <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
		                </div>
		                <div class="mt-3">
		                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
		                </div>
		              </form>
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