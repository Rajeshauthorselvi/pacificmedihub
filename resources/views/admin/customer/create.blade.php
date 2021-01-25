@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Create Customers</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">List Customer</a></li>
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
      <div class="container-fluid toggle-tabs">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Vendor</h3>
              </div>
              <div class="card-body">
                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                  <li role="presentation" class="nav-item">
                    <a href="#step1" class="nav-link customer-link active" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1"> Customer Details </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab customer-link" title="Step 2"> Company Details </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab customer-link" title="Step 3">Delivery Address</a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step4" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab customer-link" title="Step 3">Bank Accounts</a>
                  </li>
                </ul>
                {!! Form::open(['route'=>'customer.store','method'=>'POST','id'=>'form-validate']) !!}
                  <div class="tab-content py-2">
                    <div class="tab-pane customer-tabs " tab-count="1" role="tabpanel" id="step1">
                      <div class="col-sm-12">
                        <div class="customer_details">
                          <div class="col-sm-5">
                            <label for="vendorName">Customer No *</label>
                            {!! Form::text('customer_no',null,['class'=>'form-control']) !!}
                              <span class="text-danger"></span>
                          </div>
                          <div class="clearfix"></div>
                          <div class="col-sm-12 customer-sec">
                            <div class="col-sm-6">
                              <label for="">Customer Name *</label>
                              {!! Form::text('first_name', null,['class'=>'form-control required']) !!}
                              <span class="text-danger"></span>
                            </div>
                            <div class="col-sm-6">
                              <label for="">Customer UEN *</label>
                              {!! Form::text('customer_uen', null,['class'=>'form-control required']) !!}
                              <span class="text-danger"></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6">
                              <label for="">Mobile No *</label>
                              {!! Form::text('contact_number', null,['class'=>'form-control required']) !!}
                              <span class="text-danger"></span>
                            </div>
                            <div class="col-sm-6">
                              <label for="">Email *</label>
                              {!! Form::email('email', null,['class'=>'form-control required']) !!}
                              <span class="text-danger"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                     <div class="tab-pane customer-tabs active" tab-count="2" role="tabpanel" id="step2">
                         <div class="col-sm-12">
                            <div class="company-details">
                            <div class="col-sm-6">
                              <label for="">Company Name *</label>
                              {!! Form::text('company_name', null,['class'=>'form-control required']) !!}
                              <span class="text-danger"></span>
                            </div>

                            <div class="col-sm-6">
                              <label for="">Company GST No</label>
                              {!! Form::text('company_gst', null,['class'=>'form-control']) !!}
                            </div>

                            <div class="col-sm-6">
                              <label for="">Parent Company</label>
                              {!! Form::text('parent_company', null,['class'=>'form-control']) !!}
                            </div>

                            <div class="col-sm-6">
                              <label for="">Telephone No*</label>
                              {!! Form::text('parent_company', null,['class'=>'form-control']) !!}
                            </div>

                            <div class="col-sm-6">
                              <label for="">Address Line1 *</label>
                              {!! Form::text('address_1', null,['class'=>'form-control']) !!}
                            </div>

                            <div class="col-sm-6">
                              <label for="">Address Line2</label>
                              {!! Form::text('address_2', null,['class'=>'form-control']) !!}
                            </div>

                            <div class="col-sm-6">
                              <label for="">Country *</label>
                              {!! Form::text('country_id', null,['class'=>'form-control']) !!}
                            </div>


                            <div class="col-sm-6">
                              <label for="">State *</label>
                              {!! Form::text('state_id', null,['class'=>'form-control']) !!}
                            </div>
                            <div class="col-sm-6">
                              <label for="">City *</label>
                              {!! Form::text('city_id', null,['class'=>'form-control']) !!}
                            </div>

                            <div class="col-sm-6">
                              <label for="">Company Logo JPEG</label>
                              <br>
                              {!! Form::file('logo', null,['class'=>'form-control']) !!}
                            </div>
                            </div>
                         </div>
                     </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="submit-sec">
                      <a href="javascript:void(0)" class="btn reset-btn prev">
                        Previous
                      </a>
                      <a href="javascript:void(0)" class="btn save-btn next">
                        Next
                      </a>
                    </div>
                  </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>


@push('custom-scripts')
<script type="text/javascript">
  $(document).on('click', '.next', function(event) {
    
    var check_active_tab=$('.customer-tabs.active').attr('id');
    $('#'+check_active_tab+' input').each(function(index, el) {
      var type=$(this).attr('type');
      var current_val=$(this).val();
      if (current_val=="" && type=="text") {
        $(this).next('.text-danger').html('<span class="text-danger">This field is required</span>');
      }
      else if(current_val=="" && type=="email"){
          $(this).next('.text-danger').html('<span class="text-danger">This field is required</span>');
      }
      else if (current_val=="" && type=="select") {
          $(this).next('.text-danger').html('<span class="text-danger">This field is required</span>');
      }
      else if(current_val!="" && type=="email" && !validateEmail(current_val)){
          $(this).next('.text-danger').html('<span class="text-danger">This email is not valid</span>');
      }
      else{
        $(this).next('.text-danger').remove();
      }
      
    });
    
 function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  return emailReg.test( $email );
}


  });
</script>
@endpush
<style type="text/css">
  .customer-sec .col-sm-6{
    float: left;
  }
</style>
@endsection