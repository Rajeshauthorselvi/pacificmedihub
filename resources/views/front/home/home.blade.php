@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	<div id="home_slider" class="owl-carousel owl-theme">
	    <div class="item">
			<div class="banner_item">
				<div class="banner_bg"><img class="lazyOwl lazy" alt="pacificmedihub" src="{{ asset('front/img/slider1.jpg') }}"></div>
				<div class="banner_txt">
					<p><strong>TODAY HOT DISCOUNT OFFER</strong></p>
					<h3>High Protein<br /> Medical Consumables</h3>
					<p>Lorem Ipsum is simply dummy text...</p>
					<p><a href="#" class="btn">Get it Now</a></p>
				</div>
			</div>
	    </div>
	    <div class="item">
			<div class="banner_item">
				<div class="banner_bg"><img class="lazyOwl lazy" alt="pacificmedihub" src="{{ asset('front/img/slider1.jpg') }}"></div>
				<div class="banner_txt">
					<p><strong>TODAY HOT DISCOUNT OFFER</strong></p>
					<h3>High Protein<br /> Medical Consumables</h3>
					<p>Lorem Ipsum is simply dummy text...</p>
					<p><a href="#" class="btn">Get it Now</a></p>
				</div>
			</div>
	    </div>
	    <div class="item">
			<div class="banner_item">
				<div class="banner_bg"><img class="lazyOwl lazy" alt="pacificmedihub" src="{{ asset('front/img/slider1.jpg') }}"></div>
				<div class="banner_txt">
					<p><strong>TODAY HOT DISCOUNT OFFER</strong></p>
					<h3>High Protein<br /> Medical Consumables</h3>
					<p>Lorem Ipsum is simply dummy text...</p>
					<p><a href="#" class="btn">Get it Now</a></p>
				</div>
			</div>
	    </div>
	    <div class="item">
			<div class="banner_item">
				<div class="banner_bg"><img class="lazyOwl lazy" alt="pacificmedihub" src="{{ asset('front/img/slider1.jpg') }}"></div>
				<div class="banner_txt">
					<p><strong>TODAY HOT DISCOUNT OFFER</strong></p>
					<h3>High Protein<br /> Medical Consumables</h3>
					<p>Lorem Ipsum is simply dummy text...</p>
					<p><a href="#" class="btn">Get it Now</a></p>
				</div>
			</div>
	    </div>
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
				@foreach($products as $product)
					<?php 
                        if(!empty($product->main_image)){$image = "theme/images/products/main/".$product->main_image;}
                        else {$image = "front/img/product_1.jpg";}
                      ?>
				    <div class="item">
				    	<div class="product-inner">
				    		<div class="product-thumb">
				    			<a href=""><img src="{{asset($image)}}" alt="product name" width="269" height="232" /></a>
				    			<div class="pro-tag"><span class="new-label">NEW</span></div>
				    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
				    		</div>
				    		<div class="product-info">
				    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
				    			<h3><a href="javascript:void(0);">{{ $product->name }}</a></h3>
				    		</div>
				    	</div>
				    </div>
				@endforeach

			    {{-- <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_1.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_1.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_1.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_1.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_1.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div> --}}
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="slide-products lgt-bg">
		<div class="container">
			<div class="slider-title">
				<h4><strong>Medical</strong> Consumables</h4>
			</div>
			<div id="product-scroll" class="owl-carousel product-scroll owl-theme">
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_2.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_2.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_2.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_2.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_2.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_2.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="slide-products">
		<div class="container">
			<div class="slider-title">
				<h4><strong>Dental</strong> Asethetics</h4>
			</div>
			<div id="product-scroll" class="owl-carousel product-scroll owl-theme">
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_3.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_3.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_3.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_3.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_3.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			    <div class="item">
			    	<div class="product-inner">
			    		<div class="product-thumb">
			    			<a href=""><img src="{{ asset('front/img/product_3.jpg') }}" alt="product name" width="269" height="232" /></a>
			    			<div class="pro-tag"><span class="new-label">NEW</span></div>
			    			<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
			    		</div>
			    		<div class="product-info">
			    			<a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a>
			    			<h3><a href="#">Surgical Equipments Mask Dummy Product</a></h3>
			    		</div>
			    	</div>
			    </div>
			</div>
		</div>
	</div>
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