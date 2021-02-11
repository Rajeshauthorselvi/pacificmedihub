@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Vendor</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('vendor.index')}}">Vendors</a></li>
              <li class="breadcrumb-item active">Vendor Details</li>
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
          <a href="{{route('vendor.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid toggle-tabs vendor">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Vendor Details</h3>
              </div>
              <div class="card-body">

                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                  <li role="presentation" class="nav-item">
                    <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1"> General </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2"> POC </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3"> Bank Accounts </a>
                  </li>
                </ul>
                <a href="{{route('vendor.edit',$vendor->id)}}" class="btn emp-edit"><i class="far fa-edit"></i>&nbsp;Edit</a>
                <form action="{{route('vendor.update',$vendor->id)}}" method="post" enctype="multipart/form-data">
                  @csrf 
                  <input name="_method" type="hidden" value="PATCH">
                  <div class="tab-content py-2">
                    <!-- Step1 -->
                    <div class="tab-pane active" role="tabpanel" id="step1">
                      <div class="" id="general">

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorCode">Vendor Code</label>
                            <input type="text" class="form-control" disabled name="vendor_code" id="vendorCode" value="{{$vendor->code}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="vendorName">Vendor Name *</label>
                            <input type="text" class="form-control" readonly name="vendor_name" id="vendorName" value="{{$vendor->name}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorUen">Vendor UEN *</label>
                            <input type="text" class="form-control" readonly name="vendor_uen" id="vendorUen" value="{{$vendor->uen}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="vendorGst">Vendor GST No</label>
                            <input type="text" class="form-control" readonly name="vendor_gst" id="vendorGst" value="{{$vendor->gst_no}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="Email">Email *</label>
                            <input type="text" class="form-control validate-email" readonly name="vendor_email" id="Email" value="{{$vendor->email}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="Mobile">Mobile No *</label>
                            <input type="text" class="form-control contact" readonly name="vendor_contact" id="Mobile" value="{{$vendor->contact_number}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="address1">Address Line 1 *</label>
                            <input type="text" class="form-control" name="address1" readonly id="address1" value="{{$vendor->address_line1}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="address2">Address Line 2</label>
                            <input type="text" class="form-control" name="address2" readonly id="address2" value="{{$vendor->address_line2}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="Country">Country *</label>
                            {!! Form::text('country_id',$vendor->getCountry->name,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
                          </div>
                          <div class="col-sm-5">
                            <label for="State">State *</label>
                            {!! Form::text('state_id',$vendor->getState->name,['readonly','class'=>'form-control', 'id'=>'State']) !!}
                          </div>
                        </div>

                        <div class="form-group">
                          
                          <div class="col-sm-5">
                            <label for="City">City *</label>
                            {!! Form::text('city_id',$vendor->getCity->name,['readonly','class'=>'form-control', 'id'=>'City']) !!}
                          </div>
                          <div class="col-sm-5">
                            <label for="PostCode">Post Code *</label>
                            <input type="text" class="form-control" readonly name="postcode" id="PostCode" value="{{$vendor->post_code}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <?php 
                            if(!empty($vendor->logo_image)){$image = "theme/images/vendor/".$vendor->logo_image;}
                            else {$image = "theme/images/no_image.jpg";}
                          ?>
                          <div class="col-sm-5">
                            <label for="vendorLogo">Vendor Logo (JPEG,PNG)</label><br>
                            <img title="Click to Change" class="img-logo" id="output_image" style="width:100px;height:100px;" src="{{asset($image)}}">
                          </div>
                          <?php 
                            if(!empty($vendor->gst_image)){$image = "theme/images/vendor/".$vendor->gst_image;}
                            else {$image = "theme/images/no_image.jpg";}
                          ?>
                          <div class="col-sm-5">
                            {!! Form::label('', 'GST Certificate Copy(JPEG,PNG,PDF)') !!}<br>
                            <img title="Click to Change" class="img-gst" id="output_image" style="width:100px;height:100px;" src="{{asset($image)}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <div class="icheck-info d-inline">
                              <input type="checkbox" name="vendor_status" id="Status" disabled @if($vendor->status==1) checked @endif>
                              <label for="Status">Status</label>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Step2 -->
                    <div class="tab-pane" role="tabpanel" id="step2">
                      <div class="" id="poc">
                        <table class="list" id="pocList">
                          <thead>
                            <tr>
                              <th></th><th>Name</th><th>Email</th><th>Phone No</th>
                            </tr>
                          </thead>
                          @foreach($vendor_poc as $poc)
                            <tbody>
                              <tr>
                                <td><span class="counts">{{$count}}</span></td>
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
                                    <input type="text" class="form-control" name="poc[contact][]" readonly id="contact1" value="{{$poc->contact_no}}">
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                            <input type="hidden" value="{{$count++}}">
                          @endforeach
                        </table>
                      </div>
                    </div>
                    <!-- Step3 -->
                    <div class="tab-pane" role="tabpanel" id="step3">
                      <div class="" id="banck_account">
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="accountName">Account Name</label>
                            <input type="text" class="form-control" name="account_name" readonly id="accountName" value="{{$vendor->account_name}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="accountNumber">Account Number</label>
                            <input type="text" class="form-control" name="account_number" readonly id="accountNumber" value="{{$vendor->account_number}}">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="bankName">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" readonly id="bankName" value="{{$vendor->bank_name}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="bankBranch">Bank Branch</label>
                            <input type="text" class="form-control" name="bank_branch" readonly id="bankBranch" value="{{$vendor->bank_branch}}">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="payNow">PayNow Contact No</label>
                            <input type="text" class="form-control contact2" name="paynow_no" readonly id="payNow" value="{{$vendor->paynow_contact_number}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="Place">Place</label>
                            <input type="text" class="form-control" name="place" readonly id="Place" value="{{$vendor->bank_place}}">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-10">
                          <label for="Others">Others</label>
                          <textarea class="form-control" rows="3" name="others" readonly id="Others">{{$vendor->others}}</textarea>
                        </div>
                      </div>
                      
                    </div>

                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<style type="text/css">
.tab-content .form-group {
  display: flex;
}
</style>
@endsection