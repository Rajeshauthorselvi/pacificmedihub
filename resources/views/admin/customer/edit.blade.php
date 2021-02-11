@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customer</a></li>
              <li class="breadcrumb-item">Edit Customer</li>
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
                    <a href="#step1" class="nav-link customer-link active " data-toggle="tab" aria-controls="step1" role="tab"  tab-count="1" title="Step 1"> Company Details </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link" data-toggle="tab" aria-controls="step2" role="tab customer-link" tab-count="2" title="Step 2"> POC Details </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link" data-toggle="tab" aria-controls="step3" role="tab customer-link"  tab-count="3" title="Step 3">Delivery Address</a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step4" class="nav-link" data-toggle="tab" aria-controls="step3" role="tab customer-link"  tab-count="4" title="Step 3">Bank Accounts</a>
                  </li>
                </ul>
                <div class="tab-content py-2">
                  <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
                    <div class="col-sm-12">
                      <div class="form-group">
                        {!! Form::hidden('company[company_id]',$customer->company->id) !!}
                        <div class="col-sm-6">
                          <label for="">Company Name *</label>
                          {!! Form::text('company[company_name]', null,['class'=>'form-control company_name required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Parent Company</label>
                          {!! Form::select('company[parent_company]', $all_company,$customer->company->parent_company,['class'=>'form-control select2bs4']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company GST No</label>
                          {!! Form::text('company[company_gst]', null,['class'=>'form-control']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Company UEN *</label>
                          {!! Form::text('company[company_uen]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Email *</label>
                          {!! Form::text('company[company_email]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Telephone No *</label>
                          {!! Form::text('company[telephone]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line1 *</label>
                          {!! Form::text('company[address_1]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line2</label>
                          {!! Form::text('company[address_2]', null,['class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Country *</label>
                          {!! Form::select('country',$countries,$customer->company->country_id,['class'=>'form-control select2bs4 required', 'id'=>'company_country' ]) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">State *</label>
                           <select name="company[state_id]" class="form-control select2bs4 required" id="company_state"></select>
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">City *</label>
                          <select name="company[city_id]" class="form-control select2bs4 required" id="company_city"></select>
                          <span class="text-danger"></span>
                        </div>
                         <div class="col-sm-6">
                          <label for="">Post Code *</label>
                          {!! Form::text('company[post_code]', null,['class'=>'form-control required company_postcode']) !!}
                           <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Sales Rep</label>
                          {!! Form::select('company[sales_rep]',$sales_rep,$customer->company->sales_rep,['class'=>'form-control select2bs4']) !!}
                        </div>
                        <div class="col-sm-6" style="margin-top:40px">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="customer[status]" id="Status" @if($customer->status==1) checked @endif>
                            <label for="Status">Status</label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="companyLogo">Company Logo JPEG</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="company[logo]" id="companyLogo" accept="image/*">
                            <label class="custom-file-label" for="companyLogo">Choose file</label>
                          </div>
                          <br><br>
                          <img src="{{asset('theme/images/customer/company/'.$customer->company->id.'/' .$customer->company->logo)}}" width="100px" height="100px">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane customer-tabs" tab-count="2" role="tabpanel" id="step2">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="vendorName">Customer No *</label>
                          {!! Form::text('customer[customer_no]',$customer->customer_no,['class'=>'form-control required','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">POC Name *</label>
                          {!! Form::text('customer[first_name]',$customer->first_name,['class'=>'form-control required']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Contact No *</label>
                          {!! Form::text('customer[contact_number]', $customer->contact_number,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Email *</label>
                          {!! Form::email('customer[email]',  $customer->email,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
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
                            <table class="table">
                              <tr>
                                <td rowspan="2" style="vertical-align: middle; border:none;">
                                  @if ($customer->address_id==$address->id)
                                    <input type="radio" name="customer[address_id]" checked value="{{$address->id}}">
                                  @else
                                    <input type="radio" name="customer[address_id]" value="{{$address->id}}">
                                  @endif
                                </td>
                                <td>
                                  {{ $address->name }}<br>{{ $address->mobile }}<br>
                                  {{ $address->address_line1 }}<br>{{ $address->address_line2 }}<br>
                                  {{ $address->country->name }}, {{ $address->state->name }}, {{ $address->city->name }}
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
                  </div>
                </div>
                <div class="submit-sec">
                  <a href="javascript:void(0)" class="btn reset-btn prev">Previous</a>
                  <a href="javascript:void(0)" class="btn save-btn next">Next</a>
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
          <h4 class="modal-title">Edit Customer Address</h4>
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
                {!! Form::text('address[mobile]', '',['class'=>'form-control required add_mobile']) !!}
                <span class="text-danger mobile" style="display:none">Contact Number is required</span>
              </div>
            </div>
            
            <div class="form-group">
              {!! Form::label('address1', 'Address Line 1 *') !!}
              {!! Form::text('address[address_line1]', '',['class'=>'form-control required add_line_1']) !!}
              <span class="text-danger address1" style="display:none">Address Line 1 is required</span>
            </div>
            <div class="form-group">
              {!! Form::label('address2', 'Address Line 2') !!}
              {!! Form::text('address[address_line2]', '',['class'=>'form-control add_line_2']) !!}
            </div>
            <div class="form-group" style="display:flex;">
              <div class="col-sm-6" style="padding-left:0">
                {!! Form::label('postcode', 'Post Code *') !!}
                {!! Form::text('address[post_code]', '',['class'=>'form-control required add_post_code']) !!}
                <span class="text-danger postcode" style="display:none">Post Code is required</span>
              </div>

              <div class="col-sm-6" style="padding:0;">
                {!! Form::label('addresss_country', 'Country *') !!}
                {!! Form::select('address[country_id]',$countries,null,['class'=>'form-contol select2bs4 required add_country_id', 'id'=>'addresss_country']) !!}
                <span class="text-danger country" style="display:none">Country is required</span>
              </div>
            </div>
            <div class="form-group" style="display:flex;">
              <div class="col-sm-6" style="padding-left:0">
                {!! Form::label('addresss_state', 'State *') !!}
                <select name="address[state_id]" class="form-control select2bs4 required add_state_id" id="addresss_state"></select>
                <span class="text-danger state" style="display:none">State is required</span>
              </div>
              <div class="col-sm-6" style="padding:0;">
                {!! Form::label('addresss_city', 'City *') !!}
                <select name="address[city_id]" class="form-control select2bs4 required add_city_id" id="addresss_city"></select>
                <span class="text-danger city" style="display:none">City is required</span>
              </div>
            </div>
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
      $('a[href="#step3"]').trigger('click');
    });
  </script>
@endif
<script type="text/javascript">

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
        if ($(".add_post_code").val()=="") {
            $(".add_post_code").closest('.form-group').find('span.text-danger.postcode').show();
            valid = false;
        }
        if ($("#addresss_country").val()=="") {
            $("#addresss_country").closest('.form-group').find('span.text-danger.country').show();
            valid = false;
        }
        if ($("#addresss_state").val()=="") {
            $("#addresss_state").closest('.form-group').find('span.text-danger.state').show();
            valid = false;
        }
        if ($("#addresss_city").val()=="") {
            $("#addresss_city").closest('.form-group').find('span.text-danger.city').show();
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
            'customer_id':{{ $customer->id }}
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
                $('#addresss_state').selectpicker('refresh');           
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
                $('#addresss_city').selectpicker('refresh');           
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

 
  $(".prev").click(function (e) {
       var active = $('.nav-tabs li>.active');
        console.log(active);
        prevTab(active);
  });
      function prevTab(elem) {
       $(elem).parent().prev().find('a[data-toggle="tab"]').click();
      }
  $(document).on('click', '.next', function(event) {
    
    var check_active_tab=$('.tab-pane.active').attr('id');

    var length_empty=$('.tab-pane.active .required').filter(function(){return !$(this).val(); }).length;

    var active = $('.nav-tabs li>.active');
    var stepID = $(active).attr('tab-count');

    var next_node=parseInt(stepID)+1;
    if (next_node==4) {
        $('.save-btn').text('Submit');
    }
    if (stepID!=3) {
      var error_count=0;
      $('#'+check_active_tab+' .required').each(function(index, el) {
      var type=$(this).attr('type');
      var current_val=$(this).val();
      if (current_val=="" && type=="text") {
        $(this).next('.text-danger').html('<span class="text-danger">This field is required</span>');
          error_count += 1;
      }
      else if(current_val=="" && type=="email"){
          $(this).next('.text-danger').html('<span class="text-danger">This field is required</span>');
          error_count += 1;
      }
      else if (current_val=="" && type=="select") {
          $(this).next('.text-danger').html('<span class="text-danger">This field is required</span>');
          error_count += 1;
      }
      else if(current_val!=""  && type=="email" && !validateEmail(current_val)){
          $(this).next('.text-danger').html('<span class="text-danger">This email is not valid</span>');
          error_count += 1;
      }
      else if (current_val==null && type==undefined) {
        $(this).parents('.csc-sec').find('.text-danger').html('<span class="text-danger">This field is required</span>');
      }
      else if (current_val=="" && type==undefined) {
        $(this).parents('.csc-sec').find('.text-danger').html('<span class="text-danger">This field is required</span>');
      }
      else if (current_val!=null && type==undefined) {
         $(this).parents('.csc-sec').find('.text-danger').html('');
      }
      else{
        $(this).next('.text-danger').html('');
      }

    });

      if (length_empty==0 && error_count==0) {
        if (stepID!=4) {
          active.parent().next().find('.nav-link').removeClass('disabled');
          nextTab(active);
        }
        else if(stepID==4) {
          $('#form').submit();
        }
      }
    }
    else{
          active.parent().next().find('.nav-link').removeClass('disabled');
          nextTab(active);
    }

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


  });
</script>
@endpush
<style type="text/css">
  .tab-pane .col-sm-6{
    float: left;
  }
</style>
@endsection