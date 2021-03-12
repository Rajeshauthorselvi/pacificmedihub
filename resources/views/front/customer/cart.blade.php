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
				<h3>My Cart</h3>
					<div class="cart-block">
					@if($cart_count!=0)
						<table class="table-responsive cart-table">
							<thead>
								<tr><th style="width:50%">Product Name</th><th style="width:25%">SKU</th><th>Qty</th><th>Action</th></tr>
							</thead>
							<tbody>
								@foreach($cart_data as $items)
									<tr>
										<input type="hidden" class="cart-id" name="cart_id" value="{{$items['uniqueId']}}">
										<input type="hidden" class="cart-qty" name="quantity" value="{{ $items['qty'] }}">
										<input type="hidden" class="cart-variant-id" name="variant_id" value="{{ $items['variant_id'] }}">

										<td class="product-data">
											<div class="image">
												@if($items['product_image']!='placeholder.jpg')
													<img src="{{asset('theme/images/products/main/'.$items['product_image'])}}">
												@else
													<img src="{{ asset('theme/images/products/placeholder.jpg') }}">
												@endif
											</div>
											<div class="name">{{ $items['product_name'] }}</div>
										</td>
										<td><span>{{ str_replace(':', '', $items['sku']) }}</span></td>
										<td>
											<div class="number">
												<span class="minus">-</span><input type="text" class="qty-count" value="{{ $items['qty'] }}"/><span class="plus">+</span>
											</div>
										</td>
										<td style="text-align:center;">
											<form method="POST" action="{{ route('cart.destroy',$items['uniqueId']) }}">
                                                @csrf 
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button class="btn" type="submit" onclick="return confirm('Are you sure you want to remove this item?');">
                                                  <i class="far fa-trash-alt"></i>
                                                </button>
                                              </form>&nbsp;&nbsp;
											<?php 
						    					$wish_list = 0;
						    					$wish_list = array_search($items['product_id'], array_column($wishlist, 'product_id'));
						    					$row = $wish_list+1;
						    					$check_wish = (string)$wish_list;
						    					if((count($wishlist)!=0)&&$check_wish!=""){
						    						$row_id = $wishlist[$row]['row_id'];
						    						$icon = 'fas';
						    					}else{
						    						$row_id = 1;
						    						$icon = 'far';
						    					}
						    					$product_id = base64_encode($items['product_id']);
						    				?>
						    				<a productID="{{$product_id}}" rowID="{{ $row_id }}" class="wishlist-action"><i class="{{ $icon }} fa-heart"></i></a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="action-block">
							<div class="col-sm-6" style="text-align:left;padding-left:0">
								<a class="btn shopping" href="{{ route('home.index') }}">Continue Shopping</a>
							</div>
							
							<div class="col-sm-6" style="text-align:right;padding-right:0">
								<a class="btn checkout">Proceed to RFQ</a>
							</div>
						</div>
				@else
					<div class="empty-cart">
    					<img src="{{asset('theme/images/empty_cart.png') }}"><br>
    					<a class="btn shopping" href="{{ route('home.index') }}">Back to Shop</a>
    				</div>
				@endif
				</div>
			</div>
		</div>
	</div>
</div>

@push('custom-scripts')
	<script type="text/javascript">
		$('.minus').click(function () {
			var $input = $(this).parent().find('input');
			var count = parseInt($input.val()) - 1;
			count = count < 1 ? 1 : count;
			$input.val(count);
			$input.change();

			var current_qty = $(this).parent().find('input').val();

			if(current_qty==0){
				return false;
			}else{
				var cartId = $(this).parents('tr').find('.cart-id').val();
				var cartQty = $(this).parent().find('input').val();
				var cartVariantId = $(this).parents('tr').find('.cart-variant-id').val();

				console.log(cartId,cartQty,cartVariantId);

				$.ajax({
		            url:"{{ url('updatecart') }}"+'/'+cartId,
		            type:"PUT",
		            data:{
		            	"_token": "{{ csrf_token() }}",
		            	cart_id:cartId,
		            	variant_id: cartVariantId,
			            qty_count: cartQty
		            },
		        })
			}
			return false;
		});
		$('.plus').click(function () {
			var $input = $(this).parent().find('input');
			$input.val(parseInt($input.val()) + 1);
			$input.change();

			var cartId = $(this).parents('tr').find('.cart-id').val();
			var cartQty = $(this).parent().find('input').val();
			var cartVariantId = $(this).parents('tr').find('.cart-variant-id').val();

			console.log(cartId,cartQty,cartVariantId);

			$.ajax({
	            url:"{{ url('updatecart') }}"+'/'+cartId,
	            type:"PUT",
	            data:{
	            	"_token": "{{ csrf_token() }}",
	            	cart_id:cartId,
	            	variant_id: cartVariantId,
		            qty_count: cartQty
	            },
	        })

	        return false;

		});

		$(document).find('.wishlist-action').click(function () {
		var prodID  = $(this).attr('productID');
		var rowID = $(this).attr('rowID');

		if(rowID==0||rowID==1){
			$(this).children('.fa-heart').attr('class','fas fa-heart');
		}else{
			$(this).children('.fa-heart').attr('class','far fa-heart');
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
	});


	</script>
@endpush
@endsection