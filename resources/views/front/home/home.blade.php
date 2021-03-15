@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	<input type="hidden" class="user-id" value="{{ $user_id }}">
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
						    			<div class="pro-fav">
						    				<?php 
						    					$wish_list = 0;
						    					$wish_list = array_search($product->id, array_column($wishlist, 'product_id'));
						    					$row = $wish_list+1;
						    					$check_wish = (string)$wish_list;
						    					if((count($wishlist)!=0)&&$check_wish!=""){
						    						$row_id = $wishlist[$row]['row_id'];
						    						$icon = 'fas';
						    						$check = true;
						    					}else{
						    						$row_id = 1;
						    						$icon = 'far';
						    						$check = false;
						    					}
						    					
						    				?>
						    				<a productID="{{$product_id}}" check="{{$check}}" rowID="{{ $row_id }}" class="wishlist-action"><i class="{{ $icon }} fa-heart"></i></a>
					    				</div>
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
							    				<div class="pro-fav">
								    				<?php 
								    					$wish_list = 0;
								    					$wish_list = array_search($product->id, array_column($wishlist, 'product_id'));
								    					$row = $wish_list+1;
								    					$check_wish = (string)$wish_list;
								    					if((count($wishlist)!=0)&&$check_wish!=""){
								    						$row_id = $wishlist[$row]['row_id'];
								    						$icon = 'fas';
								    						$check = true;
								    					}else{
								    						$row_id = 1;
								    						$icon = 'far';
								    						$check = false;
								    					}
								    					
								    				?>
								    				<a productID="{{$product_id}}" check="{{$check}}" rowID="{{ $row_id }}" class="wishlist-action"><i class="{{ $icon }} fa-heart"></i></a>
							    				</div>
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
	$(document).find('.wishlist-action').click(function () {
		var userID = $('.user-id').val();
		if(userID!=''){
			var toastr = new Toastr({
				theme: 'ocean',
				animation: 'slide',
				timeout: 5000
			});
			var prodID  = $(this).attr('productID');
			var rowID = $(this).attr('rowID');

			var status = $(this).attr('check');
			if(status==1){
				$(this).attr('check','');
				$(this).children('.fa-heart').attr('class','far fa-heart');
				toastr.show('Item removed from Wishlist.!');
			}else{
				$(this).attr('check',1);
				$(this).children('.fa-heart').attr('class','fas fa-heart');
				toastr.show('Item added to Wishlist.!');
			}
			$.ajax({
	            url:"{{ route('wishlist.store') }}",
	            type:"POST",
	            data:{
	            	"_token": "{{ csrf_token() }}",
	            	product_id:prodID,
	            	row_id:rowID
	            },
	            success:function(res){
	            	if(res=='removed'){
	            		$(this).children('.fa-heart').attr('class','far fa-heart');
	            	}else if(res=='added'){
	            		$(this).children('.fa-heart').attr('class','fas fa-heart');
	            	}else if(res==false){
	            		window.location.href = "{{ route('customer.login') }}";
	            	}
	            }
	        })
	    }else{
			window.location.href = "{{ route('customer.login')}}";
		}
	});


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