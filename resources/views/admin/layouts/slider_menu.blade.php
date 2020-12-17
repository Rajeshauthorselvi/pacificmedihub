  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
      <img src="{{ asset('theme/images/logo.jpeg') }}" alt="Logo" class="brand-image">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
   
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link active">
              <i class="fas fa-home"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-list"></i>
              <p>
                Products
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/layout/top-nav.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/top-nav-sidebar.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/boxed.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>import Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/fixed-sidebar.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/fixed-sidebar-custom.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Product Variant</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/fixed-topnav.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Product Units</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/layout/fixed-footer.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Brands</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-shopping-cart"></i>
              <p>
                Purchase
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/charts/chartjs.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Purchase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/charts/flot.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Purchase</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-boxes"></i>
              <p>
                Stock
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/UI/general.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Stock-In-Transit</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/icons.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Return</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/buttons.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Return</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/sliders.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Wastage</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-clipboard-list"></i>
              <p>
                RFQ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/forms/general.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List RFQ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/forms/advanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add RFQ</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-dolly-flatbed"></i>
              <p>
                Orders
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/tables/simple.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Orders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/tables/data.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Orders</p>
                </a>
              </li>
            </ul>
          </li>
         
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-users"></i>
              <p>
                Customers
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/mailbox/mailbox.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Customers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/mailbox/compose.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Customers</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-people-carry"></i>
              <p>
                Vendor
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/examples/invoice.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Vendors</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/profile.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Vendor</p>
                </a>
              </li>
            </ul>
          </li>
           <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-address-card"></i>
              <p>
                Employees
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/examples/invoice.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Employees</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/profile.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Employee</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/examples/profile.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Departments</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-dollar-sign"></i>
              <p>
                Commission
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/search/simple.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Base Commission</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Product Commission</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Target Commission</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-map-marker-alt"></i>
              <p>
                Delivery Zone
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/search/simple.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Delivery Zone</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Delivery Zone</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-calculator"></i>
              <p>
                Tax
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/search/simple.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p></p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-chart-bar"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/search/simple.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p></p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="fas fa-laptop"></i>
              <p>
                Static Page
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/search/simple.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>General Settings</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Access Control</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Prefix</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Order Settings</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Customer Settings</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Shopping Cart Settings</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Currencies</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Payment Methods</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Warehoues</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="pages/search/enhanced.html" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Email Templates</p>
                </a>
              </li>
              

            </ul>
          </li>
      
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>