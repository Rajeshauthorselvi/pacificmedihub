@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	<div class="container">
		<div id="product-list" class="row">
		    {{-- <div id="column-left" class="col-sm-3 hidden-xs column-left">
		      <div class="column-block">
		        <div class="columnblock-title">Categories</div>
		        <div class="category_block">
		          <ul class="box-category treeview-list treeview collapsable">
		            <li class="expandable"><div class="hitarea expandable-hitarea"></div><a href="#" class="activSub">Desktops</a>
		              <ul style="display: none;" class="collapsable">
		                <li><a href="#">PC</a></li>
		                <li class="last"><a href="#">MAC</a></li>
		              </ul>
		            </li>
		            <li class="expandable"><div class="hitarea expandable-hitarea"></div><a href="#" class="activSub">Laptops &amp; Notebooks</a>
		              <ul style="display: none;" class="collapsable">
		                <li><a href="#">Macs</a></li>
		                <li class="last"><a href="#">Windows</a></li>
		              </ul>
		            </li>
		            <li class="expandable"><div class="hitarea expandable-hitarea"></div><a href="#" class="activSub">Components</a>
		              <ul style="display: none;" class="collapsable">
		                <li><a href="#">Mice and Trackballs</a></li>
		                <li class="expandable"><div class="hitarea expandable-hitarea"></div><a href="#" class="activSub">Monitors</a>
		                  <ul style="display: none;" class="collapsable">
		                    <li><a href="#">test 1</a></li>
		                    <li class="last"><a href="#">test 2</a></li>
		                  </ul>
		                </li>
		                <li class="last"><a href="#">Windows</a></li>
		              </ul>
		            </li>
		            <li><a href="#">Tablets</a></li>
		            <li><a href="#">Software</a></li>
		            <li><a href="#">Phones &amp; PDAs</a></li>
		            <li><a href="#">Cameras</a></li>
		            <li class="last"><a href="#">MP3 Players</a></li>
		          </ul>
		        </div>
		      </div>
		      <div class="banner">
		        <div class="item"> <a href="#"><img src="image/banners/LeftBanner.jpg" alt="Left Banner" class="img-responsive"></a> </div>
		      </div>
		    </div> --}}
		    <div class="col-sm-12">
		      <div class="row category-banner">
		        <div class="col-sm-12 category-image"><img src="https://via.placeholder.com/1300x300/cccccc/000000/?text=Product%20image" alt="product images"></div>
		      </div>
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
			              <option value="ASC">Price (Low &gt; High)</option>
			              <option value="DESC">Price (High &gt; Low)</option>
			              <option value="DESC">Rating (Highest)</option>
			              <option value="ASC">Rating (Lowest)</option>
			              <option value="ASC">Model (A - Z)</option>
			              <option value="DESC">Model (Z - A)</option>
			            </select>
			          </div>
			        </div>
			    </div>
		      </div>
		      <br>
		      <div class="grid-list-wrapper">
		      	<div class="row">
		        <div class="product-layout">
					<div class="product-inner">
				    	<div class="product-thumb">
				    		<a href=""><img src="http://localhost:8000/front/img/product_1.jpg" alt="product name" width="269" height="232"></a>
				    		<div class="pro-tag"><span class="new-label">NEW</span></div>
				    		<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
				    	</div>
				    	<div class="product-info d-flex flex-column">
				    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a></div>
				    		<h3><a href="javascript:void(0);">New Follihair Tablet</a></h3>
				    		<p class="product-desc"> Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing</p>
				    	</div>
				    </div>
		        </div>
		        <div class="product-layout">
					<div class="product-inner">
				    	<div class="product-thumb">
				    		<a href=""><img src="http://localhost:8000/front/img/product_1.jpg" alt="product name" width="269" height="232"></a>
				    		<div class="pro-tag"><span class="new-label">NEW</span></div>
				    		<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
				    	</div>
				    	<div class="product-info d-flex flex-column">
				    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a></div>
				    		<h3><a href="javascript:void(0);">New Follihair Tablet</a></h3>
				    		<p class="product-desc"> Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing</p>
				    	</div>
				    </div>
		        </div>
		        <div class="product-layout">
					<div class="product-inner">
				    	<div class="product-thumb">
				    		<a href=""><img src="http://localhost:8000/front/img/product_1.jpg" alt="product name" width="269" height="232"></a>
				    		<div class="pro-tag"><span class="new-label">NEW</span></div>
				    		<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
				    	</div>
				    	<div class="product-info d-flex flex-column">
				    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a></div>
				    		<h3><a href="javascript:void(0);">New Follihair Tablet</a></h3>
				    		<p class="product-desc"> Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing</p>
				    	</div>
				    </div>
		        </div>
		        <div class="product-layout">
					<div class="product-inner">
				    	<div class="product-thumb">
				    		<a href=""><img src="http://localhost:8000/front/img/product_1.jpg" alt="product name" width="269" height="232"></a>
				    		<div class="pro-tag"><span class="new-label">NEW</span></div>
				    		<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
				    	</div>
				    	<div class="product-info d-flex flex-column">
				    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a></div>
				    		<h3><a href="javascript:void(0);">New Follihair Tablet</a></h3>
				    		<p class="product-desc"> Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing</p>
				    	</div>
				    </div>
		        </div>
		        <div class="product-layout">
					<div class="product-inner">
				    	<div class="product-thumb">
				    		<a href=""><img src="http://localhost:8000/front/img/product_1.jpg" alt="product name" width="269" height="232"></a>
				    		<div class="pro-tag"><span class="new-label">NEW</span></div>
				    		<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
				    	</div>
				    	<div class="product-info d-flex flex-column">
				    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a></div>
				    		<h3><a href="javascript:void(0);">New Follihair Tablet</a></h3>
				    		<p class="product-desc"> Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing</p>
				    	</div>
				    </div>
		        </div>
		        <div class="product-layout">
					<div class="product-inner">
				    	<div class="product-thumb">
				    		<a href=""><img src="http://localhost:8000/front/img/product_1.jpg" alt="product name" width="269" height="232"></a>
				    		<div class="pro-tag"><span class="new-label">NEW</span></div>
				    		<div class="pro-fav"><a class="wishlist-action" href="#"></a></div>
				    	</div>
				    	<div class="product-info d-flex flex-column">
				    		<div class="order-3"><a class="btn" href="#">RFQ</a><a class="btn act" href="">VIEW</a></div>
				    		<h3><a href="javascript:void(0);">New Follihair Tablet</a></h3>
				    		<p class="product-desc"> Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing Lorem Ipsum is simply dummy text of the printing</p>
				    	</div>
				    </div>
		        </div>
		       </div>
		       <div class="clearfix visible-lg"></div>
		      </div>
		      <div class="category-page-wrapper fotnav">
		        <div class="result-inner">Showing 1 to 8 of 10 (2 Pages)</div>
		        <div class="pagination-inner">
		          <ul class="pagination">
		            <li class="active"><span>1</span></li>
		            <li><a href="category.html">2</a></li>
		            <li><a href="category.html">&gt;</a></li>
		            <li><a href="category.html">&gt;|</a></li>
		          </ul>
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