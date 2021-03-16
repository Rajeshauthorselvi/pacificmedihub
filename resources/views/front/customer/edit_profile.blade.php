@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a href="{{ route('profile.index',$user_id) }}" title="My Profile Page">My Profile</a></li>
      <li><a title="Edit Profile Page">Edit</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row customer-page">
		  <div id="column-left" class="col-sm-3 hidden-xs column-left">
		   	<div class="column-block">
		     	<ul class="box-menu treeview-list treeview collapsable" >
		     		<li>
		     			<a class="link active" href="javascript:void(0);">
           			<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
              </a>
            </li>
		        <li>
          		<a class="link" href="javascript:void(0);">
              	<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
              </a>
            </li>
            <li>
            	<a class="link" href="javascript:void(0);">
             		<i class="fas fa-dolly-flatbed"></i>&nbsp;&nbsp;My Orders
             	</a>
            </li>
            <li>
              <a class="link" href="{{ route('wishlist.index') }}">
            		<i class="far fa-heart"></i>&nbsp;&nbsp;My Wishlist
            	</a>
            </li>
            <li>
            	<a class="link" href="{{ route('my-address.index') }}">
            		<i class="fas fa-street-view"></i>&nbsp;&nbsp;My Address
            	</a>
            </li>
            <li>
            	<a class="link" href="{{route('customer.logout')}}">
            		<i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Logout
            	</a>
            </li>
          </ul>
        </div>
      </div>

		
		  <div class="col-sm-9">
        <form action="{{ route('profile.update',$customer->id) }}" method="post" id="profileForm">
          @csrf
  		    <ul class="nav nav-tabs flex-nowrap" role="tablist">
            <li role="presentation" class="nav-item">
              <a href="#step1" class="nav-link customer-link active " data-toggle="tab" aria-controls="step1" role="tab"  tab-count="1" title="Step 1"> Company Details </a>
            </li>
            <li role="presentation" class="nav-item">
              <a href="#step2" class="nav-link" data-toggle="tab" aria-controls="step2" role="tab customer-link" tab-count="2" title="Step 2"> POC Details </a>
            </li>
            <li role="presentation" class="nav-item">
              <a href="#step3" class="nav-link" data-toggle="tab" aria-controls="step3" role="tab customer-link"  tab-count="3" title="Step 3">Bank Accounts</a>
            </li>
          </ul>
        	<div class="tab-content py-2">
            <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Company Name</label>
                  {!! Form::text('company[company_name]', $customer->company->company_name,['class'=>'form-control']) !!}
                  {!! Form::hidden('company[company_id]',$customer->company->id) !!}
                </div>
                <div class="col-sm-6">
                  <label for="">Company GST No</label>
                  {!! Form::text('company[company_gst]', $customer->company->company_gst,['class'=>'form-control']) !!}
                </div>
              </div>
            
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Company Email</label>
                  {!! Form::text('company[company_email]', $customer->company->company_email,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-6">
                  <label for="">Telephone No</label>
                  {!! Form::text('company[telephone]', $customer->company->telephone,['class'=>'form-control']) !!}
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Address Line1</label>
                  {!! Form::text('company[address_1]', $customer->company->address_1,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-6">
                  <label for="">Address Line2</label>
                  {!! Form::text('company[address_2]', $customer->company->address_2,['class'=>'form-control']) !!}
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Country *</label>
                  {!! Form::select('company[country_id]',$countries,$customer->company->country_id,['class'=>'form-control select2bs4 required', 'id'=>'company_country' ]) !!}
                  <span class="text-danger"></span>
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
                  <label for="">Post Code</label>
                  {!! Form::text('company[post_code]', $customer->company->post_code,['class'=>'form-control company_postcode']) !!}
                </div>
              </div>
              
              <?php 
                if(!empty($customer->company->logo)){$image = 'theme/images/customer/company/'.$customer->company->id.'/'.$customer->company->logo;}
                else {$image = "theme/images/no_image.jpg";}
              ?>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="companyLogo">Company Logo</label>
                  <div><img class="img-company" style="width:110px;height:100px;" src="{{asset($image)}}"></div>
                </div>
              </div>

            </div>

            <div class="tab-pane customer-tabs" tab-count="2" role="tabpanel" id="step2">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">POC Name</label>
                  {!! Form::text('customer[first_name]',$customer->first_name,['class'=>'form-control']) !!}
                </div>
                <div class="col-sm-6">
                 	<label for="">Company UEN</label>
                  {!! Form::text('company[company_uen]', $customer->company->company_uen,['class'=>'form-control']) !!}
                </div>
             	</div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Email</label>
                  {!! Form::email('customer[email]',  $customer->email,['class'=>'form-control','readonly']) !!}
                </div>
                <div class="col-sm-6">
                  <label for="">Contact No</label>
                  {!! Form::text('customer[contact_number]', $customer->contact_number,['class'=>'form-control']) !!}
                </div>
              </div>
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
            </div>
            <div>
              <button class="btn btn-primary save-change">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('custom-scripts')
  <script type="text/javascript">
    $(function ($) {
      $('.select2bs4').select2();
    });


    $(document).ready(function(){
        var country_id = "<?php echo json_decode($customer->company->country_id); ?>";
        var state_id = "<?php echo json_decode($customer->company->state_id); ?>";
        getState(country_id,'#company_state',state_id);
      });
      //Get City
      $(document).ready(function(){
        var state_id = "<?php echo json_decode($customer->company->state_id); ?>";
        var city_id = "<?php echo json_decode($customer->company->city_id); ?>";
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
            url:"{{url('admin/get-state-list')}}?country_id="+countryID,
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
            url:"{{url('admin/get-city-list')}}?state_id="+stateID,
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