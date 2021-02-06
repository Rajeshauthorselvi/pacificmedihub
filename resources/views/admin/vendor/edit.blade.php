@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Vendors</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">List Vendors</a></li>
              <li class="breadcrumb-item active">Edit Vendor</li>
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
                <h3 class="card-title">Edit Vendor</h3>
              </div>
              <div class="card-body">

                <ul class="nav nav-tabs flex-nowrap" role="tablist">
                  <li role="presentation" class="nav-item">
                    <a href="#step1" class="nav-link active" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1"> General </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step2" class="nav-link disabled" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2"> POC </a>
                  </li>
                  <li role="presentation" class="nav-item">
                    <a href="#step3" class="nav-link disabled" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3"> Bank Accounts </a>
                  </li>
                </ul>
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
                            <input type="text" class="form-control" disabled name="vendor_code" id="vendorCode" value="{{old('vendor_code',$vendor->code)}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="vendorName">Vendor Name *</label>
                            <input type="text" class="form-control" name="vendor_name" id="vendorName" value="{{old('vendor_name',$vendor->name)}}">
                            <span class="text-danger" style="display:none">Vendor Name is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorUen">Vendor UEN *</label>
                            <input type="text" class="form-control" name="vendor_uen" id="vendorUen" value="{{old('vendor_uen',$vendor->uen)}}">
                            <span class="text-danger" style="display:none">Vendor UEN is required</span>
                          </div>
                          <div class="col-sm-5">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorGst">Vendor GST No</label>
                            <input type="text" class="form-control" name="vendor_gst" id="vendorGst" value="{{old('vendor_gst',$vendor->gst_no)}}">
                          </div>
                          <?php 
                            if(!empty($vendor->gst_image)){$gstImage = "theme/images/vendor/gst/".$vendor->gst_image;
                          ?>
                          <div class="col-sm-5">
                            <div class="form-group" style="display:block;">
                              <label for="vendorGstImage">Upload GST Certificate Copy(JPEG,PNG,PDF)</label><br>
                              <input type="file" name="vendorGst_image" id="vendorGstImage" accept="image/*" onchange="preview_image1(event)" style="display:none;" value="{{$vendor->gst_image}}">
                              <img title="Click to Change" class="img-vendor" id="output_image1"  onclick="$('#vendorGstImage').trigger('click'); return true;" style="width:100px;height:100px;cursor:pointer;" src="{{asset($gstImage)}}">
                            </div>
                          </div>
                          <?php
                            } else {
                          ?>
                          <div class="col-sm-5">
                            <label for="vendorGstImage">Upload GST Certificate Copy(JPEG,PNG,PDF)</label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="vendorGst_image" id="vendorGstImage" accept="image/*" value="{{old('vendorGst_image')}}">
                              <label class="custom-file-label" for="vendorGstImage">Choose file</label>
                            </div>
                          </div>
                          <?php
                            }
                          ?>
                        </div>

                        <!-- <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorPan">Vendor PAN No</label>
                            <input type="text" class="form-control" name="vendor_pan" id="vendorPan" value="{{old('vendor_pan',$vendor->pan_no)}}">
                          </div>
                          <?php 
                            if(!empty($vendor->pan_image)){$panImage = "theme/images/vendor/pan/".$vendor->pan_image;
                          ?>
                          <div class="col-sm-5">
                            <div class="form-group" style="display:block;">
                              <label for="vendorPanImage">Upload PAN Copy(JPEG,PNG,PDF)</label><br>
                              <input type="file" name="vendorPan_image" id="vendorPanImage" accept="image/*" onchange="preview_image2(event)" style="display:none;" value="{{$vendor->pan_image}}">
                              <img title="Click to Change" class="img-vendor" id="output_image2"  onclick="$('#vendorPanImage').trigger('click'); return true;" style="width:100px;height:100px;cursor:pointer;" src="{{asset($panImage)}}">
                            </div>
                          </div>
                          <?php 
                            } else {
                          ?>
                          <div class="col-sm-5">
                            <label for="vendorPanImage">Upload PAN Copy(JPEG,PNG,PDF)</label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="vendorPan_image" id="vendorPanImage" accept="image/*" value="{{old('vendorPan_image')}}">
                              <label class="custom-file-label" for="vendorPanImage">Choose file</label>
                            </div>
                          </div>
                          <?php
                            }
                          ?>
                        </div> -->

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="Email">Email *</label>
                            <input type="text" class="form-control validate-email" name="vendor_email" id="Email" value="{{old('vendor_email',$vendor->email)}}">
                            <span class="email-error" style="display:none;color:red;">Invalid email</span>
                            <span class="text-danger" style="display:none">Vendor Email is required</span>
                            @if($errors->has('vendor_email'))
                              <span class="text-danger">{{ $errors->first('vendor_email') }}</span>
                            @endif
                          </div>
                          <div class="col-sm-5">
                            <label for="Mobile">Mobile No *</label>
                            <input type="text" class="form-control contact" name="vendor_contact" id="Mobile" value="{{old('vendor_contact',$vendor->contact_number)}}">
                            <span class="text-danger" style="display:none">Vendor Mobile No is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="address1">Address Line 1 *</label>
                            <input type="text" class="form-control" name="address1" id="address1" value="{{old('address1',$vendor->address_line1)}}">
                            <span class="text-danger" style="display:none">Address Line 1 is required</span>
                          </div>
                          <div class="col-sm-5">
                            <label for="address2">Address Line 2</label>
                            <input type="text" class="form-control" name="address2" id="address2" value="{{old('address2',$vendor->address_line2)}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="PostCode">Post Code *</label>
                            <input type="text" class="form-control" name="postcode" id="PostCode" value="{{old('postcode',$vendor->post_code)}}">
                            <span class="text-danger" style="display:none">Post Code is required</span>
                          </div>
                          <div class="col-sm-5">
                            <label for="Country">Country *</label>
                            {!! Form::select('country',$countries,$vendor->country,['class'=>'form-control select2bs4', 'id'=>'Country' ]) !!}
                            <span class="text-danger" style="display:none">Country is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="State">State</label>
                            <select name="state" class="form-control select2bs4" id="State"></select>
                          </div>
                          <div class="col-sm-5">
                            <label for="City">City</label>
                            <select name="city" class="form-control select2bs4" id="City"></select>
                          </div>
                        </div>

                        <div class="form-group">
                          <?php 
                            if(!empty($vendor->logo_image)){$image = "theme/images/vendor/".$vendor->logo_image;
                          ?>
                          <div class="col-sm-5">
                            <div class="form-group" style="display:block;">
                              <label for="vendorLogo">Vendor Logo (JPEG,PNG)</label><br>
                              <input type="file" name="vendor_logo" id="vendorLogo" accept="image/*" onchange="preview_image(event)" style="display:none;" value="{{$vendor->logo_image}}">
                              <img title="Click to Change" class="img-vendor" id="output_image"  onclick="$('#vendorLogo').trigger('click'); return true;" style="width:100px;height:100px;cursor:pointer;" src="{{asset($image)}}">
                            </div>
                          </div>
                          <?php 
                            } else {
                          ?>
                          <div class="col-sm-5">
                            <label for="vendorLogo">Vendor Logo (JPEG,PNG)</label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="vendor_logo" id="vendorLogo" accept="image/*" value="{{old('vendor_logo')}}">
                              <label class="custom-file-label" for="vendorLogo">Choose file</label>
                            </div>
                          </div>
                          <?php 
                            } 
                          ?>
                          <div class="col-sm-5">
                          </div>
                        </div>

                      </div>
                      <button type="button" id="validateStep1" class="btn btn-primary next-step">Next</button>
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
                                    <input type="text" class="form-control" name="poc[contact][]" id="contact1" value="{{old('contact',$poc->contact_no)}}">
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                            <input type="hidden" value="{{$count++}}">
                          @endforeach
                        </table>
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
                      <div class="" id="banck_account">
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="accountName">Account Name</label>
                            <input type="text" class="form-control" name="account_name" id="accountName" value="{{old('account_name',$vendor->account_name)}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="accountNumber">Account Number</label>
                            <input type="text" class="form-control" name="account_number" id="accountNumber" value="{{old('account_number',$vendor->account_number)}}">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="bankName">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" id="bankName" value="{{old('bank_name',$vendor->bank_name)}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="bankBranch">Bank Branch</label>
                            <input type="text" class="form-control" name="bank_branch" id="bankBranch" value="{{old('bank_branch',$vendor->bank_branch)}}">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="payNow">PayNow Contact No</label>
                            <input type="text" class="form-control contact2" name="paynow_no" id="payNow" value="{{old('paynow_no',$vendor->paynow_contact_number)}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="Place">Place</label>
                            <input type="text" class="form-control" name="place" id="Place" value="{{old('place',$vendor->bank_place)}}">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-10">
                          <label for="Others">Others</label>
                          <textarea class="form-control" rows="3" name="others" id="Others">{{old('others',$vendor->others)}}</textarea>
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
<style type="text/css">
.tab-content .form-group {
  display: flex;
}
</style>
  @push('custom-scripts')
    <script type="text/javascript">
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
          if($("[name='vendor_name']").val()=="") {
            $("[name='vendor_name']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='vendor_uen']").val()=="") {
            $("[name='vendor_uen']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='vendor_email']").val()=="") {
            $("[name='vendor_email']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='vendor_contact']").val()=="") {
            $("[name='vendor_contact']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='vendor_email']").val()=="") {
            $("[name='vendor_email']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='vendor_contact']").val()=="") {
            $("[name='vendor_contact']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='address1']").val()=="") {
            $("[name='address1']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='postcode']").val()=="") {
            $("[name='postcode']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='country']").val()=="") {
            $("[name='country']").closest('.form-group').find('span.text-danger').show();
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

      $('.validate-email').on('keypress', function() {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{1,3}/.test(this.value);
        if(!re) {
          $('.email-error').show();
        } else {
          $('.email-error').hide();
        }
      });

      $('.validate-email1').on('keypress', function() {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{1,3}/.test(this.value);
        if(!re) {
          $('.email-error1').show();
        } else {
          $('.email-error1').hide();
        }
      });

      $('.validate-email2').on('keypress', function() {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{1,3}/.test(this.value);
        if(!re) {
          $('.email-error2').show();
        } else {
          $('.email-error2').hide();
        }
      });

      $('.validate-email3').on('keypress', function() {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{1,3}/.test(this.value);
        if(!re) {
          $('.email-error3').show();
        } else {
          $('.email-error3').hide();
        }
      });

      $('.contact').keyup(function(e){
        if(/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
          alert('Enter numbers only');
        }
      });

      $('.contact2').keyup(function(e){
        if(/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
          alert('Enter numbers only');
        }
      });

      $('#contact1').keyup(function(e){
        if(/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
          alert('Enter numbers only');
        }
      });
      $('#contact2').keyup(function(e){
        if(/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
          alert('Enter numbers only');
        }
      });
      $('#contact3').keyup(function(e){
        if(/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
          alert('Enter numbers only');
        }
      });
    </script>
    <script type='text/javascript'>
      function preview_image(event) 
      {
        var reader = new FileReader();
        reader.onload = function()
        {
          var output = document.getElementById('output_image');
          output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
      }

      function preview_image1(event) 
      {
        var reader = new FileReader();
        reader.onload = function()
        {
          var output = document.getElementById('output_image1');
          output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
      }

      function preview_image2(event) 
      {
        var reader = new FileReader();
        reader.onload = function()
        {
          var output = document.getElementById('output_image2');
          output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
      }

      //Get State
      $(document).ready(function(){
        var country_id = "<?php echo json_decode($vendor->country); ?>";
        getState(country_id);
      });
      $('#Country').change(function() {
        getState($(this).val());
      })
      function getState(countryID){
        var state_id = "<?php echo json_decode($vendor->state); ?>";      
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
        var state_id = "<?php echo json_decode($vendor->state); ?>";
        getCity(state_id);
      });
      $('#State').change(function() {
        getCity($(this).val());
      })
      function getCity(stateID){
        var city_id = "<?php echo json_decode($vendor->city); ?>";   
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

    </script>
  @endpush
@endsection