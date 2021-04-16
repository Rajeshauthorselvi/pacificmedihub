<?php 
	$categories = App\Models\Categories::where('published',1)->where('is_deleted',0)->where('parent_category_id',NULL)
																		 ->limit(6)->orderBy('display_order','asc')->get();
	$current_route=Route::current()->uri();
	if(($current_route=="home")||($current_route=="/")){
		$header_category_status = "active";
		$header_category_style  = "block";
	}else{
		$header_category_status = "";
		$header_category_style  = "none";
	}
	$cart_data = [];
	$cart_count = 0;
	if(Auth::check()){
    $user_id = Auth::id();
    Cart::instance('cart')->restore('userID_'.$user_id);
    $cart_items = Cart::content();
    $cart_count = Cart::count();
    foreach($cart_items as $key => $items)
    {
        $cart_data[$key]['uniqueId']      = $items->getUniqueId();
        $cart_data[$key]['product_id']    = $items->id;
        $cart_data[$key]['product_name']  = $items->name;
        $cart_data[$key]['product_image'] = $items->options['product_img'];
        $cart_data[$key]['qty']           = $items->quantity;
        $cart_data[$key]['variant_id']    = $items->options['variant_id'];
    }
  }
  $cartData = $cart_data;

  $setting = App\Models\Settings::where('key','front-end')->pluck('content','code')->toArray();
  if(isset($setting['header'])){
    $datas = unserialize($setting['header']);
  }
?>

<div class="header">
	<div class="container">
		<div class="header-top">
			<div class="row">
				<div class="col-sm-2 col-xs-4 logo">
					<a href="{{ route('home.index') }}"><img src="{{ asset('front/img/'.$datas['image']) }}" alt="pacificmedihub" width="137" height="57" /></a>
				</div>
				<div class="col-sm-10 col-xs-8">
					<ul>
						<li>
							<div class="head-icon">
							<i class="mail-icon"></i>
							<div class="wrap"><label>Email us:</label>{{ $datas['email'] }}</div>
							</div>
						</li>
						<li>
							<div class="head-icon">
							<i class="help-icon"></i>
							<div class="wrap"><label>Free Helpline:</label>{{ $datas['helpline'] }}</div>
							</div>
						</li>
						@if(!Auth::check())
						<li>
							<a href="{{ route('register.new.customer') }}"><label>New Customer?</label><br />Click Here</a>
						</li>
						@endif
					</ul>
				</div>
			</div>
		</div>
		
		<div class="header_bottom">
			<div class="row">
				<div class="col-sm-3 col-xs-2">
					<div class="category-dropdown dropdown">
						<button id="header-category-dropdown" class="hamburger-menu btn {{ $header_category_status }}">
              <i class="fas fa-bars"></i> &nbsp; <span>SHOP BY CATEGORIES</span>
            </button>
						<ul class="toogle-menu dropdown-menu" style="display:{{ $header_category_style }}">
							@foreach($categories as $category)
	          		<?php 
	          			if(count($category->children)!=0)
	          				$tree = "menu-list";
	          		 	else
	          		 		$tree = "";
	          		 	$catgory_id = base64_encode($category->id);
	          		?>
	            	<li class="{{ $tree }}">
	            		<a id="menu" class="dropdown-item" href="{{ url("$category->search_engine_name/$catgory_id") }}">{{$category->name}}</a>
	            		<div class="toogle-menu dropdown-menu">
										<ul class="subchildmenu">
											@foreach($category->children as $child)
											<?php $cat_id = base64_encode($child->id); ?>
												<li class="ui-menu-item level1 "><a href="{{ url("$child->search_engine_name/$cat_id") }}">{{ $child->name }}</a></li>
											@endforeach
										</ul>
									</div>
				        </li>
			        @endforeach
							<?php $all_cat_id = base64_encode('all'); ?>
							<li class="last">
								<a href="{{ url("shop-by-category/$all_cat_id") }}">More...</a>
							</li>
						</ul>
					</div>
        </div>
        <div class="col-sm-5 col-xs-7">
        	<div class="search-form">
        		<form method="GET" action="{{ route('seach') }}" id="searchForm">
							<div class="input-group">
			          <div class="input-group-btn search-panel">
			            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			             	<span id="search_concept">All Categories </span> <span class="caret"></span>
			            </button>
                  <ul class="dropdown-menu" role="menu">
                  	<li><a class="search-category" href="" catgory-id="0">All Categories </a></li>
                  	@foreach($categories as $categoy)
											<li><a class="search-category" href="" catgory-id="{{ $categoy->id }}">{{ $categoy->name }}</a></li>
										@endforeach
			            </ul>
			          </div>
                <input type="hidden" name="catgory_id" value="" id="selected_category">
                <input type="hidden" name="search_param" value="all" id="search_param">         
                <input type="text" class="form-control" id="txtSearch" name="search_text" placeholder="Search entire store here..." autocomplete="off">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
                </span>
            	</div>
          	</form>
        	</div>
			  	<ul id="search_result" style="display: none"></ul>
      	</div>
     		<div class="col-sm-4 col-xs-3">
     			<div class="bottom-nav">
     				<ul>
     					@if(!Auth::check())
	     					<li><a class="nav-link" href="{{ route('customer.login') }}">Sign In</a></li>
	     				@else
	     					<li class="nav-item dropdown">
	        				<a class="nav-link notificaion_icon" data-toggle="dropdown" href="javascript:void(0)">
	          				<i class="far fa-bell"></i>
	          				<strong><span class="badge badge-warning navbar-badge notificaion_count"></span></strong>
	        				</a>
	        				<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
					          <span class="dropdown-item dropdown-header">
					          	<span class="notificaion_count">0</span> Notifications
					          </span>
					          <div class="dropdown-divider"></div>
					          <div class="notification-append-sec"></div>
	        				</div>
	      				</li>

	     					<?php 
	     						$name = Auth::user()->name;
	     						$id = Auth::id();
	     					?>
	     					<li class="customer-dropdown">
	     						<a class="nav-link" href="javascript:void(0);">{{ $name }}<i class="fas fa-sort-down"></i></a>
	     						<div class="customer-menu">
	                  <a class="menu-list" href="{{ route('my-profile.index') }}">
	                  	<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
	                  </a>
	                	<a class="menu-list" href="{{ route('my-rfq.index') }}">
	                		<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
	                	</a>
	                	<a class="menu-list" href="{{ route('my-orders.index') }}">
	                		<i class="fas fa-dolly-flatbed"></i>&nbsp;&nbsp;My Orders
	                	</a>
	                	<a class="menu-list" href="{{ route('cart.index') }}">
	                		<i class="fas fa-shopping-cart"></i>&nbsp;&nbsp;My Cart
	                	</a>
	                	<a class="menu-list" href="{{ route('wishlist.index') }}">
	                		<i class="far fa-heart"></i>&nbsp;&nbsp;My Wishlist
	                	</a>
	                	<a class="menu-list" href="{{ route('my-address.index') }}">
	                		<i class="fas fa-street-view"></i>&nbsp;&nbsp;My Address
	                	</a>
	                		<a class="menu-list" href="{{route('customer.logout')}}">
	                		<i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Logout
	                	</a>
	                </div>
	     					</li>
     					@endif
	     				<li class="cart__menu">
								<a class="nav-link" href="javascript:void(0);" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
	                <i class="fas fa-shopping-cart"></i>
	                <span class="badge rounded-pill badge-notification">{{ $cart_count }}</span>
	              </a>
	            </li>
          	</ul>
        	</div>
      	</div>
    	</div>
  	</div>
	</div>
</div>
     				
<div class="body__overlay"></div>
<!-- Start Cart Panel -->
<div class="shopping__cart">
  @if($cart_count!=0)
    <div class="shopping__cart__inner">
	    <div class="offsetmenu__close__btn">
	      <a href="javascript:void(0);"><i class="fas fa-times"></i></a>
	    </div>
      <div class="shp__cart__wrap">
	    	@foreach($cartData as $data)
		      <div class="shp__single__product">
		        <div class="shp__pro__thumb">
		          <a href="javascript:void(0);">
		            @if($data['product_image']!='placeholder.jpg')
									<img src="{{asset('theme/images/products/main/'.$data['product_image'])}}">
								@else
									<img src="{{ asset('theme/images/products/placeholder.jpg') }}">
								@endif
		          </a>
		        </div>
		        <div class="shp__pro__details">
		          <h2><a href="javascript:void(0);">{{ $data['product_name'] }}</a></h2>
		          <span class="quantity">QTY: {{ $data['qty'] }}</span>
		        </div>
            {{-- <div class="remove__btn">
            	<form method="POST" action="{{ route('cart.destroy',$data['uniqueId']) }}">
                        @csrf 
                        <input name="_method" type="hidden" value="DELETE">
                        <button class="btn" type="submit" onclick="return confirm('Are you sure you want to remove this item?');"><i class="far fa-times-circle"></i></button>
                    </form>
            </div> --}}
        		</div>
		    	@endforeach
      	</div>
      	<ul class="shopping__btn">
      		<li><a href="{{ route('cart.index') }}">View Cart</a></li>
      		<li class="shp__checkout"><a href="{{ route('request.rfq') }}">RFQ</a></li>
      	</ul>
    	</div>
    @else
    	<div class="empty-cart">
    		<img src="{{asset('theme/images/empty_cart.png') }}">
    	</div>
	@endif
</div>
<!-- End Cart Panel -->
<style type="text/css">
	.dropdown-menu.dropdown-menu-lg.dropdown-menu-right.show{left: -6rem;min-width: 16rem;}
</style>
@push('custom-scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('#header-category-dropdown').click(function(event){
			$('.toogle-menu').slideToggle('slow');
			$(this).toggleClass('active');
		});
	    $('.search-panel .dropdown-menu').find('a').click(function(e) {
			e.preventDefault();
			var param = $(this).attr("href").replace("javascript:void(0);","");
			var concept = $(this).text();
			$('.search-panel span#search_concept').text(concept);
			$('.input-group #search_param').val(param);
		});

	    $('.search-category').on('click',function(){
	    	var catgoryId = $(this).attr('catgory-id');
	    	$('#selected_category').val(catgoryId);
	    });

	    $('#txtSearch').on('keyup', function(){
        	var keyWords = $(this).val();
	        if(keyWords!=''){
	        	$('#search_result').css('display','block');
          		var text = $('#txtSearch').val();
          		var catgoryId = $('#selected_category').val();
          		if(catgoryId==''){
          			catgoryId = 0;
          		}
          		$.ajax({
            		url: '{{route("seach.text")}}',
            		method: 'POST',
            		data: {_token: "{{csrf_token()}}",search_text:text,catgory_id:catgoryId},
            		success: function(data) {
            			$("#search_result").html(data);
            		}
          		});
	        }else{
	          $('#search_result').css('display','none');
	        }
      	});
	  	$('.cart__menu').on('click', function() {
	    	$('.shopping__cart').addClass('shopping__cart__on');
	    	$('.body__overlay').addClass('is-visible');
	  	});

	  	$('.offsetmenu__close__btn').on('click', function() {
	    	$('.shopping__cart').removeClass('shopping__cart__on');
	      	$('.body__overlay').removeClass('is-visible');
	  	});
	  	$('.body__overlay').on('click', function() {
	    	$(this).removeClass('is-visible');
	    	// $('.offsetmenu').removeClass('offsetmenu__on');
	    	$('.shopping__cart').removeClass('shopping__cart__on');
	  	});

	  	$('body').click(function() {
   			$('#search_result').hide();
		});

		$('#txtSearch').keypress(function (e) {
			var key = e.which;
			if(key == 13)
			{
				$('#searchForm').submit();
			}
		});
	});
</script>
@endpush