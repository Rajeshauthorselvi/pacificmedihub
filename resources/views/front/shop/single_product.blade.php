@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<?php $all_cat_id = base64_encode('all'); ?>
			<li><a href="{{ url("shop-by-category/$all_cat_id") }}" title="Categories Page">Categories</a></li>
			<li><a title="Single Product Page">Products</a></li>
		</ul>
	</div>
</div>
<div class="main">
	<div class="container">
		<div class="single-product">
			<div class="sec1 row">
				<div class="col-md-6">
					<div class="outer">
						<div id="big" class="owl-carousel owl-theme">
							@if(isset($product->main_image))
								<div class="item">
									<img src="{{asset('theme/images/products/main/'.$product->main_image)}}" alt="Product Name" width="590" height="600" class="img-responsive" />
								</div>
							@else
								<div class="item">
									<img src="{{asset('theme/images/products/placeholder.jpg')}}" alt="Product Name" width="590" height="600" class="img-responsive" />
								</div>
							@endif
							@if(isset($product->product_images))
							  	@foreach($product->product_images as $images)
									<div class="item">
									    <img src="{{asset('theme/images/products/'.$images->name)}}" alt="Product Name" width="590" height="600" class="img-responsive" />
									</div>
								@endforeach
							@endif
						</div>
						<div id="thumbs" class="owl-carousel owl-theme">
							@if(isset($product->main_image))
								<div class="item">
									<img src="{{asset('theme/images/products/main/'.$product->main_image)}}" alt="Product Name" width="90" height="100"/>
								</div>
							@endif
							@if(isset($product->product_images))
								@foreach($product->product_images as $images)
									<div class="item">
									    <img src="{{asset('theme/images/products/'.$images->name)}}" alt="Product Name" width="90" height="100" />
									</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>

				<input type="hidden" class="product-img" value="{{ isset($product->main_image)?$product->main_image:'placeholder.jpg' }}">

				@if(isset($product))
				<div class="col-md-6">
					<h2>{{ $product->name }}</h2>
					<div class="product-details">
						@if(isset($product->brand->name))
							<div class="data">
								<span class="title">Brand Name</span><p>: {{$product->brand->name}}</p>
							</div>
						@endif
						@if(isset($default_sku))
							<div class="data">
								<span class="title">SKU</span><p id="printSku">: {{$default_sku}}</p> 
							</div>
						@endif
						@if(isset($product->code))
							<div class="data">
								<span class="title">Product Code</span><p>: {{ $product->code }}</p> 
							</div>
						@endif
					</div>

					<div class="variants-config">
		                @foreach($product->product_variant as $key=> $variant)
		                <div class="variant-config" data-optionID1="{{$variant->option_id}}" data-optionVID1="{{$variant->option_value_id}}" data-optionID2="{{$variant->option_id2}}" data-optionVID2="{{$variant->option_value_id2}}" data-optionID3="{{$variant->option_id3}}" data-optionVID3="{{$variant->option_value_id3}}" data-optionID4="{{$variant->option_id4}}" data-optionVID4="{{$variant->option_value_id4}}" data-optionID5="{{$variant->option_id5}}" data-optionVID5="{{$variant->option_value_id5}}" data-variantID="{{$variant->id}}" data-sku="{{$variant->sku}}"></div>
		                @endforeach
              		</div>

					@if($product->product_variant->count() > 0)
						<div id="product-variants" class="product-options">
							<div class="variant-title">Select Variant</div>
							<?php $optCount=1;?>
				    		@foreach($options as $key => $option)
				  				<div class="swatch clearfix" data-optionID={{$option->id}}>
				  					<div class="header">{{$option->option_name}}</div>
			  						<div class="variant-items">
			  			  				@foreach($option->getoptionvalues as $k => $value)
			  			  					<?php 
			  			  						$default_variant = $allowed_options[$option->id][$k]['id']; 
			  			  					?>
		  			       					<div class="swatch-element square">
		  										<input id="swatch-{{$value->id}}" @if($default_variant==$default_variant_id) checked @endif class="swatch-variant" type="radio" data-optionID="{{$option->id}}" name="option-{{$optCount}}" value="{{$value->id}}" option-count="{{$optCount}}">
		  										<label for="swatch-{{$value->id}}">{{$value->option_value}}
		  										</label>
		  									</div>
			          					@endforeach
			  						</div>
			  					</div>
				  				<?php $optCount++;?>
				    		@endforeach
						</div>
					@endif

					<div class="number">
						<span class="minus">-</span><input type="text" class="qty-count" value="1"/><span class="plus">+</span>
					</div>

					<div class="buttons">
						<a class="rfq-btn" href="javascript:void(0);" variant-id={{ $default_variant_id }}>RFQ</a>
                      	<a class="cart-btn" href="javascript:void(0);" variant-id={{ $default_variant_id }}>Add to Cart</a>
					</div>

					<div class="social-links">
						<a class="wishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>
						<a class="wishlist true" href="javascript:void(0);" style="display:none"><i class="fas fa-heart"></i></a>
						<a class="share" href="javascript:void(0);"><i class="fas fa-share-alt"></i></a>
					</div>
				</div>
				@endif
			</div>

			<div class="sec2 row">
				<div class="col-sm-12">
					<div class="product-page-content">
                  		<ul id="myTab" class="nav nav-tabs">
                  			<li class="active">Description</li>
                    		{{-- <li class="active"><a href="#Description" data-toggle="tab" aria-expanded="true">Description</a></li>
                    		<li class=""><a href="#Information" data-toggle="tab" aria-expanded="false">Information</a></li> --}}
                  		</ul>
                  		<div id="myTabContent" class="tab-content">
                    		<div class="tab-pane fade active in show" id="Description">
                    			@if(isset($product->long_description))
                      				<p>{!! $product->long_description !!}</p>
                      			@else
                      				<p>{!! isset($product->name)?$product->name:'' !!}</p>
                      			@endif
                    		</div>
                    		<div class="tab-pane fade" id="Information">
                      			Test 2
                    		</div>
                  		</div>
                	</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('custom-scripts')
<script type="text/javascript">
	$(document).ready(function(){
      	$(".swatch-variant").change(onSelectChange);
       	function onSelectChange(){
          	var selector='';
          	var selectedVariant ='';
          	$(".swatch-variant").each(function(index,elem){
	            if ($(this).prop("checked")) {
	            	var attributes = $(this).data();
	            	var option_count=$(this).attr('option-count');
	            	var optionId=parseInt(attributes.optionid);
		            selector += "[data-optionid"+(option_count)+"='"+optionId+"']";
		            selector += "[data-optionvid"+(option_count)+"='"+$(this).val()+"']";
	            }
	            if($('.variant-config'+selector).length > 0 ){
		            selectedVariant = $('.variant-config'+selector).data();
		            $('#printSku').text(selectedVariant.sku);
		            $('.rfq-btn').attr('variant-id',selectedVariant.variantid);
		            $('.cart-btn').attr('variant-id',selectedVariant.variantid);
		        }
        	});
        }
    })
</script>
<script type="text/javascript">
	$(document).ready(function() {
	  	var bigimage = $("#big");
	  	var thumbs = $("#thumbs");
	  	//var totalslides = 10;
	  	var syncedSecondary = true;

	  	bigimage
	    .owlCarousel({
	    	items: 1,
	    	slideSpeed: 2000,
	    	nav: true,
	    	autoplay: false,
	    	dots: false,
	    	loop: true,
	    	responsiveRefreshRate: 200,
	    	navText: [
	      		'<i class="fas fa-chevron-left" aria-hidden="true"></i>',
	      		'<i class="fas fa-chevron-right" aria-hidden="true"></i>'
	    	]
	  	})
	    .on("changed.owl.carousel", syncPosition);
	  	thumbs
	    .on("initialized.owl.carousel", function() {
	    	thumbs
	      	.find(".owl-item")
	      	.eq(0)
	      	.addClass("current");
	  	})
	    .owlCarousel({
	    	items: 5,
	    	dots: false,
	    	nav: false,
	    	smartSpeed: 200,
	    	slideSpeed: 500,
	    	slideBy: 4,
	    	margin: 10,
	    	responsiveRefreshRate: 100
	  	})
	    .on("changed.owl.carousel", syncPosition2);

	  	function syncPosition(el) {
	    	//if loop is set to false, then you have to uncomment the next line
	    	//var current = el.item.index;

	    	//to disable loop, comment this block
	    	var count = el.item.count - 1;
	    	var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

	    	if (current < 0) {
	      		current = count;
	    	}
	    	if (current > count) {
	      		current = 0;
	    	}
	    	//to this
	    	thumbs
	      	.find(".owl-item")
	      	.removeClass("current")
	      	.eq(current)
	      	.addClass("current");
	    	var onscreen = thumbs.find(".owl-item.active").length - 1;
	    	var start = thumbs
	    	.find(".owl-item.active")
	    	.first()
	    	.index();
	    	var end = thumbs
	    	.find(".owl-item.active")
	    	.last()
	    	.index();

	    	if (current > end) {
	      		thumbs.data("owl.carousel").to(current, 100, true);
	    	}
	    	if (current < start) {
	      		thumbs.data("owl.carousel").to(current - onscreen, 100, true);
	    	}
	  	}

	  	function syncPosition2(el) {
	    	if (syncedSecondary) {
	      		var number = el.item.index;
	      		bigimage.data("owl.carousel").to(number, 100, true);
	    	}
	  	}

	  	thumbs.on("click", ".owl-item", function(e) {
	    	e.preventDefault();
	    	var number = $(this).index();
	    	bigimage.data("owl.carousel").to(number, 300, true);
	  	});

		$('.minus').click(function () {
			var $input = $(this).parent().find('input');
			var count = parseInt($input.val()) - 1;
			count = count < 1 ? 1 : count;
			$input.val(count);
			$input.change();
			return false;
		});
		$('.plus').click(function () {
			var $input = $(this).parent().find('input');
			$input.val(parseInt($input.val()) + 1);
			$input.change();
			return false;
		});

		
  		$(".qty-count").on('keyup',function(e) {
  			if (/\D/g.test(this.value))
		  	{
		    	this.value = this.value.replace(/\D/g, '');
		  	}
		  	if(this.value==''){
		  		$(this).val(1);	
		  	}
		});
	});

        $('.cart-btn').on('click',function() {
       		var variantId = $(this).attr('variant-id');
       		var productId = <?php echo $product->id; ?>;
       		var productImg = $('.product-img').val();
       		var qty = $('.qty-count').val();
       		var variantSku = $('#printSku').text();

       		$.ajax({
	            url:"{{ route('cart.store') }}",
	            type:"POST",
	            data:{
	            	"_token": "{{ csrf_token() }}",
	            	variant_id: variantId,
		            variant_sku: variantSku,
		            product_id: productId,
		            product_img: productImg,
		            qty_count: qty,
		            price: 0
	            },
	        })
       		.done(function() {
	        	window.location.href = "{{ route('cart.index') }}";
	        })
	        .fail(function() {
	        	console.log("error");
	        })
       	});
        
</script>

@endpush