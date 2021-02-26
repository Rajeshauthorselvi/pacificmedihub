@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	<div class="container">
		<h1>About Us</h1>
		<img class="lazyloaded img-fluid p-3 text-center" src="{{ asset('front/img/banner3.png') }}" alt="Pacific Medihub About" width="960" height="350" />
		<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
		<p><strong>Lorem ipsum <a class="color" href="#">dolor sit amet</a>, consectetur adipiscing elit. Nunc hendrerit tellus et nisi ultra trices, eu feugiat sapien commodo. Praesent vitae ipsum et risus.</strong></p>
	</div>
</div>
@endsection