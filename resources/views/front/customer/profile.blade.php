@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="My Profile Page">My Profile</a></li>
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
		    <ul class="nav nav-tabs flex-nowrap" role="tablist">
          <li role="presentation" class="nav-item">
            <a href="#step1" class="nav-link customer-link active " data-toggle="tab" aria-controls="step1" role="tab"  tab-count="1" title="Profile"> Profile Details </a>
          </li>
          <li role="presentation" class="nav-item">
            <a href="#step2" class="nav-link" data-toggle="tab" aria-controls="step2" role="tab customer-link" tab-count="2" title="Company"> Company Details </a>
          </li>
          <li role="presentation" class="nav-item">
            <a href="#step3" class="nav-link customer-link" data-toggle="tab" aria-controls="step3" role="tab"  tab-count="3" title="POC"> POC Details </a>
          </li>
          <li role="presentation" class="nav-item">
            <a href="#step4" class="nav-link" data-toggle="tab" aria-controls="step4" role="tab customer-link"  tab-count="4" title="Bank">Bank Accounts</a>
          </li>
          <li role="presentation" class="nav-item">
            <a href="#step5" class="nav-link password" data-toggle="tab" aria-controls="step5" role="tab customer-link"  tab-count="5" title="Password">Change Password</a>
          </li>
        </ul>

      	<div class="tab-content py-2">

          <div class="tab-pane customer-tabs active" tab-count="1" role="tabpanel" id="step1">
            <div class="form-group">
              <div class="col-sm-10">
                <label for="">Name</label>
                {!! Form::text('customer[first_name]',$customer->first_name,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-2">
                <div class="edit-customer">
                  <a class="btn btn-primary" href="{{route('my-profile.edit',$customer->id)}}">
                    <i class="far fa-edit"></i> Edit
                  </a>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-5">
                <label for="">Email</label>
                {!! Form::email('customer[email]',  $customer->email,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-5">
                <label for="">Contact No</label>
                {!! Form::text('customer[contact_number]', $customer->contact_number,['class'=>'form-control','readonly']) !!}
              </div>
            </div>
          </div>

          <div class="tab-pane company-tabs" tab-count="2" role="tabpanel" id="step2">
            <?php 
              if(!empty($customer->company->logo)){$image = 'theme/images/customer/company/'.$customer->company->id.'/'.$customer->company->logo;}
              else {$image = "theme/images/no_image.jpg";}
            ?>
            <div class="form-group">
              <div class="col-sm-6">
                <label for="companyLogo">Company Logo</label>
                <div><img class="img-company" style="width:110px;height:100px;" src="{{asset($image)}}"></div>
              </div>
              <div class="col-sm-6">
                <div class="edit-customer">
                  <a class="btn btn-primary" href="{{route('my-profile.edit',$customer->id)}}">
                    <i class="far fa-edit"></i> Edit
                  </a>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <label for="">Company Name</label>
                {!! Form::text('company[company_name]', $customer->company->company_name,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Company GST No</label>
                {!! Form::text('company[company_gst]', $customer->company->company_gst,['class'=>'form-control','readonly']) !!}
              </div>
            </div>
          
            <div class="form-group">
              <div class="col-sm-6">
                <label for="">Company Email</label>
                {!! Form::text('company[company_email]', $customer->company->company_email,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Telephone No</label>
                {!! Form::text('company[telephone]', $customer->company->telephone,['class'=>'form-control','readonly']) !!}
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6">
                <label for="">Address Line1</label>
                {!! Form::text('company[address_1]', $customer->company->address_1,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Address Line2</label>
                {!! Form::text('company[address_2]', $customer->company->address_2,['class'=>'form-control','readonly']) !!}
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-6 csc-sec">
                <label for="">Country</label>
                {!! Form::text('country_id',$customer->company->getCountry->name,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
              </div>
              <div class="col-sm-6 csc-sec">
                <label for="">State</label>
                {!! Form::text('state_id',isset($customer->company->getState->name)?$customer->company->getState->name:'',['readonly','class'=>'form-control', 'id'=>'State']) !!}
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6 csc-sec">
                <label for="">City</label>
                {!! Form::text('city_id',isset($customer->company->getCity->name)?$customer->company->getCity->name:'',['readonly','class'=>'form-control', 'id'=>'City']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Post Code</label>
                {!! Form::text('company[post_code]', $customer->company->post_code,['readonly','class'=>'form-control company_postcode']) !!}
              </div>
            </div>
          </div>

          <div class="tab-pane customer-tabs" tab-count="3" role="tabpanel" id="step3">
            <div class="form-group">
              <div class="col-sm-5">
                <label for="">Name</label>
                {!! Form::text('customer[first_name]',$customer->poc->name,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-5">
                <label for="">Company UEN</label>
                {!! Form::text('company[company_uen]', $customer->company->company_uen,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-2">
                <div class="edit-customer">
                  <a class="btn btn-primary" href="{{route('my-profile.edit',$customer->id)}}">
                    <i class="far fa-edit"></i> Edit
                  </a>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-5">
                <label for="">Email</label>
                {!! Form::email('customer[email]',  $customer->poc->email,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-5">
                <label for="">Contact No</label>
                {!! Form::text('customer[contact_number]', $customer->poc->contact_number,['class'=>'form-control','readonly']) !!}
              </div>
            </div>
          </div>

          <div class="tab-pane bank-tabs" tab-count="4" role="tabpanel" id="step4">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="col-sm-5">
                  <label for="accountName">Account Name</label>
                  {!! Form::text('bank[account_name]',$customer->bank->account_name,['class'=>'form-control','readonly']) !!}
                </div>
                <div class="col-sm-5">
                  <label for="accountNumber">Account Number</label>
                  {!! Form::text('bank[account_number]',$customer->bank->account_number,['class'=>'form-control','readonly','onkeyup'=>"validateNum(event,this);"]) !!}
                </div>
                <div class="col-sm-2">
                <div class="edit-customer">
                  <a class="btn btn-primary" href="{{route('my-profile.edit',$customer->id)}}">
                    <i class="far fa-edit"></i> Edit
                  </a>
                </div>
              </div>
              </div>
              <div class="form-group">
                <div class="col-sm-5">
                  <label for="bankName">Bank Name</label>
                  {!! Form::text('bank[bank_name]',$customer->bank->bank_name,['class'=>'form-control','readonly']) !!}
                </div>
                <div class="col-sm-5">
                  <label for="bankBranch">Bank Branch</label>
                  {!! Form::text('bank[bank_branch]',$customer->bank->bank_branch,['class'=>'form-control','readonly']) !!}
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-5">
                  <label for="payNow">PayNow Contact No</label>
                  {!! Form::text('bank[paynow_contact]',$customer->bank->paynow_contact,['class'=>'form-control','readonly','onkeyup'=>"validateNum(event,this);"]) !!}
                </div>
                <div class="col-sm-5">
                  <label for="Place">Place</label>
                  {!! Form::text('bank[place]',$customer->bank->place,['class'=>'form-control','readonly']) !!}
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane password-tabs" tab-count="5" role="tabpanel" id="step5">
            <form action="{{ route('change.cuspwd') }}" method="post" id="pwdForm">
              @csrf
              <div class="password-block">
                <input type="hidden" name="email" value="{{$customer->email}}">
                <div class="form-group">
                  <div class="col-sm-10">
                    <label for="oldPwd">Old Password</label>
                    <input type="password" name="old_pwd" class="form-control" id="oldPwd" autocomplete="off">
                    <span class="old-pwd text-danger" style="display:none">Please Enter Old Password</span>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-10">
                    <label for="newPwd">New Password</label>
                    <input type="password" name="new_pwd" class="form-control" id="newPwd" autocomplete="off">
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-10">
                    <label for="conPwd">Confirm Password</label>
                    <input type="password" name="con_pwd" class="form-control" id="conPwd" autocomplete="off">
                    <span id='erromessage' class="text-danger" style="display:none">Not Matching</span>
                  </div>
                </div>
                <button class="btn btn-primary save-change" type="submit">Change</button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@push('custom-scripts')
  <script type="text/javascript">

    $('#newPwd, #conPwd').on('keyup',function(){
      if($('#oldPwd').val()!=''){
        $('.old-pwd').css('display', 'none');
      }else{
        $('.old-pwd').css('display', 'block');
      }
      if ($('#newPwd').val() == $('#conPwd').val()) {
        $('#erromessage').css('display', 'none'); 
        $('.save-change').css('pointer-events','auto');
        $('.save-change').css('opacity','1');
      }else{
        $('.save-change').css('pointer-events','none');
        $('.save-change').css('opacity','.5');
        $('#erromessage').css('display', 'block'); 
      }
    });

    $('.save-change').on('click',function() {
      var count = $('#newPwd, #conPwd').val().length;
      
      if(count<=5){
        alert('Password need minimum 6 letters.!');
        return false;
      }
      var empty_field = $('.password-block .form-control').filter(function(){
        return !$(this).val();
      }).length;
      if (empty_field!=0) {
        alert('Please fill the empty fields');
        return false;
      }
      else{
        $('#pwdForm').submit();
      }
    });

  </script>
@endpush
@endsection