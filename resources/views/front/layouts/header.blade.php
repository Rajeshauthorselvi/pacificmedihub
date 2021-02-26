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
					<div class="dropdown">
						<button id="header-category-dropdown" class="hamburger-menu btn">
	                        <i class="fas fa-bars"></i> &nbsp; <span>SHOP BY CATEGORIES</span>
	                    </button>
						<ul class="toogle-menu dropdown-menu">
							<li><a class="dropdown-item" href="">Hair Loss</a></li>
							<li><a class="dropdown-item" href="">Hair Removal</a></li>
							<li><a class="dropdown-item" href="">Blood Pressure</a></li>
							<li><a class="dropdown-item" href="">Vitamin Supplements</a></li>
							<li><a class="dropdown-item" href="">Athlete's Foot</a></li>
							<li><a class="dropdown-item" href="">High Cholesterol</a></li>
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
			                      	<li><a href="">Hair Loss</a></li>
									<li><a href="">Hair Removal</a></li>
									<li><a href="">Blood Pressure</a></li>
									<li><a href="">Vitamin Supplements</a></li>
									<li><a href="">Athlete's Foot</a></li>
									<li><a href="">High Cholesterol</a></li>
			                    </ul>
			                </div>
			                <input type="hidden" name="search_param" value="all" id="search_param">         
			                <input type="text" class="form-control" name="x" placeholder="Search entire store here...">
			                <span class="input-group-btn">
			                    <button class="btn btn-default" type="button"><i class="fas fa-search"></i></button>
			                </span>
			            </div>
                	</div>
               	</div>
               	<div class="col-sm-4 col-xs-3">
               		<div class="bottom-nav">
               			<ul>
               				<li><a class="nav-link" href="">My Wishlist</a></li>
               				<li><a class="nav-link" href="">Sign In</a></li>
               				<li>
								<a class="nav-link" href="#" id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown" aria-expanded="false">
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
$ (document).ready(function() {
	$('#header-category-dropdown').click(function(event){
		$('.toogle-menu').slideToggle('slow');
		$(this).toggleClass('active');
	});
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
		e.preventDefault();
		var param = $(this).attr("href").replace("#","");
		var concept = $(this).text();
		$('.search-panel span#search_concept').text(concept);
		$('.input-group #search_param').val(param);
	});
});
</script>
@endpush