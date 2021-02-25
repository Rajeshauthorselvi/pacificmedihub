<!DOCTYPE html>
<html>
<head>
  <title>PACIFIC MEDIHUB | LOGIN</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('theme/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('theme/dist/css/custom-style.css') }}">
  <script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
  <style type="text/css">
.half-back{background:transparent url(/./theme/images/background_image/background.jpg) no-repeat center center / cover;position: fixed;top: 0px;left:0;width:50%;height: 100%}
.sign-in-bac .form-control {
  text-align: center;
  font-size: 18px;
  height: 41px;
}
.parent-div{
  display: flex;
}
.sign-in-bac {
  padding: 10%;
}
ul li {
  background: transparent;
  float: none;
  display: inline-block;
  box-shadow: 0 0 0;
  border-radius: 0;
}
.tabs {
  font-size: 17px;
}
  </style>
</head>
<body>
  <div class="half-back"></div>
    <div class="col-md-12 parent-div">
      <div class="col-md-6 pull-left"></div>
    <div class="col-md-6 sign-in-bac">
<!-- Tabs navs -->
      <div class="login-box-logo text-center">
        <img class="admin-logo" src="{{ asset('theme/images/logo.png') }}" >
      </div>
      <br>

<ul class="nav nav-tabs mb-3" id="ex1" role="tablist">
  <li class="nav-item" role="presentation">
    <a href="javascript:void(0)" class="nav-link tabs active admin-tab" location-active="admin">Admin Login</a>
  </li>
  <li class="nav-item" role="presentation">
    <a href="javascript:void(0)" class="nav-link tabs employee-tab" location-active="employee">Employee Login</a>
  </li>
</ul>
<!-- Tabs navs -->
<div class="tab-content" id="ex1-content">
  <div class="tab-pane fade show active admin" role="tabpanel" >

      <h4 class="text-center">ADMIN</h4>
        <div class="admin-form">
            <form action="{{ route('admin.store') }}" method="post">
              @csrf 
              @if (Session::has('error_from') && Session::get('error_from')=="admin")
                @include('flash-message')
              @endif
              <div class="input-group mb-3">
                <input type="hidden" name="from" value="admin">
                <input type="email" class="form-control" name="email" placeholder="Email">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>
              @if (Session::has('error_from') && Session::get('error_from')=="admin")
                <span class="text-danger">{{ $errors->first('email') }}</span>
              <div class="clearfix"></div>
              @endif
              <div class="input-group mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              @if (Session::has('error_from') && Session::get('error_from')=="admin")
                <span class="text-danger">{{ $errors->first('password') }}</span>
              @endif
              <div class="row">
                <div class="col-12">
                  <button type="submit" class="btn btn-primary btn-block">LOGIN</button>
                </div>
              </div>
            </form>
        </div>
  </div>
  <div class="tab-pane fade employee " role="tabpanel" aria-labelledby="ex1-tab-2">
      <h4 class="text-center">Employee</h4>
      <div class="employee-form">
          <form action="{{ route('employee.store') }}" method="post">
            @csrf 
            @if (Session::has('error_from') && Session::get('error_from')=="employee")
              @include('flash-message')
            @endif
            <input type="hidden" name="from" value="employee">
            <div class="input-group mb-3">
              <input type="email" class="form-control" name="emp_email" placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
              </div>
              @if (Session::has('error_from') && Session::get('error_from')=="employee")
                <div class="clearfix"></div>
                <span class="text-danger">{{ $errors->first('emp_email') }}</span>
                <div class="clearfix"></div>
              @endif

            <div class="input-group mb-3">
              <input type="password" class="form-control" name="emp_password" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
             @if (Session::has('error_from') && Session::get('error_from')=="employee")
                <div class="clearfix"></div>
                <span class="text-danger">{{ $errors->first('emp_password') }}</span>
              @endif
            
            <div class="row">
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">LOGIN</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
      </div>
  </div>
</div>
<!-- Tabs content -->

      <p class="mb-1">
        <a href="#">Forgot password</a>
      </p>
    
      </div>
    </div>
    <script type="text/javascript">
        $(document).on('click', '.tabs', function(event) {
          event.preventDefault();
          var activate_location=$(this).attr('location-active');
          $('.tab-pane').removeClass('show');
          $('.tab-pane').removeClass('active');

          $('.tabs').removeClass('active');
          $(this).addClass('active');

          $('.'+activate_location).addClass('active');
          $('.'+activate_location).addClass('show');
        });
    </script>
</body>
</html>