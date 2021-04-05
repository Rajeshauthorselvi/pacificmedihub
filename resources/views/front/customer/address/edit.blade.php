@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
      <li><a href="{{ route('my-address.index') }}" title="My Address">My Address</a></li>
      <li><a title="Edit Address">Edit</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row customer-page">
		  <div id="column-left" class="col-sm-3 hidden-xs column-left">
		   	@include('front.customer.customer_menu')
      </div>

      <div class="col-sm-9">
        <div class="add-address" >
          <h4>Edit Address</h4>
          <div class="address-block">
            <form action="{{ route('my-address.update',$address->id) }}" method="post" id="addressForm">
              @csrf
              <input name="_method" type="hidden" value="PATCH">
              <div class="form-group">
                <div class="col-sm-6">
                  {!! Form::label('name', 'Name *') !!}
                  {!! Form::text('name', $address->name,['class'=>'form-control required add_name']) !!}
                  <span class="text-danger name" style="display:none">Name is required</span>
                </div>
                <div class="col-sm-6">
                  {!! Form::label('mobile', 'Contact No *') !!}
                  {!! Form::text('mobile', $address->mobile,['class'=>'form-control required add_mobile','onkeyup'=>"validateNum(event,this);"]) !!}
                  <span class="text-danger mobile" style="display:none">Contact Number is required</span>
                </div>
              </div>
            
              <div class="form-group">
                <div class="col-sm-6">
                  {!! Form::label('address1', 'Address Line 1 *') !!}
                  {!! Form::textarea('address_line1', $address->address_line1,['class'=>'form-control required add_line_1','rows'=>2]) !!}
                  <span class="text-danger address1" style="display:none">Address Line 1 is required</span>
                </div>
                <div class="col-sm-6">
                  {!! Form::label('address2', 'Address Line 2') !!}
                  {!! Form::textarea('address_line2', $address->address_line2,['class'=>'form-control add_line_2','rows'=>2]) !!}
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  {!! Form::label('address_country', 'Country *') !!}
                  {!! Form::select('country_id',$countries,$address->country_id,['class'=>'form-contol select2bs4', 'id'=>'address_country','style'=>'width:100%']) !!}
                  <span class="text-danger country" style="display:none">Country is required</span>
                </div>
                <div class="col-sm-6">
                  {!! Form::label('address_state', 'State') !!}
                  <select name="state_id" class="form-control select2bs4 add_state_id" id="address_state"></select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  {!! Form::label('address_city', 'City') !!}
                  <select name="city_id" class="form-control select2bs4 add_city_id" id="address_city"></select>
                </div>
                <div class="col-sm-6">
                  {!! Form::label('postcode', 'Post Code') !!}
                  {!! Form::text('post_code', $address->post_code,['class'=>'form-control add_post_code']) !!}
                </div>
              </div>

              {!! Form::hidden('latitude', $address->latitude,['class'=>'form-control','id'=>'latitude']) !!}
              {!! Form::hidden('longitude', $address->longitude,['class'=>'form-control','id'=>'longitude']) !!}

              <div class="form-group">
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
              <input type="hidden" name="customer_id" value="{{ $user_id }}">
              <div class="form-group">
                <a class="btn reset-btn" href="{{ route('my-address.index') }}">Cancel</a>
                <button type="submit" id="submit-btn" class="btn save-btn submit-address">Save</button>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDNi6888yh6v93KRXKYeHfMv59kQHw-XPQ&libraries=places&v=weekly">
</script>

<script type="text/javascript"> 
  var lat='<?php echo isset($address->latitude)?$address->latitude:''; ?>';
  var lng='<?php echo isset($address->longitude)?$address->longitude:''; ?>';
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
    
    $(document).ready(function(){
        var country_id = "<?php echo json_decode($address->country_id); ?>";
        getState(country_id);
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

      $(document).ready(function(){
        var state_id = "<?php echo json_decode($address->state_id); ?>";
        getCity(state_id);
      });

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
        $('#addressForm').submit();
      }else{
        scroll_up();
        return false;
      }
    });

    function scroll_up(){
      $('html, body').animate({
        scrollTop: $("#addressForm").offset().top
      },1000);
    }

    </script>
  @endpush
@endsection