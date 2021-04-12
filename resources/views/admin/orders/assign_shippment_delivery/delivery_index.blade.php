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
          <div class="col-md-12 action-controllers ">
              <a class="btn btn-default load-delivery assign-shippment" href="javascript:void(0)"  status-id="14">
                <i class="fas fa-list-alt"></i>&nbsp; Load For Delivery
              </a>
              <a class="btn btn-default delivered assign-shippment" href="javascript:void(0)"  status-id="13">
                <i class="fas fa-list-alt"></i>&nbsp; Delivered
              </a>
              <a class="btn btn-default missed-delivered assign-shippment" href="javascript:void(0)"  status-id="17">
                <i class="fas fa-list-alt"></i>&nbsp; Missed Delivery
              </a>
              <div class="spinner-border text-primary hidden" role="status">
                <span class="sr-only">Loading...</span>
              </div>
          </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <div class="pull-left col-sm-6">
                  <h3 class="card-title">List Orders</h3>
                </div>
                @if ($type=="assign-shippment")
                <div class="pull-right col-sm-6">
                  <img src="{{ asset('theme/images/low_stock_color_code.png') }}"> Low Stock Orders
                </div>
                @else
                <div class="pull-right col-sm-6">
                  <div class="col-sm-12 text-right">
                      <label>Filter By Date: </label>
                    
                      <input type="text" placeholder="Select Date" class="form-control date-picker" value="{{ date('d-m-Y') }}">
                    
                  </div>
                </div>
                @endif
              </div>
              <div class="card">
                <div class="card-body">
               {{--    @if (Auth::check())
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
                  <br> --}}
                  <div class="ajax_data_append">
                  <table id="data-table" class="table table-bordered">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Region</th>
                        <th>Postcode</th>
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

   
                        $check_if_load=\App\Models\OrderHistory::CheckIfLoad($order->id);
                           if ($check_if_load) {
                                  $check_type="loaded";
                              }
                              else{
                                  $check_type="notloaded"; 
                              }
                             ?>
                        <tr style="{{ $class_bg }}">
                          <td>
                            <input type="checkbox"  name="orders_ids" class="orders_ids" value="{{$order->id}}">
                            <input type="hidden" class="order-loaded_{{ $order->id }}" value="{{ $check_type }}"> 
                          </td>
                          <?php $region=\App\Models\Orders::GetRegion($order->address->post_code); ?>
                          <td>{{ isset($region)?$region:'-' }}</td>
                          <td>{{ $order->address->post_code }}</td>
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
                               <a href="{{ url('admin/cop_pdf/'.$order->id) }}">
                                 <li class="dropdown-item">
                                  <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;Download as PDF
                                 </li>
                               </a>
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
      </div>

    </section>
  </div>

  <div class="load-error-data"></div>
  <style type="text/css">
    .form-group{display:flex;}
    .disabled{pointer-events: none;opacity: 0.5;}
    #ui-datepicker-div{z-index: 999 !important}
    .date-picker{display: inline-block;width: 50%}
  </style>

  @push('custom-scripts')
  <script type="text/javascript">
    $(document).on('click', '.assign-shippment', function(event) {
      event.preventDefault();
        var checkedNum = $('input[name="orders_ids"]:checked').length;

        var status_id=$(this).attr('status-id');
        if (checkedNum==0) {
          alert('Please select order');
        }
        else{

            var current_status=$(this).val();
            var order_ids = [];
            var order = "";
            $('input[name="orders_ids"]:checked').each(function (index,val) {
              var order_id=$(this).val();
              var load_type=$('.order-loaded_'+order_id).val();
              if (status_id==14) {
                 var order=LoadForDelivery(load_type,order_id);
                 if (order!=undefined) {
                   order_ids.push(order);
                 }
               }
               else if(status_id==13){
                 var order=Delivered(load_type,order_id);
                 if (order!=undefined) {
                   order_ids.push(order);
                 }
               }
              else if(status_id==17){
                var order=MissedDelivery(load_type,order_id);
                if (order!=undefined) {
                    order_ids.push(order);
                }
              }

            });
              
                if (order_ids.length>0){
                  if (status_id==13) {
                    var msg="Are you sure want to delivered?"
                  }
                  else if (status_id==14) {
                    var msg='Are you sure want to Load?';
                  }
                  else{
                    var msg='Are you sure change status?';
                  }
                  
                  if (!confirm(msg)) {
                    return false;
                  } 

                    $.ajax({
                    url: "{{ url('admin/update-order-status') }}",
                    type: 'POST',
                    data: {
                      '_token':"{{ csrf_token() }}",
                      status_id: status_id,
                      order_ids:order_ids
                    },
                    beforeSend: function (){
                        $('.spinner-border').removeClass('hidden');
                    }
                  })
                  .done(function() {
                    $('.spinner-border').addClass('hidden');
                      location.reload();
                  });
                }
            console.log(order_ids);

        }  
    });
    function Delivered(load_type,order_no) {
        if (load_type=="loaded") {
            return order_no;
        }
        else{
          $('input[value="'+order_no+'"]').prop('checked', false);
          if ($('.load-error-data').text()=="") {
            $('.load-error-data').append(order_no);
          }
          else{
            $('.load-error-data').append(','+order_no);
          }
        }
    }

    function LoadForDelivery(load_type,order_no) {
               if (load_type=="loaded") {
                    $('input[value="'+order_no+'"]').prop('checked', false);
                    if ($('.load-error-data').text()=="") {
                      $('.load-error-data').append(order_no);
                    }
                    else{
                      $('.load-error-data').append(','+order_no);
                    }
                }
                else{
                  return order_no;
                }

    }
    function MissedDelivery(load_type,order_no) {
        if (load_type=="loaded") {
            return order_no;
        }
        else{
          $('input[value="'+order_no+'"]').prop('checked', false);
          if ($('.load-error-data').text()=="") {
            $('.load-error-data').append(order_no);
          }
          else{
            $('.load-error-data').append(','+order_no);
          }
        }
    }
var $datepicker = $('.date-picker').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function(dateText) {
          $.ajax({
            url: "{{ url('admin/assign-delivery') }}?date="+this.value,
          })
          .done(function(reponse) {
            $('.ajax_data_append').html(reponse);
            $('#data-table').dataTable();
          });
    }
});

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


  </script>
  @endpush

@endsection