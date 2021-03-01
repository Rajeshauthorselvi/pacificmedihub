<?php 
	$parent_categories = App\Models\Categories::where('published',1)->where('is_deleted',0)
											  ->where('parent_category_id',NULL)->limit(6)
											  ->orderBy('display_order','asc')->get();
?>
<div class="header">
	<div class="container">
		<div class="header-top">
			<div class="row">
				<div class="col-sm-2 col-xs-4 logo">
					<a href=""><img src="{{ asset('front/img/pacificmedihub_logo.png') }}" alt="pacificmedihub" width="137" height="57" /></a>
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
							<a href=""><label>New Customer?</label><br />Click Here</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="header_bottom">
			<div class="row">
				<div class="col-sm-3 col-xs-2">
					<div class="category-dropdown dropdown">
						<button id="header-category-dropdown" class="hamburger-menu btn active">
	                        <i class="fas fa-bars"></i> &nbsp; <span>SHOP BY CATEGORIES</span>
	                    </button>
						<ul class="toogle-menu dropdown-menu" style="display:block">
							@foreach($parent_categories as $p_categoy)
								@php 
									$child_category = App\Models\Categories::where('published',1)->where('is_deleted',0)
																	->where('parent_category_id',$p_categoy->id)
																	->orderBy('display_order','asc')->get();
								@endphp
								@if(count($child_category)!=0)
									<li class="menu-list" get-id="{{ $p_categoy->id }}">
										<a id="menu{{ $p_categoy->id }}" class="dropdown-item" href="javascript:void(0);">{{$p_categoy->name}}</a>
										<div class="toogle-menu{{ $p_categoy->id }} dropdown-menu">
											<ul class="subchildmenu">
												
												@foreach($child_category as $c_category)
													<li class="ui-menu-item level1 "><a href="javascript:void(0);">{{ $c_category->name }}</a></li>
												@endforeach
											</ul>
										</div>
									</li>
								@elseif(count($child_category)==0)
									<li>
										<a href="javascript:void(0);">{{$p_categoy->name}}</a>
									</li>
								@endif
							@endforeach
							@if(count($parent_categories)==6)
								<li>
									<a href="javascript:void(0);">More...</a>
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
			                    	@foreach($parent_categories as $categoy)
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
               				<li><a class="nav-link" href="">My Wishlist</a></li>
               				<li><a class="nav-link" href="">Sign In</a></li>
               				<li>
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

@if(Request::url() === url('/')) @else
	<div class="breadcrumbs">
		<div class="container">
			<ul class="items">
				<li>
					<a href="/" title="Go to Home Page">Home </a>
				</li>
				<li>
					<span>About Us</span>
				</li>
			</ul>
		</div>
	</div>
@endif

@push('custom-scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('#header-category-dropdown').click(function(event){
			$('.toogle-menu').slideToggle('slow');
			$(this).toggleClass('active');
		});
		$('.menu-list').click(function(event){
			var id = $(this).attr('get-id');
			$('.toogle-menu'+id).slideToggle('slow');
			$('#menu'+id).toggleClass('active');
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

	});
</script>
@endpush