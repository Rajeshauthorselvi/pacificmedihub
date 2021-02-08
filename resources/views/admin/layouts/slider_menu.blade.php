  <span style="display: none;">{{$current_route=request()->route()->getName()}}</span>
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
          <!-- Dashboard -->
          <li class="nav-item @if($current_route=='admin.dashboard') active @endif">
            <a href="{{route('admin.dashboard')}}" class="nav-link"><i class="fas fa-home"></i><p>Dashboard</p></a>
          </li>
          <!-- Products -->
          <li class="nav-item @if($current_route=='products.index'||$current_route=='products.create'||$current_route=='products.edit'||$current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='product-units.index'||$current_route=='product-units.create'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit'||$current_route=='option_values.index'||$current_route=='option_values.create'||$current_route=='option_values.edit') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-list"></i> <p>Products <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='products.index'||$current_route=='products.create'||$current_route=='products.edit'||$current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='product-units.index'||$current_route=='product-units.create'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit'||$current_route=='option_values.index'||$current_route=='option_values.create'||$current_route=='option_values.edit') block @endif">
              <li class="nav-item @if($current_route=='products.index'||$current_route=='products.edit') active @endif">
                <a href="{{route('products.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>List Products</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='products.create') active @endif">
                <a href="{{route('products.create')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Add Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Import Products</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit') active @endif">
                <a href="{{route('categories.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Categories</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit') active @endif">
                <a href="{{route('options.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Options</p>
                </a>
              </li>
            {{-- 
              <li class="nav-item @if($current_route=='option_values.index'||$current_route=='option_values.create'||$current_route=='option_values.edit') active @endif">
                <a href="{{route('option_values.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Option Values</p>
                </a>
              </li> 
            --}}
              <li class="nav-item @if($current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit') active @endif">
                <a href="{{route('brands.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Brands</p>
                </a>
              </li>
            </ul>
          </li>


          <li class="nav-item @if($current_route=='purchase.index'||$current_route=='purchase.create'||$current_route=='purchase.edit') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-shopping-cart"></i> <p>Purchase <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='purchase.index'||$current_route=='purchase.create'||$current_route=='purchase.edit') block @endif">
              <li class="nav-item @if($current_route=='purchase.index'||$current_route=='purchase.edit') active @endif" >
                <a href="{{ route('purchase.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>List Purchase</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='purchase.create') active @endif">
                <a href="{{ route('purchase.create') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Add Purchase</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @if($current_route=='return.index'||$current_route=='stock-in-transit.index'||$current_route=='return.create'||$current_route=='return.edit'||$current_route=='wastage.index'||$current_route=='wastage.create') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-boxes"></i> <p>Stock <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='return.index'||$current_route=='stock-in-transit.index'||$current_route=='return.create'||$current_route=='return.edit'||$current_route=='wastage.index'||$current_route=='wastage.create') block @endif">
              <li class="nav-item @if($current_route=='stock-in-transit.index') active @endif">
                <a href="{{ route('stock-in-transit.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Stock-In-Transit</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='return.index'||$current_route=='return.edit') active @endif">
                <a href="{{ route('return.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>List Return</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='return.create') active @endif">
                <a href="{{ route('return.create') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Add Return</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='wastage.index'||$current_route=='wastage.create') active @endif">
                <a href="{{ route('wastage.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Wastage</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @if($current_route=='rfq.index'||$current_route=='rfq.create'||$current_route=='rfq.edit'||$current_route=='rfq.show') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-clipboard-list"></i> <p>RFQ <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='rfq.index'||$current_route=='rfq.create'||$current_route=='rfq.edit'||$current_route=='rfq.show') block @endif">
              <li class="nav-item @if($current_route=='rfq.index'||$current_route=='rfq.edit'||$current_route=='rfq.show') active @endif">
                <a href="{{ route('rfq.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>List RFQ</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='rfq.create') active @endif">
                <a href="{{ route('rfq.create') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Add RFQ</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item @if($current_route=='orders.index'||$current_route=='orders.create'||$current_route=='orders.edit') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-dolly-flatbed"></i> <p>Orders <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='orders.index'||$current_route=='orders.create'||$current_route=='orders.edit') block @endif">
              <li class="nav-item @if($current_route=='orders.index') active @endif">
                <a href="{{ route('orders.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>List Orders</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='orders.create') active @endif">
                <a href="{{ route('orders.create') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Add Orders</p>
                </a>
              </li>
            </ul>
          </li>
          
          <!-- Customer/POC -->
          <li class="nav-item @if($current_route=='customers.index'||$current_route=='customers.create'||$current_route=='customers.edit'||$current_route=='customers.show') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-users"></i>
              <p>
                Customers
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='customers.index'||$current_route=='customers.create'||$current_route=='customers.edit'||$current_route=='customers.show') block @endif">
              <li class="nav-item @if($current_route=='customers.index') active @endif">
                <a href="{{ route('customers.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Customers</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='customers.create') active @endif">
                <a href="{{ route('customers.create') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Customers</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item @if($current_route=='vendor.index'||$current_route=='vendor.create'||$current_route=='vendor.edit'||$current_route=='vendor.show'||$current_route=='vendor-products.index') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-people-carry"></i>
              <p>
                Vendor
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='vendor.index'||$current_route=='vendor.create'||$current_route=='vendor.edit'||$current_route=='vendor-products.index'||$current_route=='vendor.show') block @endif">
              <li class="nav-item @if($current_route=='vendor.index'||$current_route=='vendor.edit'||$current_route=='vendor.show') active @endif">
                <a href="{{route('vendor.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Vendors</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='vendor.create') active @endif">
                <a href="{{route('vendor.create')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Vendor</p>
                </a>
              </li>
            </ul>
          </li>
           <li class="nav-item @if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show'||$current_route=='salary.list') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-address-card"></i>
              <p>
                Employees
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show'||$current_route=='salary.list') block @endif">
              <li class="nav-item @if($current_route=='employees.index'||$current_route=='employees.edit'||$current_route=='employees.show') active @endif">
                <a href="{{route('employees.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Employees</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='employees.create') active @endif">
                <a href="{{route('employees.create')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Add Employee</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='salary.list') active @endif">
                <a href="{{route('salary.list')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Salary</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Commission</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item @if($current_route=='delivery_zone.index'||$current_route=='delivery_zone.create'||$current_route=='delivery_zone.edit') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-map-marker-alt"></i>
              <p>
                Delivery Zone
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='delivery_zone.index'||$current_route=='delivery_zone.create'||$current_route=='delivery_zone.edit') block @endif">
              <li class="nav-item  @if($current_route=='delivery_zone.index'||$current_route=='delivery_zone.edit') active @endif">
                <a href="{{ route('delivery_zone.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>List Delivery Zone</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='delivery_zone.create') active @endif">
                <a href="{{ route('delivery_zone.create') }}" class="nav-link">
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
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
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
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p></p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item">
            <a href="" class="nav-link">
              <i class="fas fa-laptop"></i>
              <p>
                Static Page
              </p>
            </a>
          </li>

          <li class="nav-item @if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show'||$current_route=='settings.index'||$current_route=='currency.index'||$current_route=='payment_method.index'||$current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit'||$current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create') menu-is-opening menu-open @endif">
            <a href="#" class="nav-link">
              <i class="fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show'||$current_route=='settings.index'||$current_route=='currency.index'||$current_route=='payment_method.index'||$current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit'||$current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create') block @endif">
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>General Settings</p>
                </a>
              </li>
              {{-- <li class="nav-item @if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show') active @endif">
                                            <a href="{{route('access-control.index')}}" class="nav-link">
                                              <i class="fas fa-angle-double-right"></i>
                                              <p>Access Control</p>
                                            </a>
                                          </li> --}}
              <li class="nav-item @if($current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit') active @endif">
                <a href="{{route('departments.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Departments</p>
                </a>
              </li>

               <li class="nav-item @if($current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create') active @endif">
                <a href="{{route('comission_value.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Commission</p>
                </a>
              </li>

              <li class="nav-item @if($current_route=='settings.index') active @endif">
                <a href="{{ route('settings.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Prefix</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Order Settings</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Customer Settings</p>
                </a>
              </li>

               <li class="nav-item @if($current_route=='currency.index') active @endif">
                <a href="{{ route('currency.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Currencies</p>
                </a>
              </li>
               <li class="nav-item @if($current_route=='payment_method.index') active @endif">
                <a href="{{ route('payment_method.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>Payment Methods</p>
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