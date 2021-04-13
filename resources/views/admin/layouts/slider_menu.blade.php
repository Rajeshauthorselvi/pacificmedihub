  <span style="display: none;">{{$current_route=request()->route()->getName()}}</span>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary">
    <!-- Brand Logo -->
    <a href="{{route('admin.dashboard')}}" class="brand-link">
      <img src="{{ asset('theme/images/logo_mtcu.png') }}" alt="Logo" class="brand-image">
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
          <li class="nav-item @if($current_route=='products.index'||$current_route=='products.create'||$current_route=='products.edit'||$current_route=='products.show'||$current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit'||$current_route=='product.import') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-list"></i> <p>Products <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='products.index'||$current_route=='products.create'||$current_route=='products.edit'||$current_route=='product.import') block @endif">
              <li class="nav-item @if($current_route=='products.index'||$current_route=='products.edit'||$current_route=='products.show'||$current_route=='product.import'||$current_route=='products.create') active @endif">
                <a href="{{route('products.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>List Products</p>
                </a>
              </li>
              
              <li class="nav-item inner-menu @if($current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit') block menu-is-opening menu-open @endif">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="fas fa-angle-double-right"></i>
                  <p>
                    Product Settings
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>

                <ul class="nav nav-treeview" style="display:@if($current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit') block @endif">

                  <li class="nav-item @if($current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit') active @endif">
                    <a href="{{route('categories.index')}}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i>
                      <p>Categories</p>
                    </a>
                  </li>
                  <li class="nav-item @if($current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit') active @endif">
                    <a href="{{route('options.index')}}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i>
                      <p>Options</p>
                    </a>
                  </li>
                  <li class="nav-item @if($current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit') active @endif">
                    <a href="{{route('brands.index')}}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i>
                      <p>Brands</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>

          <!-- Purchase Menu -->
          <li class="nav-item @if($current_route=='purchase.index'||$current_route=='purchase.create'||$current_route=='purchase.edit'||$current_route=='purchase.show') active @endif">
            <a href="{{route('purchase.index')}}" class="nav-link">
              <i class="fas fa-shopping-cart"></i><p>Purchase</p>
            </a>
          </li>

          <!-- Stock Menu -->
          <li class="nav-item @if($current_route=='return.index'||$current_route=='stock-in-transit.index'||$current_route=='return.create'||$current_route=='return.edit'||$current_route=='wastage.index'||$current_route=='wastage.create'||$current_route=='stock-in-transit.edit'||$current_route=='low-stocks.index') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-boxes"></i> <p>Stock <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='return.index'||$current_route=='stock-in-transit.index'||$current_route=='return.create'||$current_route=='return.edit'||$current_route=='wastage.index'||$current_route=='wastage.create'||$current_route=='stock-in-transit.edit'||$current_route=='low-stocks.index') block @endif">

              <li class="nav-item @if($current_route=='stock-in-transit.index'||$current_route=='stock-in-transit.edit') active @endif">
                <a href="{{ route('stock-in-transit.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Stock-In-Transit (Vendor)</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Stock-In-Transit (Customer)</p>
                </a>
              </li>
              
              <li class="nav-item @if($current_route=='return.index'||$current_route=='return.edit') active @endif">
                <a href="{{ route('return.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>List Return</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='wastage.index'||$current_route=='wastage.create') active @endif">
                <a href="{{ route('wastage.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Wastage/Write Off</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='low-stocks.index') active @endif">
                <a href="{{ route('low-stocks.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Low Stock Alert</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- RFQ Menu -->
          <li class="nav-item @if($current_route=='rfq.index'||$current_route=='rfq.create'||$current_route=='rfq.edit'||$current_route=='rfq.show') active @endif">
            <a href="{{route('rfq.index')}}" class="nav-link"><i class="fas fa-clipboard-list"></i> <p>RFQ</p></a>
          </li>

          <!-- Orders Menu -->
          <li class="nav-item @if($current_route=='new-orders.index'||$current_route=='orders.create'||$current_route=='new-orders.show'||$current_route=='new-orders.edit'||$current_route=='assign-shippment.index'||$current_route=='assign-delivery.index'||$current_route=='completed-orders.index'||$current_route=='cancelled-orders.index') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-dolly-flatbed"></i> <p>Orders <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='new-orders.index'||$current_route=='orders.create'||$current_route=='new-orders.show'||$current_route=='new-orders.edit'||$current_route=='assign-shippment.index'||$current_route=='assign-delivery.index'||$current_route=='completed-orders.index'||$current_route=='cancelled-orders.index') block @endif">
              <li class="nav-item @if($current_route=='new-orders.index'||$current_route=='orders.create'||$current_route=='new-orders.show'||$current_route=='new-orders.edit') active @endif">
                <a href="{{ route('new-orders.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>New Orders</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='assign-shippment.index') active @endif">
                <a href="{{ route('assign-shippment.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Assigned for Delivery</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='assign-delivery.index') active @endif">
                <a href="{{ route('assign-delivery.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Delivery In Progress</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='completed-orders.index') active @endif">
                <a href="{{ route('completed-orders.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Orders Completed</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='cancelled-orders.index') active @endif">
                <a href="{{ route('cancelled-orders.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Cancelled/Missed Orders</p>
                </a>
              </li>
{{--               <li class="nav-item">
                <a href="{{ route('orders.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>List Orders</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('delivery-assign.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Delivery Assign</p>
                </a>
              </li> --}}
            </ul>
          </li>
          
          <!-- Customer Menu -->
          <li class="nav-item @if($current_route=='customers.index'||$current_route=='customers.create'||$current_route=='customers.edit'||$current_route=='customers.show'||$current_route=='reject.customer'||$current_route=='new.customer') active @endif">
            <a href="{{ route('customers.index') }}" class="nav-link"><i class="fas fa-users"></i><p>Customers</p></a>
          </li>
          
          <!-- Vendor Menu -->
          <li class="nav-item @if($current_route=='vendor.index'||$current_route=='vendor.create'||$current_route=='vendor.edit'||$current_route=='vendor.show'||$current_route=='vendor-products.index') active @endif">
            <a href="{{route('vendor.index')}}" class="nav-link"><i class="fas fa-people-carry"></i><p>Vendor</p></a>
          </li>

          <!-- Employee Menu -->
          <li class="nav-item @if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show'||$current_route=='salary.list'||$current_route=='salary.view'||$current_route=='pay.slip'||$current_route=='emp.commission.list') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link"><i class="fas fa-address-card"></i>
              <p>Employees<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show'||$current_route=='salary.list'||$current_route=='salary.view'||$current_route=='pay.slip'||$current_route=='emp.commission.list') block @endif">
              <li class="nav-item @if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show') active @endif">
                <a href="{{route('employees.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>List Employees</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='salary.list'||$current_route=='salary.view'||$current_route=='pay.slip'||$current_route=='emp.commission.list') active @endif">
                <a href="{{route('salary.list',date('m-Y'))}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Salary</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Delivery Menu -->
          <li class="nav-item @if($current_route=='delivery_zone.index'||$current_route=='delivery_zone.create'||$current_route=='delivery_zone.edit') active @endif">
            <a href="{{ route('delivery_zone.index') }}" class="nav-link">
              <i class="fas fa-map-marker-alt"></i><p>Delivery Zone</p>
            </a>
          </li>

          <!-- Reports Menu -->
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link"><i class="fas fa-chart-bar"></i><p>Reports</p></a>
          </li>
          
          <!-- Static Page Menu -->
          <li class="nav-item  @if($current_route=='static-page.index'||$current_route=='static-page.create'||$current_route=='static-page.edit'||$current_route=='static-page-slider.index'||$current_route=='static-page-slider.create'||$current_route=='static-page-slider.edit'||$current_route=='static-page-features.index'||$current_route=='static-page-features.create'||$current_route=='static-page-features.edit') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-laptop"></i><p>Static Page<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='static-page.index'||$current_route=='static-page.create'||$current_route=='static-page.edit'||$current_route=='static-page-slider.index'||$current_route=='static-page-slider.create'||$current_route=='static-page-slider.edit'||$current_route=='static-page-features.index'||$current_route=='static-page-features.create'||$current_route=='static-page-features.edit') block @endif">
              <li class="nav-item @if($current_route=='static-page.index'||$current_route=='static-page.create'||$current_route=='static-page.edit') active @endif">
                <a href="{{ route('static-page.index') }}" class="nav-link"><i class="fas fa-angle-double-right"></i>
                  <p>Pages</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='static-page-slider.index'||$current_route=='static-page-slider.create'||$current_route=='static-page-slider.edit') active @endif">
                <a href="{{ route('static-page-slider.index') }}" class="nav-link"><i class="fas fa-angle-double-right"></i><p>Sliders</p></a>
              </li>
              <li class="nav-item @if($current_route=='static-page-features.index'||$current_route=='static-page-features.create'||$current_route=='static-page-features.edit') active @endif">
                <a href="{{ route('static-page-features.index') }}" class="nav-link"><i class="fas fa-angle-double-right"></i><p>Features</p></a>
              </li>
            </ul>
          </li>

          <!-- Settings Menu -->
          <li class="nav-item @if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show'||$current_route=='settings-prefix.index'||$current_route=='currency.index'||$current_route=='payment_method.index'||$current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit'||$current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create'||$current_route=='currency.create'||$current_route=='currency.edit'||$current_route=='payment_method.create'||$current_route=='payment_method.edit'||$current_route=='tax.index'||$current_route=='tax.create'||$current_route=='tax.edit'||$current_route=='delivery-methods.index'||$current_route=='delivery-methods.edit') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link"><i class="fas fa-cog"></i>
              <p>Settings<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show'||$current_route=='settings-prefix.index'||$current_route=='currency.index'||$current_route=='payment_method.index'||$current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit'||$current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create'||$current_route=='currency.create'||$current_route=='currency.edit'||$current_route=='payment_method.create'||$current_route=='payment_method.edit'||$current_route=='tax.index'||$current_route=='tax.create'||$current_route=='tax.edit'||$current_route=='delivery-methods.index'||$current_route=='delivery-methods.edit') block @endif">
              {{-- <li class="nav-item">
                <a href="" class="nav-link"><i class="fas fa-angle-double-right"></i><p>General Settings</p></a>
              </li> --}}
              <li class="nav-item @if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show') active @endif">
                <a href="{{route('access-control.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Access Control</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit') active @endif">
                <a href="{{route('departments.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Departments</p>
                </a>
              </li>

               <li class="nav-item @if($current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create') active @endif">
                <a href="{{route('comission_value.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Commission</p>
                </a>
              </li>

               <li class="nav-item @if($current_route=='delivery-methods.index'||$current_route=='delivery-methods.edit'||$current_route=='delivery-methods.create') active @endif">
                <a href="{{route('delivery-methods.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Delivery Methods</p>
                </a>
              </li>

              <li class="nav-item @if($current_route=='settings-prefix.index') active @endif">
                <a href="{{ route('settings-prefix.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Prefix</p>
                </a>
              </li>
              {{-- <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Order Settings</p>
                </a>
              </li> --}}
               {{-- <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Customer Settings</p>
                </a>
              </li> --}}
              
              <li class="nav-item @if($current_route=='tax.index'||$current_route=='tax.create'||$current_route=='tax.edit') active @endif">
                <a href="{{route('tax.index')}}" class="nav-link"><i class="fas fa-angle-double-right"></i><p>Tax</p></a>
              </li>

               <li class="nav-item @if($current_route=='currency.index'||$current_route=='currency.create'||$current_route=='currency.edit') active @endif">
                <a href="{{ route('currency.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Currencies</p>
                </a>
              </li>
              <li class="nav-item @if($current_route=='payment_method.index'||$current_route=='payment_method.create'||$current_route=='payment_method.edit') active @endif">
                <a href="{{ route('payment_method.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Payment Methods</p>
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