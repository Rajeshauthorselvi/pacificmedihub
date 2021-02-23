@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Employee</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('employees.index')}}">List Employee</a></li>
              <li class="breadcrumb-item active">Employee Details</li>
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
          <a href="{{route('employees.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid toggle-tabs">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Employee Details</h3>
              </div>
              <div class="card-body">
                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                  <li role="presentation" class="nav-item">
                    <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1"> General </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2"> Bank Accounts </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3"> Salary </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step4" class="nav-link" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4"> Commission </a>
                  </li>
                </ul>
              <a href="{{route('employees.edit',$employees->id)}}" class="btn emp-edit"><i class="far fa-edit"></i>&nbsp;Edit</a>
                <div class="tab-content py-2">
                  <!-- Step1 -->
                  <div class="tab-pane active" role="tabpanel" id="step1">
                    <div class="" id="general">

                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('employeeId', 'Employee Code') !!}
                          {!! Form::text('emp_id',$employees->emp_id,['readonly','class'=>'form-control','id'=>'employeeId']) !!}
                        </div>
                        <div class="col-sm-5">
                          {!! Form::label('employeeName', 'Employee Name *') !!}
                          {!! Form::text('emp_name',$employees->emp_name,['readonly','class'=>'form-control','id'=>'employeeName']) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('dept', 'Department *') !!}
                          {!! Form::text('dept_id',$employees->department->dept_name,['readonly','class'=>'form-control', 'id'=>'dept']) !!}
                        </div>
                        <div class="col-sm-5">
                          {!! Form::label('designation', 'Designation *') !!}
                          {!! Form::text('designation',$employees->emp_designation,['readonly','class'=>'form-control','id'=>'designation']) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('doj', 'Date of Join') !!}
                          {!! Form::date('doj_date',$employees->emp_doj,['readonly','class'=>'form-control','id'=>'doj']) !!}
                        </div>
                        
                        <div class="col-sm-5">
                          {!! Form::label('identification', 'Identification No') !!}
                          {!! Form::text('identification_no',$employees->emp_identification_no,['readonly','class'=>'form-control','id'=>'identification']) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('Email', 'Email *') !!}
                          {!! Form::text('emp_email',$employees->emp_email,['readonly','class'=>'form-control validate-email','id'=>'Email']) !!}
                        </div>
                        <div class="col-sm-5">
                          {!! Form::label('Mobile', 'Contact No *') !!}
                          {!! Form::text('emp_contact',$employees->emp_mobile_no,['readonly','class'=>'form-control','id'=>'Mobile']) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('address1', 'Address Line 1 *') !!}
                          {!! Form::text('address1',$employees->emp_address_line1,['readonly','class'=>'form-control','id'=>'address1']) !!}
                        </div>
                        <div class="col-sm-5">
                          {!! Form::label('address2', 'Address Line 2') !!}
                          {!! Form::text('address2',$employees->emp_address_line2,['readonly','class'=>'form-control','id'=>'address2']) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('Country', 'Country *') !!}
                          {!! Form::text('country_id',$employees->country->name,['readonly','class'=>'form-control', 'id'=>'Country']) !!}
                        </div>
                        <div class="col-sm-5">
                          {!! Form::label('State', 'State') !!}
                          {!! Form::text('state_id',isset($employees->state->name)?$employees->state->name:'',['readonly','class'=>'form-control', 'id'=>'State']) !!}
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('City', 'City') !!}
                          {!! Form::text('city_id',isset($employees->city->name)?$employees->city->name:'',['readonly','class'=>'form-control', 'id'=>'City']) !!}
                        </div>
                        <div class="col-sm-5">
                          {!! Form::label('PostCode', 'Post Code') !!}
                          {!! Form::text('postcode',$employees->emp_postcode,['readonly','class'=>'form-control','id'=>'PostCode']) !!}
                        </div>
                      </div>
                      <?php 
                        if(!empty($employees->emp_image)){$image = "theme/images/employees/".$employees->emp_image;}
                        else {$image = "theme/images/no_image.jpg";}
                      ?>
                      <div class="form-group">
                        <div class="col-sm-5">
                          {!! Form::label('empImage', 'Employee Photo (JPEG,PNG)') !!}<br>
                          <img title="Click to Change" class="img-employee" id="output_image" style="width:125px;height:110px;" src="{{asset($image)}}">
                        </div>
                        <div class="col-sm-5">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="emp_status" id="Status" @if($employees->status==1) checked @endif disabled>
                            <label for="Status">Published</label>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!-- Step2 -->
                  <div class="tab-pane" role="tabpanel" id="step2">
                    <div class="" id="banck_account">
                      <div class="form-group">
                        <div class="col-sm-5">
                          <label for="accountName">Account Name</label>
                          <input type="text" class="form-control" name="account_name" id="accountName" readonly value="{{$employees->emp_account_name}}">
                        </div>
                        <div class="col-sm-5">
                          <label for="accountNumber">Account Number</label>
                          <input type="text" class="form-control" name="account_number" id="accountNumber" readonly value="{{$employees->emp_account_number}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-5">
                          <label for="bankName">Bank Name</label>
                          <input type="text" class="form-control" name="bank_name" id="bankName" readonly value="{{$employees->emp_bank_name}}">
                        </div>
                        <div class="col-sm-5">
                          <label for="bankBranch">Bank Branch</label>
                          <input type="text" class="form-control" name="bank_branch" id="bankBranch" readonly value="{{$employees->emp_bank_branch}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-5">
                          <label for="payNow">PayNow Contact No</label>
                          <input type="text" class="form-control contact2" name="paynow_no" id="payNow" readonly value="{{$employees->emp_paynow_contact_number}}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- Step3 -->
                  <div class="tab-pane" role="tabpanel" id="step3">
                    <div class="" id="banck_account">
                      <div class="form-group">
                        <div class="col-sm-5">
                          <label for="basic">Basic *</label>
                          <input type="text" class="form-control" name="basic" id="basic" readonly value="{{$employees->basic}}">
                        </div>
                        <div class="col-sm-5">
                          <label for="sdl">SDL</label>
                          <input type="text" class="form-control" name="sdl" readonly value="{{$employees->sdl}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-5">
                          <label for="cpfSelf">CPF (Self)</label>
                          <input type="text" class="form-control" name="cpf_self" id="cpfSelf" readonly value="{{$employees->self_cpf}}">
                        </div>
                        <div class="col-sm-5">
                          <label for="cpfEmp">CPF (Employer)</label>
                          <input type="text" class="form-control" name="cpf_emp" id="cpfEmp" readonly value="{{$employees->emp_cpf}}">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Step4 -->
                  <div class="tab-pane" role="tabpanel" id="step4">
                    <h5>Basic Commission:</h5>
                    <div class="form-group">
                      <?php 
                        if($employees->baseCommission->commission_type=='f'){
                          $basic_commission_type = 'Percentage (%)';
                        }else{
                          $basic_commission_type = 'Fixed (amount)';
                        }
                      ?>
                      <div class="col-sm-3">
                        <label for="commissionType">Commission Type</label>
                        <input type="text" class="form-control" id="commissionType" readonly value="{{$basic_commission_type}}">
                      </div>
                      <div class="col-sm-3">
                        <label for="commissionValue">Value</label>
                        <input type="text" name="commision_value" class="form-control" id="commissionValue" readonly value="{{$employees->basic_commission_value}}">
                      </div>
                      <div class="col-sm-3"></div>
                      <div class="col-sm-3"></div>
                    </div><br>
                    <h5>Target Commission:</h5>
                    <div class="form-group">
                      <?php 
                       if($employees->targetCommission->commission_type=='f'){
                          $target_commision_type = 'Percentage (%)';
                        }else{
                          $target_commision_type = 'Fixed (amount)';
                        }
                      ?>
                      <div class="col-sm-3">
                        <label for="targetCommissionType">Commission Type</label>
                        <input type="text" class="form-control" id="target_commision_type" readonly value="{{$target_commision_type}}">
                      </div>

                      <div class="col-sm-3">
                        <label for="targetCommissionValue">Value</label>
                        <input type="text" name="target_commission_value" class="form-control" id="targetCommissionValue" readonly value="{{$employees->target_commission_value}}">
                      </div>

                      <div class="col-sm-3">
                        <label for="targetValue">Target Value</label>
                        <input type="text" name="target_value " class="form-control" id="targetValue" readonly value="{{$employees->target_value}}">
                      </div>

                    </div>
                  </div>

                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection