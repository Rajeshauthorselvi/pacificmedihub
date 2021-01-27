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
<div class="clearfix"></div>
<div class="bs-example">
    <div class="accordion" id="accordionExample">
      @foreach ($purchase_products as $product_id=>$products)
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $product_id }}"><i class="fa fa-plus"></i> {{ $product_name[$product_id] }}</button>                  
                </h2>
            </div>
            <div id="collapse{{ $product_id }}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
                  <div class="table-responsive">
  <table class="table table-bordered purchase-table vatiant_table">
                      <thead>
                        <tr>
                          <th>Product</th>
                          @foreach($options[$product_id]['options'] as $option)
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
                        <?php 
                        $total_amount=$quantity=$all_quantity=$all_amount=0;
                        ?>

                        @foreach ($products as $key=>$variant)
                        <?php $option_count=$options[$product_id]['option_count']; ?>
                        <?php 
                          


                        $option_id1=isset($variant['option_id1'])?$variant['option_id1']:'';
                        $product_id=isset($variant['product_id'])?$variant['product_id']:0;
                    
                        $product_id=$variant['product_id'];
                        $option_id2=isset($variant['option_id2'])?$variant['option_id2']:'';
                        $option_id3=isset($variant['option_id3'])?$variant['option_id3']:'';
                        $option_id4=isset($variant['option_id4'])?$variant['option_id4']:'';
                        $option_id5=isset($variant['option_id5'])?$variant['option_id5']:'';

                        $option_value_id1=isset($variant['option_value_id1'])?$variant['option_value_id1']:'';
                        $option_value_id2=isset($variant['option_value_id2'])?$variant['option_value_id2']:'';
                        $option_value_id3=isset($variant['option_value_id3'])?$variant['option_value_id3']:'';
                        $option_value_id4=isset($variant['option_value_id4'])?$variant['option_value_id4']:'';
                        $option_value_id5=isset($variant['option_value_id5'])?$variant['option_value_id5']:'';

  

                        $total=\App\Models\PurchaseProducts::StockQuantity($product_id,$option_id1, $option_id2, $option_id3, $option_id4, $option_value_id1, $option_value_id2, $option_value_id3, $option_value_id4, $option_value_id5,$option_count,'sub_total',$purchase->id);



                        $total_quantity=\App\Models\PurchaseProducts::StockQuantity($product_id,$option_id1, $option_id2, $option_id3, $option_id4, $option_value_id1, $option_value_id2, $option_value_id3, $option_value_id4, $option_value_id5,$option_count,'quantity',$purchase->id);


                        ?>
                          <tr>
                              <td>
                                {{ $variant['product_name'] }}
                                <input type="hidden" name="variant[product_id][]" value="{{ $product_id }}" class="product_id">
                              </td>
                            <td>
                              <div class="form-group">
                                <input type="hidden" name="variant[option_id1][]" value="{{$variant['option_id1']}}">
                                <input type="hidden" name="variant[option_value_id1][]" value="{{$variant['option_value_id1']}}">
                                {{$variant['option_value1']}}
                              </div>
                            </td>
                            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id2][]" value="{{$variant['option_id2']}}">
                                  <input type="hidden" name="variant[option_value_id2][]" value="{{$variant['option_value_id2']}}">
                                  {{$variant['option_value2']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==3||$option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id3][]" value="{{$variant['option_id3']}}">
                                  <input type="hidden" name="variant[option_value_id3][]" value="{{$variant['option_value_id3']}}">
                                  {{$variant['option_value3']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id4][]" value="{{$variant['option_id4']}}">
                                  <input type="hidden" name="variant[option_value_id4][]" value="{{$variant['option_value_id4']}}">
                                  {{$variant['option_value4']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id5][]" value="{{$variant['option_id5']}}">
                                  <input type="hidden" name="variant[option_value_id5][]" value="{{$variant['option_value_id5']}}">
                                  {{$variant['option_value5']}}
                                </div>
                              </td>
                            @endif
              <td class="base_price">
                {{$variant['base_price']}}
                            </td>
                            <td>
                              <input type="hidden" name="variant[base_price][]" value="{{$variant['base_price']}}">
                              <input type="hidden" name="variant[retail_price][]" value="{{$variant['retail_price']}}">
                              {{$variant['retail_price']}}
                            </td>
                            <td>
                              <input type="hidden" name="variant[minimum_selling_price][]" value="{{$variant['minimum_selling_price']}}">
                              {{$variant['minimum_selling_price']}}
                            </td>
                          <td>
                              <div class="form-group">
                                <input type="text" class="form-control stock_qty" onkeyup="validateNum(event,this);" name="variant[stock_qty][]" value="{{ $total_quantity }}">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <span class="sub_total">{{ $variant['base_price']*$total_quantity }}</span>
                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $variant['base_price']*$total_quantity  }}">
                              </div>
                            </td>
                            <td>
                              <a href="javascript::void(0)" class="btn btn-danger remove-item">
                                <i class="fa fa-trash"></i>
                              </a>
                            </td>
                          </tr>
                          <?php $total_amount +=$variant['base_price']*$total_quantity; ?>
                          <?php $quantity +=$total_quantity; ?>
                        @endforeach
                        <tr>
                          <td colspan="{{ count($options[$product_id]['options'])+4 }}"></td>
                          <td class="total_quantity">{{ $quantity }}</td>
                          <td class="total_amount">{{ $total_amount }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
        <br>

        <?php $all_quantity +=$quantity; ?>
        <?php $all_amount +=$total_amount; ?>
      @endforeach
    </div>
</div>


                  </div>
                  <div class="col-sm-12 order-total-sec">

                    <div class="panel panel-default">
                      <div class="panel-body">
                        <table class="table table-bordered total-sec">
                          <tr>
                            <td>Grand Total</td>
                            <td class="all_quantity">{{ $all_quantity }}</td>
                            <td class="all_total">{{ $all_amount }}</td>
                          </tr>
                        </table>
                      </div>
                      </div>
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
<style type="text/css">
  .order-total-sec{
    background-color: #f1f1f1;
    padding-bottom: 5px;
    padding-top: 26px;
    padding-right: 20px;
    padding-left: 20px;
    box-shadow: none !important;    
  }
  .total-sec{
    background-color: #fcf8e3;
  }
</style>
@push('custom-scripts')
<script type="text/javascript">

    function SumAllTotal() {
            $('.order-total-sec').show();
            var sum = 0;
            $('.total_amount').each(function(){
                sum += parseFloat($(this).text());
            });

            $('.all_total').text(sum);

            var quantity=0;
            $('.total_quantity').each(function(){
                quantity += parseFloat($(this).text());
            });
            $('.all_quantity').text(quantity);

    }


    $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
          $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
    });
  
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

            var subtotal=$(this).parents('.vatiant_table').find('.subtotal_hidden');
            var sum = 0;
            $(subtotal).each(function(){
                sum += parseFloat(this.value);
            });
            $(this).parents('.vatiant_table').find('.total_amount').text(sum);
            var stock_qty=$(this).parents('.vatiant_table').find('.stock_qty');
            var quantity=0;
            $(stock_qty).each(function(){
                quantity += parseFloat(this.value);
            });
            $(this).parents('.vatiant_table').find('.total_quantity').text(quantity);
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

        var check_length=$('.product_id[value='+ui.item.value+']').length;
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
            $('.order-item-sec').append(response)
            SumAllTotal();
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });


        return false;
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