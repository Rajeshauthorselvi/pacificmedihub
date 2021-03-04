@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	<div class="container">
		<div id="product-list" class="row">
		    <div id="column-left" class="col-sm-3 hidden-xs column-left">
		      <div class="column-block">
		        <div class="columnblock-title">Categories</div>
		        <div class="category_block">
		          <ul class="box-category treeview-list treeview collapsable">
		          	@foreach($categories as $category)
		          		<?php 
		          			if(count($category->children)!=0)
		          				$tree = "expandable";
		          		 	else
		          		 		$tree = "";
		          		 	$catgory_id = base64_encode($category->id);
		          		?>
			            <li class="{{ $tree }}">
			            	<a href="{{ url("$category->search_engine_name/$catgory_id") }}" class="link" id="list">{{ $category->name }}</a>
			              <ul class="collapsable expandable-hitarea">
			              	@foreach($category->children as $child)
			              	<?php $cat_id = base64_encode($child->id); ?>
			                	<li><a href="{{ url("$child->search_engine_name/$cat_id") }}">{{ $child->name }}</a></li>
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
			          <span class="tot-pro">13 Items</span> </div>
			        <div class="col-md-6 text-right sort-wrapper">
			          <label class="control-label" for="input-sort">View by :</label>
			          <div class="sort-inner">
			            <select id="input-sort" class="form-control">
			              <option value="ASC" selected="selected">Default</option>
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
						    		<a href=""><img src="{{asset($image)}}" alt="{{ $product->name }}" width="269" height="232"></a>
						    		<div class="pro-tag"><span class="new-label">NEW</span></div>
						    		<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
						    	</div>
						    	<div class="product-info d-flex flex-column">
						    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="{{ url("$category_slug/$product->search_engine_name/$product_id") }}">VIEW</a></div>
						    		<h3><a href="javascript:void(0);">{{ Str::limit($product->name, 30) }} </a></h3>
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