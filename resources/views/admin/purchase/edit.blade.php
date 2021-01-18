@extends('admin.layouts.master')
@section('main_container')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Purchase</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">Purchase</a></li>
              <li class="breadcrumb-item active">Add Purchase</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <!-- Main content -->
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('purchase.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Purchase</h3>
              </div>
              <div class="card-body">
                <form action="{{route('purchase.store')}}" method="post" enctype="multipart/form-data" id="productForm">
                  @csrf
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Date *</label>

                    <div class="input-group date" id="reservationdate" data-target-input="nearest">
                        <input name="purchase_date" type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                        <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>

                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Purchase Order Number *</label>
                          {!! Form::text('purchase_order_number',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('purchase_status',$order_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="col-sm-8">
                        <div class="form-group">
                          <label for="purchase_order_number">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control','id'=>'prodct-add-sec']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Vendor *</label>
                          {!! Form::select('vendor_id',$vendors, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="order-item-sec">
                    <table class="table table-bordered purchase-table">
                      <thead>
                        <th>Product (Code-Name)</th>
                        <th>Options</th>
                        <th>Base Price</th>
                        <th>Retail Price</th>
                        <th>Minimum Selling Price</th>
                        <th>Quantity</th>
                        <th>Sub Total</th>
                        <th></th>
                      </thead>
                      <tbody>
                        <tr class="no-match">
                          <td>No Entry Found</td>
                          <td>dd/mm/yyyy</td>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                          <td>0.00</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="tax-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Order Tax</label>
                          {!! Form::text('order_tax', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Order Discount</label>
                          {!! Form::text('order_discount', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Term</label>
                          {!! Form::text('payment_term', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Status *</label>
                          <?php $payment_status=[''=>'Please Select',1=>'Paid',2=>'Not Paid']; ?>
                          {!! Form::select('payment_status',$payment_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="panel panel-default payment-note-sec">
                    <div class="panel-body">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Reference Number</label>
                          {!! Form::text('payment_reference_no', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Amount</label>
                          {!! Form::text('amount', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Paying By</label>
                          {!! Form::select('paying_by', $payment_method, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Payment Note</label>
                          {!! Form::textarea('payment_note', null,['class'=>'form-control','rows'=>5]) !!}
                        </div>
                    </div>
                    </div>
                  </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', null,['class'=>'form-control','rows'=>5]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('purchase.index') }}" class="btn  reset-btn">
                        Cancel
                      </a>
                      <button class="btn save-btn" type="submit">
                        Submit
                      </button>

                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

@push('custom-scripts')
<script type="text/javascript">

   /* $('#reservationdate').datetimepicker({
        format: 'L'
    });
    */
    $(document).on('keyup', '.quantity', function(event) {
      if (/\D/g.test(this.value))
      {
        this.value = this.value.replace(/\D/g, '');
      }
      else{
        var base=$(this).parents('tr');
          var base_price=base.find('.base_price').text();
          var total_price=base_price*$(this).val();
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);
      }
    });
  var path ="{{ url('admin/product-search') }}";

    $('#prodct-add-sec').autocomplete({
      source: function( request, response) {
        $.ajax({
          url: "{{ url('admin/product-search') }}",
          data: {
            name: request.term,
            product_search_type:'product'
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {
        $(this).val('');
        $('.no-match').hide();
        $.ajax({
          url: "{{ url('admin/product-search') }}",
          data: {
            product_search_type: 'options',
            product_id:ui.item.value
          },
        })
        .done(function(response) {
            $.each(response, function(index, val) {
              var tr_val ="<tr>";
                       tr_val +="<td>"+val.label+"</td>";
                        tr_val +="<td>"+val.option_value+"</td>";
                        tr_val +="<td class='base_price'>"+val.base_price+"</td>";
                        tr_val +="<td>"+val.retail_price+"</td>";
                        tr_val +="<td>"+val.minimum_selling_price+"</td>";
                        tr_val +="<td><input type='text' name='quantity["+val.value+"]["+val.option_id+"]["+val.option_value_id+"]' value='1' class='form-control quantity'></td>";
                        tr_val +="<td class='sub_total'>"+val.base_price+"</td>";
                        tr_val +="<td><input type='hidden' class='subtotal_hidden' name='subtotal["+val.value+"]["+val.option_id+"]["+val.option_value_id+"]' value='"+val.base_price+"'></td>";
                        tr_val +="</tr>"; 
                  $('.purchase-table').append(tr_val);
            });
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });

      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });

</script>
@endpush
  <style type="text/css">
    .date-sec .col-sm-4, .tax-sec .col-sm-4 , .payment-note-sec .col-sm-4 {
      float: left;
    }
    .product-sec .col-sm-8,.product-sec .col-sm-4{
      float: left;
    }
  </style>
@endsection