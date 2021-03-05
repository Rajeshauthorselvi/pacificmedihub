@extends('front.layouts.default')
@section('front_end_container')
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
							@foreach($product->product_images as $images)
								<div class="item">
								    <img src="{{asset('theme/images/products/'.$images->name)}}" alt="Product Name" width="90" height="100" />
								</div>
							@endforeach
						</div>
					</div>
				</div>

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
								<span class="title">SKU</span><p id="sku">: {{$default_sku}}</p> 
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
		                <div class="variant-config" data-optionID1="{{$variant->option_id}}" data-optionVID1="{{$variant->option_value_id}}"data-optionID2="{{$variant->option_id2}}" data-optionVID2="{{$variant->option_value_id2}}" data-optionID3="{{$variant->option_id3}}" data-optionVID3="{{$variant->option_value_id3}}" data-optionID4="{{$variant->option_id4}}" data-optionVID4="{{$variant->option_value_id4}}" data-optionID5="{{$variant->option_id5}}" data-optionVID5="{{$variant->option_value_id5}}" data-variantID="{{$variant->id}}" data-sku="{{$variant->sku}}"></div>
		                @endforeach
              		</div>
             		

              		{{-- @if($product->product_variant->count() > 0)
	              		<div class="variant-option">
             				<div class="variant-title">Select Variant</div>
	                		<?php $optCount=1;?>
	                		@foreach($options as $key => $option)
	                			<div class="option-box" data-optionID={{$option->id}}>
	                    			<label>{{$option->option_name}}</label>
	                    			<select name="option{{$optCount}}" class="options-selectbox selecty">
	                      				@foreach($option->getoptionvalues as $value)
	                        				@if(in_array($value->id, $allowed_options[$option->id]))
	                        					<option  data-optionID="{{$option->id}}" value="{{$value->id}}">{{$value->option_value}}</option>
	                        				@endif
	                      				@endforeach
	                    			</select>
	                			</div>
	                			<?php $optCount++;?>
	                		@endforeach
	              		</div>
              		@endif --}}

					@if($product->product_variant->count() > 0)
						<div id="product-variants" class="product-options">
							<div class="variant-title">Select Variant</div>
							<?php $optCount=1;?>
				    		@foreach($options as $key => $option)
				  				<div class="swatch clearfix" data-option-index="{{$optCount}}">
				  					<div class="header">{{$option->option_name}}</div>
			  						<div class="variant-items">
			  			  				@foreach($option->getoptionvalues as $value)
			            					@if(in_array($value->id, $allowed_options[$option->id]))
			  			       					<div data-value="{{$value->option_value}}" class="swatch-element square">
			  										<input id="swatch-{{$value->id}}" class="swatch-variant" type="radio" optionID="{{$option->id}}" data-optionValueID="{{$value->id}}" name="option-{{$optCount}}" value="{{$value->id}}">
			  										<label for="swatch-{{$value->id}}">{{$value->option_value}}
			  											<img class="crossed-out" src="{{ asset('theme/images/soldout.png') }}">
			  										</label>
			  									</div>
			  								@endif
			          					@endforeach
			  						</div>
			  					</div>
				  				<?php $optCount++;?>
				    		@endforeach
						</div>
					@endif

					<div class="number">
						<span class="minus">-</span><input type="text" value="1"/><span class="plus">+</span>
					</div>

					<div class="buttons">
						<a class="rfq-btn" href="javascript:void(0);">RFQ</a>
                      	<a class="cart-btn" href="javascript:void(0);">Add to Cart</a>
					</div>

					<div class="social-links">
						<a class="wishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>
						<a class="wishlist true" href="javascript:void(0);" style="display:none"><i class="fas fa-heart"></i></a>
						<a class="share" href="javascript:void(0);"><i class="fas fa-share-alt"></i></a>
					</div>
				</div>
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
                      				<p>{!! $product->name !!}</p>
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

		$(".swatch-variant").on('change',function(){
			if( $(this).is(":checked") ){ 
				onselectChange();
			}
        });

		function onselectChange(){
			var selector='';
          	$('.swatch-variant').each(function(index,elem){
            	optionId = $('.swatch-element').find("input[type='radio']:checked").attr('optionID');
            	optionValueId = $('.swatch-element').find("input[type='radio']:checked").val();
            	selector += "[data-optionid"+(index+1)+"='"+optionId+"']";
            	selector += "[data-optionvid"+(index+1)+"='"+optionValueId+"']";
            	console.log(optionId,optionValueId,selector);

            	if($('.variant-config'+selector).length > 0 ){
		            var selectedVariant = $('.variant-config'+selector).data();
		            $('.product_variant_id').val(selectedVariant.variantid);
		            $('#sku').text(selectedVariant.sku);
		        }
          	});
		}

      /*$(".options-selectbox").change(onSelectChange);
       function onSelectChange(){
          var selector='';
          $(".options-selectbox").each(function(index,elem){
            attributes = $(this).find('option:selected').data();
            selector += "[data-optionid"+(index+1)+"='"+attributes.optionid+"']";
            selector += "[data-optionvid"+(index+1)+"='"+$(this).val()+"']";
            console.log(attributes,selector);
          });
          
          if($('.variant-config'+selector).length > 0 ){
            var selectedVariant = $('.variant-config'+selector).data();
            $('.product_variant_id').val(selectedVariant.variantid);
            $('#sku').text(selectedVariant.sku);
          }
        }*/
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
	});
</script>

@endpush