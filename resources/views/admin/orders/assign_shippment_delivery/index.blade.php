@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List Orders</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">List Orders</li>
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
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">List Orders</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Ordered Date</th>
                        <th>Delivered Date</th>
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
                            $class_bg="background:#ffedb9 !important";
                        }
                        else{
                          $class_bg="";
                        }
                        if ($order->order_status==17 || $order->order_status==11) {
                          $disabled_edit="pointer-events:none;opacity:0.5";
                        }
                       ?>
                        <tr style="{{ $class_bg }}">
                          <td><input type="checkbox" name="orders_ids" value="{{$order->id}}"></td>
                          <td>{{ date('m/d/Y',strtotime($order->created_at)) }}</td>
                          <td>
                            {{ isset($order->delivered_at)?date('m/d/Y',strtotime($order->delivered_at)):'-' }}
                          </td>
                          <td><a href="{{route('orders.show',$order->id)}}">{{ $order->order_no }}</a></td>
                          <td>{{ $order->customer->first_name }}</td>

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

                                <a href="{{route($edit_route,$order->id)}}"  style="{{ $disabled_edit }}">
                                  <li class="dropdown-item">
                                    <i class="far fa-edit"></i>&nbsp;&nbsp;Edit
                                  </li>
                                </a>
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

  <style type="text/css">
    .form-group{display:flex;}
    .disabled{pointer-events: none;opacity: 0.5;}
  </style>

  @push('custom-scripts')
  <script type="text/javascript">

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