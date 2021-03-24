<?php 
$product=$category=$option=$brands=$purchase=$stock_vendor_allow=$stock_customer_allow=$return=$wastage=$rfq=$order=$customer=$vendor=$settings=$reports=$employee=$salary=$delivery_zone=$static_page=$pages=$slider="";

if(Auth::guard('employee')->user()->isAuthorized('product','read')){
  $product="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('category','read')){
  $category="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('option','read')){
  $option="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('brands','read')){
  $brands="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('stock_transist_vendor','read')){
  $stock_vendor_allow="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('purchase','read')){
  $purchase="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('stock_transist_customer','read')){
  $stock_customer_allow="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('return','read')){
  $return="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('wastage','read')){
  $wastage="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('rfq','read')){
  $rfq="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('new_order','read')){
  $new_order="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('assign_order','read')){
  $assign_order="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('delivery_order','read')){
  $delivery_order="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('cancelled_order','read')){
  $cancelled_order="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('completed_orders','read')){
  $completed_order="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('customer','read')){
  $customer="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('vendor','read')){
  $vendor="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('settings','read')){
  $settings="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('employee','read')){
  $employee="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('salary','read')){
  $salary="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('delivery_zone','read')){
  $delivery_zone="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('pages','read')){
  $pages="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('static','read')){
  $slider="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('general_settings','read')){
  $general_settings="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('access_control','read')){
  $access_control="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('department_setting','read')){
  $department_setting="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('commission_setting','read')){
  $commission_setting="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('prefix_setting','read')){
  $prefix_setting="yes";
}
if(Auth::guard('employee')->user()->isAuthorized('order_setting','read')){
  $order_setting="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('customer_setting','read')){
  $customer_setting="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('tax_setting','read')){
  $tax_setting="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('currency_setting','read')){
  $currency_setting="yes";
}

if(Auth::guard('employee')->user()->isAuthorized('payment_setting','read')){
  $payment_setting="yes";
}
?>
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
          @if ($product!="" || $category!="" || $option!="" || $brands!="")
          <!-- Products -->
          <li class="nav-item @if($current_route=='products.index'||$current_route=='products.create'||$current_route=='products.edit'||$current_route=='products.show'||$current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit'||$current_route=='product.import') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-list"></i> <p>Products <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='products.index'||$current_route=='products.create'||$current_route=='products.edit'||$current_route=='product.import') block @endif">
              @if ($product!="")
                  <li class="nav-item @if($current_route=='products.index'||$current_route=='products.edit'||$current_route=='products.show'||$current_route=='product.import'||$current_route=='products.create') active @endif">
                    <a href="{{route('products.index')}}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i> <p>List Products</p>
                    </a>
                  </li>
                @endif
                <li class="nav-item inner-menu @if($current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit') block menu-is-opening menu-open @endif">
                   @if ($category!="" || $option!="" || $brands!="")
                  <a href="javascript:void(0)" class="nav-link">
                    <i class="fas fa-angle-double-right"></i>
                    <p>
                      Product Settings
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                 
                    <ul class="nav nav-treeview" style="display:@if($current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit'||$current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit'||$current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit') block @endif">
                      @if ($category!="")
                      <li class="nav-item @if($current_route=='categories.index'||$current_route=='categories.create'||$current_route=='categories.edit') active @endif">
                        <a href="{{route('categories.index')}}" class="nav-link">
                          <i class="fas fa-angle-double-right"></i>
                          <p>Categories</p>
                        </a>
                      </li>
                      @endif
                      @if ($option!="")
                        <li class="nav-item @if($current_route=='options.index'||$current_route=='options.create'||$current_route=='options.edit') active @endif">
                          <a href="{{route('options.index')}}" class="nav-link">
                            <i class="fas fa-angle-double-right"></i>
                            <p>Options</p>
                          </a>
                        </li>
                      @endif
                      @if ($brands!="")
                        <li class="nav-item @if($current_route=='brands.index'||$current_route=='brands.create'||$current_route=='brands.edit') active @endif">
                          <a href="{{route('brands.index')}}" class="nav-link">
                            <i class="fas fa-angle-double-right"></i>
                            <p>Brands</p>
                          </a>
                        </li>
                    @endif
                  </ul>
              </li>
                @endif
            </ul>
          </li>
          @endif
          @if ($purchase!="")
          <!-- Purchase Menu -->
          <li class="nav-item @if($current_route=='purchase.index'||$current_route=='purchase.create'||$current_route=='purchase.edit'||$current_route=='purchase.show') active @endif">
            <a href="{{route('purchase.index')}}" class="nav-link">
              <i class="fas fa-shopping-cart"></i><p>Purchase</p>
            </a>
          </li>
          @endif

          @if ($stock_vendor_allow !="" || $stock_customer_allow || $return!="" || $wastage!="")
          <li class="nav-item @if($current_route=='return.index'||$current_route=='stock-in-transit.index'||$current_route=='return.create'||$current_route=='return.edit'||$current_route=='wastage.index'||$current_route=='wastage.create') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-boxes"></i> <p>Stock <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='return.index'||$current_route=='stock-in-transit.index'||$current_route=='return.create'||$current_route=='return.edit'||$current_route=='wastage.index'||$current_route=='wastage.create') block @endif">
              @if($stock_vendor_allow!="")
              <li class="nav-item @if($current_route=='stock-in-transit.index') active @endif">
                <a href="{{ route('stock-in-transit.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Stock-In-Transit (Vendor)</p>
                </a>
              </li>
              @endif
              @if ($stock_customer_allow!="")
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="fas fa-angle-double-right"></i> <p>Stock-In-Transit (Customer)</p>
                </a>
              </li>
              @endif
              @if($return!="")
                <li class="nav-item @if($current_route=='return.index'||$current_route=='return.edit') active @endif">
                  <a href="{{ route('return.index') }}" class="nav-link">
                    <i class="fas fa-angle-double-right"></i> <p>List Return</p>
                  </a>
                </li>
              @endif
              @if ($wastage!="")
                <li class="nav-item @if($current_route=='wastage.index'||$current_route=='wastage.create') active @endif">
                  <a href="{{ route('wastage.index') }}" class="nav-link">
                    <i class="fas fa-angle-double-right"></i> <p>Wastage/Write Off</p>
                  </a>
                </li>
              @endif
            </ul>
          </li>
          @endif
          <!-- Stock Menu -->
          @if ($rfq!="")
            <!-- RFQ Menu -->
            <li class="nav-item @if($current_route=='rfq.index'||$current_route=='rfq.create'||$current_route=='rfq.edit'||$current_route=='rfq.show') active @endif">
              <a href="{{route('rfq.index')}}" class="nav-link"><i class="fas fa-clipboard-list"></i> <p>RFQ</p></a>
            </li>
          @endif
          {{-- Ordesr Menu --}}
          @if (isset($new_order) || isset($assign_order) || isset($delivery_order) || isset($completed_order) || isset($cancelled_order))
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-dolly-flatbed"></i> <p>Orders <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
                @if (isset($new_order))
                  <li class="nav-item">
                    <a href="{{ route('new-orders.index') }}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i> <p>New Orders</p>
                    </a>
                  </li>
                @endif
                @if (isset($assign_order))
                  <li class="nav-item">
                    <a href="{{ route('assign-shippment.index') }}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i> <p>Assigned for Delivery</p>
                    </a>
                  </li>
                @endif
                @if (isset($delivery_order))
                  <li class="nav-item">
                    <a href="{{ route('assign-delivery.index') }}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i> <p>Delivery In Progress</p>
                    </a>
                  </li>
                @endif
                @if (isset($completed_order))
                  <li class="nav-item">
                    <a href="{{ route('completed-orders.index') }}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i> <p>Orders Completed</p>
                    </a>
                  </li>
                @endif
                @if (isset($cancelled_order))
                  <li class="nav-item">
                    <a href="{{ route('cancelled-orders.index') }}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i> <p>Cancelled/Missed Orders</p>
                    </a>
                  </li>
                @endif
            </ul>
          </li>
          @endif
          

          @if ($customer!="")
          <!-- Customer Menu -->
          <li class="nav-item @if($current_route=='customers.index'||$current_route=='customers.create'||$current_route=='customers.edit'||$current_route=='customers.show') active @endif">
            <a href="{{ route('customers.index') }}" class="nav-link"><i class="fas fa-users"></i><p>Customers</p></a>
          </li>
          @endif
          @if ($vendor!="")
          <!-- Vendor Menu -->
          <li class="nav-item @if($current_route=='vendor.index'||$current_route=='vendor.create'||$current_route=='vendor.edit'||$current_route=='vendor.show'||$current_route=='vendor-products.index') active @endif">
            <a href="{{route('vendor.index')}}" class="nav-link"><i class="fas fa-people-carry"></i><p>Vendor</p></a>
          </li>
          @endif

          @if ($employee!="" || $salary!="")
            <li class="nav-item @if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show'||$current_route=='salary.list'||$current_route=='salary.view'||$current_route=='pay.slip'||$current_route=='emp.commission.list') menu-is-opening menu-open @endif">
              <a href="javascript:void(0)" class="nav-link"><i class="fas fa-address-card"></i>
                <p>Employees<i class="fas fa-angle-left right"></i></p>
              </a>
              <ul class="nav nav-treeview" style="display:@if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show'||$current_route=='salary.list'||$current_route=='salary.view'||$current_route=='pay.slip'||$current_route=='emp.commission.list') block @endif">
                @if ($employee!="")
                  <li class="nav-item @if($current_route=='employees.index'||$current_route=='employees.create'||$current_route=='employees.edit'||$current_route=='employees.show') active @endif">
                    <a href="{{route('employees.index')}}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i><p>List Employees</p>
                    </a>
                  </li>
                @endif
                @if ($salary!="")
                  <li class="nav-item @if($current_route=='salary.list'||$current_route=='salary.view'||$current_route=='pay.slip'||$current_route=='emp.commission.list') active @endif">
                    <a href="{{route('salary.list',date('m-Y'))}}" class="nav-link">
                      <i class="fas fa-angle-double-right"></i><p>Salary</p>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif
          @if ($delivery_zone!="")
            <!-- Delivery Menu -->
            <li class="nav-item @if($current_route=='delivery_zone.index'||$current_route=='delivery_zone.create'||$current_route=='delivery_zone.edit') active @endif">
              <a href="{{ route('delivery_zone.index') }}" class="nav-link">
                <i class="fas fa-map-marker-alt"></i><p>Delivery Zone</p>
              </a>
            </li>
          @endif
          @if ($reports!="")
          <!-- Reports Menu -->
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link"><i class="fas fa-chart-bar"></i><p>Reports</p></a>
          </li>
          @endif
          <!-- Static Page Menu -->
          @if ($pages!="" || $slider!="")
          <li class="nav-item  @if($current_route=='static-page-slider.index'||$current_route=='static-page-slider.create'||$current_route=='static-page-slider.edit') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link">
              <i class="fas fa-laptop"></i><p>Static Page<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='static-page-slider.index'||$current_route=='static-page-slider.create'||$current_route=='static-page-slider.edit') block @endif">
              @if ($pages!="")
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link"><i class="fas fa-angle-double-right"></i>
                  <p>List Pages</p>
                </a>
              </li>
              @endif
              @if ($slider!="")
              <li class="nav-item @if($current_route=='static-page-slider.index'||$current_route=='static-page-slider.create'||$current_route=='static-page-slider.edit') active @endif">
                <a href="{{ route('static-page-slider.index') }}" class="nav-link"><i class="fas fa-angle-double-right"></i><p>Sliders</p></a>
              </li>
              @endif
            </ul>
          </li>
          @endif
          @if (isset($general_settings) || isset($department_setting) || isset($commission_setting) || isset($prefix_setting) || isset($order_setting) || isset($customer_setting) || isset($tax_setting) || isset($currency_setting) || isset($payment_setting))

          <li class="nav-item @if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show'||$current_route=='settings-prefix.index'||$current_route=='currency.index'||$current_route=='payment_method.index'||$current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit'||$current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create'||$current_route=='currency.create'||$current_route=='currency.edit'||$current_route=='payment_method.create'||$current_route=='payment_method.edit'||$current_route=='tax.index'||$current_route=='tax.create'||$current_route=='tax.edit') menu-is-opening menu-open @endif">
            <a href="javascript:void(0)" class="nav-link"><i class="fas fa-cog"></i>
              <p>Settings<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display:@if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show'||$current_route=='settings-prefix.index'||$current_route=='currency.index'||$current_route=='payment_method.index'||$current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit'||$current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create'||$current_route=='currency.create'||$current_route=='currency.edit'||$current_route=='payment_method.create'||$current_route=='payment_method.edit'||$current_route=='tax.index'||$current_route=='tax.create'||$current_route=='tax.edit') block @endif">
              @if (isset($general_settings))
                  <li class="nav-item">
                    <a href="" class="nav-link">
                      <i class="fas fa-angle-double-right"></i><p>General Settings</p>
                    </a>
                  </li>
              @endif
              @if (isset($access_control))
                <li class="nav-item @if($current_route=='access-control.index'||$current_route=='access-control.create'||$current_route=='access-control.edit'||$current_route=='access-control.show') active @endif">
                  <a href="{{route('access-control.index')}}" class="nav-link">
                    <i class="fas fa-angle-double-right"></i><p>Access Control</p>
                  </a>
                </li>
              @endif
              @if (isset($department_setting))
                <li class="nav-item @if($current_route=='departments.index'||$current_route=='departments.create'||$current_route=='departments.edit') active @endif">
                  <a href="{{route('departments.index')}}" class="nav-link">
                    <i class="fas fa-angle-double-right"></i><p>Departments</p>
                  </a>
                </li>
              @endif
              @if (isset($commission_setting))
               <li class="nav-item @if($current_route=='comission_value.index'||$current_route=='comission_value.edit'||$current_route=='comission_value.create') active @endif">
                <a href="{{route('comission_value.index')}}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Commission</p>
                </a>
              </li>
              @endif

              @if (isset($prefix_setting))
                <li class="nav-item @if($current_route=='settings-prefix.index') active @endif">
                  <a href="{{ route('settings-prefix.index') }}" class="nav-link">
                    <i class="fas fa-angle-double-right"></i><p>Prefix</p>
                  </a>
                </li>
              @endif
              @if (isset($order_setting))
                <li class="nav-item">
                  <a href="" class="nav-link">
                    <i class="fas fa-angle-double-right"></i><p>Order Settings</p>
                  </a>
                </li>
              @endif
              @if (isset($customer_setting))
               <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Customer Settings</p>
                </a>
              </li>
              @endif
              @if (isset($tax_setting))
                <li class="nav-item @if($current_route=='tax.index'||$current_route=='tax.create'||$current_route=='tax.edit') active @endif">
                  <a href="{{route('tax.index')}}" class="nav-link"><i class="fas fa-angle-double-right"></i><p>Tax</p></a>
                </li>
              @endif
              @if (isset($currency_setting))
               <li class="nav-item @if($current_route=='currency.index'||$current_route=='currency.create'||$current_route=='currency.edit') active @endif">
                <a href="{{ route('currency.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Currencies</p>
                </a>
              </li>
              @endif
              @if (isset($payment_setting))
              <li class="nav-item @if($current_route=='payment_method.index'||$current_route=='payment_method.create'||$current_route=='payment_method.edit') active @endif">
                <a href="{{ route('payment_method.index') }}" class="nav-link">
                  <i class="fas fa-angle-double-right"></i><p>Payment Methods</p>
                </a>
              </li>
              @endif

               
            </ul>
          </li>
          <!-- Settings Menu -->
          @endif
      
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>