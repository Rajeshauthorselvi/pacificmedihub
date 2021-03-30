@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customer</a></li>
              <li class="breadcrumb-item">Customer Details</li>
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
          <a href="{{route('customers.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid toggle-tabs">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Customer Details</h3>
              </div>
              <a href="{{route('customers.edit',$customer->id)}}" class="btn emp-edit"><i class="far fa-edit"></i>&nbsp;Edit</a>
              <div class="card-body">
                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                  <li role="presentation" class="nav-item">
                    <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab"  tab-count="1" title="Company"> Company Details </a>
                  </li>
                  
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link " data-toggle="tab" aria-controls="step2" role="tab" tab-count="2" title="POC"> POC Details </a>
                  </li>

                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link " data-toggle="tab" aria-controls="step3" role="tab"  tab-count="3" title="Delivery">Delivery Address</a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step4" class="nav-link" data-toggle="tab" aria-controls="step4" role="tab"  tab-count="4" title="Bank">Bank Accounts</a>
                  </li>
                </ul>
                
                <div class="tab-content py-2">
                  <div class="tab-pane company-tabs active" tab-count="1" role="tabpanel" id="step1">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          {!! Form::hidden('company[company_id]',$customer->id) !!}
                          <label for="">Customer Code *</label>
                          {!! Form::text('customer[customer_no]',$customer->customer_no,['class'=>'form-control','readonly']) !!}
                        </div>
                         <div class="col-sm-6">
                          <label for="">Company UEN *</label>
                          {!! Form::text('company[company_uen]', $customer->company_uen,['class'=>'form-control','readonly']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Name *</label>
                          {!! Form::text('company[company_name]', $customer->name,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <?php
                          if($customer->parent_company==0){
                            $parent = '-';
                          }else{
                            $parent = $customer->ParentCompany($customer->parent_company);
                          }
                        ?>
                        <div class="col-sm-6">
                          <label for="">Parent Company</label>
                          {!! Form::text('null',isset($parent)?$parent:'',['readonly','class'=>'form-control']) !!}
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Email *</label>
                          {!! Form::text('company[company_email]', $customer->email,['class'=>'form-control','readonly']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Contact No *</label>
                          {!! Form::text('company[telephone]', $customer->contact_number,['class'=>'form-control','readonly']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line1 *</label>
                          {!! Form::text('company[address_1]', $customer->address_1,['class'=>'form-control','readonly']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line2</label>
                          {!! Form::text('company[address_2]', $customer->address_2,['class'=>'form-control','readonly']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Country *</label>
                          {!! Form::text('country_id',$customer->getCountry->name,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">State</label>
                          {!! Form::text('state_id',isset($customer->getState->name)?$customer->getState->name:'',['readonly','class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">City</label>
                          {!! Form::text('city_id',isset($customer->getCity->name)?$customer->getCity->name:'',['readonly','class'=>'form-control', 'id'=>'City']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Post Code</label>
                          {!! Form::text('company[post_code]', $customer->post_code,['readonly','class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Sales Rep</label>
                          @php 
                            $sales_rep = isset($customer->getSalesRep->emp_name)?$customer->getSalesRep->emp_name:'' @endphp
                          {!! Form::text('salesrep',$sales_rep,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Company GST No</label>
                          {!! Form::text('company[company_gst]', $customer->company_gst,['class'=>'form-control','readonly']) !!}
                        </div>
                      </div>
                      <?php 
                        if(!empty($customer->logo)){$image = 'theme/images/customer/company/'.$customer->id.'/'.$customer->logo;}
                        else {$image = "theme/images/no_image.jpg";}

                        if(!empty($customer->company_gst_certificate)){$gst_file = 'theme/images/customer/company/'.$customer->id.'/'.$customer->company_gst_certificate;}
                        else {$gst_file = "theme/images/no_image.jpg";}

                      ?>
                      <div class="form-group">
                        <div class="col-sm-6">
                          {!! Form::label('companyLogo', 'Company Logo JPEG') !!}<br>
                          <img class="img-company" style="width:125px;height:100px;" src="{{asset($image)}}">
                        </div>
                        <div class="col-sm-6">
                          {!! Form::label('companyGst', 'Company GST Certificate Copy(JPEG,PNG,PDF)') !!}<br>
                          <img class="img-company" style="width:125px;height:100px;" src="{{asset($gst_file)}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="customer[status]" id="Status" disabled @if($customer->status==1) checked @endif>
                            <label for="Status">Published</label>
                          </div>
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
                        @foreach($customer_poc as $poc)
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
                                  <input type="text" class="form-control" name="poc[contact][]" readonly id="contact1" value="{{$poc->contact_number}}">
                                </div>
                              </td>
                            </tr>
                          </tbody>
                          <input type="hidden" value="{{$count++}}">
                        @endforeach
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane address-tabs " tab-count="3" role="tabpanel" id="step3">
                    <div class="address-list-sec col-sm-10">
                      @if(count($customer->alladdress)!=0)
                      @foreach ($customer->alladdress as $address)
                        <div class="col-sm-12">
                          <div class="list">
                            <table class="table">
                              <tr>
                                <td>
                                  {{ $address->name }}<br>{{ $address->mobile }}<br>
                                  {{ $address->address_line1 }}<br>{{ $address->address_line2 }}<br>
                                  {{ $address->country->name }}
                                  @if(isset($address->state->name)) , {{$address->state->name}} @endif
                                  @if(isset($address->city->name)) ,{{$address->city->name}} @endif
                                  <br>{{ $address->post_code }}
                                </td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <br>
                      @endforeach
                      @else
                        <span>No Address.</span>
                      @endif
                    </div>
                  </div>

                  <div class="tab-pane " tab-count="4" role="tabpanel" id="step4">
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="accountName">Account Name</label>
                        {!! Form::text('bank[account_name]',isset($customer->bank->account_name)?$customer->bank->account_name:'',['class'=>'form-control','readonly']) !!}
                        <span class="text-danger"></span>
                      </div>
                      <div class="col-sm-6">
                        <label for="accountNumber">Account Number</label>
                        {!! Form::text('bank[account_number]',null,['class'=>'form-control','readonly','onkeyup'=>"validateNum(event,this);"]) !!}
                        <span class="text-danger"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="bankName">Bank Name</label>
                        {!! Form::text('bank[bank_name]',null,['class'=>'form-control','readonly']) !!}
                        <span class="text-danger"></span>
                      </div>
                      <div class="col-sm-6">
                        <label for="bankBranch">Bank Branch</label>
                        {!! Form::text('bank[bank_branch]',null,['class'=>'form-control','readonly']) !!}
                        <span class="text-danger"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="payNow">PayNow Contact No</label>
                        {!! Form::text('bank[paynow_contact]',null,['class'=>'form-control','readonly','onkeyup'=>"validateNum(event,this);"]) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="Place">Place</label>
                        {!! Form::text('bank[place]',null,['class'=>'form-control','readonly']) !!}
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

<style type="text/css">
  .address-tabs .form-group{display: inherit !important;}
  .address-details,.list{border: 2px solid #eee;padding: 20px;}
  .table td, .table th{border: none;}
  .add-new-address {width: 97%;margin: auto;padding-bottom: 5px;}
  .tab-pane .col-sm-5{float: left;}
</style>
@endsection