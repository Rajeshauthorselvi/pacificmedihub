@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Profile</li>
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('theme/images/profile/'.$admin->profile_image) }}"
                       alt="Profile picture">
                </div>
                <h3 class="profile-username text-center">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</h3>
                <p class="text-muted text-center">Admin</p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-sm-9">
            <div class="card">
              <!-- <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item">
                    <a class="nav-link active" href="#persnol_details" data-toggle="tab">Profile</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#change_password" data-toggle="tab">Change Password</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#settings" data-toggle="tab">Avatar</a>
                  </li>
                </ul>
              </div> --><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="persnol_details">
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="firstName">First Name *</label>
                        {!! Form::text('first_name', $admin->first_name,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="lastName">Last Name *</label>
                        {!! Form::text('last_name', $admin->last_name,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="email">Email *</label>
                        {!! Form::text('email', $admin->email,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="contactNo">Contact No *</label>
                        {!! Form::text('contact_number', $admin->contact_number,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="gender">Gender *</label>
                        <?php 
                          if($admin->gender=='Male') $gender='Male';
                          else if($admin->gender=='Female') $gender='Female'
                        ?>
                        {!! Form::text('gender', $gender,['class'=>'form-control','readonly']) !!}
                        {{-- <?php $gender=[''=>'Please select']+['Male'=>'Male','Female'=>'Female']; ?>
                        {!! Form::select('gender',$gender,null,['class'=>'form-control']) !!} --}}
                      </div>
                      <div class="col-sm-6">
                        <label for="gst">GST No *</label>
                        {!! Form::text('gst', $company->company_gst,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="address1">Address Line 1 *</label>
                        {!! Form::text('address1', $company->address_1,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="address2">Address Line 2</label>
                        {!! Form::text('address2', $company->address_2,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="address1">Country *</label>
                        {!! Form::text('country', $company->getCountry->name,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="state">State</label>
                        {!! Form::text('state', $company->getState->name,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="city">City</label>
                        {!! Form::text('city', $company->getCity->name,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-6">
                        <label for="postCode">Post Code</label>
                        {!! Form::text('postcode', $company->post_code,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                    <!-- <div class="col-sm-4">
                        <div class="form-group">
                            <a href="{{ url('admin/admin-dashboard') }}" class="btn reset-btn">Cancel</a>
                            <button class="btn save-btn" type="submit">Save</button>
                        </div>
                    </div> -->
                  </div>
                  <div class="tab-pane" id="change_password">
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="oldPassword">Old Password *</label>
                        {!! Form::text('old_password', null,['class'=>'form-control required','id'=>'oldPassword']) !!}
                        <span class="text-danger"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="newPassword">New Password *</label>
                        {!! Form::text('new_password', null,['class'=>'form-control required','id'=>'newPassword']) !!}
                        <span class="text-danger"></span>
                        <span>At least 1 capital, 1 lowercase, 1 number and more then 8 characters long</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-6">
                        <label for="confirmPassword">Confirm Password *</label>
                        {!! Form::text('confirm_password', null,['class'=>'form-control required','id'=>'confirmPassword']) !!}
                        <span class="text-danger"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn save-btn change-password">Change Password</button>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    .form-group{ display:flex;}
  </style>

  @push('custom-scripts')
    <script type="text/javascript">
      $(document).on('click', '.change-password',function(){
        alert('hi');
        var error_count=0;
        $('#change_password' '.required').each(function(index, el) {

          var type=$(this).attr('type');
          var current_val=$(this).val();
          if (current_val=="" && type=="text") {
            $(this).next('.text-danger').html('<span class="text-danger">This field is required</span>');
            error_count += 1;
          }
          if (error_count==0) {
            alert('done');
          }
          alert('error');

      });  
    </script>
    
  @endpush

  @endsection