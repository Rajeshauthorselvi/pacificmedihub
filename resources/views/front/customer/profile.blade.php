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
            	<a class="link" href="javascript:void(0);">
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
          <li role="presentation" class="nav-item">
            <a href="#step4" class="nav-link" data-toggle="tab" aria-controls="step4" role="tab customer-link"  tab-count="4" title="Step 4">Change Password</a>
          </li>
        </ul>

      	<div class="tab-content py-2">

          <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Company Name</label>
                </div>
                <div class="col-sm-6">
                  <label for="">Company GST No</label>
                </div>
              </div>
            
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Company Email</label>
                </div>
                <div class="col-sm-6">
                  <label for="">Telephone No</label>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Address Line1</label>
                </div>
                <div class="col-sm-6">
                  <label for="">Address Line2</label>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-6 csc-sec">
                  <label for="">Country</label>
                </div>
                <div class="col-sm-6 csc-sec">
                  <label for="">State</label>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6 csc-sec">
                  <label for="">City</label>
                </div>
                <div class="col-sm-6">
                  <label for="">Post Code</label>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6">
                  <label for="companyLogo">Company Logo JPEG</label>
                </div>
                <div class="col-sm-6">
                  <label for="companyGst">Upload Company GST Certificate Copy(JPEG,PNG,PDF)</label>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane customer-tabs" tab-count="2" role="tabpanel" id="step2">
            <div class="col-sm-12">
              <div class="form-group">
	              <div class="col-sm-6">
	                <label for="">POC Name *</label>
	              </div>
	              <div class="col-sm-6">
	               	<label for="">Company UEN *</label>
	              </div>
             	</div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="">Email *</label>
                </div>
                <div class="col-sm-6">
                  <label for="">Contact No *</label>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane bank-tabs" tab-count="3" role="tabpanel" id="step3">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="accountName">Account Name</label>
                </div>
                <div class="col-sm-6">
                  <label for="accountNumber">Account Number</label>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="bankName">Bank Name</label>
                </div>
                <div class="col-sm-6">
                  <label for="bankBranch">Bank Branch</label>
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-6">
                  <label for="payNow">PayNow Contact No</label>
                </div>
                <div class="col-sm-6">
                  <label for="Place">Place</label>
                </div>
              </div>
            </div>
          </div>

          <div class="tab-pane password-tabs " tab-count="4" role="tabpanel" id="step4">

          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<style type="text/css">
	.row.customer-page {margin-bottom: 30px;}
	.customer-page #column-left a.link i {
    position: relative;
    top: 5px;
}
.form-group{display:flex;}
</style>
@endsection