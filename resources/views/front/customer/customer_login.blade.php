@extends('front.layouts.default')
@section('front_end_container')
<div class="login-page">
	<div class="login-box">
  		<div class="card">
    		<div class="card-body">
      			<div class="login-header">
        			<h4>Customer Login</h4>
        			<p>If you have an account with us, please login.</p>
      			</div>
      			<form action="{{ route('customer.store') }}" method="post">
        			@csrf
        			@include('flash-message')

			        <div class="form-group mb-3">
			        	<label>Email</label>
			          	<input type="email" class="form-control" name="email">
			        </div>
			        <div class="form-group mb-3">
			        	<label>Password</label>
			          	<input type="password" class="form-control" name="password">
			        </div>
			        <div class="form-group mb-3 forget">
		        		<a href="{{ route('forget.password') }}">Forgot Yout Password?</a>
		      		</div>
			        <div class="row">
			          <div class="col-12">
			            <button type="submit" class="btn btn-primary btn-block">SIGN IN</button>
			          </div>
			        </div>
      			</form>
    		</div>
  		</div>
	</div>
</div>
@endsection