<span style="display: none;">{{$currentRoute=request()->route()->getName()}}</span>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!-- SidebarSearch Form -->
      <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

    </ul>
    <?php 
    if (Auth::guard('employee')->check()) {
      $profile_image = Auth::guard('employee')->user()->logo;
      $name = Auth::guard('employee')->user()->emp_name;
    }
    else{
      $profile_image = Auth::user()->logo;
      $name = Auth::user()->name;
    }
    ?>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  
                  <span class="float-right text-sm text-danger"></span>
                </h3>
                <p class="text-sm"></p>
                <p class="text-sm text-muted"></p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          
          <a href="#" class="dropdown-item dropdown-footer"></a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link notificaion_icon" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <strong><span class="badge badge-warning navbar-badge notificaion_count"></span></strong>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><span class="notificaion_count">0</span> Notifications</span>
          <div class="dropdown-divider"></div>
          <div class="notification-append-sec"></div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>

      <div class="user-panel d-flex">
        <div class="image" >
          <?php 
          if ($profile_image!=null){
              $image=asset('theme/images/profile/'.$profile_image);
            }
            else{
              $image=asset('theme/images/user_placeholder.png');              
            }
            ?>
          <img src="{{ $image }}" class="img-circle" alt="Profile Image">
        </div>
      </div>

      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">{{$name}}&nbsp;<i class="fas fa-angle-down"></i></a>
        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right" style="left:inherit;right:0px;">
          <a href="{{route('admin-profile.index')}}" class="dropdown-item @if($currentRoute=='admin-profile.index') active @endif"><i class="fas fa-user mr-2"></i>Profile</a>
          <div class="dropdown-divider"></div>
          <a href="{{route('admin.logout')}}" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </div>
      </li>

      
    </ul>
  </nav>
  <!-- /.navbar -->