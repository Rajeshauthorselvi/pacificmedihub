@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Address</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('departments.index')}}">Address</a></li>
                <li class="breadcrumb-item active">Edit Address</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    <!-- Main content -->
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('customers.edit',[Request::get('customer_id')])}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                  <h3 class="card-title">Edit Address</h3>
              </div>
              <div class="card-body"><form method="post" action="{{route('save.address')}}" class="edit-address">
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
            {!! Form::label('postcode', 'Post Code *') !!}
            {!! Form::text('postcode',$post_code,['class'=>'form-control','id'=>'postcode','onkeyup'=>"validateNum(event,this);"]) !!}
            <span class="text-danger post_code" style="display:none">Post Code is required</span>
        </div>
  </div>
            {!! Form::hidden('latitude', $latitude,['class'=>'form-control','id'=>'latitude','readonly']) !!}
            {!! Form::hidden('longitude', $longitude,['class'=>'form-control','id'=>'longitude','readonly']) !!}
    {{-- <div class="form-group" style="display:flex;">
        <div class="col-sm-6">
            <label for="city">Latitude</label>
            {!! Form::text('latitude', $latitude,['class'=>'form-control','id'=>'latitude','readonly']) !!}
        </div>
        <div class="col-sm-6">
            <label for="postCode">Longitude</label>
            {!! Form::text('longitude', $longitude,['class'=>'form-control','id'=>'longitude','readonly']) !!}
        </div>
    </div> --}}
                      <div class="form-group">
                        <div class="col-sm-12">
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
                        </div>
                      </div>
  <div class="form-group">
    <a href="{{route('customers.edit',[Request::get('customer_id')])}}" class="btn reset-btn">
      Cancel
    </a>
        <button type="submit" id="submit-btn" class="btn save-btn">Save</button>
  </div>
</form></div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @push('custom-scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDNi6888yh6v93KRXKYeHfMv59kQHw-XPQ&libraries=places&v=weekly">
</script>
  <script type="text/javascript"> 
 
var lat="{{ $latitude }}";
var lng="{{ $longitude }}";
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

          var input = document.getElementById('pac-input');
          var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();

                console.log(place.formatted_address);
                $('#latitude,#longitude').show();
                $('#address1').val(place.formatted_address);
                $('#latitude').val( place.geometry.location.lat());
                $('#longitude').val( place.geometry.location.lng());
                infowindow.setContent(place.formatted_address);
                infowindow.open(map, marker);
            });
      google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
              $('#address1').val(results[0].formatted_address);
              $('#latitude').val(marker.getPosition().lat());
              $('#longitude').val(marker.getPosition().lng());
              infowindow.setContent(results[0].formatted_address);
              infowindow.open(map, marker);
            }
          }
        });
      });
      const card = document.getElementById("pac-card");
          map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
         
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
                $('#state').selectpicker('refresh');           
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
                $('#city').selectpicker('refresh');           
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
        if($('#postcode').val()==""){
          $("#postcode").closest('.form-group').find('span.text-danger.post_code').show();
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
  @endpush
@endsection