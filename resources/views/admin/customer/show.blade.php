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
          {{ Form::model($customer,['method' => 'PATCH', 'route' =>['customers.update',$customer->id],'id'=>'form','files'=>true]) }}
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Customer Details</h3>
              </div>
              <a href="{{route('customers.edit',$customer->id)}}" class="btn emp-edit"><i class="far fa-edit"></i>&nbsp;Edit</a>
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
                          {!! Form::text('company[company_name]', null,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <?php
                          if($customer->company->parent_company==0){
                            $parent = '';
                          }else{
                            $parent = $customer->company->company_name;
                          }
                        ?>
                        <div class="col-sm-6">
                          <label for="">Parent Company</label>
                          {!! Form::text('null',$parent,['readonly','class'=>'form-control']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company GST No</label>
                          {!! Form::text('company[company_gst]', null,['class'=>'form-control','readonly']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Company UEN *</label>
                          {!! Form::text('company[company_uen]', null,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Company Email *</label>
                          {!! Form::text('company[company_email]', null,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Telephone No *</label>
                          {!! Form::text('company[telephone]', null,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Address Line1 *</label>
                          {!! Form::text('company[address_1]', null,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Address Line2</label>
                          {!! Form::text('company[address_2]', null,['class'=>'form-control','readonly']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Country *</label>
                          {!! Form::text('country_id',$customer->company->getCountry->name,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">State *</label>
                          {!! Form::text('state_id',$customer->company->getState->name,['readonly','class'=>'form-control', 'id'=>'State']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">City *</label>
                          {!! Form::text('city_id',$customer->company->getCity->name,['readonly','class'=>'form-control', 'id'=>'City']) !!}
                        </div>
                        <div class="col-sm-6">
                          <label for="">Post Code *</label>
                          {!! Form::text('company[post_code]', null,['readonly','class'=>'form-control company_postcode']) !!}
                           <span class="text-danger"></span>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Sales Rep</label>
                          @php 
                            $sales_rep = isset($customer->company->getSalesRep->emp_name)?$customer->company->getSalesRep->emp_name:'' @endphp
                          {!! Form::text('salesrep',$sales_rep,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
                        </div>
                        <div class="col-sm-6" style="margin-top:40px">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="customer[status]" id="Status" disabled @if($customer->status==1) checked @endif>
                            <label for="Status">Status</label>
                          </div>
                        </div>
                      </div>
                      <?php 
                        if(!empty($customer->company->logo)){$image = 'theme/images/customer/company/'.$customer->company->id.'/'.$customer->company->logo;}
                        else {$image = "theme/images/no_image.jpg";}
                      ?>
                      <div class="form-group">
                        <div class="col-sm-6">
                          {!! Form::label('companyLogo', 'Company Logo JPEG') !!}<br>
                          <img title="Click to Change" class="img-company" id="output_image" style="width:125px;height:100px;" src="{{asset($image)}}">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="tab-pane customer-tabs" tab-count="2" role="tabpanel" id="step2">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="vendorName">Customer No *</label>
                          {!! Form::text('customer[customer_no]',$customer->customer_no,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">POC Name *</label>
                          {!! Form::text('customer[first_name]',$customer->first_name,['class'=>'form-control','readonly']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-6">
                          <label for="">Contact No *</label>
                          {!! Form::text('customer[contact_number]', $customer->contact_number,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                        <div class="col-sm-6">
                          <label for="">Email *</label>
                          {!! Form::email('customer[email]',  $customer->email,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                   
                  <div class="tab-pane address-tabs " tab-count="3" role="tabpanel" id="step3">
                    <div class="address-list-sec col-sm-10">
                      @foreach ($customer->alladdress as $address)
                        <div class="col-sm-12">
                          <div class="list">
                            <table class="table">
                              <tr>
                                <td>
                                  {{ $address->name }}<br>{{ $address->mobile }}<br>
                                  {{ $address->address_line1 }}<br>{{ $address->address_line2 }}<br>
                                  {{ $address->country->name }}, {{ $address->state->name }}, {{ $address->city->name }}
                                  <br>{{ $address->post_code }}
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
                          {!! Form::text('bank[account_name]',$customer->bank->account_name,['class'=>'form-control','readonly']) !!}
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