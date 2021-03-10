@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	@if($slider_status==1)
		@if(isset($banners))
			<div id="home_slider" class="owl-carousel owl-theme">
				@foreach($banners as $banner)
				    <div class="item">
						<div class="banner_item">
							<div class="banner_bg"><img class="lazyOwl lazy" alt="pacificmedihub" src="{{ asset('theme/images/sliders/'.$banner->images) }}"></div>
							<div class="banner_txt">
								<h3>{{ $banner->title }}</h3>
								<p>{{ $banner->description }}</p>
								@if(isset($banner->button))
									<p><a href="{{ $banner->link }}" class="btn">{{ $banner->button }}</a></p>
								@endif
							</div>
						</div>
				    </div>
				@endforeach
			</div>
		@endif
	@endif

	@if($features_status==1)
		<div class="ser-sec container">
			<ul>
				@foreach($features as $feature)
					<li class="features">
						<img src="{{asset('theme/images/features/'.$feature->images)  }}">
						<h3>{{ $feature->title }}</h3>
						{{ $feature->message }}
					</li>
				@endforeach
				{{-- <li class="supp"><h3>SUPPORT 24x7</h3>
				Lorem Ipsum is simply dummy text of the printing</li>
				<li class="retn"><h3>30 DAYS RETURN</h3>
				Lorem Ipsum is simply dummy text of the printing</li>
				<li class="paysh"><h3>100% PAYMENT SECURE</h3>
				Lorem Ipsum is simply dummy text of the printing</li> --}}
			</ul>
		</div>
	@endif

	<div class="clearfix"></div>
	@if($new_arrival_status==1)
		@if(count($products)!=0)
			<div class="slide-products">
				<div class="container">
					<div class="slider-title">
						<h4><strong>New Arrival</strong> Products</h4>
					</div>
					<div id="product-scroll" class="owl-carousel product-scroll owl-theme">
						@foreach($products as $product)
							<?php 
		                        if(!empty($product->main_image)){$image = "theme/images/products/main/".$product->main_image;}
		                        else {$image = "theme/images/products/placeholder.jpg";}

		                        $category_slug = $product->category->search_engine_name;
		                        $product_id = base64_encode($product->id);
		                    ?>
						    <div class="item">
						    	<div class="product-inner">
						    		<div class="product-thumb">
						    			<a href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}"><img src="{{asset($image)}}" alt="{{ $product->name }}" width="269" height="232" /></a>
						    			<div class="pro-tag"><span class="new-label">NEW</span></div>
						    			<div class="pro-fav"><a class="wishlist-action" href="javascript:void(0);"></a></div>
						    		</div>
						    		<div class="product-info">
						    			<a class="btn" href="javascript:void(0);">RFQ</a><a class="btn act" href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}">VIEW</a>
						    			<h3><a href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}">{{ Str::limit($product->name, 30) }}</a></h3>
						    		</div>
						    	</div>
						    </div>
						@endforeach
					</div>
				</div>
			</div>
		@endif
	@endif

	@if($category_block_status==1)
		@if(isset($category_products))
			<?php $date = \Carbon\Carbon::today()->subDays(7); ?>
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

			                        	$category_slug = $product->category_search_engine_name;
		                        		$product_id = base64_encode($product->id);
			                      	?>
							    	<div class="item">
							    		<div class="product-inner">
							    			<div class="product-thumb">
							    				<a href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}"><img src="{{asset($image)}}" alt="{{ $product->name }}" width="269" height="232" /></a>
							    				@if(($product->created_at)>=($date))
							    					<div class="pro-tag"><span class="new-label">NEW</span></div>
							    				@endif
							    				<div class="pro-fav"><a class="wishlist-action" href="javascript:void(0);"></a></div>
							    			</div>
							    			<div class="product-info">
							    				<a class="btn" href="javascript:void(0)">RFQ</a><a class="btn act" href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}">VIEW</a>
							    				<h3><a href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}">{{ Str::limit($product->name, 30) }}</a></h3>
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
	@endif
</div>

@push('custom-scripts')
<script type="text/javascript">
$ (document).ready(function() {
	$('#home_slider').owlCarousel({
	    loop:true,
	    lazyLoad:true,
	    nav:false,
	    items:1,
	    autoplay:true,
    	autoplayTimeout:5000,
    	autoplayHoverPause:true
	});
	$(".product-scroll").each(function(){
		$(this).owlCarousel({
		    loop:false,
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