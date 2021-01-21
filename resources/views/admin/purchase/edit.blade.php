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
            <h1 class="m-0">Edit Purchase</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">Purchase</a></li>
              <li class="breadcrumb-item active">Edit Purchase</li>
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
                  {{ Form::model($purchase,['method' => 'PATCH', 'route' =>['purchase.update',$purchase->id]]) }}
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Date *</label>
                          {!! Form::text('purchase_date', null,['class'=>'form-control','readonly'=>true]) !!}
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
                        <tr>
                          <th>Product</th>
                          @foreach($options as $option)
                            <th>{{$option}}</th>
                          @endforeach
                          <th>Base Price</th>
                          <th>Retail Price</th>
                          <th>Minimum Selling Price</th>
                          <th>Quantity</th>
                          <th>Sub Total</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($purchase_products as $key=>$variant)
                        <?php 

                        $option_id1=isset($variant[$key]['option_id1'])?$variant[$key]['option_id1']:'';

                        $product_id=$variant[$key]['product_id'];
                        $option_id2=isset($variant[$key]['option_id2'])?$variant[$key]['option_id2']:'';
                        $option_id3=isset($variant[$key]['option_id3'])?$variant[$key]['option_id3']:'';
                        $option_id4=isset($variant[$key]['option_id4'])?$variant[$key]['option_id4']:'';
                        $option_id5=isset($variant[$key]['option_id5'])?$variant[$key]['option_id5']:'';



                        $option_value_id1=isset($variant[$key]['option_value_id1'])?$variant[$key]['option_value_id1']:'';
                        $option_value_id2=isset($variant[$key]['option_value_id2'])?$variant[$key]['option_value_id2']:'';
                        $option_value_id3=isset($variant[$key]['option_value_id3'])?$variant[$key]['option_value_id3']:'';
                        $option_value_id4=isset($variant[$key]['option_value_id4'])?$variant[$key]['option_value_id4']:'';
                        $option_value_id5=isset($variant[$key]['option_value_id5'])?$variant[$key]['option_value_id5']:'';

  

                        $total=\App\Models\PurchaseProducts::StockQuantity($product_id,'sub_total');
                        $total_quantity=\App\Models\PurchaseProducts::StockQuantity($product_id,'quantity');


                        ?>
                          <tr>
                              <td>
                                {{ $variant[$key]['product_name'] }}
                                <input type="hidden" name="variant[product_id][]" value="{{ $product_id }}" class="product_id">
                              </td>
                            <td>
                              <div class="form-group">
                                <input type="hidden" name="variant[option_id1][]" value="{{$variant[$key]['option_id1']}}">
                                <input type="hidden" name="variant[option_value_id1][]" value="{{$variant[$key]['option_value_id1']}}">
                                {{$variant[$key]['option_value1']}}
                              </div>
                            </td>
                            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id2][]" value="{{$variant[$key]['option_id2']}}">
                                  <input type="hidden" name="variant[option_value_id2][]" value="{{$variant[$key]['option_value_id2']}}">
                                  {{$variant[$key]['option_value2']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==3||$option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id3][]" value="{{$variant[$key]['option_id3']}}">
                                  <input type="hidden" name="variant[option_value_id3][]" value="{{$variant[$key]['option_value_id3']}}">
                                  {{$variant[$key]['option_value3']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id4][]" value="{{$variant[$key]['option_id4']}}">
                                  <input type="hidden" name="variant[option_value_id4][]" value="{{$variant[$key]['option_value_id4']}}">
                                  {{$variant[$key]['option_value4']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id5][]" value="{{$variant[$key]['option_id5']}}">
                                  <input type="hidden" name="variant[option_value_id5][]" value="{{$variant[$key]['option_value_id5']}}">
                                  {{$variant[$key]['option_value5']}}
                                </div>
                              </td>
                            @endif
              <td class="base_price">
                {{$variant[$key]['base_price']}}
                            </td>
                            <td>
                              <input type="hidden" name="variant[base_price][]" value="{{$variant[$key]['base_price']}}">
                              <input type="hidden" name="variant[retail_price][]" value="{{$variant[$key]['retail_price']}}">
                              {{$variant[$key]['retail_price']}}
                            </td>
                            <td>
                              <input type="hidden" name="variant[minimum_selling_price][]" value="{{$variant[$key]['minimum_selling_price']}}">
                              {{$variant[$key]['minimum_selling_price']}}
                            </td>
                          <td>
                              <div class="form-group">
                                <input type="text" class="form-control stock_qty" onkeyup="validateNum(event,this);" name="variant[stock_qty][]" value="{{ $total_quantity[$key] }}">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <span class="sub_total">{{ $total[$key] }}</span>
                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $variant[$key]['base_price'] }}">
                              </div>
                            </td>
                            <td>
                              <a href="javascript::void(0)" class="btn btn-danger remove-item">
                                <i class="fa fa-trash"></i>
                              </a>
                            </td>
                          </tr>
                        @endforeach
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
                          {!! Form::textarea('payment_note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    </div>
                  </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', null,['class'=>'form-control summernote','rows'=>5]) !!}
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
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

@push('custom-scripts')
<script type="text/javascript">

  $(document).on('click', '.remove-item', function(event) {
    $(this).parents('tr').remove();
  });

    $(document).on('keyup', '.stock_qty', function(event) {
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
        var check_length=$('.product_id[value=1]').length;
        if (check_length>0) {
            alert('This product already exists');
            $(this).val('');
            return false;
        }
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