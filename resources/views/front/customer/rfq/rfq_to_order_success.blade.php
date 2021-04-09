@extends('front.layouts.default')
@section('front_end_container')
	
<div class="breadcrumbs"></div>

<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="rfq message">
					<img src="{{ asset('theme/images/successfully.png') }}">
					<h5>Thank you, We received your Order request.</h5>
					<h5>Your Order Code : {{ $order_number }}</h5>
					<a href="{{ route('my-orders.index') }}" class="btn save-btn">MY ORDER</a>
				</div>
			</div>
		</div>
	</div>
</div>
@push('custom-scripts')
	<script type="text/javascript">
	    history.pushState(null, null, location.href);
	    history.back();
	    history.forward();
	    window.onpopstate = function () { history.go(1); };

	    $(function () {  
	        $(document).keydown(function (e) {  
	            return (e.which || e.keyCode) != 116;  
	        });  
	    });  

	    document.onkeydown = function() {
			switch (event.keyCode) {
				case 116 : //F5 button
					event.returnValue = false;
					event.keyCode = 0;
					return false;
				case 82 : //R button
				if (event.ctrlKey) {
					event.returnValue = false;
					event.keyCode = 0;
					return false;
				}
			}
		}
	</script>
@endpush
@endsection