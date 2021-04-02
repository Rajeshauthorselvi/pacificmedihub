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
                         ?>
          <div class="col-md-12 action-controllers ">
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('order','delete'))
            <div class="col-sm-8 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
              @if ($active_menu[0]!="completed-orders")
              <a href="javascript:void(0)" class="btn btn-default" id="assign-order" status-id="18">
                <i class="fa fa-tasks" aria-hidden="true"></i> Assign to Shipment
              </a>
              <a href="javascript:void(0)" class="btn btn-default" id="assign-order" status-id="20">
                <i class="fa fa-tasks" aria-hidden="true"></i> Assign to InProcess
              </a>
              <a href="javascript:void(0)" class="btn btn-default" id="assign-order" status-id="21">
                <i class="fa fa-tasks" aria-hidden="true"></i> Move to Rejected
              </a>
              @endif
            </div>
            @endif
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('order','create'))
            <div class="col-sm-4 text-right pull-right">
              <a class="btn add-new" href="{{route('orders.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
            @endif
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <div class="pull-left">
                  <h3 class="card-title">List Orders</h3>
                </div>
                @if ($type=="new-orders")
                  <div class="pull-right">
                    <img src="{{ asset('theme/images/low_stock_color_code.png') }}"> Low Stock Orders
                  </div>
                @endif
              </div>
              <div class="card">
                <div class="card-body ">
                  <div class="action_sec order-page">

                         
                            <ul class="list-unstyled">
                              <li>
                                <a href="{{ route('new-orders.index') }}" class="new {{ $new }}">
                                  <i class="fab fa-first-order"></i>&nbsp; New Orders
                                </a>
                              </li>
                              <li>
                                <a href="{{ route('assign-shippment.index') }}" class="assigned-del {{ $assign_delivery }}">
                                  <i class="fa fa-shipping-fast"></i>&nbsp; Assigned for Delivery
                                </a>
                              </li>
                              <li>
                                <a href="{{ route('assign-delivery.index') }}" class="del-inprogress {{ $del_inpro }}">
                                  <i class="fa fa-spinner "></i>&nbsp; Delivery In Progress
                                </a>
                              </li>
                              <li>
                                <a href="{{ route('completed-orders.index') }}" class="order-completed {{ $completed }}">
                                  <i class="fa fa-check "></i>&nbsp; Orders Completed
                                </a>
                              </li>
                              <li>
                                <a href="{{ route('cancelled-orders.index') }}" class="missed">
                                   <i class="fa fa-window-close"></i>&nbsp; Cancelled/Missed Orders 
                                </a>
                              </li>
                            </ul>
                  </div>
                  <div class="clearfix"></div>
                  <br>
                  <table id="example2" class="table table-bordered">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Ordered Date</th>
                        <th>Delivered Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Sales Rep</th>
                        <th>Order Status</th>
                        <th>Grand Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                      <?php $check_quantity=\App\Models\Orders::CheckQuantity($order->id);
                        if (isset($check_quantity[0]) && $check_quantity[0]=="yes") {
                            $class_bg="background:#ffc1c1 !important";
                            $low_stock='yes';
                        }
                        else{
                          $class_bg="";
                          $low_stock='no';
                        }
                       ?>                      
                        <tr style="{{ $class_bg }}">
                          <td>
                            <input type="hidden" class="low_stock_data_{{ $order->id }}" value="{{ $low_stock }}">
                            <input type="checkbox" name="orders_ids" value="{{$order->id}}">
                            <input type="hidden" class="order-no_{{ $order->id }}" value="{{ $order->order_no }}">

                          </td>
                          <td>{{ date('m/d/Y',strtotime($order->created_at)) }}</td>
                          <td>
                            {{ isset($order->delivered_at)?date('m/d/Y',strtotime($order->delivered_at)):'-' }}
                          </td>
                          <td><a href="{{route($show_route,$order->id)}}">{{ $order->order_no }}</a></td>
                          <td>{{ $order->customer->name }}</td>
                          <td>{{ $order->salesrep->emp_name }}</td>
                          <td>
                            <span class="badge" style="background: {{ $order->statusName->color_codes }};color: #fff">
                            {{  $order->statusName->status_name  }}
                            </span>
                          </td>
                          <td class="total_amount">{{$order->sgd_total_amount}}</td>
                          <?php 
                            $balance_amount=\App\Models\PaymentHistory::FindPendingBalance($order->id,$order->sgd_total_amount,2);
                          ?>
                          <td>{{ $balance_amount['paid_amount'] }}</td>
                          <td class="balance">
                            {{ number_format($balance_amount['balance_amount'],2,'.','') }}

                          </td>
                          <td>
                            <?php $color_code=[1=>'#00a65a',2=>'#5bc0de',3=>'#f0ad4e']?>
                            <?php $payment_status=[0=>'',1=>'Paid',2=>'Partly Paid',3=>'Not Paid']; ?>
                              <span class="badge" style="background:{{ $color_code[$order->payment_status] }};color: #fff ">
                                {{ $payment_status[$order->payment_status] }}
                              </span>
                          </td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="{{route($show_route,$order->id)}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('order_payment','read'))
                                <a href="javascript:void(0)" class="view-payment" order-id="{{$order->id}}">
                                  <li class="dropdown-item">
                                    <i class="fa fa-credit-card"></i>&nbsp;&nbsp;View Payments
                                  </li>
                                </a>
                                @endif
                                @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('order_payment','create'))
                                <a href="javascript:void(0)" class="add-payment" order-id="{{$order->id}}">
                                  <li class="dropdown-item">
                                    <i class="fa fa-credit-card"></i>&nbsp;&nbsp;Add Payment
                                  </li>
                                </a>
                                @endif
                              
                                @if ($edit_permission=="yes")
                                @if ($order->order_status!=13)
                                <a href="{{route($edit_route,[$order->id,'low_stock'=>$low_stock])}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                @endif
                                @endif

                                @if($low_stock=="yes")
                                  <a href="{{ url('admin/order-purchase/'.$order->id) }}">
                                    <li class="dropdown-item">
                                      <i class="far fa-star"></i>&nbsp;&nbsp;Create Purchase
                                    </li>
                                  </a>
                                @endif

                                <a href="{{ url('admin/cop_admin_pdf/'.$order->id) }}">
                                  <li class="dropdown-item">
                                    <i class="far fa-file-pdf"></i>&nbsp;&nbsp;Download Invoice
                                  </li>
                                </a>

                                <a href="javascript:void(0)"><li class="dropdown-item"><i class="fa fa-envelope"></i>&nbsp;&nbsp;Email</li></a>

                                <a href="{{ url('admin/cop_admin_print/'.$order->id) }}" target="_blank">
                                  <li class="dropdown-item">
                                    <i class="fas fa-print"></i>&nbsp;&nbsp;Print
                                  </li>
                                </a>
                                @if ($delete_permission=="yes")
                                <a href="javascript:void(0)"><li class="dropdown-item">
                                  <form method="POST" action="{{ route($delete_route,$order->id) }}">@csrf 
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                  </form>
                                </li></a>
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

  <div class="modal fade" id="payment_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Update Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{route('create.order.payment')}}" method="post" id="payment_form">
        @csrf
          <div class="modal-body">
            <div class="form-group">
              <div class="col-sm-6">
                <label>Payment Type *</label>
                {!! Form::select('payment_id',$payment_method, null,['class'=>'form-control no-search select2bs4','id'=>'payType']) !!}
                <span class="text-danger pay-type" style="display:none">Please select Payment Type</span>
              </div>
              <div class="col-sm-6">
                <label>Date *</label>
                <input type="text" name="created_at" class="form-control" readonly value="{{ date('Y-m-d H:i:s') }}">
              </div>
            </div>
            <div class="form-group">
              <div class=" col-sm-6">
                <label>Reference No</label>
                <input type="text" name="reference_no" class="form-control">
              </div>
              <div class="col-sm-6">
                <label>Amount *</label>
                <input type="text" name="amount" class="form-control balance_amount">
                <span class="text-danger amount" style="display:none">Please enter Amount</span>
              </div>
            </div>
            <input type="hidden" name="payment_from" value="2">
            <input type="hidden" name="id" class="model_purchase_id" value="0">
            <input type="hidden" name="total_payment" class="total-amount" value="">
            <div class="form-group">
              <div class="col-sm-12">
                <label>Notes</label>
                {!! Form::textarea('payment_notes',null,['class'=>'form-control summernote']) !!}
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary addpayment-submit">Add Payment</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="edit_payment_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Payment History</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @csrf
        <div class="modal-body">
          <table class="table table-bordered">
            <thead>
              <th>Date</th>
              <th>Reference No</th>
              <th>Amount</th>
              <th>Paid By</th>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addign-status" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Assign Order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        @csrf
        <div class="modal-body">
            <div class="col-sm-12">
              <div><h3>Selected Orders</h3></div>
              <div class="col-sm-6 order_ids"></div>
              <div class="col-sm-6">
                <div class="form-group">
                    
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <style type="text/css">
    .form-group{display:flex;}
    .disabled{pointer-events: none;opacity: 0.5;}
  </style>

  @push('custom-scripts')
  <script type="text/javascript">

    $(document).on('click', '#assign-order', function(event) {
        var checkedNum = $('input[name="orders_ids"]:checked').length;
        var status_id=$(this).attr('status-id');
        if (checkedNum==0) {
          alert('Please select order');
        }   
        else{
/*          $('#addign-status').modal('show');

          return false;*/
          var order_ids = [];
          $('.order_ids').html('');
          $('input[name="orders_ids"]:checked').each(function (index,val) {          
              
              var order_no=$(this).next('.order-no').val();
              var order_id=$(this).val();

              var check_low_stock=$('.low_stock_data_'+order_id).val();
              if (check_low_stock=="yes" && status_id=="18") {
                if ($('.order_ids').text()=="") {
                  $('.order_ids').append(order_no);
                }
                else{
                  $('.order_ids').append(','+order_no);
                }
                $(this).prop('checked',false);
              }
              else{
                 order_ids.push($(this).val());
              }
          });

          var low_stock_orders=$('.order_ids').text();
          if (low_stock_orders!="" && status_id=="18") {
            alert(low_stock_orders+' Orders are low stock. That orders will not update status');
          }

            $.ajax({
              url: "{{ url('admin/update-order-status') }}",
              type: 'POST',
              data: {
                '_token':"{{ csrf_token() }}",
                status_id: status_id,
                order_ids:order_ids
              },
            })
            .done(function() {
              location.reload();
            });
        }
    });

    $(function ($) {
      $('.no-search.select2bs4').select2({
        minimumResultsForSearch: -1
      });
    });

    $(document).on('click', '.addpayment-submit', function(event) {
      if(validate()!=false){
        $('#payment_form').submit();
      }else{
        return false;
      }
    });

    function validate(){
      var valid=true;
      if ($("#payType").val()=="") {
        $("#payType").closest('.form-group').find('span.text-danger.pay-type').show();
        valid = false;
      }
      if($('.balance_amount').val()=="")
      {
        $(".balance_amount").closest('.form-group').find('span.text-danger.amount').show();
        valid = false;
      }
      return valid;
    }

      $(document).on('click', '.add-payment', function(event) {
          event.preventDefault();
          var balance_amount=$(this).parents('tr').find('.balance').text();
          var balance_amount=parseInt(balance_amount);

          var total_amount=$(this).parents('tr').find('.total_amount').text();
          var total_amount=parseInt(total_amount);

          if (balance_amount==0) {
            alert('Payment status is already paid for the purchase.');
            return false;
          }
          $('.total-amount').val(total_amount);
          $('.balance_amount').val(balance_amount);
          var order_id=$(this).attr('order-id');
          $('.model_purchase_id').val(order_id);

          $('#payment_model').modal('show');
      });


      $(document).on('click', '.view-payment', function(event) {
          event.preventDefault();
          var order_id=$(this).attr('order-id');

          $.ajax({
            url: '{{ url('admin/view_order_payment') }}'+'/'+order_id,
          })
          .done(function(response) {
              $('.modal-body tbody tr').remove();
              if (response.length>0) {
                $.each(response, function(index, val) {
                    var html ="<tr>";
                        html +="<td>"+moment(val.created_at).format('DD-MM-yyyy HH:mm')+"</td>";
                        if (val.reference_no==null) {
                          html +="<td>-</td>";
                        }
                        else{
                          html +="<td>"+  val.reference_no+"</td>";
                        }
                        html +="<td>"+val.amount+"</td>";
                        html +="<td>"+val.payment_method.payment_method+"</td>";
                        html +="<tr>";

                        $('.modal-body tbody').append(html);
                });
              }
              else{
                var html ="<tr>";
                    html +="<td colspan='5' class='text-center'>No record found</td>";
                    html +="<tr>";
                    $('.modal-body tbody').append(html);
              }
          });
            $('#edit_payment_model').modal('show');
      });

    



    $('.select-all').change(function() {
      if ($(this). prop("checked") == true) {
        $('input:checkbox').prop('checked',true);
      }
      else{
        $('input:checkbox').prop('checked',false);
      }
    });

    $('.delete-all').click(function(event) {
      var checkedNum = $('input[name="orders_ids"]:checked').length;
      if (checkedNum==0) {
        alert('Please select order');
      }
      else{
        if (confirm('Are you sure want to delete?')) {
          $('input[name="orders_ids"]:checked').each(function () {
            var current_val=$(this).val();
            $.ajax({
              url: "{{ url('admin/orders/') }}/"+current_val,
              type: 'DELETE',
              data:{
                "_token": $("meta[name='csrf-token']").attr("content")
              }
            })
            .done(function() {
              location.reload(); 
            })
            .fail(function() {
              console.log("Ajax Error :-");
            });
          });
        }
      }
    });


  </script>
  @endpush

@endsection