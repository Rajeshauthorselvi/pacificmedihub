@extends('front.layouts.default')
@section('front_end_container')
	
<div class="breadcrumbs"></div>

<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="rfq message">
					<img src="{{ asset('theme/images/successfully.png') }}">
					<h4>Thank you, We received your Request.</h4>
					<h5>We will verify your details and send you access details to login</h5>
					<a href="{{ route('home.index') }}" class="btn save-btn">Home</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    history.pushState(null, null, location.href);
    history.back();
    history.forward();
    window.onpopstate = function () { history.go(1); };
</script>

@endsection