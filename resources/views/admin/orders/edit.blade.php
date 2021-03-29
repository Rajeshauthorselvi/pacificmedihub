@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('orders.index')}}">Order List</a></li>
              <li class="breadcrumb-item active">Edit Order</li>
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
          <a href="{{route($back_route)}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Order</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  {!! Form::model($order,['method' => 'PATCH', 'route' =>[$update_route,$order->id]]) !!}
                    <div class="date-sec">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="date">Date *</label>
                          <input type="text" class="form-control" name="created_at" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                        <div class="col-sm-4">
                          <label for="order_no">Order Code *</label>
                          {!! Form::text('order_no',null,['class'=>'form-control','readonly']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="status">Status *</label>
                          {!! Form::select('order_status',$order_status, $order->order_status,['class'=>'form-control no-search select2bs4']) !!}
                        </div>
                      </div>
                    </div>

                    <div class="product-sec">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="customer_id">Customer *</label>
                            {!! Form::select('customer_id',$customers,  null,['class'=>'form-control select2bs4','id'=>'customer','disabled']) !!}
                            {!! Form::hidden('customer_id',$order->customer_id,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger customer" style="display:none;">Customer is required. Please Select</span>
                        </div>
                        <div class="col-sm-4">
                          <label for="sales_rep_id">Sales Rep *</label>
                          {!! Form::select('sales_rep_id',$sales_rep,null,['class'=>'form-control select2bs4','id'=>'sales_rep_id']) !!}
                          <span class="text-danger sales_rep" style="display:none;">Sales Rep is required. Please Select</span>
                        </div>
                        <div class="col-sm-4">
                          <label for="currency_rate">Currency</label>
                          <select class="form-control no-search select2bs4" name="currency" id="currency_rate">
                            @foreach($currencies as $currency)
                              <option currency-rate="{{$currency->exchange_rate}}" currency-code="{{$currency->currency_code}}" value="{{$currency->id}}" @if($order->currency==$currency->id)  selected="selected" @endif {{ (collect(old('currency'))->contains($currency->id)) ? 'selected':'' }}>
                                {{$currency->currency_code}} - {{$currency->currency_name}}
                              </option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label for="product">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec']) !!}
                        </div>
                      </div>
                    </div>

                    <div class="product-sec">
                      <div class="container my-4">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="heading-top">
                              <?php $total_products=\App\Models\OrderProducts::TotalDatas($order->id); ?>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">
                                    Total Quantity:&nbsp;
                                    <span class="all_quantity">{{ $total_products->quantity }}</span>   
                                </th>
                                <!-- <th scope="col">
                                    Price:&nbsp;
                                    <span class="all_rfq_price">{{ $total_products->final_price  }}</span>  
                                </th> -->
                                <th scope="col"></th>
                                <th>
                                    Total Amount:&nbsp;
                                    <span class="all_amount" id="allAmount">{{ $total_products->sub_total }}</span>
                                </th>
                                <th></th>

                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($product_datas as $product)
                                  <?php
                                    $total_based_products=\App\Models\OrderProducts::TotalDatas($order->id,$product['product_id']);
                                  ?>
                                <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                  <td class="expand-button"></td>
                                  <td>{{ $product['product_name'] }}</td>
                                  <td>
                                    Quantity: &nbsp;
                                    <span class="total_quantity">{{ $total_based_products->quantity }}</span>
                                  </td>
                                  <td></td>
                                  <td class="total-head">
                                    Total: &nbsp;<span class="total">{{ $total_based_products->sub_total }}</span>
                                  </td>
                                  <td>
                                    <a href="javascript:void(0)" class="btn btn-danger remove-product-row"><i class="fa fa-trash"></i></a>
                                  </td>
                                </tr>
                                <tr class="hide-table-padding">
                                  <td></td>
                                  <td colspan="5">
                                    <div id="collapse{{ $product['product_id'] }}" class="collapse in p-3">
                                      <table class="table table-bordered" style="width: 100%">
                                        <thead>
                                          <tr>
                                            @foreach ($product['options'] as $option)
                                              <th>{{ $option }}</th>
                                            @endforeach
                                            <th>Base Price</th>
                                            <th>Retail Price</th>
                                            <th>Minimum Selling Price</th>
                                            <th>Final Price</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php $total_amount=$total_quantity=$final_price=0 ?>
                                          @foreach($product['product_variant'] as $key=>$variant)
                                            <?php 
                                              $option_count=$product['option_count'];
                                              $variation_details=\App\Models\OrderProducts::VariationPrice($product['product_id'],$variant['variant_id'],$order->id);
                                            ?>
                                            <tr class="parent_tr">
                                              <input type="hidden" name="variant[row_id][]" value="{{$variation_details->id}}">
                                              <input type="hidden" name="variant[product_id][]" value="{{ $product['product_id'] }}" class="product_id">
                                              <input type="hidden" name="variant[id][]" value="{{$variant['variant_id']}}" class="variant_id">
                                              <td>{{$variant['option_value1']}}</td>
                                              @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                                                <td>{{$variant['option_value2']}}</td>
                                              @endif
                                              @if($option_count==3||$option_count==4||$option_count==5)
                                                <td>{{$variant['option_value3']}}</td>
                                              @endif
                                              @if($option_count==4||$option_count==5)
                                                <td>{{$variant['option_value4']}}</td>
                                              @endif
                                              @if($option_count==5)
                                                <td> {{$variant['option_value5']}} </td>
                                              @endif
                                              <td class="base_price"> {{$variant['base_price']}} </td>
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
                                                 <?php $high_value=$variation_details['final_price']; ?>
                                                <input type="text" name="variant[final_price][]" value="{{ $high_value }}" autocomplete="off" class="form-control final_price">
                                                <input type="hidden" class="max_price" value="{{ $high_value }}">
                                              </td>
                                              <td>
                                                <div class="form-group">
                                                  <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" autocomplete="off" value="{{ $variation_details['quantity'] }}">
                                                </div>
                                              </td>
   
                                              <td>
                                                <div class="form-group">
                                                  <span class="sub_total">
                                                    {{ $variation_details['sub_total'] }}
                                                  </span>
                                                </div>
                                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $variation_details['sub_total'] }}">
                                              </td>
                                            </tr>
                                            <?php $total_amount +=$variation_details['sub_total']; ?>
                                            <?php $final_price +=$variation_details['final_price']; ?>
                                            <?php $total_quantity +=$variation_details['quantity']; ?>
                                          @endforeach
                                          <tr>
                                            <td colspan="{{count($product['options'])+3}}" class="text-right">Total:</td>
                                            <td class="all_final_price">{{ $final_price }}</td>
                                            <td class="total_quantity">{{ $total_quantity }}</td>
                                            <td class="total_amount">{{ $total_amount }}</td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </td>
                                </tr>
                              @endforeach
                              <tr class="total-calculation">
                                <td colspan="4" class="title">Total</td>
                                <td><span class="all_amount">{{ $order->total_amount }}</span></td>
                                <input type="hidden" name="total_amount" id="total_amount_hidden" value="{{$order->total_amount}}">
                              </tr>
                              <tr class="total-calculation">
                                <td colspan="4" class="title">Order Discount</td>
                                <td><span class="order-discount">{{$order->order_discount}}</span></td>
                              </tr>
                              <tr class="total-calculation">
                                <td colspan="4" class="title">Order Tax</td>
                                <td id="orderTax">{{$order->order_tax_amount}}</td>
                                <input type="hidden" name="order_tax_amount" id="order_tax_amount_hidden" value="{{$order->order_tax_amount}}">
                              </tr>
                              <tr class="total-calculation">
                                <td colspan="4" class="title">Delivery Charge</td>
                                <td id="deliveryCharge">{{$order->delivery_charge}}</td>
                              </tr>
                              <tr class="total-calculation">
                                <th colspan="4" class="title">Total Amount(SGD)</th>
                                <th id="total_amount_sgd">{{$order->sgd_total_amount}}</th>
                                <input type="hidden" name="sgd_total_amount" id="sgd_total_amount_hidden" value="{{$order->sgd_total_amount}}">
                              </tr>
                              @if($order->currencyCode->currency_code!='SGD')
                                @php $currency = 'contents'; @endphp 
                              @else
                                @php $currency = 'none'; @endphp
                              @endif
                              <tr class="total-calculation" id="total_exchange" style="display:{{$currency}}">
                                <th colspan="4" class="title">
                                  Total Amount (<span class="exchange-code">{{$order->currencyCode->currency_code}}</span>)
                                </th>
                                <th>
                                  <input type="text" name="exchange_rate" class="form-control" id="toatl_exchange_rate" value="{{$order->exchange_total_amount}}" onkeyup="validateNum(event,this);" autocomplete="off">
                                </th>
                              </tr>
                              <tr><td colspan="6"></td></tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <div class="tax-sec">
                      <div class="form-group">
                        <div class="col-sm-3">
                          <label for="purchase_date">Delivery Methods</label>
                          {!! Form::hidden('free_delivery_amount',$free_delivery,['class'=>'free_delivery_amount']) !!}
                          {!! Form::hidden('delivery_charge',$free_delivery,['class'=>'del_charge_hidden']) !!}
                          <select class="form-control no-search " id="delivery-methods" name="delivery_method_id">
                            <option value="">Please Select</option>
                            @foreach($delivery_methods as $method)
                              @if ($order->delivery_method_id==$method->id)
                                <option value="{{ $method->id }}" attr-fee="{{ $method->amount }}" selected="selected">
                                  {{ $method->delivery_method }}
                                </option>
                              @else
                                <option value="{{ $method->id }}" attr-fee="{{ $method->amount }}">
                                  {{ $method->delivery_method }}
                                </option>
                              @endif
                            @endforeach
                          </select>
                        </div>
                        <div class="col-sm-3">
                          <label for="purchase_date">Order Tax</label>
                          <select class="form-control no-search select2bs4" name="order_tax" id="order_tax">
                            @foreach($taxes as $tax)
                              <option tax-rate="{{$tax->rate}}" value="{{$tax->id}}" @if($order->order_tax==$tax->id)  selected="selected" @endif {{ (collect(old('order_tax'))->contains($tax->id)) ? 'selected':'' }}>
                                {{$tax->name}} 
                                @if($tax->name=='No Tax') 
                                @else @  {{round($tax->rate,2)}}% 
                                @endif
                              </option>
                            @endforeach
                          </select>

                        </div>
                        <div class="col-sm-3">
                          <label for="purchase_date">Order Discount</label>
                          {!! Form::text('order_discount', null,['class'=>'form-control','id'=>'order-discount']) !!}
                        </div>
                        <div class="col-sm-3">
                          <label for="purchase_date">Payment Term</label>
                          {!! Form::select('payment_term',$payment_terms,null,['class'=>'form-control no-search select2bs4']) !!}
                        </div>

                      </div>
                      <div class="form-group">
                        <div class="col-sm-3">
                          <label for="purchase_date">Payment Status *</label>
                          <?php $payment_status=[1=>'Paid',3=>'Partly Paid',2=>'Not Paid']; ?>
                          {!! Form::select('payment_status',$payment_status, $order->payment_status,['class'=>'form-control no-search select2bs4','id'=>'payment_status']) !!}
                        </div>
                      </div>
                    </div>

                    <div class="panel panel-default payment-note-sec" style="display:@if($order->payment_status==3) none @else block @endif">
                      <div class="panel-body">
                        <div class="form-group">
                          <div class="col-sm-4">
                            <label for="purchase_date">Payment Reference Number</label>
                            {!! Form::text('payment_ref_no', null,['class'=>'form-control']) !!}
                          </div>
                          <div class="col-sm-4">
                            <label for="purchase_date">Amount</label>
                            {!! Form::text('paid_amount', null,['class'=>'form-control']) !!}
                          </div>
                          <div class="col-sm-4">
                            <label for="purchase_date">Paying By</label>
                            {!! Form::select('paying_by', $payment_method, null,['class'=>'form-control no-search select2bs4']) !!}
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-12">
                            <label for="purchase_date">Payment Note</label>
                            {!! Form::textarea('payment_note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <label for="purchase_date">Note</label>
                        {!! Form::textarea('notes', null,['class'=>'form-control summernote','rows'=>5]) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12 submit-sec">
                        <a href="{{ route('orders.index') }}" class="btn  reset-btn">Cancel</a>
                        <button class="btn save-btn" type="submit">Save</button>
                      </div>
                    </div>
                  {!! Form::close() !!}
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
  </style>
  @push('custom-scripts')
    <script type="text/javascript">
      $(document).ready(function() {

        var all_amount = $('#allAmount').text();
        var free_del_amount=$('.free_delivery_amount').val();
          if ( parseInt(all_amount) >= parseInt(free_del_amount)) {
              $('#delivery-methods option[value="3"]').show();
          }
          else{
              $('#delivery-methods option[value="3"]').hide();
          }
          
      });   

      $(document).on('change','#delivery-methods', function(event) {

          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          var free_delivery=$('.free_delivery_amount').val();
          var free_del_amount=$('.free_delivery_amount').val();
          var del_fees = $('#delivery-methods option:selected').attr('attr-fee');
          var del_type = $('#delivery-methods option:selected').val();

          if (del_fees!="undefined") {
            if (del_type==1 || del_type==2) {
                var all_amount=parseInt(all_amount)+parseInt(del_fees);
            }
            else{
              var all_amount=parseInt(all_amount);
              var del_fees='0.00';
            }
            
          }
          else{
              var all_amount=all_amount;
          }
          $('.del_charge_hidden').val(del_fees);
          $('#deliveryCharge').text(del_fees);
          overallCalculation(all_amount,tax_rate,currency);

      });

      $(function ($) {
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });

      $(document).on('click', '.remove-product-row', function(event) {
        event.preventDefault();
        var curr_tr_quantity=$(this).closest('tr').find('.total_quantity').text();
        var curr_tr_total=$(this).closest('tr').find('.total').text();

        $(this).closest('tr').next('tr').remove();
        $(this).closest('tr').remove();

        var all_amount = $('#allAmount').text();
        var all_quantity = $('.all_quantity').text();
        var balance_amount=parseInt(all_amount)-parseInt(curr_tr_total);
        var balance_quantity=parseInt(all_quantity)-parseInt(curr_tr_quantity);

        $('.all_amount').text(balance_amount);
        $('.all_quantity').text(balance_quantity);
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        overallCalculation(balance_amount,tax_rate);

      });
  
      $(document).on('click', '.remove-item', function(event) {
        $(this).parents('.parent_tr').remove();
      });

      $('#prodct-add-sec').autocomplete({
        source: function( request, response) {
          $.ajax({
            url: "{{ url('admin/orders-product') }}",
            data: {
              name: request.term,
              product_search_type:'product'
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        minLength: 1,
        select: function( event, ui ) {
          var check_length=$('.product_id[value='+ui.item.value+']').length;

          if (check_length>0) {
            alert('This Product is already exists');
            $(this).val('');
            return false;
          }
          $.ajax({
            url: "{{ url('admin/orders-product') }}",
            data: {
              product_search_type: 'product_options',
              product_id:ui.item.value,
              from:'edit'
            },
          })
          .done(function(response) {
            if (response.status==false) {
              return false;
            }
            if ($('.vatiant_table').length==0) {
              var currencyCode = $('option:selected', '#currency_rate').attr("currency-code");
              createTable(currencyCode);
              if(currencyCode!='SGD'){
                $('#total_exchange').show();
              }
            } 
            // $('.parent_tbody').append(response);
            $('.total-calculation:first').before(response);
          });
           $(this).val('');
          return false;
        },
        open: function() {
          $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
          $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
      });

      $(document).on('keyup', '.stock_qty', function(event) {
          var base=$(this).parents('.parent_tr');
          var base_price=base.find('.final_price').val();
          var total_price=base_price*$(this).val();

          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);
            
          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var attr=$(this).parents('tbody').find('.collapse.show');
          var total_quantity=SumTotal('.collapse.show .stock_qty');
          console.log(total_quantity);
          
          $('.collapse.show').find('.total_quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total_quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          
          $('.collapse.show').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount.toFixed(2));
          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden').toFixed(2));

          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          var free_delivery=$('.free_delivery_amount').val();
          var free_del_amount=$('.free_delivery_amount').val();
          var del_fees = $('#delivery-methods option:selected').attr('attr-fee');
          var del_type = $('#delivery-methods option:selected').val();

          if ( parseInt(all_amount) >= parseInt(free_del_amount)) {
              $('#delivery-methods option[value="3"]').show();
          }
          else{
              $('#delivery-methods option[value="3"]').hide();
          }

          if (del_fees!="undefined") {
            if (del_type==1 || del_type==2) {
                var all_amount=parseInt(all_amount)+parseInt(del_fees);
            }
            else{
              var all_amount=parseInt(all_amount);
              var del_fees='0.00';
            }
            
          }
          else{
              var all_amount=all_amount;
          }
          $('#deliveryCharge').text(del_fees);
          overallCalculation(all_amount,tax_rate,currency);
        });

      $(document).on('keyup', '.final_price', function(event) {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
        else{
          var base=$(this).parents('.parent_tr');
          var base_price=base.find('.stock_qty').val();
          var total_price=base_price*$(this).val();
        
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);
            
          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var attr=$(this).parents('tbody').find('.collapse.show');
          var total_quantity=SumTotal('.collapse.show .stock_qty');

          $('.collapse.show').find('.total_quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total_quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount.toFixed(2));

          var total_final_price=SumTotal('.collapse.show .final_price');
          $('.collapse.show').find('.all_final_price').text(total_final_price);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden').toFixed(2));

          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate,currency);
        }
      });

      function SumTotal(class_name) {
        var sum = 0;
        $(class_name).each(function(){
           sum += parseFloat(this.value);
        });
        return sum;
      }

      $(document).on('keyup', '#order-discount', function() {
        var discount = $(this).val();
        $('.order-discount').text(discount);
        var currency = $('option:selected', '#currency_rate').attr("currency-rate");
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        overallCalculation(all_amount,tax_rate,currency);
      });

      $(document).on('change', '#order_tax', function() {
        var currency = $('option:selected', '#currency_rate').attr("currency-rate");
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', this).attr("tax-rate");
        overallCalculation(all_amount,tax_rate,currency);
      });

      $(document).on('change', '#currency_rate', function() {
        var currency = $('option:selected', this).attr("currency-rate");
        var currencyCode = $('option:selected', this).attr("currency-code");
        if(currencyCode!='SGD'){
          $('#total_exchange').show();
        }else if(currencyCode=='SGD'){
          $('#total_exchange').hide();
        }
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        $('.exchange-code').text(currencyCode);
        overallCalculation(all_amount,tax_rate,currency);
      });

      function overallCalculation(all_amount,tax_rate,currency_rate){
        var allAmount = all_amount;
        var taxRate = tax_rate;
        var currencyRate = currency_rate;
        var tax = taxRate/100;
        var calculatTax = tax*allAmount;
        var taxAmount = calculatTax.toFixed(2);
        var discount = $('#order-discount').val();
        $('#orderTax').text(taxAmount);
        var calculateSGD = parseFloat(allAmount)+parseFloat(taxAmount);
        var totalSGD = parseFloat(calculateSGD)-parseFloat(discount);
        $('#total_amount_sgd').text(totalSGD.toFixed(2));
        var totalExchangeRate = totalSGD*currencyRate;
        $('#toatl_exchange_rate').val(totalExchangeRate.toFixed(2));

          var del_fees = $('#delivery-methods option:selected').attr('attr-fee');
          var del_type = $('#delivery-methods option:selected').val();

          if (del_fees!="undefined") {
            if (del_type==1 || del_type==2) {
                var allAmount=parseInt(allAmount)-parseInt(del_fees);
            }
          }

        $('#total_amount_hidden').val(allAmount);
        $('#order_tax_amount_hidden').val(taxAmount);
        $('#sgd_total_amount_hidden').val(totalSGD);
      }

      function createTable(currency_code){
        var data='<div class="container my-4">';
            data +='<div class="table-responsive vatiant_table">';
            data +='<table class="table">';
            data +='<thead class="heading-top">';
            data +='<tr>';
            data +='<td>#</td>';
            data +='<th scope="col">Product Name</th>';
            data +='<th>Total Quantity:&nbsp;<span class="all_quantity"></span></th>';
            data +='<th>Total Amount:&nbsp;<span class="all_amount" id="allAmount"></span></th>';
            data +='<th></th>';
            data +='</tr>';
            data +='</thead>';
            data +='<tbody class="parent_tbody">';
            data +='</tbody>';
            data +='<tr class="total-calculation"><td colspan="3" class="title">Total</td>';
            data +='<td colspan="2"><span class="all_amount">0.00</span></td></tr>';
            data +='<tr class="total-calculation"><td colspan="3" class="title">Order Discount</td>';
            data +='<td colspan="2"><span class="order-discount">0.00</span></td></tr>';
            data +='<tr class="total-calculation"><td colspan="3" class="title">Order Tax</td>';
            data +='<td colspan="2" id="orderTax">0.00</td></tr>';
            data +='<tr class="total-calculation"><th colspan="3" class="title">Total Amount(SGD)</th>';
            data +='<th colspan="2" id="total_amount_sgd">0.00</th></tr>';
            data +='<tr class="total-calculation" id="total_exchange" style="display:none"><th colspan="3" class="title">Total Amount (<span class="exchange-code">'+currency_code+'</span>)</th>';
            data +='<th colspan="2">';
            data +='<input type="text" name="exchange_rate" class="form-control" id="toatl_exchange_rate" value="0.00" onkeyup="validateNum(event,this);" autocomplete="off"></th></tr>';
            data +='<tr><td colspan="5"></td></tr>';
            data +='</table>';
            data +='</div>';
            data +='</div>';
        $('.product-append-sec').html(data);
      }
        
      $(document).on('change', '#payment_status', function() {
        var paymentStatus = $('option:selected', this).val();
        if((paymentStatus==1) || (paymentStatus==3)){
          $('.payment-note-sec').css('display','block');
        }else{
          $('.payment-note-sec').css('display','none');
        }
      });

      $('.orders-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });

      $(document).on('click', '.save-btn', function(event) {
        if(validate()!=false){
          $('.orders-form').submit();
        }else{
          scroll_to();
          return false;
        }

      });
      
      function validate(){
        var valid=true;
        if ($("#customer").val()=="") {
          $("#customer").closest('.form-group').find('span.text-danger.customer').show();
          valid = false;
        }else{
          $("#customer").closest('.form-group').find('span.text-danger.customer').hide();
        }
        if ($("#sales_rep_id").val()=="") {
          $("#sales_rep_id").closest('.form-group').find('span.text-danger.sales_rep').show();
          valid = false;
        }else{
          $("#sales_rep_id").closest('.form-group').find('span.text-danger.sales_rep').hide();
        }
        if ($("#orderStatus").val()=="") {
          $("#orderStatus").closest('.form-group').find('span.text-danger.order').show();
          valid = false;
        }else{
          $("#orderStatus").closest('.form-group').find('span.text-danger.order').hide();
        }
        if($('#total_amount_hidden').val()=="")
        {
          alert('Please enter minimum Quantity');
          valid = false;
        }
        return valid;
      }

      function scroll_to(form){
        $('html, body').animate({
          scrollTop: $(".orders-form").offset().top
        },1000);
      }

    </script>
  @endpush
@endsection