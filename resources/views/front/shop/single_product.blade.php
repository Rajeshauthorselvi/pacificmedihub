@extends('front.layouts.default')
@section('front_end_container')
<div class="main">
	<div class="container">
		<div class="single-product">
			<div class="sec1 row">
				<div class="col-md-6">
					<div class="outer">
						<div id="big" class="owl-carousel owl-theme">
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614420492.webp" alt="Product Name" width="590" height="600" class="img-responsive" />
						  </div>
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614419243.webp" alt="Product Name" width="590" height="600" class="img-responsive" />
						  </div>
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614420390.webp" alt="Product Name" width="590" height="600" class="img-responsive" />
						  </div>
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614420657.webp" alt="Product Name" width="590" height="600" class="img-responsive" />
						  </div>
						</div>
						<div id="thumbs" class="owl-carousel owl-theme">
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614420492.webp" alt="Product Name" width="90" height="100" />
						  </div>
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614419243.webp" alt="Product Name" width="90" height="100" />
						  </div>
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614420390.webp" alt="Product Name" width="90" height="100" />
						  </div>
						  <div class="item">
						    <img src="http://localhost:8000/theme/images/products/main/1614420657.webp" alt="Product Name" width="90" height="100" />
						  </div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h1>Oxy99 First Aid Emergency Medical Kit</h1>
					Brand Name : Surgical EQPO
					Availability: In Stock
					SKU : 01256 Surgical EQPO
					Model No : COVIDMASK001
<div class="number">
	<span class="minus">-</span>
	<input type="text" value="1"/>
	<span class="plus">+</span>
</div>
				</div>
			</div>
			<div class="sec2 row">
				<div class="col-sm-12">
<div class="product-page-content">
                  <ul id="myTab" class="nav nav-tabs">
                    <li class="active"><a href="#Description" data-toggle="tab" aria-expanded="true">Description</a></li>
                    <li class=""><a href="#Information" data-toggle="tab" aria-expanded="false">Information</a></li>
                    
                  </ul>
                  <div id="myTabContent" class="tab-content">
                    <div class="tab-pane fade active in show" id="Description">
                      <p>Lorem ipsum dolor ut sit ame dolore  adipiscing elit, sed sit nonumy nibh sed euismod laoreet dolore magna aliquarm erat sit volutpat Nostrud duis molestie at dolore. Lorem ipsum dolor ut sit ame dolore  adipiscing elit, sed sit nonumy nibh sed euismod laoreet dolore magna aliquarm erat sit volutpat Nostrud duis molestie at dolore. Lorem ipsum dolor ut sit ame dolore  adipiscing elit, sed sit nonumy nibh sed euismod laoreet dolore magna aliquarm erat sit volutpat Nostrud duis molestie at dolore. </p>
                    </div>
                    <div class="tab-pane fade" id="Information">
                      Test 2
                    </div>
                    
                  </div>
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
	  var bigimage = $("#big");
	  var thumbs = $("#thumbs");
	  //var totalslides = 10;
	  var syncedSecondary = true;

	  bigimage
	    .owlCarousel({
	    items: 1,
	    slideSpeed: 2000,
	    nav: true,
	    autoplay: false,
	    dots: false,
	    loop: true,
	    responsiveRefreshRate: 200,
	    navText: [
	      '<i class="fas fa-chevron-left" aria-hidden="true"></i>',
	      '<i class="fas fa-chevron-right" aria-hidden="true"></i>'
	    ]
	  })
	    .on("changed.owl.carousel", syncPosition);

	  thumbs
	    .on("initialized.owl.carousel", function() {
	    thumbs
	      .find(".owl-item")
	      .eq(0)
	      .addClass("current");
	  })
	    .owlCarousel({
	    items: 5,
	    dots: false,
	    nav: false,
	    smartSpeed: 200,
	    slideSpeed: 500,
	    slideBy: 4,
	    margin: 10,
	    responsiveRefreshRate: 100
	  })
	    .on("changed.owl.carousel", syncPosition2);

	  function syncPosition(el) {
	    //if loop is set to false, then you have to uncomment the next line
	    //var current = el.item.index;

	    //to disable loop, comment this block
	    var count = el.item.count - 1;
	    var current = Math.round(el.item.index - el.item.count / 2 - 0.5);

	    if (current < 0) {
	      current = count;
	    }
	    if (current > count) {
	      current = 0;
	    }
	    //to this
	    thumbs
	      .find(".owl-item")
	      .removeClass("current")
	      .eq(current)
	      .addClass("current");
	    var onscreen = thumbs.find(".owl-item.active").length - 1;
	    var start = thumbs
	    .find(".owl-item.active")
	    .first()
	    .index();
	    var end = thumbs
	    .find(".owl-item.active")
	    .last()
	    .index();

	    if (current > end) {
	      thumbs.data("owl.carousel").to(current, 100, true);
	    }
	    if (current < start) {
	      thumbs.data("owl.carousel").to(current - onscreen, 100, true);
	    }
	  }

	  function syncPosition2(el) {
	    if (syncedSecondary) {
	      var number = el.item.index;
	      bigimage.data("owl.carousel").to(number, 100, true);
	    }
	  }

	  thumbs.on("click", ".owl-item", function(e) {
	    e.preventDefault();
	    var number = $(this).index();
	    bigimage.data("owl.carousel").to(number, 300, true);
	  });
			$('.minus').click(function () {
				var $input = $(this).parent().find('input');
				var count = parseInt($input.val()) - 1;
				count = count < 1 ? 1 : count;
				$input.val(count);
				$input.change();
				return false;
			});
			$('.plus').click(function () {
				var $input = $(this).parent().find('input');
				$input.val(parseInt($input.val()) + 1);
				$input.change();
				return false;
			});
	});
</script>
@endpush