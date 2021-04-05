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
            <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab customer-link" tab-count="1" title="Company"> Company Details </a>
          </li>
          @if($customer->role_id!=1)
          <li role="presentation" class="nav-item">
            <a href="#step2" class="nav-link customer-link" data-toggle="tab" aria-controls="step2" role="tab"  tab-count="2" title="POC"> POC Details </a>
          </li>
          <li role="presentation" class="nav-item">
            <a href="#step3" class="nav-link" data-toggle="tab" aria-controls="step3" role="tab customer-link"  tab-count="3" title="Bank">Bank Accounts</a>
          </li>
          <li role="presentation" class="nav-item">
            <a href="#step4" class="nav-link password" data-toggle="tab" aria-controls="step4" role="tab customer-link"  tab-count="4" title="Password">Change Password</a>
          </li>
          @endif
        </ul>

      	<div class="tab-content py-2">
          <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
            <?php 
              if(!empty($customer->logo)){$image = 'theme/images/customer/company/'.$customer->id.'/'.$customer->logo;}
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
                {!! Form::text('company[company_name]', $customer->name,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Company UEN</label>
                {!! Form::text('company[company_uen]', $customer->company_uen,['class'=>'form-control','readonly']) !!}
              </div>
            </div>
          
            <div class="form-group">
              <div class="col-sm-6">
                <label for="">Company/Login Email</label>
                {!! Form::text('company[company_email]', $customer->email,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Contact Number</label>
                {!! Form::text('company[contact_number]', $customer->contact_number,['class'=>'form-control','readonly']) !!}
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6">
                <label for="">Address Line1</label>
                {!! Form::text('company[address_1]', $customer->address_1,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Address Line2</label>
                {!! Form::text('company[address_2]', $customer->address_2,['class'=>'form-control','readonly']) !!}
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-6 csc-sec">
                <label for="">Country</label>
                {!! Form::text('country_id',$customer->getCountry->name,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
              </div>
              <div class="col-sm-6 csc-sec">
                <label for="">State</label>
                {!! Form::text('state_id',isset($customer->getState->name)?$customer->getState->name:'',['readonly','class'=>'form-control', 'id'=>'State']) !!}
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6 csc-sec">
                <label for="">City</label>
                {!! Form::text('city_id',isset($customer->getCity->name)?$customer->getCity->name:'',['readonly','class'=>'form-control', 'id'=>'City']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Post Code</label>
                {!! Form::text('company[post_code]', $customer->post_code,['readonly','class'=>'form-control company_postcode']) !!}
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-6">
                <label for="">Company GST No</label>
                {!! Form::text('company[company_gst]', $customer->company_gst,['class'=>'form-control','readonly']) !!}
              </div>
              <div class="col-sm-6">
                <label for="">Sales Ref</label>
                {!! Form::text('company[company_gst]', $customer->getSalesRep->emp_name,['class'=>'form-control','readonly']) !!}
              </div>
            </div>
          </div>
          @if($customer->role_id!=1)
          <div class="tab-pane customer-tabs" tab-count="2" role="tabpanel" id="step2">
            <div class="edit-customer">
              <a class="btn btn-primary" href="{{route('my-profile.edit',$customer->id)}}">
                <i class="far fa-edit"></i> Edit
              </a>
            </div>
            <table class="list" id="pocList">
              <thead>
                <tr>
                  <th>Name</th><th>Email</th><th>Phone No</th>
                </tr>
              </thead>
              <tbody>
                @foreach($customer_poc as $poc)
                  <tr>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[name][]" readonly value="{{$poc->name}}">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control validate-email1" readonly name="poc[email][]" value="{{$poc->email}}">
                      </div>
                    </td>
                    <td>
                      <div class="form-group">
                        <input type="text" class="form-control" name="poc[contact][]" readonly id="contact1" value="{{$poc->contact_number}}">
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="tab-pane bank-tabs" tab-count="3" role="tabpanel" id="step3">
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

          <div class="tab-pane password-tabs" tab-count="5" role="tabpanel" id="step4">
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
          @endif
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