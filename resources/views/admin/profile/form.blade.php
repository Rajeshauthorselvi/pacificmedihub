@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Profile</li>
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('theme/images/profile/'.$admin->logo) }}"
                       alt="Profile picture">
                </div>
                <h3 class="profile-username text-center">{{ Auth::user()->name }}</h3>
                <p class="text-muted text-center">Admin</p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-sm-9">
            <div class="card">
              <div class="card-body">
                <div class="tab-content">
                  {!! Form::model($admin,['method' => 'PATCH','class'=>'rfq-form','route' =>['admin-profile.update',$admin->id]]) !!}
                  <div class="active tab-pane" id="persnol_details">
                    <div class="form-group">
                      <div class="col-sm-12">
                        <label for="firstName">Name *</label>
                        {!! Form::text('name', $admin->name,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="email">Email *</label>
                        {!! Form::text('email', $admin->email,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="contactNo">Contact No *</label>
                        {!! Form::text('contact_number', $admin->contact_number,['class'=>'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('contact_number') }}</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="gst">GST No *</label>
                        {!! Form::text('gst', $admin->company_gst,['class'=>'form-control']) !!}
                        <span class="text-danger">{{ $errors->first('gst') }}</span>
                      </div>
                    </div>
                     <div class="form-group">
                      <div class="col-sm-6">
                        <label for="address1">Address Line 1 *</label>
                        {!! Form::textarea('address1', $admin->address_1,['class'=>'form-control','readonly','id'=>'address','rows'=>3]) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="address2">Address Line 2</label>
                        {!! Form::textarea('address2', $admin->address_2,['class'=>'form-control','rows'=>3]) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="address1">Country *</label>
                        {!! Form::text('country', $admin->getCountry->name,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="state">State</label>
                        {!! Form::text('state', $admin->getState->name,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="city">City</label>
                        {!! Form::text('city', $admin->getCity->name,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="postCode">Post Code</label>
                        {!! Form::text('postcode', $admin->post_code,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    {!! Form::hidden('latitude', $admin->latitude,['id'=>'latitude']) !!}
                    {!! Form::hidden('longitude', $admin->latitude,['id'=>'longitude']) !!}
                    {{-- <div class="form-group">
                      <div class="col-sm-6">
                        <label for="city">Warehouse Latitude</label>
                        {!! Form::text('latitude', $company->latitude,['class'=>'form-control','id'=>'latitude']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="postCode">Warehouse Longitude</label>
                        {!! Form::text('longitude', $company->longitude,['class'=>'form-control','id'=>'longitude']) !!}
                      </div>
                    </div> --}}
                    <div class="form-group">
                      <div class="col-sm-12">
                        <div id="myMap"></div>
                        <div class="pac-card" id="pac-card">
                          <div>
                            <div id="title">Search Place</div><br/>
                          </div>
                          <div id="pac-container">
                            <input id="pac-input" type="text" placeholder="Enter a location" class="form-control" />
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <div class="col-sm-4">
                        <a href="{{ url('admin/admin-dashboard') }}" class="btn reset-btn">Cancel</a>
                        <button class="btn save-btn" type="submit">Save</button>
                      </div>
                    </div>
                  </div> 
                  {!! Form::close() !!}
                </div>
              </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <style type="text/css">
    .form-group{ display:flex;}
  </style>

  <style>
    #myMap {height: 350px;width: 100%;}
  </style>
  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDNi6888yh6v93KRXKYeHfMv59kQHw-XPQ&libraries=places&v=weekly">
  </script>

  <script type="text/javascript"> 
    var lat='{{ $admin->latitude }}';
    var lng='{{ $admin->longitude }}';
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
            $('#address').val(results[0].formatted_address);
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
              $('#address').val(results[0].formatted_address);
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

  <style type="text/css">  
    #map {height: 100%;}
    html,body {height: 100%;margin: 0;padding: 0;}
    .pac-card {margin: 10px 10px 0 0;border-radius: 2px 0 0 2px;box-sizing: border-box;-moz-box-sizing: border-box;outline: none;box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);background-color: #fff;font-family: Roboto;}
    #pac-container {padding-bottom: 12px;margin-right: 12px;}
    .pac-controls {display: inline-block;padding: 5px 11px;}
    .pac-controls label {font-family: Roboto;font-size: 13px;font-weight: 300;}
    #pac-input {background-color: #fff;font-family: Roboto;font-size: 15px;font-weight: 300;margin-left: 12px;padding: 0 11px 0 13px;text-overflow: ellipsis;width: 400px;}
    #pac-input:focus {border-color: #4d90fe;}
    #title {color: #fff;background-color: #4d90fe;font-size: 22px;font-weight: 500;padding: 5px;}
  </style>
@endsection