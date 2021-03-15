@extends('front.layouts.default')
@section('front_end_container')
	
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="My Cart Page">My Cart</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h3>My Wishlist ({{ $count }})</h3>
				<div class="wishlist-block">
					@if($count!=0)
						@foreach($wishlist_data as $items)
							<div class="product-data">
								<div class="image">
									@if($items['product_image']!=null)
										<a href="{{ $items['link'] }}"><img src="{{asset('theme/images/products/main/'.$items['product_image'])}}"></a>
									@else
										<a href="{{ $items['link'] }}"><img src="{{ asset('theme/images/products/placeholder.jpg') }}"></a>
									@endif
								</div>
								<div class="name"><a href="{{ $items['link'] }}">{{ $items['product_name'] }}</a></div>
								<div class="remove">
									<form method="POST" action="{{ route('wishlist.destroy',$items['uniqueId']) }}">
                                        @csrf 
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn" type="submit" onclick="return confirm('Are you sure you want to remove this item?');">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
								</div>
							</div>
						@endforeach
					@else
						<a class="btn shopping" href="{{ route('home.index') }}">Back to Shop</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection