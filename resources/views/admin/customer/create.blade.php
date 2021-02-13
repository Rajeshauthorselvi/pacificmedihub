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
                    <a href="#step1" class="nav-link customer-link active " data-toggle="tab" aria-controls="step1" role="tab"  tab-count="1" title="Step 1"> Company Details </a>
                  </li>
                  
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab customer-link" tab-count="2" title="Step 2"> POC Details </a>
                  </li>

                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab customer-link"  tab-count="3" title="Step 3">Delivery Address</a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step4" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab customer-link"  tab-count="4" title="Step 3">Bank Accounts</a>
                  </li>
                </ul>
                <div class="tab-content py-2">

                  <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Name *</label>
                          {!! Form::text('company[company_name]', null,['class'=>'form-control company_name required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Parent Company</label>
                          {!! Form::select('company[parent_company]', $all_company, null,['class'=>'form-control select2bs4']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        
                        
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Email *</label>
                          {!! Form::email('company[company_email]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Telephone No*</label>
                          {!! Form::text('company[telephone]', null,['class'=>'form-control company_contact required','onkeyup'=>"validateNum(event,this);"]) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line1 *</label>
                          {!! Form::text('company[address_1]', null,['class'=>'form-control company_add1 required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line2</label>
                          {!! Form::text('company[address_2]', null,['class'=>'form-control company_add2']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6 csc-sec">
                          <label for="">Country *</label>
                          {!! Form::select('company[country_id]',$countries,null,['class'=>'form-control select2bs4 required', 'id'=>'company_country']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6 csc-sec">
                          <label for="">State</label>
                          <select name="company[state_id]" class="form-control select2bs4" id="company_state"></select>
                          <span class="text-danger"></span>
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
                           <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Sales Rep</label>
                          {!! Form::select('company[sales_rep]',$sales_rep,null,['class'=>'form-control select2bs4']) !!}
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
                          <label for="companyGst">Upload Company GST Certificate Copy(JPEG,PNG,PDF)</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" name="company[company_gst_certificate]" id="companyGst">
                            <label class="custom-file-label" for="companyGst">Choose file</label>
                          </div>
                        </div>
                      </div>
                        
                      <div class="form-group">
                        <div class="col-sm-6">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="customer[status]" id="Status" checked>
                            <label for="Status">Published</label>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>

                  <div class="tab-pane customer-tabs" tab-count="2" role="tabpanel" id="step2">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="vendorName">Customer No *</label>
                          {!! Form::text('customer[customer_no]',$product_id,['class'=>'form-control required','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">POC Name *</label>
                          {!! Form::text('customer[first_name]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Contact No *</label>
                          {!! Form::text('customer[contact_number]', null,['class'=>'form-control required', 'onkeyup'=>"validateNum(event,this);"]) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Email *</label>
                          {!! Form::email('customer[email]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company UEN *</label>
                          {!! Form::text('company[company_uen]', null,['class'=>'form-control required']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane address-tabs " tab-count="3" role="tabpanel" id="step3">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Name *</label>
                          {!! Form::text('address[name]', null,['class'=>'form-control del_add_name required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Contact No *</label>
                          {!! Form::text('address[mobile]', null,['class'=>'form-control del_add_contact required','onkeyup'=>"validateNum(event,this);"]) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line *</label>
                          {!! Form::text('address[address_line1]', null,['class'=>'form-control del_add_1 required']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line 2</label>
                          {!! Form::text('address[address_line2]', null,['class'=>'form-control del_add_2']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Country *</label>
                          {!! Form::text('null',null,['class'=>'form-control required address_country']) !!}
                          {!! Form::hidden('address[country_id]',null,['class'=>'form-control required address_country_id']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">State</label>
                          {!! Form::text('null',null,['class'=>'form-control address_state']) !!}
                          {!! Form::hidden('address[state_id]',null,['class'=>'form-control address_state_id']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">City</label>
                          {!! Form::text('null',null,['class'=>'form-control address_city']) !!}
                          {!! Form::hidden('address[city_id]',null,['class'=>'form-control address_city_id']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Post Code</label>
                          {!! Form::text('address[post_code]', null,['class'=>'form-control address_postcode']) !!}
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane address-tabs " tab-count="4" role="tabpanel" id="step4">
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
                  </div>

                  <div class="submit-sec">
                    <a href="javascript:void(0)" class="btn reset-btn prev">Previous</a>
                    <a href="javascript:void(0)" class="btn save-btn next">Next</a>
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


  @push('custom-scripts')
  <script type="text/javascript">

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

      var next_node = parseInt(stepID)+1;
      if (next_node==4) {
        $('.save-btn').text('Submit');
      }

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

      var company_name = $('.company_name').val();
      var company_add1 = $('.company_add1').val();
      var company_add2 = $('.company_add2').val();
      var company_contact = $('.company_contact').val();
      var country = $('#company_country option:selected').text();
      var country_id = $('#company_country').val();
      var state = $('#company_state option:selected').text();
      var state_id = $('#company_state').val();
      var city = $('#company_city option:selected').text();
      var city_id = $('#company_city').val();
      var postcode = $('#company_postcode').val();

      $('.del_add_name').val(company_name);
      $('.del_add_1').val(company_add1);
      $('.del_add_2').val(company_add2);
      $('.del_add_contact').val(company_contact);      
      $('.address_country').val(country);
      $('.address_country_id').val(country_id);
      $('.address_state').val(state);
      $('.address_state_id').val(state_id);
      $('.address_city').val(city);
      $('.address_city_id').val(city_id);
      $('.address_postcode').val(postcode);
      
      if (length_empty==0 && error_count==0) {
        if (stepID!=4) {
          active.parent().next().find('.nav-link').removeClass('disabled');
          nextTab(active);
        }
        else if(stepID==4) {
          $('#form').submit();
        }
      }


    });

    function nextTab(elem) {
      $(elem).parent().next().find('a[data-toggle="tab"]').click();
    }

    function validateEmail($email) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      return emailReg.test( $email );
    }

    /* address_city */
    $(document).on('change', '#company_country', function(event) {
        var country_id = $(this).val();
        getState(country_id,'#company_state');
    });

    $(document).on('change', '.address_country', function(event) {
        var country_id = $(this).val();
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
    //Get City
    $('#company_state').change(function() {
      var state_id = $(this).val();
      getCity(state_id,'#company_city');
    })
    //Get City
    $('.address_state').change(function() {
      var state_id = $(this).val();
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

  </script>
  @endpush
@endsection