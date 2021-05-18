@extends('front.layouts.default')
@section('front_end_container')
	
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="{{$page->page_title}}&nbsp;Page">{{ $page->page_title }}</a></li>
		</ul>
	</div>
</div>

<div class="main">
	<div class="container">
		<div class="row">
		    <h3>{{ $page->page_title }}</h3>
		    <div class="content-block">
		    	{!! $page->page_content !!}
		    </div>
		</div>
	</div>
</div>
	
<style type="text/css">
	.content-block {margin-bottom: 2rem;width: 100%;}
</style>

@endsection