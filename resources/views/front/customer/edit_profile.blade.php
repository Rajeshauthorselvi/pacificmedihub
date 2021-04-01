@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a href="{{ route('my-profile.index') }}" title="My Profile Page">My Profile</a></li>
      <li><a title="Edit Profile Page">Edit</a></li>
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
        <form action="{{ route('my-profile.update',$customer->id) }}" method="post" id="profileForm" enctype="multipart/form-data">
          @csrf
          <input name="_method" type="hidden" value="PATCH">
  		    <ul class="nav nav-tabs flex-nowrap" role="tablist">
            <li role="presentation" class="nav-item">
              <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab customer-link" tab-count="2" title="Company"> Company Details </a>
            </li>
            <li role="presentation" class="nav-item">
              <a href="#step2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab"  tab-count="2" title="POC"> POC Details </a>
            </li>
            <li role="presentation" class="nav-item">
              <a href="#step3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab customer-link"  tab-count="3" title="Bank">Bank Accounts</a>
            </li>
          </ul>
        	<div class="tab-content py-2">

            <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Company Name *</label>
                  {!! Form::text('company[name]', $customer->name,['class'=>'form-control company']) !!}
                  <span class="text-danger company" style="display:none">Company Name is required</span>
                </div>
                <div class="col-sm-6">
                  <label for="">Company UEN *</label>
                  {!! Form::text('company[company_uen]', $customer->company_uen,['class'=>'form-control company_uen']) !!}
                  <span class="text-danger company_uen" style="display:none">Company UEN is required</span>
                </div>
              </div>
            
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Company Email *</label>
                  {!! Form::text('company[email]', $customer->email,['class'=>'form-control cmpy-email','readonly']) !!}
                  <span class="text-danger cmpy-email" style="display:none">Company/Login Email is required</span>
                </div>
                <div class="col-sm-6">
                  <label for="">Contact Number *</label>
                  {!! Form::text('company[contact_number]', $customer->contact_number,['class'=>'form-control telephone','onkeyup'=>'validateNum(event,this);']) !!}
                  <span class="text-danger telephone" style="display:none">Company Contact is required</span>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Address Line1 *</label>
                  {!! Form::text('company[address_1]', $customer->address_1,['class'=>'form-control address1','id'=>'autocomplete']) !!}
                  <span class="text-danger address1" style="display:none">Company Contact is required</span>
                </div>
                <div class="col-sm-6">
                  <label for="">Address Line2</label>
                  {!! Form::text('company[address_2]', $customer->address_2,['class'=>'form-control']) !!}
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Country *</label>
                  {!! Form::select('company[country_id]',$countries,$customer->country_id,['class'=>'form-control select2bs4 country', 'id'=>'company_country' ]) !!}
                  <span class="text-danger country" style="display:none">Country is required</span>
                </div>
                <div class="col-sm-6">
                  <label for="">State</label>
                   <select name="company[state_id]" class="form-control select2bs4" id="company_state"></select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">City</label>
                  <select name="company[city_id]" class="form-control select2bs4" id="company_city"></select>
                </div>
                 <div class="col-sm-6">
                  <label for="">Post Code *</label>
                  {!! Form::text('company[post_code]', $customer->post_code,['class'=>'form-control company_postcode']) !!}
                  <span class="text-danger post-code" style="display:none">Post Code is required</span>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Company GST No</label>
                  {!! Form::text('company[company_gst]', $customer->company_gst,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-6">
                  <label for="">Sales Rep</label>
                  {!!Form::text('sales_rep',$customer->getSalesRep->emp_name,['class'=>'form-control','readonly'])!!}
                </div>
                {!!Form::hidden('company[latitude]',$customer->latitude,['id'=>'latitude'])!!}
                {!!Form::hidden('company[longitude]',$customer->longitude,['id'=>'longitude'])!!}
              </div>
              <?php 
                if(!empty($customer->logo)){$image = 'theme/images/customer/company/'.$customer->id.'/'.$customer->logo;}
                else {$image = "theme/images/no_image.jpg";}
              ?>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="companyLogo">Company Logo</label>
                  <div class="custom-file">
                      <input type="file" class="custom-file-input" name="company[logo]" id="companyLogo" accept="image/*" >
                      <label class="custom-file-label" for="companyLogo">Choose file</label>
                    </div>
                </div>
                  <div><img class="img-company" style="width:110px;height:100px;" src="{{asset($image)}}"></div>
              </div>

              <button type="button" class="btn reset-btn prev-step">Previous</button>
              <button type="button" id="validateStep1" class="btn save-btn next-step">Next</button>

            </div>

            <div class="tab-pane poc-tabs" tab-count="2" role="tabpanel" id="step2">
             <table class="list" id="pocList">
                <thead>
                  <tr>
                    <th></th><th>Name</th><th>Email</th><th>Phone No</th>
                  </tr>
                </thead>
                <?php $count=1; ?>
                <tbody>
                  @foreach($customer_poc as $poc)
                    <tr>
                      <td><span class="counts">{{$count}}</span></td>
                      <td>
                        <div class="form-group">
                          <input type="hidden" name="poc[id][]" value="{{ $poc->id }}">
                          <input type="text" class="form-control" name="poc[name][]" value="{{old('name',$poc->name)}}">
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control validate-email1" name="poc[email][]" value="{{old('email',$poc->email)}}">
                          <span class="email-error1" style="display:none;color:red;">Invalid email</span>
                        </div>
                      </td>
                      <td>
                        <div class="form-group">
                          <input type="text" class="form-control" name="poc[contact][]" value="{{old('contact',$poc->contact_number)}}" onkeyup="validateNum(event,this);">
                        </div>
                      </td>
                    </tr>
                  <input type="hidden" value="{{$count++}}">
                  @endforeach
                </tbody>
                @if(count($customer_poc)<3 && count($customer_poc)==1)
                  <tr>
                    <input type="hidden" name="poc[id][]" value="0">
                    <td><span class="counts">2</span></td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[name][]">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[email][]">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[contact][]" onkeyup="validateNum(event,this);">
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <input type="hidden" name="poc[id][]" value="0">
                    <td><span class="counts">3</span></td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[name][]">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[email][]">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[contact][]" onkeyup="validateNum(event,this);">
                      </div>
                    </td>
                  </tr>
                @endif
                @if(count($customer_poc)<3 && count($customer_poc)==2)
                  <tr>
                    <input type="hidden" name="poc[id][]" value="0">
                    <td><span class="counts">3</span></td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[name][]">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[email][]">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[contact][]" onkeyup="validateNum(event,this);">
                      </div>
                    </td>
                  </tr>
                @endif
              </table>
              <button type="button" class="btn reset-btn prev-step">Previous</button>
              <button type="button" id="validateStep2" class="btn save-btn next-step">Next</button>
            </div>


            <div class="tab-pane bank-tabs" tab-count="3" role="tabpanel" id="step3">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="col-sm-6">
                    {!! Form::hidden('bank[account_id]',$customer->bank->id) !!}
                    <label for="accountName">Account Name</label>
                    {!! Form::text('bank[account_name]',$customer->bank->account_name,['class'=>'form-control']) !!}
                  </div>
                  <div class="col-sm-6">
                    <label for="accountNumber">Account Number</label>
                    {!! Form::text('bank[account_number]',$customer->bank->account_number,['class'=>'form-control','onkeyup'=>"validateNum(event,this);"]) !!}
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-6">
                    <label for="bankName">Bank Name</label>
                    {!! Form::text('bank[bank_name]',$customer->bank->bank_name,['class'=>'form-control']) !!}
                  </div>
                  <div class="col-sm-6">
                    <label for="bankBranch">Bank Branch</label>
                    {!! Form::text('bank[bank_branch]',$customer->bank->bank_branch,['class'=>'form-control']) !!}
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-6">
                    <label for="payNow">PayNow Contact No</label>
                    {!! Form::text('bank[paynow_contact]',$customer->bank->paynow_contact,['class'=>'form-control','onkeyup'=>"validateNum(event,this);"]) !!}
                  </div>
                  <div class="col-sm-6">
                    <label for="Place">Place</label>
                    {!! Form::text('bank[place]',$customer->bank->place,['class'=>'form-control']) !!}
                  </div>
                </div>
              </div>
              <button type="button" class="btn reset-btn prev-step">Previous</button>
              <button type="button" id="validateStep3" class="btn save-btn next-step">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&language=en-AU&key=AIzaSyDcjnEF0OMP2a5UIqJol_WPtz2GgyTvH24"></script>

<script type="text/javascript">
  var autocomplete = new google.maps.places.Autocomplete($("#autocomplete")[0], {});

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    var place = autocomplete.getPlace();
    var location = place.geometry.location;
    console.log(place,location);
    $('#latitude').val(location.lat()); 
    $('#longitude').val(location.lng());
  });

  $('body').attr('onload','initialize()');
</script>

@push('custom-scripts')
  <script type="text/javascript">
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
          if($(".company").val()=="") {
            $(".company").closest('.form-group').find('span.text-danger.company').show();
            valid=false;
          }
          if($(".company_uen").val()=="") {
            $(".company_uen").closest('.form-group').find('span.text-danger.company_uen').show();
            valid=false;
          }
          if($(".cmpy-email").val()=="") {
            $(".cmpy-email").closest('.form-group').find('span.text-danger.cmpy-email').show();
            valid=false;
          }
          if($(".telephone").val()=="") {
            $(".telephone").closest('.form-group').find('span.text-danger.telephone').show();
            valid=false;
          }
          if($(".address1").val()=="") {
            $(".address1").closest('.form-group').find('span.text-danger.address1').show();
            valid=false;
          }
          if($(".country").val()=="") {
            $(".country").closest('.form-group').find('span.text-danger.country').show();
            valid=false;
          }
          if($(".company_postcode").val()==""){
            $(".company_postcode").closest('.form-group').find('span.text-danger.post-code').show();
            valid=false; 
          }
          return valid;
        }
        function validateStep2(e){
          var valid=true;
          return valid;
        }

        function validateStep3(e){
          var valid=true;
          return valid;
        }
  
        $(".next-step").click(function (e) {
          var $active = $('.nav-tabs li>.active');
          var stepID = $(e.target).attr('id');
          var formFields=$(e.target).closest('.tab-pane.active').find('input,select');
          var fieldsToValidate=[];
          if((stepID=="validateStep1" && validateStep1(e)) || (stepID=="validateStep2" && validateStep2(e)) || (stepID=="validateStep3" && validateStep3(e)) ){
            if(stepID=="validateStep3"){
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

    $(function ($) {
      $('.select2bs4').select2();
    });


    $(document).ready(function(){
        var country_id = "<?php echo json_decode($customer->country_id); ?>";
        var state_id = "<?php echo json_decode($customer->state_id); ?>";
        getState(country_id,'#company_state',state_id);
      });
      //Get City
      $(document).ready(function(){
        var state_id = "<?php echo json_decode($customer->state_id); ?>";
        var city_id = "<?php echo json_decode($customer->city_id); ?>";
        getCity(state_id,'#company_city',city_id);
      });

      $(document).on('change', '#company_country', function(event) {
          var country_id = $(this).val();
          getState(country_id,'#company_state',0);
      });
      $('#company_state').change(function() {
        var state_id = $(this).val();
        getCity(state_id,'#company_city',0);
      })
      function getState(countryID,append_id,state_id){

        if(countryID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('get-state')}}?country_id="+countryID,
            success:function(res){  
              if(res){
                $(append_id).empty();
                $(append_id).append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_state="";
                  if(state_id == key) { var select_state = "selected" }
                  $(append_id).append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
                });
                // $(append_id).selectpicker('refresh');           
              }else{
                $(append_id).empty();
              }
            },
            error: function(res) { console.log(res.responseText) }
          });
        }else{
          $(append_id).empty();        
        }      
      }
      function getCity(stateID,append_id,city_id){
        if(stateID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('get-city')}}?state_id="+stateID,
            success:function(res){  
              if(res){
                $(append_id).empty();
                $(append_id).append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_city="";
                  if(city_id == key) { var select_city = "selected" }
                  $(append_id).append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
                });
                // $(append_id).selectpicker('refresh');           
              }else{
                $(append_id).empty();
              }
            },
            error: function(res) { console.log(res.responseText) }
          });
        }else{
          $(append_id).empty();        
        }      
      }

    /*$('.save-change').on('click',function() {
     
    });
*/
  </script>
@endpush
@endsection