@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	<div id="home_slider" class="owl-carousel owl-theme">
		@if(isset($banners))
			@foreach($banners as $banner)
			    <div class="item">
					<div class="banner_item">
						<div class="banner_bg"><img class="lazyOwl lazy" alt="pacificmedihub" src="{{ asset('theme/images/sliders/'.$banner->images) }}"></div>
						<div class="banner_txt">
							<h3>{{ $banner->title }}</h3>
							<p>{{ $banner->description }}</p>
							<p><a href="{{ $banner->link }}" class="btn">{{ $banner->button }}</a></p>
						</div>
					</div>
			    </div>
			@endforeach
		@else
			<div class="item">
				<div class="banner_item">
					<div class="banner_bg"><img class="lazyOwl lazy" alt="pacificmedihub" src="{{ asset('theme/images/background_image/background.png') }}"></div>
					<div class="banner_txt">
						<h3>High Protein Medical Consumables</h3>
						<p>Lorem Ipsum is simply dummy text of the printing</p>
						<p><a href="javascript(0);" class="btn">Shop Now</a></p>
					</div>
				</div>
		    </div>
		@endif
	</div>
	<div class="ser-sec container">
		<ul>
			<li class="free-ship"><h3>FREE SHIPPING</h3>
			Lorem Ipsum is simply dummy text of the printing</li>
			<li class="supp"><h3>SUPPORT 24x7</h3>
			Lorem Ipsum is simply dummy text of the printing</li>
			<li class="retn"><h3>30 DAYS RETURN</h3>
			Lorem Ipsum is simply dummy text of the printing</li>
			<li class="paysh"><h3>100% PAYMENT SECURE</h3>
			Lorem Ipsum is simply dummy text of the printing</li>
		</ul>
	</div>
	<div class="clearfix"></div>
	<div class="slide-products">
		<div class="container">
			<div class="slider-title">
				<h4><strong>New Arrival</strong> Products</h4>
			</div>
			<div id="product-scroll" class="owl-carousel product-scroll owl-theme">
				@if(isset($products))
					@foreach($products as $product)
						<?php 
	                        if(!empty($product->main_image)){$image = "theme/images/products/main/".$product->main_image;}
	                        else {$image = "theme/images/products/placeholder.jpg";}
	                      ?>
					    <div class="item">
					    	<div class="product-inner">
					    		<div class="product-thumb">
					    			<a href=""><img src="{{asset($image)}}" alt="{{ $product->name }}" width="269" height="232" /></a>
					    			<div class="pro-tag"><span class="new-label">NEW</span></div>
					    			<div class="pro-fav"><a class="wishlist-action" href="javascript:void(0);"></a></div>
					    		</div>
					    		<div class="product-info">
					    			<a class="btn" href="javascript:void(0);">RFQ</a><a class="btn act" href="javascript:void(0)">VIEW</a>
					    			<h3><a href="javascript:void(0);">{{ $product->name }}</a></h3>
					    		</div>
					    	</div>
					    </div>
					@endforeach
				@else
					<div class="item">
				    	<div class="product-inner">
				    		<div class="product-thumb">
				    			<a href=""><img src="{{asset('theme/images/products/placeholder.jpg')}}" alt="Product Name" width="269" height="232" /></a>
				    			<div class="pro-tag"><span class="new-label">NEW</span></div>
				    			<div class="pro-fav"><a class="wishlist-action" href="javascript:void(0);"></a></div>
				    		</div>
				    		<div class="product-info">
				    			<a class="btn" href="javascript:void(0);">RFQ</a><a class="btn act" href="javascript:void(0)">VIEW</a>
				    			<h3><a href="javascript:void(0);">New Product</a></h3>
				    		</div>
				    	</div>
				    </div>
				@endif
			</div>
		</div>
	</div>
	@if(isset($category_products))
		@foreach($category_products as $key => $products)
			<div class="slide-products ">
				<div class="container">
					<div class="slider-title">
						<h4><strong>{{ $key }}</strong></h4>
					</div>
					<div id="product-scroll" class="owl-carousel product-scroll owl-theme">
						@if(isset($products))
							@foreach($products as $product)
								<?php 
		                        	if(!empty($product->main_image)){$image="theme/images/products/main/".$product->main_image;}
		                        	else {$image = "theme/images/products/placeholder.jpg";}
		                      	?>
						    	<div class="item">
						    		<div class="product-inner">
						    			<div class="product-thumb">
						    				<a href=""><img src="{{asset($image)}}" alt="{{ $product->name }}" width="269" height="232" /></a>
						    				<div class="pro-tag"><span class="new-label">NEW</span></div>
						    				<div class="pro-fav"><a class="wishlist-action" href="javascript:void(0);"></a></div>
						    			</div>
						    			<div class="product-info">
						    				<a class="btn" href="javascript:void(0)">RFQ</a><a class="btn act" href="javascript:void(0)">VIEW</a>
						    				<h3><a href="javascript:void(0)">{{ $product->name }}</a></h3>
						    			</div>
						    		</div>
						    	</div>
					    	@endforeach
					    @endif
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		@endforeach
	@endif
</div>

@push('custom-scripts')
<script type="text/javascript">
$ (document).ready(function() {
	$('#home_slider').owlCarousel({
	    loop:true,
	    lazyLoad:true,
	    nav:false,
	    items:1
	});
	$(".product-scroll").each(function(){
		$(this).owlCarousel({
		    loop:true,
		    margin:30,
		    dots:false,
		    nav:true,
		    navText : ['<i class="fas fa-chevron-left"></i>','<i class="fa fa-chevron-right" aria-hidden="true"></i>'],
		    responsive:{
		        0:{
		            items:1
		        },
		        600:{
		            items:3
		        },
		        1000:{
		            items:4
		        }
		    }
		});
	});
});
</script>
@endpush
@endsection