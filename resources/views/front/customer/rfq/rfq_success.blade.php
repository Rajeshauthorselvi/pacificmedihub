@extends('front.layouts.default')
@section('front_end_container')
	
<div class="breadcrumbs"></div>

<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="rfq message">
					<img src="{{ asset('theme/images/successfully.png') }}">
					<h5>Thank you, We received your RFQ request.</h5>
					<h5>Your RFQ Code : {{ Session::get('message') }}</h5>
					<a href="{{ route('my-rfq.index') }}" class="btn save-btn">MY RFQ</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection