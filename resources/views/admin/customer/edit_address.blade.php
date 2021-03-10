
<form method="post" action="{{route('save.address')}}" class="edit-address">
	@csrf
	<div class="form-group" style="display:flex;">
		<div class="col-sm-6" style="padding-left:0">
			{!! Form::label('name', 'Name *') !!}
			{!! Form::text('name',$name,['class'=>'form-control','id'=>'name']) !!}
			{!! Form::hidden('add_id',$id,['class'=>'form-control']) !!}
            {!! Form::hidden('cus_id',$cus_id,['class'=>'form-control']) !!}
            <span class="text-danger name" style="display:none">Name is required</span>
		</div>

		<div class="col-sm-6" style="padding:0;">
			{!! Form::label('mobile', 'Contact No *') !!}
			{!! Form::text('mobile',$mobile,['class'=>'form-control','id'=>'mobile','onkeyup'=>"validateNum(event,this);"]) !!}
            <span class="text-danger mobile" style="display:none">Contact Number is required</span>
		</div>
	</div>
	
	<div class="form-group" style="display:flex;">
        <div class="col-sm-6" style="padding-left:0;">
    		{!! Form::label('address1', 'Address Line 1 *') !!}
    		{!! Form::textarea('address1',$address_line1,['class'=>'form-control','id'=>'address1','rows'=>2]) !!}
            <span class="text-danger address1" style="display:none">Address Line 1 is required</span>
        </div>
        <div class="col-sm-6" style="padding:0">
            {!! Form::label('address2', 'Address Line 2') !!}
            {!! Form::textarea('address2',$address_line2,['class'=>'form-control','id'=>'address2','rows'=>2]) !!}
        </div>
	</div>
	<div class="form-group" style="display:flex;">
		<div class="col-sm-6" style="padding-left:0;">
			{!! Form::label('country', 'Country *') !!}
			{!! Form::select('country_id',$countries,$country_id,['class'=>'form-contol select2bs4', 'id'=>'country']) !!}
            <span class="text-danger country" style="display:none">Country is required</span>
		</div>
        <div class="col-sm-6" style="padding:0">
            {!! Form::label('state', 'State') !!}
            <select name="state_id" class="form-control select2bs4" id="state"></select>
        </div>
	</div>
	<div class="form-group" style="display:flex;">
		<div class="col-sm-6" style="padding-left:0;">
			{!! Form::label('city', 'City') !!}
			<select name="city_id" class="form-control select2bs4" id="city"></select>
		</div>
        <div class="col-sm-6" style="padding:0">
            {!! Form::label('postcode', 'Post Code') !!}
            {!! Form::text('postcode',$post_code,['class'=>'form-control','id'=>'postcode']) !!}
        </div>
	</div>
    <div class="form-group" style="display:flex;">
        <div class="col-sm-6" style="padding-left:0;">
            <label for="city">Latitude</label>
            {!! Form::text('latitude', $latitude,['class'=>'form-control','id'=>'latitude']) !!}
        </div>
        <div class="col-sm-6"  style="padding:0">
            <label for="postCode">Longitude</label>
            {!! Form::text('longitude', $longitude,['class'=>'form-control','id'=>'longitude']) !!}
        </div>
    </div>
{{--                     <div class="col-sm-12">
                      <div id="myMap"></div>
                      <div class="pac-card" id="pac-card">
                        <div>
                          <div id="title">Search Place</div>
                          <br />
                        </div>
                        <div id="pac-container">
                          <input id="pac-input" type="text" placeholder="Enter a location" class="form-control" />
                        </div>
                      </div>
                    </div> --}}
	<div class="form-group">
		<button type="button" class="btn reset-btn" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit-btn" class="btn save-btn">Save</button>
	</div>
</form>

<style>
      #myMap {
         height: 350px;
         width: 100%;
      }  
      #map {
        height: 100%;
      }

      .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
      }

      #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }
      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 22px;
        font-weight: 500;
        padding: 5px;
      }

</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDNi6888yh6v93KRXKYeHfMv59kQHw-XPQ&libraries=places&v=weekly">
</script>
<script type="text/javascript"> 
var lat=12.967728230061345;
var lng=79.18363143444613;
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
        $('.del_add_1').val(results[0].formatted_address);
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
          $('.del_add_1').val(results[0].formatted_address);
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
          infowindowContent.children["place-address"].textContent = place.formatted_address;
          infowindow.open(map, marker);
        });
}
google.maps.event.addDomListener(window, 'load', initialize);


</script>

<script type="text/javascript">

    $(function () {
        $('body').find('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });
	//Get State
    $(document).ready(function(){
        var country_id = "<?php echo json_decode($country_id); ?>";
        getState(country_id);
    });
    $('#country').change(function() {
    	getState($(this).val());
    })
    function getState(countryID){
        var state_id = "<?php echo json_decode($state_id); ?>";      
        if(countryID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-state-list')}}?country_id="+countryID,
            success:function(res){  
              if(res){
                $("#state").empty();
                $("#state").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_state="";
                  if(state_id == key) { var select_state = "selected" }
                  $("#state").append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
                });
              }else{
                $("#state").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#state").empty();        
        }      
    }

    //Get City
    $(document).ready(function(){
        var state_id = "<?php echo json_decode($state_id); ?>";
        getCity(state_id);
    });
    $('#state').change(function() {
        getCity($(this).val());
    })
    function getCity(stateID){
        var city_id = "<?php echo json_decode($city_id); ?>";   
        if(stateID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-city-list')}}?state_id="+stateID,
            success:function(res){  
              if(res){
                $("#city").empty();
                $("#city").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_city="";
                  if(city_id == key) { var select_city = "selected" }
                  $("#city").append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
                });
              }else{
                $("#city").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#city").empty();        
        }      
    }

    $(document).on('click', '#submit-btn', function(){
        var valid=true;
        if ($("#name").val()=="") {
            $("#name").closest('.form-group').find('span.text-danger.name').show();
            valid = false;
        }
        if ($("#mobile").val()=="") {
            $("#mobile").closest('.form-group').find('span.text-danger.mobile').show();
            valid = false;
        }
        if ($("#address1").val()=="") {
            $("#address1").closest('.form-group').find('span.text-danger.address1').show();
            valid = false;
        }
        if ($("#Country").val()=="") {
            $("#Country").closest('.form-group').find('span.text-danger.country').show();
            valid = false;
        }
        return valid;

        if(valid==false){
            return false
        }else{
            $('.edit-address').submit();
            return true
        }
    });
        
</script>