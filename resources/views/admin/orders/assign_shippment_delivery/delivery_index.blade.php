@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ $data_title }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">{{ $data_title }}</li>
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
          @if ($type=="assign-shippment")
          <div class="col-md-12 action-controllers ">
            <div class="col-sm-6 text-left pull-left">
              <a class="btn btn-primary download-summary" href="javascript:void(0)">
              <i class="fas fa-list-alt"></i>&nbsp;Generate Summary
              </a>
            </div>
          </div>
          @endif
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <div class="pull-left">
                  <h3 class="card-title">List Orders</h3>
                </div>
                @if ($type=="assign-shippment")
                <div class="pull-right">
                  <img src="{{ asset('theme/images/low_stock_color_code.png') }}"> Low Stock Orders
                </div>
                @endif
              </div>
              <div class="card">
                <div class="card-body">
                  @if (Auth::check())
                    <div class="action_sec  order-page">
                        <?php $active_menu=explode('.',$show_route);

                        $new=$assign_delivery=$del_inpro=$completed=$cancelled="";
                        if ($active_menu[0]=="new-orders") {
                            $new="active";
                        }
                        elseif ($active_menu[0]=="assign-shippment") {
                          $assign_delivery="active";
                        }
                        elseif ($active_menu[0]=="assign-delivery") {
                          $del_inpro="active";
                        }
                        elseif ($active_menu[0]=="completed-orders") {
                          $completed="active";
                        }
                        elseif ($active_menu[0]=="cancelled-orders") {
                          $cancelled="active";
                        }

                         ?>
                        <ul class="list-unstyled">
                          <li>
                            <a href="{{ route('new-orders.index') }}" class="new">
                              <i class="fab fa-first-order"></i>&nbsp; New Orders
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('assign-shippment.index') }}" class="assigned-del">
                              <i class="fa fa-shipping-fast"></i>&nbsp; Assigned for Delivery
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('assign-delivery.index') }}" class="del-inprogress {{ $del_inpro }}">
                              <i class="fa fa-spinner "></i>&nbsp; Delivery In Progress
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('completed-orders.index') }}" class="order-completed">
                              <i class="fa fa-check "></i>&nbsp; Orders Completed
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('cancelled-orders.index') }}" class="missed {{ $cancelled }}">
                               <i class="fa fa-window-close"></i>&nbsp; Cancelled/Missed Orders 
                            </a>
                          </li>
                        </ul>
                    </div>
                  @endif
                  <div class="clearfix"></div>
                  <br>
                  <table id="data-table" class="table table-bordered">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Ordered Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Order Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                      <?php $order=$order['orders'] ?>
                      <?php 
                      $check_quantity=\App\Models\Orders::CheckQuantity($order->id);
                        $disabled_stock_notify=$disabled_edit="";
                        if(isset($order->deliveryPerson) ){
                          $disabled_stock_notify="pointer-events:none;opacity:0.5";
                        }
                        if (isset($check_quantity[0]) && $check_quantity[0]=="yes") {
                            $class_bg="background:#ffc1c1 !important";
                            $low_stock="yes";
                        }
                        else{
                          $class_bg="";
                          $low_stock="no";
                        }
                        if ($order->order_status==11) {
                          $disabled_edit="pointer-events:none;opacity:0.5";
                        }
                       ?>
                        <tr style="{{ $class_bg }}">
                          <td><input type="checkbox" class="orders_ids" value="{{$order->id}}"></td>
                          <td>{{ date('m/d/Y',strtotime($order->created_at)) }}</td>
                          <td><a href="{{route($show_route,$order->id)}}">{{ $order->order_no }}</a></td>
                          <td>{{ $order->customer->name }}</td>

                          <td>
                            <span class="badge" style="background: {{ $order->statusName->color_codes }};color: #fff">
                            {{  $order->statusName->status_name  }}
                            </span>
                          </td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="{{route($show_route,$order->id)}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('delivery_order','update'))
                                  @if ($low_stock!="yes")
                                  <a href="{{route($edit_route,$order->id)}}"  style="{{ $disabled_edit }}">
                                    <li class="dropdown-item">
                                      <i class="far fa-edit"></i>&nbsp;&nbsp;Edit
                                    </li>
                                  </a>
                                  @endif
                                @endif
                               <a href="{{ url('admin/cop_pdf/'.$order->id) }}"><li class="dropdown-item">
                                  <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;Download as PDF
                                 </li></a>
                                <a href="{{ url('admin/cop_print/'.$order->id) }}" target="_blank">
                                  <li class="dropdown-item" >
                                  <i class="fa fa-print"></i>&nbsp;&nbsp;Print
                                </li>
                                </a>
                                @if (isset($check_quantity[0]) && $check_quantity[0]=="yes") 
                                 <a href="{{ route('verify-stock.show',[$order->id]) }}">
                                  <li class="dropdown-item"  style="{{ $disabled_stock_notify }}">
                                  <i class="fa fa-check-circle"></i>&nbsp;&nbsp;Verify Stock
                                </li>
                                </a>
                                <a href="{{ route('verify-stock.index',['order_id'=>$order->id]) }}" target="_blank" style="{{$disabled_stock_notify}}">
                                  <li class="dropdown-item" >
                                  <i class="fa fa-user"></i>&nbsp;&nbsp;Notify Admin
                                </li>
                                </a>
                                @endif
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->address->latitude.','.$order->address->longitude }}&dir_action=navigate" target="_blank">
                                   <li class="dropdown-item">
                                     <i class="fa fa-map-marker"></i>&nbsp;&nbsp;View On Map
                                   </li>
                                 </a>
                                  </ul>
                                </div>
                              </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>
  </div>


  <style type="text/css">
    .form-group{display:flex;}
    .disabled{pointer-events: none;opacity: 0.5;}
  </style>

  @push('custom-scripts')
  <script type="text/javascript">

  var oTable = $('#data-table').dataTable();
  var allPages = oTable.fnGetNodes();
    $('body').on('click', '.select-all', function () {
        if ($(this).hasClass('allChecked')) {
            $('input[type="checkbox"]', allPages).prop('checked', false);
        } else {
            $('input[type="checkbox"]', allPages).prop('checked', true);
        }
        $(this).toggleClass('allChecked');
    });

    $('.download-summary').click(function(event) {
      var checkedNum = $('.orders_ids:checked',allPages).length;

      if (checkedNum==0) {
        alert('Please select order');
      }
      else{
        if (confirm('Are you sure want to download?')) {

          var order_ids = [];
          $('.orders_ids:checked',allPages).each(function () {
            order_ids.push($(this).val());
          });

            $.ajax({
              url: "{{ url('admin/order-summary/') }}",
              type: 'GET',
              data:{
                order_ids:order_ids
              }
            })
            .done(function(reponse) {
               window.open(reponse.url,'_blank');
            })
            .fail(function() {
              console.log("Ajax Error :-");
            });
        }
      }
    });

  </script>
  @endpush

@endsection