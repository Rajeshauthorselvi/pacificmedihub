@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="Categories Page">Categories</a></li>
		</ul>
	</div>
</div>
<div class="main">
	<div class="container">
		<div id="product-list" class="row">
		    <div id="column-left" class="col-sm-3 hidden-xs column-left">
		    <input type="hidden" class="user-id" value="{{ $user_id }}">
		      <div class="column-block">
		        <div class="columnblock-title">Categories</div>
		        <div class="category_block">
		          <ul class="box-menu treeview-list treeview collapsable" >
		          	@foreach($categories as $category)
		          		<?php 
		          			if(count($category->children)!=0)
		          				$tree = "expandable";
		          		 	else
		          		 		$tree = "";
		          		 	$catgory_id = base64_encode($category->id);
		          		?>
			            <li class="{{ $tree }} @if($category->id==$parent_id) active @endif">
			            	<a href="{{ url("$category->search_engine_name/$catgory_id") }}" class="link @if($category->id==$selected_id)active @endif" id="list">{{ $category->name }}</a>
			              <ul class="collapsable expandable-hitarea">
			              	@foreach($category->children as $child)
			              	<?php $cat_id = base64_encode($child->id); ?>
			                	<li><a  class="@if($child->id==$selected_id)active @endif" href="{{ url("$child->search_engine_name/$cat_id") }}">{{ $child->name }}</a></li>
			                @endforeach
			              </ul>
			            </li>
			        @endforeach
		          </ul>
		        </div>
		      </div>

		      {{-- <div class="banner">
		        <div class="item"> <a href="#"><img src="image/banners/LeftBanner.jpg" alt="Left Banner" class="img-responsive"></a> </div>
		      </div> --}}
		    </div>
		    <div class="col-sm-9">
		    	@if($image)
			      <div class="row category-banner">
			        <div class="col-sm-12 category-image"><img src="{{ asset("theme/images/categories/$image")}}"></div>
			      </div>
			    @endif
		      <div class="category-page-wrapper">
		      	<div class="row">
			        <div class="col-md-6 list-grid-wrapper">
			          <div class="btn-group btn-list-grid">
			            <button type="button" id="grid-view" class="btn btn-default grid active" data-toggle="tooltip" title="" data-original-title="Grid"><i class="fa fa-th"></i></button>
			            <button type="button" id="list-view" class="btn btn-default list" data-toggle="tooltip" title="" data-original-title="List"><i class="fa fa-th-list"></i></button>
			          </div>
			          <span class="tot-pro">{{ $products->total() }} Items</span> </div>
			        <div class="col-md-6 text-right sort-wrapper">
			          <label class="control-label" for="input-sort">View by :</label>
			          <div class="sort-inner">
			            <select id="input-sort" class="form-control">
			              <option value="" selected="selected">Default</option>
			              <option value="ASC">Name (A - Z)</option>
			              <option value="DESC">Name (Z - A)</option>
			            </select>
			          </div>
			        </div>
			    </div>
		      </div>
		      <br>
		      <div class="grid-list-wrapper">
		      	<div class="row">
		      	@if(!empty($products) && $products->count())
		      		<?php $date = \Carbon\Carbon::today()->subDays(7); ?>
			      	@foreach($products as $product)
			      		<?php 
	                        if(!empty($product->main_image)){$image = "theme/images/products/main/".$product->main_image;}
	                        else {$image = "theme/images/products/placeholder.jpg";}
	                        $category_slug = $product->category->search_engine_name;
	                        $product_id = base64_encode($product->id);
	                    ?>
				        <div class="product-layout">
							<div class="product-inner">
						    	<div class="product-thumb">
						    		<a href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}"><img src="{{asset($image)}}" alt="{{ $product->name }}" width="269" height="232"></a>
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
						    	<div class="product-info d-flex flex-column">
						    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}">VIEW</a></div>
						    		<h3><a href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}">{{ Str::limit($product->name, 30) }} </a></h3>
						    		@if(isset($product->short_description))
						    			<p class="product-desc"> {{ $product->short_description }}</p>
						    		@endif
						    	</div>
						    </div>
				        </div>
				    @endforeach
				@else
                	<span>There are no data.</span>
        		@endif
		       </div>
		       <div class="clearfix visible-lg"></div>
		      </div>
		      <div class="category-page-wrapper fotnav">
		        <div class="result-inner">
		        	Showing {{($products->firstItem())}} to {{($products->lastItem())}} of {{ $products->total() }} ({{ $products->currentpage()  }} Pages)
		        </div>
		        <div class="pagination-inner">
		        	{{ $products->links() }}
		        </div>
		      </div>
		    </div>
		  </div>
	</div>
</div>
@endsection

@push('custom-scripts')
<script type="text/javascript">
	$('#input-sort').on('change',function(){
		var sortVal = $('option:selected', this).val();
		if(sortVal=='ASC'){
			$('.product-layout').sort(function(a, b) {
			  	if (a.textContent < b.textContent) {
			    	return -1;
			  	} else {
			    	return 1;
			  	}
			}).appendTo('.grid-list-wrapper .row');
		}else if(sortVal=='DESC'){
			$('.product-layout').sort(function(a, b) {
			  	if (a.textContent > b.textContent) {
			    	return -1;
			  	} else {
			    	return 1;
			  	}
			}).appendTo('.grid-list-wrapper .row');
		}else{
			location.reload();
		}
	});

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
	
	$(document).ready(function() {
		/* JS for Active Grid List */
		function gridlistactive(){
			$('.btn-list-grid button').on('click', function() {
				if($(this).hasClass('grid')) {
		  			$('.btn-list-grid button').addClass('active');
		  			$('.btn-list-grid button.list').removeClass('active');
				}
		  		else if($(this).hasClass('list')) {
					$('.btn-list-grid button').addClass('active');
		  			$('.btn-list-grid button.grid').removeClass('active');
		  		}
			});

			var displayType = localStorage.getItem("display");
			if(displayType=='grid'){
				$('.btn-list-grid button').addClass('active');
		  		$('.btn-list-grid button.list').removeClass('active');
			}else if(displayType=='list'){
				$('.btn-list-grid button').addClass('active');
		  		$('.btn-list-grid button.grid').removeClass('active');
			}
			
		}
		$(document).ready(function(){gridlistactive();});
		$(window).resize(function(){gridlistactive();});

		// Product List
		$('#list-view').click(function() {
			$('#product-list .product-layout > .clearfix').remove();
			$('#product-list .product-layout').attr('class', 'product-layout product-list col-12');
			localStorage.setItem('display', 'list');
		});

		// Product Grid
		$('#grid-view').click(function() {
			$('#product-list .product-layout > .clearfix').remove();
			$('#product-list .product-layout').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		    localStorage.setItem('display', 'grid');
		});
		if (localStorage.getItem('display') == 'list') {
			$('#list-view').trigger('click');
		} else {
			$('#grid-view').trigger('click');
		}
	});
</script>
@endpush