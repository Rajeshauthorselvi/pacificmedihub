<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PACIFIC MEDIHUB | LOGIN</title>
  <link rel="icon" type="image/png" href="{{ asset('theme/images/fav.png') }}" />
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
    .left-half{background:transparent url('theme/images/background_image/background.png') no-repeat center center/cover;position: fixed;top: 0px;left:0;width:50%;height: 100%;background-color: #3287c7;}
    .login-page {width: 100%;display: flex;}
    .login-page .right-half {width: 50%;position: fixed;right: 0;background-color: #297ab5;height:100%;}
    .login-page .login-box {width: 75%;margin: auto;position: relative;top: 15%;padding: 20px 30px 30px;background: #fff;border-radius: 5px;box-shadow: 0 0 1px rgb(200, 213, 221),0 0 0 rgba(0,0,0,.5);}
    .login-page ul#ex1{border: none;padding-bottom:20px;}
    .login-page .login-box .login-box-logo {text-align: center;padding: 0 0 25px 0;}
    .login-page .login-box .input-block {width: 90%;margin: auto;}
    .login-page .login-box #ex1-content .input-group .form-control {padding: 25px 15px;border-radius: 0;border-right: 0;}
    .login-page .login-box #ex1-content .input-group-text .fas{color: #3287c7;}
    .login-page .login-box #ex1-content .input-group-text {padding: 0.375rem 1.2rem;background-color:#fff;border-left:0;border-radius: 0;}
    .login-page .login-box #ex1-content .btn-primary{border-radius: 0;}
    .login-page .login-box #ex1-content .btn-primary:hover{background: #297ab5;}
    ul#ex1 li.nav-item {width: 50%;border-top-left-radius: 0;border-top-right-radius: 0;text-align: center;font-weight: normal;}
    ul#ex1 li.nav-item a.tabs.nav-link, ul.#ex1 li.nav-item a.tabs.nav-link.active {color: #fff;background-color:#3287c7;border-color: #3287c7;font-weight: bold;border-radius: 0;font-weight: normal;}
    ul#ex1 li.nav-item .nav-link.tabs.active{border:none;color:#fff;background:#3287c7;padding:12px 10px;border-radius:0;}
    ul#ex1 li.nav-item a.nav-link{padding: 12px 10px;border: none;color: #49506a;}
    ul#ex1 li.nav-item a.nav-link:hover {border: none;color: #3287c7;padding: 12px 10px;}
    .tab-pane.active{animation: slide-down .8s ease-out;}
    @keyframes slide-down {0% { opacity: 0; transform: translateY(20%); }100% { opacity: 1; transform: translateX(0); }}
  </style>
</head>

<body class="hold-transition">
  <div class="container-fluid">
    <div class="row">
      <div class="login-page">
        <div class="left-half"></div>
        <div class="right-half">
          <div class="login-box">
            <div class="login-box-logo text-center">
              <img class="admin-logo" src="{{ asset('theme/images/logo.png') }}" >
            </div>
            <div class="input-block">

              <ul class="nav nav-tabs" id="ex1" role="tablist">
                <li class="nav-item" role="presentation">
                  <a href="javascript:void(0)" class="nav-link tabs active admin-tab" location-active="admin">ADMIN</a>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="javascript:void(0)" class="nav-link tabs employee-tab" location-active="employee">EMPLOYEE</a>
                </li>
              </ul>
              <!-- Tabs navs -->
              <div class="tab-content" id="ex1-content">
                <div class="tab-pane fade show active admin" role="tabpanel" >
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
            </div>
          </div>
        </div>
      </div>
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