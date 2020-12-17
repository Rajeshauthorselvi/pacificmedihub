<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PACIFIC MEDIHUB | ADMIN DASHBOARD</title>
  <link rel="icon" type="image/png" href="{{ asset('theme/images/fav.png') }}" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('theme/dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('theme/dist/css/custom-style.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('theme/plugins/daterangepicker/daterangepicker.css') }}">
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Topbar -->
    @include('admin.layouts.header')
    <!-- End of Topbar -->

    <!-- Sidebar -->
    @include('admin.layouts.slider_menu')
    <!-- End of Sidebar -->

    <!-- Begin Page Content -->
    @yield('main_container')
    <!-- End of Main Content -->
  </div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('theme/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('theme/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('theme/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('theme/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('theme/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ asset('theme/dist/js/adminlte.min.js') }}"></script>

</body>
</html>
