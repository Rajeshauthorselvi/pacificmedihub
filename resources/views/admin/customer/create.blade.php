@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Create Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customer</a></li>
              <li class="breadcrumb-item active">Add Customer</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <!-- Main content -->
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('customers.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid toggle-tabs">
        <div class="row">
          <div class="col-md-12">
          {!! Form::open(['route'=>'customers.store','method'=>'POST','id'=>'form','files'=>true]) !!}
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Customer</h3>
              </div>
              <div class="card-body">
                
                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                  <li role="presentation" class="nav-item">
                    <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab"  tab-count="1" title="Company"> Company Details </a>
                  </li>
                  
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab" tab-count="2" title="POC"> POC Details </a>
                  </li>

                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab"  tab-count="3" title="Delivery">Delivery Address</a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step4" class="nav-link disabled" data-toggle="tab" aria-controls="step4" role="tab"  tab-count="4" title="Bank">Bank Accounts</a>
                  </li>
                </ul>

                <div class="tab-content py-2">

                  <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Customer Code *</label>
                          {!! Form::text('company[customer_no]',$customer_code,['class'=>'form-control required','readonly']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Company UEN *</label>
                          {!! Form::text('company[company_uen]', null,['class'=>'form-control company_uen']) !!}
                          <span class="text-danger company_uen" style="display:none">Company UEN is required</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Name *</label>
                          {!! Form::text('company[name]', null,['class'=>'form-control company_name']) !!}
                          <span class="text-danger company_name" style="display:none">Company Name is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Parent Company</label>
                          {!! Form::select('company[parent_company]', $all_company, 0,['class'=>'form-control select2bs4']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company/Login Email *</label>
                          {!! Form::email('company[email]', null,['class'=>'form-control company_email']) !!}
                          <span class="text-danger company_email" style="display:none">Company/Login Email is required</span>
                          <span class="text-danger company_email_validate" style="display:none">Please enter valid Email Address</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Contact No*</label>
                          {!! Form::text('company[contact_number]', null,['class'=>'form-control company_contact','onkeyup'=>"validateNum(event,this);"]) !!}
                          <span class="text-danger company_contact" style="display:none">Company Contact is required</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line1 *</label>
                          {!! Form::text('company[address_1]', null,['class'=>'form-control company_add1']) !!}
                          <span class="text-danger company_add1" style="display:none">Address Line 1 is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line2</label>
                          {!! Form::text('company[address_2]', null,['class'=>'form-control company_add2']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6 csc-sec">
                          <label for="">Country *</label>
                          {!! Form::select('company[country_id]',$countries,196,['class'=>'form-control select2bs4 country_id', 'id'=>'company_country']) !!}
                          <span class="text-danger country_id" style="display:none">Country is required</span>
                        </div>
                        <div class="col-sm-6 csc-sec">
                          <label for="">State</label>
                          <select name="company[state_id]" class="form-control select2bs4" id="company_state"></select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6 csc-sec">
                          <label for="">City</label>
                          <select name="company[city_id]" class="form-control select2bs4" id="company_city"></select>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Post Code</label>
                          {!! Form::text('company[post_code]', null,['class'=>'form-control','id'=>'company_postcode']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Sales Rep *</label>
                          {!! Form::select('company[sales_rep]',$sales_rep,null,['class'=>'form-control select2bs4 sales_rep']) !!}
                           <span class="text-danger sales_rep" style="display:none">Sales Rep is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Company GST No</label>
                          {!! Form::text('company[company_gst]', null,['class'=>'form-control']) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="companyLogo">Company Logo JPEG</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="company[logo]" id="companyLogo" accept="image/*">
                            <label class="custom-file-label" for="companyLogo">Choose file</label>
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <label for="companyGst">Company GST Certificate Copy(JPEG,PNG,PDF)</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="company[company_gst_certificate]" id="companyGst">
                            <label class="custom-file-label" for="companyGst">Choose file</label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="company[status]" id="Status" checked>
                            <label for="Status">Published</label>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-6">
                          <a href="{{ route('customers.index') }}" class="btn reset-btn">Cancle</a>
                          <button type="button" id="validateStep1" class="btn save-btn next-step">Next</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane poc-tabs" tab-count="2" role="tabpanel" id="step2">
                    <div class="col-sm-12">
                      <table class="list" id="pocList">
                        <thead>
                          <tr>
                            <th></th><th>Name</th><th>Email</th><th>Phone No</th>
                          </tr>
                        </thead>
                        <tr>
                          <td><span class="counts">1</span></td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control poc_name1" name="poc[name][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control poc_email1" name="poc[email][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control poc_contact1" name="poc[contact][]" onkeyup="validateNum(event,this);">
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><span class="counts">2</span></td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" name="poc[name][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control validate-email2" name="poc[email][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" name="poc[contact][]" onkeyup="validateNum(event,this);">
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td><span class="counts">3</span></td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" name="poc[name][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control validate-email3" name="poc[email][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" name="poc[contact][]" onkeyup="validateNum(event,this);">
                            </div>
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <button type="button" class="btn reset-btn prev-step">Previous</button>
                        <button type="button" id="validateStep2" class="btn save-btn next-step">Next</button>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane address-tabs" tab-count="3" role="tabpanel" id="step3">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Name *</label>
                          {!! Form::text('address[name]', null,['class'=>'form-control del_add_name']) !!}
                          <span class="text-danger del_add_name" style="display:none">Name is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Contact No *</label>
                          {!! Form::text('address[mobile]', null,['class'=>'form-control del_add_contact','onkeyup'=>"validateNum(event,this);"]) !!}
                          <span class="text-danger del_add_contact" style="display:none">Contact is required</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line *</label>
                          {!! Form::textarea('address[address_line1]', null,['class'=>'form-control del_add_1','rows'=>3]) !!}
                          <span class="text-danger del_add_1" style="display:none">Address Line 1 is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line 2</label>
                          {!! Form::textarea('address[address_line2]', null,['class'=>'form-control del_add_2','rows'=>3]) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Country</label>
                          {!! Form::select('address[country_id]',$countries,196,['class'=>'form-control select2bs4 address_country']) !!}
                          
                        </div>
                        <div class="col-sm-6">
                          <label for="">State</label>
                          <select name="address[state_id]" class="form-control select2bs4 address_state"></select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">City</label>
                          <select name="address[city_id]" class="form-control select2bs4 address_city"></select>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Post Code</label>
                          {!! Form::text('address[post_code]', null,['class'=>'form-control address_postcode']) !!}
                        </div>
                      </div>
                      {!! Form::hidden('address[latitude]', null,['id'=>'latitude']) !!}
                      {!! Form::hidden('address[longitude]', null,['id'=>'longitude']) !!}
{{--                       <div class="form-group">
                        <div class="col-sm-6">
                          <label for="city">Latitude</label>
                          {!! Form::text('address[latitude]', null,['class'=>'form-control','id'=>'latitude']) !!}
                        </div>
                        <div class="col-sm-6">

                          <label for="postCode">Longitude</label>
                          {!! Form::text('address[longitude]', null,['class'=>'form-control','id'=>'longitude']) !!}
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
                        <div class="col-sm-6">
                          <button type="button" class="btn reset-btn prev-step">Previous</button>
                          <button type="button" id="validateStep3" class="btn save-btn next-step">Next</button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane bank-tabs " tab-count="4" role="tabpanel" id="step4">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="accountName">Account Name</label>
                          {!! Form::text('bank[account_name]',null,['class'=>'form-control']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="accountNumber">Account Number</label>
                          {!! Form::text('bank[account_number]',null,['class'=>'form-control','onkeyup'=>"validateNum(event,this);"]) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="bankName">Bank Name</label>
                          {!! Form::text('bank[bank_name]',null,['class'=>'form-control']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="bankBranch">Bank Branch</label>
                          {!! Form::text('bank[bank_branch]',null,['class'=>'form-control']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="payNow">PayNow Contact No</label>
                          {!! Form::text('bank[paynow_contact]',null,['class'=>'form-control','onkeyup'=>"validateNum(event,this);"]) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="Place">Place</label>
                          {!! Form::text('bank[place]',null,['class'=>'form-control']) !!}
                        </div>                          
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <button type="button" class="btn reset-btn prev-step">Previous</button>
                        <button type="button" id="validateStep4" class="btn save-btn next-step">Submit</button>
                      </div>
                    </div>
                    
                  </div>

                </div>
              </div>
            </div>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </section>
  </div>

  

  <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDNi6888yh6v93KRXKYeHfMv59kQHw-XPQ&libraries=places&v=weekly"></script>
  <script type="text/javascript"> 
    var lat='1.3604544084905643';
    var lng='103.90657638821588';
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
        infowindowContent.children["place-address"].textContent =
          place.formatted_address;
        infowindow.open(map, marker);
      });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
  </script>

  @push('custom-scripts')
  <script type="text/javascript">
    $(document).ready(function() {
      var country_id = 196;
      getState(country_id,'#company_state');
    });

    $(document).ready(function() {
      var state_id = 3186;
      getCity(state_id,'#company_city');
    });

    $(document).ready(function () {
      $('.nav-tabs > li a[title]').tooltip();
      $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        var $target = $(e.target);
        if ($target.parent().hasClass('disabled')) {
          return false;
        }
      });

      function validateStep1(e){
        var valid=true;
        if($(".company_name").val()=="") {
          $(".company_name").closest('.form-group').find('span.text-danger.company_name').show();
          valid=false;
        }else{
          $(".company_name").closest('.form-group').find('span.text-danger.company_name').hide();
        }
        if($(".company_email").val()=="") {
          $(".company_email").closest('.form-group').find('span.text-danger.company_email').show();
          valid=false;
        }else{
          $(".company_email").closest('.form-group').find('span.text-danger.company_email').hide();
        }
        if(!validateEmail($('.company_email').val())){
          $(".company_email").closest('.form-group').find('span.text-danger.company_email_validate').show();
          valid=false;
        }else{
          $(".company_email").closest('.form-group').find('span.text-danger.company_email_validate').hide();
        }
        if($(".company_contact").val()=="") {
          $(".company_contact").closest('.form-group').find('span.text-danger.company_contact').show();
          valid=false;
        }else{
          $(".company_contact").closest('.form-group').find('span.text-danger.company_contact').hide();
        }
        if($(".company_add1").val()=="") {
          $(".company_add1").closest('.form-group').find('span.text-danger.company_add1').show();
          valid=false;
        }else{
          $(".company_add1").closest('.form-group').find('span.text-danger.company_add1').hide();
        }
        if($(".country_id").val()=="") {
          $(".country_id").closest('.form-group').find('span.text-danger.country_id').show();
          valid=false;
        }else{
          $(".country_id").closest('.form-group').find('span.text-danger.country_id').hide();
        }
        if($(".sales_rep").val()=="") {
          $(".sales_rep").closest('.form-group').find('span.text-danger.sales_rep').show();
          valid=false;
        }else{
          $(".sales_rep").closest('.form-group').find('span.text-danger.sales_rep').hide();
        }
        if($(".company_uen").val()=="") {
          $(".company_uen").closest('.form-group').find('span.text-danger.company_uen').show();
          valid=false;
        }else{
          $(".company_uen").closest('.form-group').find('span.text-danger.company_uen').hide();
        }
        return valid;
      }

      function validateStep2(e){
        var valid=true;
        return valid;
      }

      function validateStep3(e){
        var valid=true;
        if($(".del_add_name").val()=="") {
          $(".del_add_name").closest('.form-group').find('span.text-danger.del_add_name').show();
          valid=false;
        }else{
          $(".del_add_name").closest('.form-group').find('span.text-danger.del_add_name').hide();
        }
        if($(".del_add_contact").val()=="") {
          $(".del_add_contact").closest('.form-group').find('span.text-danger.del_add_contact').show();
          valid=false;
        }else{
          $(".del_add_contact").closest('.form-group').find('span.text-danger.del_add_contact').hide();
        }
        if($(".del_add_1").val()=="") {
          $(".del_add_1").closest('.form-group').find('span.text-danger.del_add_1').show();
          valid=false;
        }else{
          $(".del_add_1").closest('.form-group').find('span.text-danger.del_add_1').hide();
        }
        return valid;
      }

      function validateStep4(e){
        var valid=true;
        return valid;
      }

      $('#validateStep1').on('click',function (e) {
        var company_name  = $('.company_name').val();
        var company_email = $('.company_email').val();
        var company_add1  = $('.company_add1').val();
        var company_add2 = $('.company_add2').val();
        var company_contact = $('.company_contact').val();
        var country_id = $('#company_country').val();
        var state_id = $('#company_state').val();
        var city_id = $('#company_city').val();
        var postcode = $('#company_postcode').val();
        console.log(country_id,state_id,city_id);
        $('.del_add_name').val(company_name);
        $('.del_add_1').val(company_add1);
        $('.del_add_2').val(company_add2);
        $('.del_add_contact').val(company_contact);
        getState(country_id,'.address_state');
        getCity(state_id,'.address_city');
        $('.address_postcode').val(postcode);

        $('.poc_name1').val(company_name);
        $('.poc_email1').val(company_email);
        $('.poc_contact1').val(company_contact);
      });

      $(".next-step").click(function (e) {
        var $active = $('.nav-tabs li>.active');
        var stepID = $(e.target).attr('id');
        var formFields=$(e.target).closest('.tab-pane.active').find('input,select');
        if((stepID=="validateStep1" && validateStep1(e)) || (stepID=="validateStep2" && validateStep2(e)) || (stepID=="validateStep3" && validateStep3(e)) || (stepID=="validateStep4" && validateStep4(e))){
          if(stepID=="validateStep4"){
            $(e.target).closest('form').submit();
            return;
          }
          $active.parent().next().find('.nav-link').removeClass('disabled');
          nextTab($active);
        }
      });
        
      $(".prev-step").click(function (e) {
        var $active = $('.nav-tabs li>a.active');
        prevTab($active);
      });

    });

    function nextTab(elem) {
      $(elem).parent().next().find('a[data-toggle="tab"]').click();
    }
    function prevTab(elem) {
      $(elem).parent().prev().find('a[data-toggle="tab"]').click();
    }

    function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      return emailReg.test( $email );
    }

    /* address_city */
    $(document).on('change', '#company_country', function(event) {
        var country_id = $(this).val();
        $('#company_state').val('').trigger('change');
        getState(country_id,'#company_state');
    });

    $(document).on('change', '.address_country', function(event) {
        var country_id = $(this).val();
        $('.address_state').val('').trigger('change');
        getState(country_id,'.address_state');
    });

    function getState(countryID,append_id){
      var state_id = "{{old('State')}}" ;        
      if(countryID){
        $.ajax({
          type:"GET",
          dataType: 'json',
          url:"{{url('admin/get-state-list')}}?country_id="+countryID,
          success:function(res){  
            if(res){
              $("").empty();
              $.each(res,function(key,value){
                var select_state="";
                if(state_id == key) { var select_state = "selected" }
                $(append_id).append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
              });
              $(append_id).selectpicker('refresh');           
            }else{
              $(append_id).empty();
            }
          },
          error: function(res) { alert(res.responseText) }
        });
      }else{
        $(append_id).empty();        
      }      
    }
    //Get City
    $('#company_state').change(function() {
      var state_id = $(this).val();
      $('#company_city').val('').trigger('change');
      getCity(state_id,'#company_city');
    })
    //Get City
    $('.address_state').change(function() {
      var state_id = $(this).val();
      $('.address_city').val('').trigger('change');
      getCity(state_id,'.address_city');
    })
    function getCity(stateID,append_id){
      var city_id = "{{old('State')}}" ;        
      if(stateID){
        $.ajax({
          type:"GET",
          dataType: 'json',
          url:"{{url('admin/get-city-list')}}?state_id="+stateID,
          success:function(res){  
            if(res){
              $(append_id).empty();
              $.each(res,function(key,value){
                var select_city="";
                if(city_id == key) { var select_city = "selected" }
                $(append_id).append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
              });
              $(append_id).selectpicker('refresh');           
            }else{
              $(append_id).empty();
            }
          },
          error: function(res) { alert(res.responseText) }
        });
      }else{
        $(append_id).empty();        
      }      
    }
  </script>
  @endpush
@endsection