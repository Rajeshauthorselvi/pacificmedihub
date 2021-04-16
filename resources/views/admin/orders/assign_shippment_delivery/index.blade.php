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
              <a class="btn btn-primary download-summary" href="javascript:void(0)">
                <i class="fas fa-list-alt"></i>&nbsp;Generate Summary
              </a>
            {{--   <a class="btn btn-default assign-shippment" href="javascript:void(0)" status-id="15">
                <i class="fas fa-list-alt"></i>&nbsp; Assign to Delivery Person
              </a> --}}
              <a class="btn btn-default missed-delivered assign-shippment" href="javascript:void(0)"  status-id="notify_admin">
                <i class="fas fa-list-alt"></i>&nbsp; Notify Admin
              </a>
              <div class="spinner-border text-primary hidden" role="status">
                <span class="sr-only">Loading...</span>
              </div>
          </div>
          @endif
          <div class="assign-delivery hidden col-md-12">
            <div class="col-sm-4 pull-left">
                <label>Delivery Person</label>
              <div class="clearfix"></div>
              <div class="form-group">
                {!! Form::select('drivery_id',$all_drivers,null,['class'=>'form-control']) !!}
              </div>
            </div>
            <div class="col-sm-4 pull-left">
                <label>Delivery Date</label>
                <br>
              <div class="form-group">
                <input type="text" class="form-control date-picker" value="{{ date('d-m-Y') }}" />
              </div>
            </div>
            <div class="col-sm-4 pull-left driver-btn">
                <button type="button" class="btn btn-primary assign-delivery-btn">Assign Driver</button>
             </div>
          </div>
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
{{--                   @if (Auth::check())
                    <div class="action_sec order-page">
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
                            <a href="{{ route('assign-shippment.index') }}" class="assigned-del {{ $assign_delivery }}">
                              <i class="fa fa-shipping-fast"></i>&nbsp; Assigned for Delivery
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('assign-delivery.index') }}" class="del-inprogress">
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
                  <table id="data-table" class="table table-bordered">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Region</th>
                        <th>Post Code</th>
                        <th>Ordered Date</th>
                        <th>Delivery Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Order Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
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
                          <td class="text-center">
                            <?php 

                              if ($order->quantity_deducted!="") {
                                  $check_type="loaded";
                              }
                              else{
                                  $check_type="notloaded"; 
                              }

                              if ($order->delivery_person_id!=0) {
                                  $del_person="assigned";
                              }
                              else{
                                $del_person="notassigned";
                              }

                             ?>
                            <input type="hidden" class="del-person_{{ $order->id }}" value="{{ $del_person }}"> 
                            <input type="hidden" class="order-loaded_{{ $order->id }}" value="{{ $check_type }}"> 
                            <input type="checkbox" name="orders_ids" value="{{$order->id}}" order-id="{{ $order->id }}">
                            <input type="hidden" class="order-no_{{ $order->id }}" value="{{ $order->order_no }}">
                            <input type="hidden" class="low_stock_{{ $order->id }}" value="{{ $low_stock }}">
                          </td>
                          <?php $region=\App\Models\Orders::GetRegion($order->address->post_code); ?>
                          <td>{{ isset($region)?$region:'-' }}</td>
                          <td>{{ $order->address->post_code }}</td>
                          <td>{{ date('m/d/Y',strtotime($order->created_at)) }}</td>
                          <td>
                            {{ isset($order->approximate_delivery_date)?date('m/d/Y',strtotime($order->approximate_delivery_date)):'-' }}
                          </td>
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
                                @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('order','update'))
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
                                <a href="{{ route('verify-stock.index',['order_id'=>$order->id]) }}" style="{{$disabled_stock_notify}}">
                                  <li class="dropdown-item" >
                                  <i class="fa fa-user"></i>&nbsp;&nbsp;Notify Admin
                                </li>
                                </a>
                                @endif
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
  .driver-btn {
    padding-top: 32px;
  }
</style>
  <span class="load-error-data"></span>
  {!! Form::hidden('order_ids_hidden',null,['class'=>'order_ids_hidden']) !!}
  <style type="text/css">
    .form-group{display:flex;}
    .disabled{pointer-events: none;opacity: 0.5;}
  </style>

  @push('custom-scripts')
  <script type="text/javascript">
    $('.date-picker').datepicker({ minDate: 0,dateFormat: 'dd-mm-yy'});

    $(document).on('click', '.assign-shippment', function(event) {
      event.preventDefault();
        var checkedNum = $('input[name="orders_ids"]:checked').length;

        var status_id=$(this).attr('status-id');
        if (checkedNum==0) {
          alert('Please select order');
        }    
        else{

          var status_id=$(this).attr('status-id');
          var load_error_data="";
          $('.load-error-data').html('');
          var order_ids = [];
          $('input[name="orders_ids"]:checked').each(function (index,val) {
            var order_id=$(this).val()
            /*Load For Delivery*/
            var load_type=$('.order-loaded_'+order_id).val();
            var order_no=$('.order-no_'+order_id).val();
            var del_type=$('.del-person_'+order_id).val();
            var low_stock=$('.low_stock_'+order_id).val();

            if (status_id=="notify_admin") {
              if (low_stock=="yes") {
                order_ids.push(order_id);
              }
              else{
                $(this).prop('checked',false);
              }
            }

            if (status_id==14) {

              var order_id=LoadForDelivery(load_type,order_id);
              if (order_id!="") {
                  order_ids.push(order_id);
              }
            }
            else if (status_id==15) {
              var order_id=AssignDeliveryPerson(load_type,del_type,order_id);
              if (order_id!="") {
                order_ids.push(order_id);
              }
            }
            else if(status_id==13){
              var order_id=Delivered(del_type,order_id);
              if (order_id!="") {
                  order_ids.push(order_id);
              }
            }
            else if(status_id==17){
              var order_id=MissedDelivery(del_type,order_id);
              if (order_id!="") {
                  order_ids.push(order_id);
              }
            }
          });

            if (order_ids.length>0 && status_id!=15) {
              if (status_id=="notify_admin") {
                  var msg='Are you sure want to notify?'
              }
              else{
                  var msg="Are you sure want to change status?";
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
            else{
              $('.order_ids_hidden').val(order_ids);
              if (order_ids.length>0) {
                $('.assign-delivery').removeClass('hidden');
              }
            }
        }     
    });


    $(document).on('click', '.assign-delivery-btn', function(event) {

      var driver=$('[name="drivery_id"] option:selected').val();
      var delivery_date=$('.date-picker').val();
      if (driver=="") {
        alert('Please select driver');
        return false;
      }
      var order_ids = [];
        $('input[name="orders_ids"]:checked').each(function (index,val) {
             order_ids.push($(this).val());
        });

          if (!confirm('Are you sure want to assign delivery?')) {
              return false;
          }
              $.ajax({
                url: "{{ url('admin/update-order-status') }}",
                type: 'POST',
                data: {
                  '_token':"{{ csrf_token() }}",
                  status_id: 15,
                  order_ids:order_ids,
                  drivery_id:driver,
                  delivery_date:delivery_date
                }
              })
              .done(function() {
                  location.reload();
              });

    });

    function MissedDelivery(del_type,order_no) {

        if (del_type!="notassigned") {
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
          return "";
        }
    }

    function Delivered(del_type,order_no) {
        if (del_type=="assigned") {
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
          return "";
        }
    }

    function AssignDeliveryPerson(load_type,del_type,order_no) {
        if (del_type=="notassigned" && load_type=="loaded") {
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
          return "";
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
                    return "";
                }
                else{
                  return order_no;
                }

    }

  var oTable = $('#data-table').dataTable({"ordering": false});
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
      var checkedNum = $('input[name="orders_ids"]:checked',allPages).length;

      if (checkedNum==0) {
        alert('Please select order');
      }
      else{
        if (confirm('Are you sure want to download?')) {

          var order_ids = [];
          $('input[name="orders_ids"]:checked',allPages).each(function () {
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