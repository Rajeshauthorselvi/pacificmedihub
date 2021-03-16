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
			<h3>Request RFQ</h3>
		<div class="row request-rfq">

			<div class="col-sm-6">
				<div class="address-container">
					<h5>Delivery Address</h5>
					@if($delivery!=null)
			         <div class="address-block">
			         	<input type="hidden" name="delivery_address" id="primaryAddID" value="{{ $delivery->id }}">
			           	<h6>{{ $delivery->name }}</h6>
			           	<div class="address-box">
			           		<div class="show-address">{{ $delivery->address_line1 }}  {{ $delivery->address_line2 }}</div>
			            </div>
			            <div class="contact">
			            	<span>Contact Number </span>: {{ $delivery->mobile }}
			            </div>
			            <div class="action-block">
			            	<a class="btn save-btn" id="changeDelivery">Change</a>
			            </div>
			         </div>
			      @else
			         <div class="address-block">
			         	<input type="hidden" name="delivery_address" id="primaryAddID" value="{{ $primary->id }}">
			           	<h6>{{ $primary->name }}</h6>
			           	<div class="address-box">
			           		<div class="show-address">{{ $primary->address_line1 }}  {{ $primary->address_line2 }}</div>
			            </div>
			            <div class="contact">
			            	<span>Contact Number </span>: {{ $primary->mobile }}
			            </div>
			            <div class="action-block">
			            	<a class="btn save-btn" id="changeDelivery">Change</a>
			            </div>
			         </div>
			      @endif

		         <div class="delivery-address" style="display:none">
                	<h5>Change Delivery Address</h5>
               	<select class="form-control select2bs4" id="deliveryAddress">
               		<option value="" selected>Please Select Address</option>
               		@foreach($all_address as $address)
               			<option value="{{ $address->id }}">{{ $address->name }}, {{ $address->address_line1 }} {{ $address->address_line2 }}</option>
               		@endforeach
               	</select>
               	<div class="action-block">
		            	<a class="btn reset-btn" id="cancelChangeDelivery">Cancel</a>
		            	<a class="btn save-btn" id="saveChangeDelivery">Save</a>
		            </div>
               </div>

               <div class="icheck-info d-inline">
                  <input type="checkbox" id="sameAs" checked>
                  <label for="sameAs">Same as Billing Address</label>
               </div>

               <input type="hidden" name="billing_address" id="billingAddID" value="{{ $primary->id }}">
               <div class="billing-address">
                	<h5>Billing Address</h5>
               	<select class="form-control select2bs4" id="allAddress">
               		<option value="" selected>Please Select Address</option>
               		@foreach($billing_address as $address)
               			<option value="{{ $address->id }}">{{ $address->name }}, {{ $address->address_line1 }} {{ $address->address_line2 }}</option>
               		@endforeach
               		<option value="new">Add New</option>
               	</select>
               </div>
		      </div>
			</div>

			<div class="col-sm-6">
				<div class="cart-block">
					<h5>Product Details</h5>
					@if($cart_count!=0)
						<table class="table-responsive cart-table">
							<thead>
								<tr><th style="width:72%">Product Name</th><th style="width:25%">Qty</th></tr>
							</thead>
							<tbody>
								@foreach($cart_data as $items)
									<tr>
										<input type="hidden" class="cart-id" name="cart_id" value="{{$items['uniqueId']}}">
										<input type="hidden" class="cart-qty" name="quantity" value="{{ $items['qty'] }}">
										<input type="hidden" class="cart-variant-id" name="variant_id" value="{{ $items['variant_id'] }}">

										<td class="product-data">
											<div class="image">
												@if($items['product_image']!='placeholder.jpg')
													<img src="{{asset('theme/images/products/main/'.$items['product_image'])}}">
												@else
													<img src="{{ asset('theme/images/products/placeholder.jpg') }}">
												@endif
											</div>
											<div class="name">{{ $items['product_name'] }}</div>
										</td>
										<td>
											<div class="number">
												<span class="minus">-</span><input type="text" class="qty-count" value="{{ $items['qty'] }}"/><span class="plus">+</span>
											</div>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif	
				</div>
			</div>

			<a class="btn save-btn">Send RFQ</a>

		</div>
	</div>
</div>


<!-- Add Address Box -->
  <div class="modal fade address-block" id="add-address">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Billing Address</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="address-details">
            <div class="form-group">
              <div class="col-sm-6">
                {!! Form::label('name', 'Name *') !!}
                {!! Form::text('address[name]', '',['class'=>'form-control required add_name']) !!}
                <span class="text-danger name" style="display:none">Name is required</span>
              </div>

              <div class="col-sm-6">
                {!! Form::label('mobile', 'Contact No *') !!}
                {!! Form::text('address[mobile]', '',['class'=>'form-control required add_mobile','onkeyup'=>"validateNum(event,this);"]) !!}
                <span class="text-danger mobile" style="display:none">Contact Number is required</span>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-6">
                {!! Form::label('address1', 'Address Line 1 *') !!}
                {!! Form::textarea('address[address_line1]', '',['class'=>'form-control required add_line_1','rows'=>2]) !!}
                <span class="text-danger address1" style="display:none">Address Line 1 is required</span>
              </div>
              <div class="col-sm-6">
                {!! Form::label('address2', 'Address Line 2') !!}
                {!! Form::textarea('address[address_line2]', '',['class'=>'form-control add_line_2','rows'=>2]) !!}
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                {!! Form::label('address_country', 'Country *') !!}
                {!! Form::select('address[country_id]',$countries,null,['class'=>'form-contol select2bs4 required add_country_id', 'id'=>'address_country']) !!}
                <span class="text-danger country" style="display:none">Country is required</span>
              </div>
              <div class="col-sm-6">
                {!! Form::label('address_state', 'State') !!}
                <select name="address[state_id]" class="form-control select2bs4 add_state_id" id="address_state"></select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                {!! Form::label('address_city', 'City') !!}
                <select name="address[city_id]" class="form-control select2bs4 add_city_id" id="address_city"></select>
              </div>
              <div class="col-sm-6">
                {!! Form::label('postcode', 'Post Code') !!}
                {!! Form::text('address[post_code]', '',['class'=>'form-control add_post_code']) !!}
              </div>
            </div>
            <div class="form-group">
             	<div class="col-sm-6">
               	<label for="city">Latitude</label>
               	{!! Form::text('address[latitude]', null,['class'=>'form-control','id'=>'latitude']) !!}
             	</div>
             	<div class="col-sm-6">
               	<label for="postCode">Longitude</label>
               	{!! Form::text('address[longitude]', null,['class'=>'form-control','id'=>'longitude']) !!}
             	</div>
           	</div>

           	<div class="form-group">
           		<div class="col-sm-12">
	            	<div class="map-block">
	               	<div id="myMap"></div>
	               	<div class="pac-card" id="pac-card">
		                 	<div id="title">Search Place</div>
		                 	<div id="pac-container">
		                  	<input id="pac-input" type="text" placeholder="Enter a location" class="form-control" />
		                 	</div>
	               	</div>
	             	</div>
	            </div>
           	</div>
            <div class="form-group">
            	<div class="col-sm-6">
              		<button type="button" class="btn reset-btn" data-dismiss="modal">Cancel</button>
              		<button type="submit" id="submit-btn" class="btn save-btn submit-address">Save</button>
              	</div>
            </div>
          </div>
        </div>
            
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDNi6888yh6v93KRXKYeHfMv59kQHw-XPQ&libraries=places&v=weekly">
</script>

<script type="text/javascript"> 
  var lat='1.290270';
  var lng='103.851959';
  var map;
  var marker;
  var myLatlng = new google.maps.LatLng(lat,lng);
  var geocoder = new google.maps.Geocoder();
  var infowindow = new google.maps.InfoWindow();
  function initialize(){
    var mapOptions = {
      zoom: 15,
      center: myLatlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

    marker = new google.maps.Marker({
      map: map,
      position: myLatlng,
      draggable: true 
    }); 

    geocoder.geocode({'latLng': myLatlng }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          $('#latitude,#longitude').show();
          $('.add_line_1').val(results[0].formatted_address);
          $('#latitude').val(marker.getPosition().lat());
          $('#longitude').val(marker.getPosition().lng());
          infowindow.setContent(results[0].formatted_address);
          infowindow.open(map, marker);
        }
      }
    });

    google.maps.event.addListener(marker, 'dragend', function() {
      geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
            $('.add_line_1').val(results[0].formatted_address);
            $('#latitude').val(marker.getPosition().lat());
            $('#longitude').val(marker.getPosition().lng());
            infowindow.setContent(results[0].formatted_address);
            infowindow.open(map, marker);
          }
        }
      });
    });

    const card = document.getElementById("pac-card");
    const input = document.getElementById("pac-input");
    const options = {
      fields: ["formatted_address", "geometry", "name"],
      origin: map.getCenter(),
      strictBounds: false,
      types: ["establishment"],
    };
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
    const autocomplete = new google.maps.places.Autocomplete(
      input,
      options
    );
    autocomplete.bindTo("bounds", map);
  
    autocomplete.addListener("place_changed", () => {
      marker.setVisible(false);
      const place = autocomplete.getPlace();
      if (!place.geometry || !place.geometry.location) {
        window.alert(
          "No details available for input: '" + place.name + "'"
        );
        return;
      }
          
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);
      }
      marker.setPosition(place.geometry.location);
        marker.setVisible(true);
        infowindowContent.children["place-name"].textContent = place.name;
        infowindowContent.children["place-address"].textContent =
        place.formatted_address;
        infowindow.open(map, marker);
      });
  }
  google.maps.event.addDomListener(window, 'load', initialize);
</script>

@push('custom-scripts')
	<script type="text/javascript">
		$(function ($) {
      		$('.select2bs4').select2();
    	});

		$('#changeDelivery').on('click',function () {
				$('.delivery-address').show();
				$(this).hide();
		});

		$('#cancelChangeDelivery').on('click',function () {
				$('#changeDelivery').show();
				$('.delivery-address').hide();
		});

		$(document).ready(function() {
			if ($('#sameAs').is(':checked') == true) {
				$('.billing-address').hide();
			}
		});

		$('#sameAs').change(function() {
		    if ($('#sameAs').is(':checked') == true) {
		        $('.billing-address').hide();
		        var billID = $('#primaryAddID').val();
		        $('#billingAddID').val(billID);
		    } else {
		        $('.billing-address').show();
		    }
		});

		$('#allAddress').on('change',function() {
        	var billID = $("option:selected", this).val();
        	if(billID!='new'){
        		$('#billingAddID').val(billID);
        	}else{
        		$('#add-address').modal({backdrop: 'static', keyboard: false, show: true});
        	}
        });


		$('#saveChangeDelivery').on('click',function(){

			if (!confirm('Are you sure?')) return false;
			
			var delAddID = $("#deliveryAddress option:selected").val();

			if(delAddID==''){
				alert('Please select address');
			}


			
			$.ajax({
            url:"{{ url('set-primary-address') }}"+'/'+delAddID,
            type:"GET",
            data:{
            	"_token": "{{ csrf_token() }}",
            	delivery_address_id:delAddID,
            },
        	}).done(function(data) {
        		if(data==true){
        			location.reload();
        		}else{
        			alert('Somthing wrong please try again.!');
        		}
        	})
		});

		$('.minus').click(function () {
			var $input = $(this).parent().find('input');
			var count = parseInt($input.val()) - 1;
			count = count < 1 ? 1 : count;
			$input.val(count);
			$input.change();

			var current_qty = $(this).parent().find('input').val();

			if(current_qty==0){
				return false;
			}else{
				var cartId = $(this).parents('tr').find('.cart-id').val();
				var cartQty = $(this).parent().find('input').val();
				var cartVariantId = $(this).parents('tr').find('.cart-variant-id').val();

				console.log(cartId,cartQty,cartVariantId);

				$.ajax({
		            url:"{{ url('updatecart') }}"+'/'+cartId,
		            type:"PUT",
		            data:{
		            	"_token": "{{ csrf_token() }}",
		            	cart_id:cartId,
		            	variant_id: cartVariantId,
			            qty_count: cartQty
		            },
		        })
			}
			return false;
		});
		$('.plus').click(function () {
			var $input = $(this).parent().find('input');
			$input.val(parseInt($input.val()) + 1);
			$input.change();

			var cartId = $(this).parents('tr').find('.cart-id').val();
			var cartQty = $(this).parent().find('input').val();
			var cartVariantId = $(this).parents('tr').find('.cart-variant-id').val();

			console.log(cartId,cartQty,cartVariantId);

			$.ajax({
	            url:"{{ url('updatecart') }}"+'/'+cartId,
	            type:"PUT",
	            data:{
	            	"_token": "{{ csrf_token() }}",
	            	cart_id:cartId,
	            	variant_id: cartVariantId,
		            qty_count: cartQty
	            },
	        })

	        return false;

		});


	$('#address_country').on('change',function() {
        var country_id = $(this).val();
        getState(country_id);
      })
      function getState(countryID){
        var state_id = "{{old('State')}}" ;        
        if(countryID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('get-state')}}?country_id="+countryID,
            success:function(res){  
              if(res){
                $("#address_state").empty();
                $.each(res,function(key,value){
                  var select_state="";
                  if(state_id == key) { var select_state = "selected" }
                  $("#address_state").append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
                });
                $('#address_state').selectpicker('refresh');           
              }else{
                $("#address_state").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#address_state").empty();        
        }      
      }

      //Get City
      $('#address_state').change(function() {
        var state_id = $(this).val();
        getCity(state_id);
      })
      function getCity(stateID){
        var city_id = "{{old('State')}}" ;        
        if(stateID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('get-city')}}?state_id="+stateID,
            success:function(res){  
              if(res){
                $("#address_city").empty();
                $.each(res,function(key,value){
                  var select_city="";
                  if(city_id == key) { var select_city = "selected" }
                  $("#address_city").append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
                });
                $('#address_city').selectpicker('refresh');           
              }else{
                $("#address_city").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#address_city").empty();        
        }      
      }

      function addressValidate(){
        var valid=true;
        if ($(".add_name").val()=="") {
            $(".add_name").closest('.form-group').find('span.text-danger.name').show();
            valid = false;
        }
        if ($(".add_mobile").val()=="") {
            $(".add_mobile").closest('.form-group').find('span.text-danger.mobile').show();
            valid = false;
        }
        if ($(".add_line_1").val()=="") {
            $(".add_line_1").closest('.form-group').find('span.text-danger.address1').show();
            valid = false;
        }
        if ($("#address_country").val()=="") {
            $("#address_country").closest('.form-group').find('span.text-danger.country').show();
            valid = false;
        }
        return valid;
      }

      $(document).on('click', '.submit-address', function(event) {
      if(addressValidate()){
        $.ajax({
          url: '{{ route('my-address.store') }}',
          type: 'POST',
          data: {
            '_token': '{{ csrf_token() }}',
            'name':$('.add_name').val(),
            'mobile':$('.add_mobile').val(),
            'address_line1':$('.add_line_1').val(),
            'address_line2':$('.add_line_2').val(),
            'post_code':$('.add_post_code').val(),
            'country_id':$('.add_country_id').val(),
            'state_id':$('.add_state_id').val(),
            'city_id':$('.add_city_id').val(),
            'address_type':2,
            'customer_id':{{ $user_id }}
          },
        })
        .done(function(data) {
        	location.reload();
        })
        .fail(function() {
          console.log("error");
        })
      }else{
      	scroll_up();
      }
    });

   function scroll_up(){
      $('#add-address').animate({
        scrollTop: $(".address-details").offset().top
      },1000);
    }

	</script>
@endpush
@endsection