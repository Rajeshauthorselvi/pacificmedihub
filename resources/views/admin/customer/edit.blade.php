@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            @if($from!='approve')
              <h1 class="m-0">Edit Customer</h1>
            @else
              <h1 class="m-0">Approve Customer</h1>
              @endif
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customer</a></li>
              @if($from!='approve')
                <li class="breadcrumb-item">Edit Customer</li>
              @else
                <li class="breadcrumb-item">Approve Customer</li>
              @endif
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
          {{ Form::model($customer,['method' => 'PATCH', 'route' =>['customers.update',$customer->id],'id'=>'form','files'=>true]) }}
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Customer</h3>
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
                        @if($from=='approve')
                          {!! Form::hidden('approve_status',3) !!}  
                        @endif
                        {!! Form::hidden('company[company_id]',$customer->id) !!}
                        <div class="col-sm-6">
                          <label for="">Customer Code *</label>
                          {!! Form::text('company[customer_no]',$customer->customer_no,['class'=>'form-control required','readonly']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Company UEN *</label>
                          {!! Form::text('company[company_uen]', $customer->company_uen,['class'=>'form-control company_uen']) !!}
                          <span class="text-danger company_uen" style="display:none">Company UEN is required</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Name *</label>
                          {!! Form::text('company[name]', $customer->name,['class'=>'form-control company_name']) !!}
                          <span class="text-danger company_name" style="display:none">Company Name is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Parent Company</label>
                          {!! Form::select('company[parent_company]', $all_company,$customer->parent_company,['class'=>'form-control select2bs4']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Email *</label>
                          {!! Form::text('company[email]', $customer->email,['class'=>'form-control company_email']) !!}
                           <span class="text-danger company_email" style="display:none">Company Email is required</span>
                          <span class="text-danger company_email_validate" style="display:none">Please enter valid Email Address</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Contact No *</label>
                          {!! Form::text('company[contact_number]', $customer->contact_number,['class'=>'form-control company_contact']) !!}
                          <span class="text-danger company_contact" style="display:none">Company Contact is required</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line1 *</label>
                          {!! Form::text('company[address_1]', $customer->address_1,['class'=>'form-control company_add1']) !!}
                          <span class="text-danger company_add1" style="display:none">Address Line 1 is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line2</label>
                          {!! Form::text('company[address_2]', $customer->address_2,['class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Country *</label>
                          {!! Form::select('company[country_id]',$countries,$customer->country_id,['class'=>'form-control select2bs4 country_id', 'id'=>'company_country' ]) !!}
                          <span class="text-danger country_id" style="display:none">Country is required</span>
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
                          {!! Form::text('company[post_code]', $customer->post_code,['class'=>'form-control company_postcode','onkeyup'=>"validateNum(event,this);"]) !!}
                          <span class="text-danger post-code" style="display:none">Post Code is required</span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Sales Rep *</label>
                          {!! Form::select('company[sales_rep]',$sales_rep,$customer->sales_rep,['class'=>'form-control select2bs4 sales_rep']) !!}
                          <span class="text-danger sales_rep" style="display:none">Sales Rep is required</span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Company GST No</label>
                          {!! Form::text('company[company_gst]', $customer->company_gst,['class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="companyLogo">Company Logo JPEG</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="company[logo]" id="companyLogo" accept="image/*">
                            <label class="custom-file-label" for="companyLogo">Choose file</label>
                          </div>
                          @if (isset($customer->logo))
                          <br><br>
                          <img src="{{asset('theme/images/customer/company/'.$customer->id.'/' .$customer->logo)}}" width="100px" height="100px">
                          @endif
                        </div>
                        <div class="col-sm-6">
                          <label for="companyGst">Company GST Certificate Copy(JPEG,PNG,PDF)</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="company[company_gst_certificate]" id="companyGst" accept="image/*">
                            <label class="custom-file-label" for="companyGst">Choose file</label>
                          </div>
                          @if (isset($customer->company_gst_certificate))
                          <br><br>
                          <img src="{{asset('theme/images/customer/company/'.$customer->id.'/' .$customer->company_gst_certificate)}}" width="100px" height="100px">
                          @endif
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="company[status]" id="Status" @if($customer->status==1) checked @endif>
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

                  <div class="tab-pane customer-tabs" tab-count="2" role="tabpanel" id="step2">
                    <div class="col-sm-12">
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
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <button type="button" class="btn reset-btn prev-step">Previous</button>
                        <button type="button" id="validateStep2" class="btn save-btn next-step">Next</button>
                      </div>
                    </div>
                  </div>
                   
                  <div class="tab-pane address-tabs " tab-count="3" role="tabpanel" id="step3">
                    <div class="add-new-address">
                      <button type="button" class="btn btn-info add" address-id="{{$customer['id']}}" data-toggle="modal" data-target="#add-address">Add New Address</button>
                    </div>
                    <div class="address-list-sec col-sm-12">
                      @foreach ($customer->alladdress as $address)
                        <div class="col-sm-12">
                          <div class="list">
                            <table class="table del-address">
                              <tr>
                                <td rowspan="2" style="vertical-align: middle; border:none;">
                                  @if ($customer->address_id==$address->id)
                                    <input type="radio" name="company[address_id]" checked value="{{$address->id}}">
                                  @else
                                    <input type="radio" name="company[address_id]" value="{{$address->id}}">
                                  @endif
                                </td>
                                <td>
                                  {{ $address->name }}<br>{{ $address->mobile }}<br>
                                  {{ $address->address_line1 }}<br>{{ $address->address_line2 }}<br>
                                  {{ $address->country->name }} 
                                  @if(isset($address->state->name)) , {{$address->state->name}} @endif
                                  @if(isset($address->city->name)) ,{{$address->city->name}} @endif
                                  <br>{{ $address->post_code }}
                                </td>
                                <td>
                                  <button type="button" class="btn btn-info edit" address-id="{{$address['id']}}" data-toggle="modal" data-target="#edit-address">Edit</button>
                                </td>
                              </tr>
                              
                            </table>
                          </div>
                        </div>
                        <br>
                      @endforeach
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <button type="button" class="btn reset-btn prev-step">Previous</button>
                        <button type="button" id="validateStep3" class="btn save-btn next-step">Next</button>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane address-tabs " tab-count="4" role="tabpanel" id="step4">
                    <div class="col-sm-12" style="display:flow-root">
                      {!! Form::hidden('bank[account_id]',$customer->bank->id) !!}
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="accountName">Account Name</label>
                          {!! Form::text('bank[account_name]',$customer->bank->account_name,['class'=>'form-control']) !!}
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
                      @if($from!='approve')
                        <button type="button" id="validateStep4" class="btn save-btn next-step">Save</button>
                      @else
                        <button type="button" id="validateStep4" class="btn save-btn next-step">Save & Approve</button>
                      @endif
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

  <!-- Edit Address Box -->
  <div class="modal fade" id="edit-address">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Delivery Address</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="edit-form-block"></div>
        </div>
            
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Add Address Box -->
  <div class="modal fade" id="add-address">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Customer Address</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="address-details">
            <div class="form-group" style="display:flex;">
              <div class="col-sm-6" style="padding-left:0">
                {!! Form::label('name', 'Name *') !!}
                {!! Form::text('address[name]', '',['class'=>'form-control required add_name']) !!}
                <span class="text-danger name" style="display:none">Name is required</span>
              </div>

              <div class="col-sm-6" style="padding:0;">
                {!! Form::label('mobile', 'Contact No *') !!}
                {!! Form::text('address[mobile]', '',['class'=>'form-control required add_mobile','onkeyup'=>"validateNum(event,this);"]) !!}
                <span class="text-danger mobile" style="display:none">Contact Number is required</span>
              </div>
            </div>
            
            <div class="form-group" style="display:flex;">
              <div class="col-sm-6" style="padding-left:0">
                {!! Form::label('address1', 'Address Line 1 *') !!}
                {!! Form::textarea('address[address_line1]', '',['class'=>'form-control required add_line_1','rows'=>2]) !!}
                <span class="text-danger address1" style="display:none">Address Line 1 is required</span>
              </div>
              <div class="col-sm-6" style="padding:0;">
                {!! Form::label('address2', 'Address Line 2') !!}
                {!! Form::textarea('address[address_line2]', '',['class'=>'form-control add_line_2','rows'=>2]) !!}
              </div>
            </div>
            <div class="form-group" style="display:flex;">
              <div class="col-sm-6" style="padding-left:0;">
                {!! Form::label('addresss_country', 'Country *') !!}
                {!! Form::select('address[country_id]',$countries,null,['class'=>'form-contol select2bs4 required add_country_id', 'id'=>'addresss_country']) !!}
                <span class="text-danger country" style="display:none">Country is required</span>
              </div>
              <div class="col-sm-6" style="padding:0">
                {!! Form::label('addresss_state', 'State') !!}
                <select name="address[state_id]" class="form-control select2bs4 add_state_id" id="addresss_state"></select>
              </div>
            </div>
            <div class="form-group" style="display:flex;">
              <div class="col-sm-6" style="padding-left:0;">
                {!! Form::label('addresss_city', 'City') !!}
                <select name="address[city_id]" class="form-control select2bs4 add_city_id" id="addresss_city"></select>
              </div>
              <div class="col-sm-6" style="padding:0">
                {!! Form::label('postcode', 'Post Code *') !!}
                {!! Form::text('address[post_code]', '',['class'=>'form-control add_post_code','onkeyup'=>"validateNum(event,this);"]) !!}
                <span class="text-danger post_code" style="display:none">Post Code is required</span>
              </div>
            </div>
            {!! Form::hidden('address[latitude]', null,['id'=>'latitude']) !!}
            {!! Form::hidden('address[longitude]', null,['id'=>'longitude']) !!}
            
{{--             <div class="form-group" style="display:flex;" >
                <div class="col-sm-6" style="padding-left:0;">
                    <label for="city">Latitude</label>
                    {!! Form::text('address[latitude]', null,['class'=>'form-control','id'=>'latitude']) !!}
                </div>
                <div class="col-sm-6"  style="padding:0">
                    <label for="postCode">Longitude</label>
                    {!! Form::text('address[longitude]', null,['class'=>'form-control','id'=>'longitude']) !!}
                </div>
            </div> --}}
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
               <div class="clearfix"></div>
                  <br>
            <div class="form-group">
              <button type="button" class="btn reset-btn" data-dismiss="modal">Cancel</button>
              <button type="submit" id="submit-btn" class="btn save-btn submit-address">Save</button>
            </div>
          </div>
        </div>
            
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

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
    .pac-container {
        z-index: 10000 !important;
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
        $('.add_line_1').val(results[0].formatted_address);
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
          $('.add_line_1').val(results[0].formatted_address);
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
  .address-tabs .form-group{
    display: inherit !important;
  }
  .address-details,.list{
      border: 2px solid #eee;
      padding: 20px;
  }
  .table td, .table th{
    border: none;
  }
  .add-new-address {
    width: 97%;
    margin: auto;
    padding-bottom: 5px;
  }

</style>

@push('custom-scripts')

  <script type="text/javascript">

    $('.edit').click(function(){
      var addId = $(this).attr('address-id');
      $.ajax({
        url:"{{ url('admin/edit-address-form') }}?add_id="+addId,
        type:"GET",
        success: function (data) { 
          //console.log(data);
          $('#edit-form-block').html(data);
        }
      });
    });
  </script>



@if (Session::has('from'))
  <script type="text/javascript">
    $(document).ready(function() {
      $('a[href="#step2"]').removeClass('disabled');
      $('a[href="#step3"]').removeClass('disabled');
      $('a[href="#step3"]').trigger('click');
    });
  </script>
@endif
<script type="text/javascript">

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
            error: function(res) { alert(res.responseText) }
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
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $(append_id).empty();        
        }      
      }

      function addressValidate(){
        var valid=true;
        if ($(".add_name").val()=="") {
            $(".add_name").closest('.form-group').find('span.text-danger.name').show();
            valid = false;
        }
        if ($(".add_mobile").val()=="") {
            $(".add_mobile").closest('.form-group').find('span.text-danger.mobile').show();
            valid = false;
        }
        if ($(".add_line_1").val()=="") {
            $(".add_line_1").closest('.form-group').find('span.text-danger.address1').show();
            valid = false;
        }
        if ($("#addresss_country").val()=="") {
            $("#addresss_country").closest('.form-group').find('span.text-danger.country').show();
            valid = false;
        }
        if($('.add_post_code').val()==""){
          $(".add_post_code").closest('.form-group').find('span.text-danger.post_code').show();
          valid = false;
        }
        return valid;
      }

    $(document).on('click', '.submit-address', function(event) {
      if(addressValidate()){
        $.ajax({
          url: '{{ url("admin/add-new-address") }}',
          type: 'POST',
          data: {
            '_token': '{{ csrf_token() }}',
            'name':$('.add_name').val(),
            'mobile':$('.add_mobile').val(),
            'address_line1':$('.add_line_1').val(),
            'address_line2':$('.add_line_2').val(),
            'post_code':$('.add_post_code').val(),
            'country_id':$('.add_country_id').val(),
            'state_id':$('.add_state_id').val(),
            'city_id':$('.add_city_id').val(),
            'customer_id':{{ $customer->id }},
            'latitude':$('#latitude').val(),
            'longitude':$('#longitude').val()
          },
        })
        .done(function() {
          location.reload(); 
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });        
      }
    });


  //Get State
      $('#addresss_country').change(function() {
        var get_country_id = $(this).val();
        addressGetState(get_country_id);
      })
      function addressGetState(countryID){
        var get_state_id = "";        
        if(countryID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-state-list')}}?country_id="+countryID,
            success:function(res){  
              if(res){
                $("#addresss_state").empty();
                $("#addresss_state").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_state="";
                  if(get_state_id == key) { var select_state = "selected" }
                  $("#addresss_state").append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
                });
                // $('#addresss_state').selectpicker('refresh');           
              }else{
                $("#addresss_state").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#addresss_state").empty();        
        }      
      }

      //Get City
      $('#addresss_state').change(function() {
        var get_state_id = $(this).val();
        addressGetCity(get_state_id);
      })
      function addressGetCity(stateID){
        var get_city_id = "";
        if(stateID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-city-list')}}?state_id="+stateID,
            success:function(res){  
              if(res){
                $("#addresss_city").empty();
                $("#addresss_city").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_city="";
                  if(get_city_id == key) { var select_city = "selected" }
                  $("#addresss_city").append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
                });
                // $('#addresss_city').selectpicker('refresh');           
              }else{
                $("#addresss_city").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#addresss_city").empty();        
        }      
      }

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
        if($(".company_postcode").val()=="") {
          $(".company_postcode").closest('.form-group').find('span.text-danger.post-code').show();
          valid=false;
        }else{
          $(".company_postcode").closest('.form-group').find('span.text-danger.post-code').hide();
        }
        return valid;
      }

      function validateStep2(e){
        var valid=true;
        return valid;
      }

      function validateStep3(e){
       var valid=true;
        var addCount = $('.del-address').length;
        if(addCount==0){
          alert('Please Add Delivery Address');
          valid=false;
        }else{
          valid=true;
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

</script>
@endpush
<style type="text/css">
  .tab-pane .col-sm-6{
    float: left;
  }
</style>
@endsection