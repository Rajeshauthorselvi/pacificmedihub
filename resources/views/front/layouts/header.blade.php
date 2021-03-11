<?php 
	$categories = App\Models\Categories::where('published',1)->where('is_deleted',0)
											  ->where('parent_category_id',NULL)->limit(6)
											  ->orderBy('display_order','asc')->get();
	$current_route=Route::current()->uri();

	if(($current_route=="home")||($current_route=="/")){
		$header_category_status = "active";
		$header_category_style  = "block";
	}else{
		$header_category_status = "";
		$header_category_style  = "none";
	}
?>
<div class="header">
	<div class="container">
		<div class="header-top">
			<div class="row">
				<div class="col-sm-2 col-xs-4 logo">
					<a href="{{ route('home.index') }}"><img src="{{ asset('front/img/pacificmedihub_logo.png') }}" alt="pacificmedihub" width="137" height="57" /></a>
				</div>
				<div class="col-sm-10 col-xs-8">
					<ul>
						<li>
							<div class="head-icon">
							<i class="mail-icon"></i>
							<div class="wrap"><label>Email us:</label>info@pacificmedihub.com</div>
							</div>
						</li>
						<li>
							<div class="head-icon">
							<i class="help-icon"></i>
							<div class="wrap"><label>Free Helpline:</label>(+100) 123 456 7890</div>
							</div>
						</li>
						<li>
							<a href="javascript:void(0);"><label>New Customer?</label><br />Click Here</a>
						</li>
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
					        @if(count($categories)>6)
								<li>
									<a href="{{ url("shop-by-category/$all_cat_id") }}">More...</a>
								</li>
							@endif
						</ul>
					</div>
                </div>
                <div class="col-sm-5 col-xs-7">
                	<div class="search-form">
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
			                <input type="hidden" value="" id="selected_category">
			                <input type="hidden" name="search_param" value="all" id="search_param">         
			                <input type="text" class="form-control" id="txtSearch" name="search_text" placeholder="Search entire store here...">
			                <span class="input-group-btn">
			                    <button class="btn btn-default" type="button"><i class="fas fa-search"></i></button>
			                </span>
			            </div>
                	</div>
			        <ul id="search_result" style="display: none"></ul>
               	</div>
               	<div class="col-sm-4 col-xs-3">
               		<div class="bottom-nav">
               			<ul>
               				@if(!Auth::check())
               					<li><a class="nav-link" href="{{ route('customer.login') }}">Sign In</a></li>
               				@else
               					<?php 
               						$name = Auth::user()->first_name.' '.Auth::user()->last_name;
               						$id = Auth::id();
               					?>
               					<li class="customer-dropdown">
               						<a class="nav-link" href="javascript:void(0);">
               							{{ $name }}<i class="fas fa-sort-down"></i>
               						</a>
               						<div class="customer-menu">
				                    	<a class="menu-list" href="{{ route('profile.index',$id) }}">
				                    		<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
				                    	</a>
				                    	<a class="menu-list" href="javascript:void(0);">
				                    		<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
				                    	</a>
				                    	<a class="menu-list" href="javascript:void(0);">
				                    		<i class="fas fa-dolly-flatbed"></i>&nbsp;&nbsp;My Orders
				                    	</a>
				                    	<a class="menu-list" href="javascript:void(0);">
				                    		<i class="far fa-heart"></i>&nbsp;&nbsp;My Wishlist
				                    	</a>
				                    	<a class="menu-list" href="javascript:void(0);">
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
				                  <span class="badge rounded-pill badge-notification">12</span>
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
    <div class="shopping__cart__inner">
        <div class="offsetmenu__close__btn">
            <a href="#"><i class="fas fa-times"></i></a>
        </div>
        <div class="shp__cart__wrap">
            <div class="shp__single__product">
                <div class="shp__pro__thumb">
                    <a href="#">
                        <img src="https://via.placeholder.com/150/cccccc/000000/?text=Product%20image" alt="product images">
                    </a>
                </div>
                <div class="shp__pro__details">
                    <h2><a href="product-details.html">BO&Play Wireless Speaker</a></h2>
                    <span class="quantity">QTY: 1</span>
                </div>
                <div class="remove__btn">
                    <a href="#" title="Remove this item"><i class="far fa-times-circle"></i></a>
                </div>
            </div>
            <div class="shp__single__product">
                <div class="shp__pro__thumb">
                    <a href="#">
                        <img src="https://via.placeholder.com/150/cccccc/000000/?text=Product%20image" alt="product images">
                    </a>
                </div>
                <div class="shp__pro__details">
                    <h2><a href="product-details.html">Brone Candle</a></h2>
                    <span class="quantity">QTY: 1</span>
                </div>
                <div class="remove__btn">
                    <a href="#" title="Remove this item"><i class="far fa-times-circle"></i></a>
                </div>
            </div>
        </div>
        <ul class="shopping__btn">
            <li><a href="cart.html">View Cart</a></li>
            <li class="shp__checkout"><a href="checkout.html">RGQ</a></li>
        </ul>
    </div>
</div>
<!-- End Cart Panel -->

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


	});
</script>
@endpush