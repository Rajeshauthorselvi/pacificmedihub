@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Employee</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('employees.index')}}">Employee</a></li>
              <li class="breadcrumb-item active">Edit Employee</li>
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
                <h3 class="card-title">Edit Employee</h3>
              </div>
              <div class="card-body">

                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                  <li role="presentation" class="nav-item">
                    <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1"> General </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2"> Bank Accounts </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3"> Salary & Commission</a>
                  </li>
                </ul>
                <form action="{{route('employees.update',$employees->id)}}" method="post" enctype="multipart/form-data">
                  @csrf 
                   <input name="_method" type="hidden" value="PATCH">
                  <div class="tab-content py-2">
                    <!-- Step1 -->
                    <div class="tab-pane active" role="tabpanel" id="step1">
                      <div class="" id="general">

                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('employeeId', 'Employee Code') !!}
                            {!! Form::text('emp_id',$employees->emp_id,['class'=>'form-control','id'=>'employeeId']) !!}
                          </div>
                          <div class="col-sm-5">
                            {!! Form::label('employeeName', 'Employee Name *') !!}
                            {!! Form::text('emp_name',$employees->emp_name,['class'=>'form-control','id'=>'employeeName']) !!}
                            <span class="text-danger" style="display:none">Employee Name is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('role', 'Roles *') !!}
                            {!! Form::select('role_id',$roles,$employees->role_id,['class'=>'form-contol select2bs4', 'id'=>'role', 'style'=>'width:100%'  ]) !!}
                            <span class="text-danger dept" style="display:none">Role is required</span>
                          </div>
                          <div class="col-sm-5">
                              {!! Form::label('dept', 'Department *') !!}
                              {!! Form::select('dept_id',$departments,$employees->emp_department ,['class'=>'form-contol select2bs4', 'id'=>'dept', 'style'=>'width:100%'  ]) !!}
                              <span class="text-danger dept" style="display:none">Role is required</span>
                          </div>


                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('designation', 'Designation *') !!}
                            {!! Form::text('designation',$employees->emp_designation,['class'=>'form-control','id'=>'designation']) !!}
                            <span class="text-danger" style="display:none">Designation is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('doj', 'Date of Join') !!}
                            <?php $doj = date('d/m/Y',strtotime($employees->emp_doj)); ?>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                              <input type="text" name="doj_date" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{old('doj_date',$doj)}}"/>
                              <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-sm-5">
                            {!! Form::label('identification', 'Identification No') !!}
                            {!! Form::text('identification_no',$employees->emp_identification_no,['class'=>'form-control','id'=>'identification']) !!}
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('Email', 'Email *') !!}
                            {!! Form::text('email',$employees->email,['class'=>'form-control validate-email','id'=>'Email']) !!}
                            <span class="email-error" style="display:none;color:red;">Invalid email</span>
                            <span class="text-danger" style="display:none">Email is required</span>
                            @if($errors->has('email'))
                              <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                          </div>
                          <div class="col-sm-5">
                            {!! Form::label('Mobile', 'Contact No *') !!}
                            {!! Form::text('emp_contact',$employees->emp_mobile_no,['class'=>'form-control','id'=>'Mobile' , 'onkeyup'=>"validateNum(event,this);"]) !!}
                            <span class="text-danger" style="display:none">Mobile No is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('address1', 'Address Line 1 *') !!}
                            {!! Form::text('address1',$employees->emp_address_line1,['class'=>'form-control','id'=>'address1']) !!}
                            <span class="text-danger" style="display:none">Address Line 1 is required</span>
                          </div>
                          <div class="col-sm-5">
                            {!! Form::label('address2', 'Address Line 2') !!}
                            {!! Form::text('address2',$employees->emp_address_line2,['class'=>'form-control','id'=>'address2']) !!}
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('Country', 'Country *') !!}
                            {!! Form::select('country_id',$countries,$employees->emp_country,['class'=>'form-contol select2bs4', 'id'=>'Country', 'style'=>'width:100%' ]) !!}
                            <span class="text-danger country" style="display:none">Country is required</span>
                          </div>
                          <div class="col-sm-5">
                            {!! Form::label('State', 'State') !!}
                            <select name="state_id" class="form-control select2bs4" id="State"></select>
                          </div>
                        </div>

                        <div class="form-group">
                          
                          <div class="col-sm-5">
                            {!! Form::label('City', 'City') !!}
                            <select name="city_id" class="form-control select2bs4" id="City"></select>
                          </div>
                          <div class="col-sm-5">
                            {!! Form::label('PostCode', 'Post Code') !!}
                            {!! Form::text('postcode',$employees->emp_postcode,['class'=>'form-control','id'=>'PostCode']) !!}
                            <span class="text-danger" style="display:none">Post Code is required</span>
                          </div>
                        </div>

                        <?php 
                          if(!empty($employees->emp_image)){$image = "theme/images/employees/".$employees->emp_image;}
                          else {$image = "theme/images/no_image.jpg";}
                        ?>

                        <div class="form-group">
                          <div class="col-sm-5">
                            {!! Form::label('empImage', 'Employee Photo (JPEG,PNG)') !!}
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="emp_image" id="empImage" accept="image/*" value="{{old('emp_image')}}">
                              <label class="custom-file-label" for="empImage">Choose file</label>
                            </div><br><br>
                            <img class="img-employee" id="output_image" style="width:125px;height:110px;" src="{{asset($image)}}">
                          </div>
                          <div class="col-sm-5" style="margin-top: 40px">
                            <div class="icheck-info d-inline">
                              <input type="checkbox" name="emp_status" id="Status" @if((old('emp_status')=='on')||($employees->status==1)) checked @endif>
                              <label for="Status">Published</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <button type="button" id="validateStep1" class="btn btn-primary next-step">Next</button>
                    </div>
                    <!-- Step2 -->
                    <div class="tab-pane" role="tabpanel" id="step2">
                      <div class="" id="banck_account">
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="accountName">Account Name</label>
                            <input type="text" class="form-control" name="account_name" id="accountName" value="{{old('account_name',$employees->emp_account_name)}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="accountNumber">Account Number</label>
                            <input type="text" class="form-control" name="account_number" onkeyup="validateNum(event,this);" id="accountNumber" value="{{old('account_number',$employees->emp_account_number)}}">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="bankName">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" id="bankName" value="{{old('bank_name',$employees->emp_bank_name)}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="bankBranch">Bank Branch</label>
                            <input type="text" class="form-control" name="bank_branch" id="bankBranch" value="{{old('bank_branch',$employees->emp_bank_branch)}}">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="payNow">PayNow Contact No</label>
                            <input type="text" class="form-control contact2" name="paynow_no" onkeyup="validateNum(event,this);" id="payNow" value="{{old('paynow_no',$employees->emp_paynow_contact_number)}}">
                          </div>
                        </div>
                      </div>
                      <ul class="float-left">
                        <li class="list-inline-item">
                          <button type="button" class="btn btn-outline-primary prev-step">Previous</button>
                        </li>
                        <li class="list-inline-item">
                          <button type="button" id="validateStep2" class="btn btn-primary next-step">Next</button>
                        </li>
                      </ul>
                    </div>
                    <!-- Step3 -->
                    <div class="tab-pane" role="tabpanel" id="step3">
                      <div id="salaryCommission">
                        <h5 class="title">Payments</h5>
                        <div class="col-md-12 payment block">
                          <div class="form-group">
                            <div class="col-sm-4">
                              <label for="basic">Basic Salary*</label>
                              <input type="text" class="form-control" name="basic" id="basic" value="{{old('basic',$employees->basic)}}" onkeyup="validateNum(event,this);" autocomplete="off">
                              <span class="text-danger" style="display:none">Basic is required</span>
                            </div>
                            <div class="col-sm-4">
                              <label for="commissionType">Basic Commission Type</label>
                              <select class="form-control commission no-search select2bs4" name="commision_type" id="commissionType">
                                @foreach($base_commissions as $base)
                                  <?php 
                                    if($base->commission_type=='f') $type = 'Fixed (amount)';
                                    else $type = 'Percentage (%)';
                                  ?>
                                  <option @if($employees->basic_commission_type==$base->id) selected="selected" @endif value="{{$base->id}}" {{ (collect(old('commision_type'))->contains($base->id)) ? 'selected':'' }}>{{$type}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-sm-4">
                              <label for="commissionValue">Basic Commission Value</label>
                              <input type="text" name="commision_value" class="form-control" id="commissionValue" autocomplete="off" value="{{old('commision_value',$employees->basic_commission_value)}}" onkeyup="validateNum(event,this);">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-4">
                              <label for="targetCommissionType">Target Commission Type</label>
                              <select class="form-control target-commission no-search select2bs4" name="target_commission_type" id="targetCommissionType">
                                <option selected="selected" value="">Select Target Commission</option>
                                @foreach($target_commissions as $target)
                                  <?php 
                                    if($target->commission_type=='f') $target_type = 'Fixed (amount)';
                                    else $target_type = 'Percentage (%)';
                                  ?>
                                  <option @if($employees->target_commission_type==$target->id) selected="selected" @endif value="{{$target->id}}" {{ (collect(old('target_commission_type'))->contains($target->id)) ? 'selected':'' }}>{{$target_type}}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-sm-4">
                              <label for="targetCommissionValue">Target Commission Value</label>
                              <input type="text" name="target_commission_value" class="form-control" id="targetCommissionValue" autocomplete="off"  value="{{old('target_commission_value',$employees->target_commission_value)}}" onkeyup="validateNum(event,this);">
                            </div>
                            <div class="col-sm-4">
                              <label for="targetValue">Target Value</label>
                              <input type="text" name="target_value" class="form-control" id="targetValue" autocomplete="off" value="{{old('target_value',$employees->target_value)}}" onkeyup="validateNum(event,this);">
                            </div>
                          </div>
                        </div>
                        <h5 class="title">Deductables</h5>
                        <div class="col-md-12 deductable block">
                          <div class="form-group">
                            <div class="col-sm-4">
                              <label for="sdl">SDL</label>
                              <input type="text" class="form-control" name="sdl" id="sdl" value="{{old('sdl',$employees->sdl)}}" onkeyup="validateNum(event,this);">
                            </div>
                            <div class="col-sm-4">
                              <label for="cpfSelf">CPF (Self)</label>
                              <input type="text" class="form-control" name="cpf_self" id="cpfSelf" value="{{old('cpf_self',$employees->self_cpf)}}" onkeyup="validateNum(event,this);">
                            </div>
                          </div>
                        </div>
                        <h5 class="title">Contribution</h5>
                        <div class="col-md-12 contribution block">
                          <div class="form-group">
                            <div class="col-sm-4">
                              <label for="cpfEmp">Employer CPF</label>
                              <input type="text" class="form-control" name="cpf_emp" id="cpfEmp" value="{{old('cpf_emp',$employees->emp_cpf)}}" onkeyup="validateNum(event,this);">
                            </div>
                          </div>
                        </div>
                      </div>
                      <ul class="float-left">
                        <li class="list-inline-item">
                          <button type="button" class="btn btn-outline-primary prev-step">Previous</button>
                        </li>
                        <li class="list-inline-item">
                          <button type="button" id="validateStep3" class="btn btn-primary btn-info-full next-step">Submit</button>
                        </li>
                      </ul>
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

  @push('custom-scripts')
    <script type="text/javascript">
      $(function ($) {
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });

      $(document).ready(function () {
        $(document).ajaxSend(function() {
          $("#overlay").fadeIn(300);　
        });
        
        $('.nav-tabs > li a[title]').tooltip();
        //Wizard
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
          var $target = $(e.target);
          if ($target.parent().hasClass('disabled')) {
            return false;
          }
        });

        function validateStep1(e){
          var valid=true;
          fieldsToValidate=['customer','order_type'];
          //$(e.target).closest('.tab-pane.active').find('span.text-danger').hide();
          if($("[name='emp_name']").val()=="") {
            $("[name='emp_name']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if(!$("#dept").val()) {
            $("#dept").closest('.form-group').find('span.text-danger.dept').show();
            valid=false;
          }
          if($("[name='designation']").val()=="") {
            $("[name='designation']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='email']").val()=="") {
            $("[name='email']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='emp_contact']").val()=="") {
            $("[name='emp_contact']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='address1']").val()=="") {
            $("[name='address1']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("#Country").val()=="") {
            $("#Country").closest('.form-group').find('span.text-danger.country').show();
            valid=false;
          }
          return valid;
        }
        function validateStep2(e){
          var valid=true;
          return valid;
        }
        function validateStep3(e){
          var valid=true;
          if($("[name='basic']").val()=="") {
            $("[name='basic']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if(($('#targetCommissionValue').val()!="")&& ($('#targetValue').val()=="")){
            alert('Please enter the target Value.!');
            valid = false;
          }
          return valid;
        }

        $(".next-step").click(function (e) {
          var $active = $('.nav-tabs li>.active');
          var stepID = $(e.target).attr('id');
          var formFields=$(e.target).closest('.tab-pane.active').find('input,select');
          var fieldsToValidate=[];
          if((stepID=="validateStep1" && validateStep1(e)) || (stepID=="validateStep2" && validateStep2(e)) || (stepID=="validateStep3" && validateStep3(e)) ){
            if(stepID=="validateStep3"){
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

      //Get Basic Commission
      $('.commission').on('change',function(){
        var commissionTypeId = $('#commissionType option:selected').val();
        if(commissionTypeId!=""){
          $('#commissionValue').removeAttr('disabled');  
        }else{
          $('#commissionValue').attr('disabled','disabled');
          $('#commissionValue').val("");
        }
        
        $.ajax({
          url:"{{ url('admin/get-commission-value') }}",
          type:"GET",
          data:{"_token": "{{ csrf_token() }}",id:commissionTypeId},
          success: function (data) { 
            $('#commissionValue').val(data);
          }
        });
      });

      //Get Target Commission
      $('.target-commission').on('change',function(){
        var targetCommissionTypeId = $('#targetCommissionType option:selected').val();
        if(targetCommissionTypeId!=""){
          $('#targetValue').removeAttr('disabled');
          $('#targetCommissionValue').removeAttr('disabled');
        }else{
          $('#targetValue').attr('disabled','disabled');
          $('#targetCommissionValue').attr('disabled','disabled');
          $('#targetValue').val("");
          $('#targetCommissionValue').val("");
        }
        $.ajax({
          url:"{{ url('admin/get-commission-value') }}",
          type:"GET",
          data:{"_token": "{{ csrf_token() }}",id:targetCommissionTypeId},
          success: function (data) { 
            $('#targetCommissionValue').val(data);
          }
        });
      });

      //Get State
      $(document).ready(function(){
        var country_id = "<?php echo json_decode($employees->emp_country); ?>";
        getState(country_id);
      });
      $('#Country').change(function() {
        getState($(this).val());
      })
      function getState(countryID){
        var state_id = "<?php echo json_decode($employees->emp_state); ?>";      
        if(countryID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-state-list')}}?country_id="+countryID,
            success:function(res){  
              if(res){
                $("#State").empty();
                $("#State").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_state="";
                  if(state_id == key) { var select_state = "selected" }
                  $("#State").append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
                });
                $('#State').selectpicker('refresh');           
              }else{
                $("#State").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#State").empty();        
        }      
      }

      //Get City
      $(document).ready(function(){
        var state_id = "<?php echo json_decode($employees->emp_state); ?>";
        getCity(state_id);
      });
      $('#State').change(function() {
        getCity($(this).val());
      })
      function getCity(stateID){
        var city_id = "<?php echo json_decode($employees->emp_city); ?>";   
        if(stateID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-city-list')}}?state_id="+stateID,
            success:function(res){  
              if(res){
                $("#City").empty();
                $("#City").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_city="";
                  if(city_id == key) { var select_city = "selected" }
                  $("#City").append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
                });
                $('#City').selectpicker('refresh');           
              }else{
                $("#City").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#City").empty();        
        }      
      }

      $('.validate-email').on('keypress', function() {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{1,3}/.test(this.value);
        if(!re) {
          $('.email-error').show();
        } else {
          $('.email-error').hide();
        }
      });

    </script>
  @endpush
@endsection