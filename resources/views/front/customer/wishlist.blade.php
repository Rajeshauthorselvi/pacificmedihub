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
		<div class="row customer-page">
		  	<div id="column-left" class="col-sm-3 hidden-xs column-left">
		   		<div class="column-block">
		     		<ul class="box-menu treeview-list treeview collapsable" >
		     			<li>
		     				<a class="link" href="{{ route('my-profile.index') }}">
           						<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
              				</a>
            			</li>
		        		<li>
			          		<a class="link" href="{{ route('myrfq.index') }}">
			              	<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
			              </a>
			            </li>
			            <li>
			            	<a class="link" href="javascript:void(0);">
			             		<i class="fas fa-dolly-flatbed"></i>&nbsp;&nbsp;My Orders
			             	</a>
			            </li>
			            <li>
			              <a class="link active" href="javascript:void(0);">
			            		<i class="far fa-heart"></i>&nbsp;&nbsp;My Wishlist
			            	</a>
			            </li>
			            <li>
			            	<a class="link" href="{{ route('my-address.index') }}">
			            		<i class="fas fa-street-view"></i>&nbsp;&nbsp;My Address
			            	</a>
			            </li>
			            <li>
			            	<a class="link" href="{{route('customer.logout')}}">
			            		<i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Logout
			            	</a>
			            </li>
			        </ul>
        		</div>
      		</div>

		 	<div class="col-sm-9">
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