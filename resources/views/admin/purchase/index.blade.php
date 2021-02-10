@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List Purchase</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">List Purchase</li>
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
          <div class="col-md-12 action-controllers ">
            <div class="col-sm-6 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
            </div>
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('purchase.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Purchase</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Purchase Date</th>
                        <th>PO No</th>
                        <th>Vendor</th>
                        <th>Quantity</th>
                        <th>Grand Total</th>
                        <th>Paid</th>
                        <th>Balance</th>
                        <th>Purchase Status</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td><input type="checkbox" name="currency_id" value="{{ $order['purchase_id'] }}"></td>
                          <td>{{ date('d-m-Y',strtotime($order['purchase_date'])) }}</td>
                          <td>{{ $order['po_number'] }}</td>
                          <td>{{ $order['vendor'] }}</td>
                          <td>{{ $order['quantity'] }}</td>
                          <td class="total_amount">{{ $order['grand_total'] }}</td>
                          <?php 
                              $balance_amount=\App\Models\PaymentHistory::FindPendingBalance($order['purchase_id'],$order['grand_total']);
                            ?>
                          <td>{{ $balance_amount['paid_amount'] }}</td>
                          <td class="balance">
                            {{ $balance_amount['balance_amount'] }}
                          </td>
                          <td>{{ $order['order_status'] }}</td>
                          <td>{{ $order['payment_status'] }}</td>
                          <td>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu">
                                    <a href="{{route('purchase.show',$order['purchase_id'])}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>

                                    <a href="javascript:void(0)" class="view-payment" purchase-id="{{ $order['purchase_id'] }}">
                                      <li class="dropdown-item">
                                        <i class="fa fa-credit-card"></i>&nbsp;&nbsp;View Payments
                                      </li>
                                    </a>

                                    <a href="javascript:void(0)" class="add-payment" purchase-id="{{ $order['purchase_id'] }}">
                                      <li class="dropdown-item">
                                        <i class="fa fa-credit-card"></i>&nbsp;&nbsp;Add Payment
                                      </li>
                                    </a>

                                    <a href="{{route('purchase.edit',$order['purchase_id'])}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                    <a href="javascript:void(0)"><li class="dropdown-item">
                                      <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;Download as PDF
                                    </li></a>
                                    <a href="{{route('purchase.edit',$order['purchase_id'])}}"><li class="dropdown-item">
                                      <i class="fa fa-envelope"></i>&nbsp;&nbsp;Email
                                    </li></a>
                                    <a href="javascript:void(0)"><li class="dropdown-item">
                                      <i class="fa fa-print"></i>&nbsp;&nbsp;Print
                                    </li></a>
                                    <a href="#"><li class="dropdown-item">
                                      <form method="POST" action="{{ route('purchase.destroy',$order['purchase_id']) }}">@csrf 
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                      </form>
                                    </li></a>
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
        <form action="{{route('create.purchase.payment')}}" method="post" enctype="multipart/form-data" id="payment_form">
        @csrf
          <div class="modal-body">
            <div class="col-sm-12">
              <div class="form-group col-sm-6">
                <label>Payment Type *</label>
                {!! Form::select('payment_id',$payment_method, null,['class'=>'form-control required']) !!}
                <span class="text-danger"></span>
              </div>
              <div class="form-group col-sm-6">
                <label>Date *</label>
                <input type="text" name="created_at" class="form-control" readonly value="{{ date('Y-m-d H:i:s') }}">
              </div>
              <div class="clearfix"></div>
              <div class="form-group col-sm-6">
                <label>Reference No</label>
                <input type="text" name="reference_no" class="form-control">
              </div>
              <div class="form-group  col-sm-6">
                <label>Amount</label>
                <input type="text" name="amount" class="form-control balance_cmount required">
                <span class="text-danger"></span>
              </div>
            <input type="hidden" name="payment_from" value="1">
            <input type="hidden" name="id" class="model_purchase_id" value="0">
            <input type="hidden" name="total_payment" class="total-amount" value="">
            <div class="form-group">
              <div class="clearfix"></div>
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
  #payment_model .col-sm-6,#edit_payment_model .col-sm-6{
    float: left;
  }
</style>
  @push('custom-scripts')
  <script type="text/javascript">
    

    $(document).on('click', '.addpayment-submit', function(event) {
      var error_count=0;
      $('#payment_form .required').each(function(index, el) {
        event.preventDefault();
          var type=$(this).attr('type');
          var current_val=$(this).val();
          if (current_val=="" && type=="text") {
            $(this).next('.text-danger').html('This field is required');
              error_count += 1;
          } 
          else if (current_val=="" && type==undefined) {
            $(this).next('.text-danger').html('This field is required');
          } 
      });    
      var length_empty=$('#payment_form .required').filter(function(){return !$(this).val(); }).length;
      if (length_empty==0 && error_count==0)   {
          $('#payment_form').submit();
      }
        
    });

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
        $('.balance_cmount').val(balance_amount);
        var purchase_id=$(this).attr('purchase-id');
        $('.model_purchase_id').val(purchase_id);

        $('#payment_model').modal('show');
    });


    $(document).on('click', '.view-payment', function(event) {
        event.preventDefault();
        var purchase_id=$(this).attr('purchase-id');


        $.ajax({
          url: '{{ url('admin/view_purchase_payment') }}'+'/'+purchase_id,
        })
        .done(function(response) {
            $('.modal-body tbody tr').remove();
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
        var checkedNum = $('input[name="currency_id"]:checked').length;
        if (checkedNum==0) {
          alert('Please select purchase');
        }
        else{
          if (confirm('Are you sure want to delete?')) {
            $('input[name="currency_id"]:checked').each(function () {
              var current_val=$(this).val();
              $.ajax({
                url: "{{ url('admin/purchase/') }}/"+current_val,
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