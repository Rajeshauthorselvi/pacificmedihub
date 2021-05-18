<?php 
	$categories = App\Models\Categories::where('published',1)->where('is_deleted',0)
											  ->where('parent_category_id',NULL)->limit(5)
											  ->orderBy('display_order','asc')->get();

	$static_pages = App\Models\StaticPage::where('published',1)->where('is_deleted',0)->orderBy('sort_by','asc')->get();
?>

<div class="footer">
	<div class="footer-top">
		<div class="container">
			<div class="row">
				<div class="col-sm-7">
					<i class="far fa-paper-plane"></i> &nbsp;Submit your email for signup approval process 
				</div>
				<div class="col-sm-5">
					<form action="{{ route('news.letter') }}" method="POST" id="subForm">
						@csrf
						<div class="row">
							<div class="col-8 p-0">
								<input type="email" name="email" placeholder="Your email address..." id="subMail" autocomplete="off">
							</div>
							<div class="col-4 p-0"><input type="button" id="subscribe" value="Send !"></div>
							<span class="text-danger email_validate" style="display:none">Please enter valid Email Address</span>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-center">
		<div class="container">
			<ul>
				<li><strong>Useful Links:</strong></li>
				@foreach($static_pages as $page)
					<?php $page_id = base64_encode($page->id); ?>
					<li><a href="{{ url("static/page/$page->slug/$page_id") }}">{{ $page->page_title }}</a></li>
				@endforeach
			</ul>
			<ul>
				<li><strong>Categories:</strong></li>
				@foreach($categories as $category)
					<?php $catgory_id = base64_encode($category->id); ?>
					<li><a id="menu" class="dropdown-item" href="{{ url("$category->search_engine_name/$catgory_id") }}">{{$category->name}}</a></li>
				@endforeach
				<?php $all_cat_id = base64_encode('all'); ?>
				<li><a href="{{ url("shop-by-category/$all_cat_id") }}">More...</a></li>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					Copyright &copy {{ now()->year }} <a href="{{ url('/') }}">MTC U TRADING</a>
				</div>
				<div class="col-sm-6">
					<ul>
						<li><a href=""><i class="fab fa-facebook-f"></i></a></li>
						<li><a href=""><i class="fab fa-twitter"></i></a></li>
						<li><a href=""><i class="fab fa-linkedin-in"></i></a></li>
						<li><a href=""><i class="fab fa-youtube"></i></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.text-danger.email_validate {
    	font-size: 16px;
	    padding: 5px 15px 0;
	    color: #bd515c;
	}
</style>
@push('custom-scripts')
	<script type="text/javascript">
		var toastr = new Toastr({
				theme: 'ocean',
				animation: 'slide',
				timeout: 5000
			});
		@if(Session::has('mail_staus'))
			scroll_to();
			toastr.show('You are Subscribed successfully.!');
		@elseif($errors->any())
			scroll_to();
			toastr.show('This Email address has already subscribed.!');
		@endif

		$('#subscribe').on('click',function(event) {
			var email = $('#subMail').val();
			if(email!=''){
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				var validateEmail = emailReg.test( email );
				if(!validateEmail){
					$('.email_validate').show();
					return false;
				}else{
					$('.email_validate').hide();
					$('#subForm').submit();
				}	
			}else{
				alert('Please enter an Email address.!');
				return false;
			}
		});

		function scroll_to(form){
	        $('html, body').animate({
	          scrollTop: $(".footer-bottom").offset().top
	        },1000);
      	}

	</script>
@endpush