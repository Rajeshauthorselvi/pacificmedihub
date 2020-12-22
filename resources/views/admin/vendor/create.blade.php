@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Create Vendors</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">List Vendors</a></li>
              <li class="breadcrumb-item active">Add Vendor</li>
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
      <div class="container-fluid vendor">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Vendor</h3>
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
                <form action="{{route('vendor.store')}}" method="post" enctype="multipart/form-data">
                  @csrf 

                  <div class="tab-content py-2">
                    <!-- Step1 -->
                    <div class="tab-pane active" role="tabpanel" id="step1">
                      <div class="" id="general">

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorCode">Vendor Code</label>
                            <input type="text" class="form-control" disabled name="vendor_code" id="vendorCode" value="{{old('vendor_code')}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="vendorName">Vendor Name *</label>
                            <input type="text" class="form-control" name="vendor_name" id="vendorName" value="{{old('vendor_name')}}">
                            <span class="text-danger" style="display:none">Vendor Name is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorUen">Vendor UEN *</label>
                            <input type="text" class="form-control" name="vendor_uen" id="vendorUen" value="{{old('vendor_uen')}}">
                            <span class="text-danger" style="display:none">Vendor UEN is required</span>
                          </div>
                          <div class="col-sm-5">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorGst">Vendor GST No</label>
                            <input type="text" class="form-control" name="vendor_gst" id="vendorGst" value="{{old('vendor_gst')}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="vendorGstImage">Upload GST Certificate Copy(JPEG,PNG,PDF)</label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="vendorGst_image" id="vendorGstImage" accept="image/*" value="{{old('vendorGst_image')}}">
                              <label class="custom-file-label" for="vendorGstImage">Choose file</label>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorPan">Vendor PAN No</label>
                            <input type="text" class="form-control" name="vendor_pan" id="vendorPan" value="{{old('vendor_pan')}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="vendorPanImage">Upload PAN Copy(JPEG,PNG,PDF)</label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="vendorPan_image" id="vendorPanImage" accept="image/*" value="{{old('vendorPan_image')}}">
                              <label class="custom-file-label" for="vendorPanImage">Choose file</label>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="Email">Email *</label>
                            <input type="text" class="form-control" name="vendor_email" id="Email" value="{{old('vendor_email')}}">
                            <span class="text-danger" style="display:none">Vendor Email is required</span>
                            @if($errors->has('vendor_email'))
                              <span class="text-danger">{{ $errors->first('vendor_email') }}</span>
                            @endif
                          </div>
                          <div class="col-sm-5">
                            <label for="Mobile">Mobile No *</label>
                            <input type="text" class="form-control" name="vendor_contact" id="Mobile" value="{{old('vendor_contact')}}">
                            <span class="text-danger" style="display:none">Vendor Mobile No is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="address1">Address Line 1 *</label>
                            <input type="text" class="form-control" name="address1" id="address1" value="{{old('address1')}}">
                            <span class="text-danger" style="display:none">Address Line 1 is required</span>
                          </div>
                          <div class="col-sm-5">
                            <label for="address2">Address Line 2</label>
                            <input type="text" class="form-control" name="address2" id="address2" value="{{old('address2')}}">
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="PostCode">Post Code *</label>
                            <input type="text" class="form-control" name="postcode" id="PostCode" value="{{old('postcode')}}">
                            <span class="text-danger" style="display:none">Post Code is required</span>
                          </div>
                          <div class="col-sm-5">
                            <label for="Country">Country *</label>
                            <input type="text" class="form-control" name="country" id="Country" value="{{old('country')}}">
                            <span class="text-danger" style="display:none">Country is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="State">State *</label>
                            <input type="text" class="form-control" name="state" id="State" value="{{old('state')}}">
                            <span class="text-danger" style="display:none">State is required</span>
                          </div>
                          <div class="col-sm-5">
                            <label for="City">City *</label>
                            <input type="text" class="form-control" name="city" id="City" value="{{old('city')}}">
                            <span class="text-danger" style="display:none">City is required</span>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="vendorLogo">Vendor Logo (JPEG,PNG)</label>
                            <div class="custom-file">
                              <input type="file" class="custom-file-input" name="vendor_logo" id="vendorLogo" accept="image/*" value="{{old('vendor_logo')}}">
                              <label class="custom-file-label" for="vendorLogo">Choose file</label>
                            </div>
                          </div>
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
                              <th></th><th>Name</th><th>Email</th><th>Phone No</th><th><a href="#" id="add"><i class="fas fa-plus-circle"></i>&nbsp;ADD</a></th>
                            </tr>
                          </thead>
                          <tr>
                            <td>1</td>
                            <td>
                              <div class="form-group">
                                <input type="text" class="form-control" name="poc[name][]" value="{{old('name')}}">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <input type="text" class="form-control" name="poc[email][]" value="{{old('email')}}">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <input type="text" class="form-control" name="poc[contact][]" value="{{old('contact')}}">
                              </div>
                            </td>
                            <td>
                              <a href="#" class="deleteButton"><i class="fas fa-minus-circle"></i></a>
                            </td>
                          </tr>
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
                            <label for="accountName">Account Name *</label>
                            <input type="text" class="form-control" name="account_name" id="accountName" value="{{old('account_name')}}">
                            <span class="text-danger" style="display:none">Account Name is required</span>
                          </div>
                          <div class="col-sm-5">
                            <label for="accountNumber">Account Number *</label>
                            <input type="text" class="form-control" name="account_number" id="accountNumber" value="{{old('account_number')}}">
                            <span class="text-danger" style="display:none">Account Number is required</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="bankName">Bank Name *</label>
                            <input type="text" class="form-control" name="bank_name" id="bankName" value="{{old('bank_name')}}">
                            <span class="text-danger" style="display:none">Bank Name is required</span>
                          </div>
                          <div class="col-sm-5">
                            <label for="bankBranch">Bank Branch *</label>
                            <input type="text" class="form-control" name="bank_branch" id="bankBranch" value="{{old('bank_branch')}}">
                            <span class="text-danger" style="display:none">Bank Branch is required</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-5">
                            <label for="payNow">PayNow Contact No</label>
                            <input type="text" class="form-control" name="paynow_no" id="payNow" value="{{old('paynow_no')}}">
                          </div>
                          <div class="col-sm-5">
                            <label for="Place">Place</label>
                            <input type="text" class="form-control" name="place" id="Place" value="{{old('place')}}">
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-10">
                          <label for="Others">Others</label>
                          <textarea class="form-control" rows="3" name="others" id="Others">{{old('others')}}</textarea>
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
          if($("[name='state']").val()=="") {
            $("[name='state']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='city']").val()=="") {
            $("[name='city']").closest('.form-group').find('span.text-danger').show();
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
          if($("[name='account_name']").val()=="") {
            $("[name='account_name']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='account_number']").val()=="") {
            $("[name='account_number']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='bank_name']").val()=="") {
            $("[name='bank_name']").closest('.form-group').find('span.text-danger').show();
            valid=false;
          }
          if($("[name='bank_branch']").val()=="") {
            $("[name='bank_branch']").closest('.form-group').find('span.text-danger').show();
            valid=false;
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

     function createRow(id) {
    var newrow = [
        id,
        '<div class="form-group"><input type="text" class="form-control" name="poc[name][]"></div>',
        '<div class="form-group"><input type="text" class="form-control" name="poc[email][]"></div>',
        '<div class="form-group"><input type="text" class="form-control" name="poc[contact][]"></div>',
        '<a href="#" class="deleteButton"><i class="fas fa-minus-circle"></i></a>'
    ];

    return '<tr><td>'+newrow.join('</td><td>')+'</td></tr>';
}

function renumberRows() {
    $('table#pocList tbody tr').each(function(index) {
        $(this).children('td:first').text(index+1);
    });
}

$('a#add').click(function() {
    var lastvalue = 1 + parseInt($('table#pocList tbody').children('tr:last').children('td:first').text());
    $('table#pocList tbody').append(createRow(lastvalue));
});

$('table#pocList').on('click','.addButton',function() {
    $(this).closest('tr').after(createRow(0));
    renumberRows();
}).on('click','.deleteButton',function() {
    $(this).closest('tr').remove();
    renumberRows();
});


    </script>
  @endpush

@endsection