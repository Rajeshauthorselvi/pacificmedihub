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
            <h1 class="m-0">Edit RFQ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">RFQ</a></li>
              <li class="breadcrumb-item active">Edit RFQ</li>
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
          <a href="{{route('rfq.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit RFQ</h3>
              </div>
              <div class="card-body">
              	{!! Form::model($rfqs,['method' => 'PATCH','class'=>'rfq-form','route' =>['rfq.update',$rfqs->id]]) !!}
                  <div class="date-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="date">Date *</label>
                        {!! Form::text('created_at',null,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="order_no">RFQ Code *</label>
                        {!! Form::text('order_no',null,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Status *</label>
                        {!!Form::select('status',$order_status, null,['class'=>'form-control no-search select2bs4'])!!}
                        <span class="text-danger rfq" style="display:none;">Status is required. Please Select</span>
                      </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                          <label for="customer_id">Customer *</label>
                          <select class="form-control select2bs4 customer_id" name="customer_id">
                              <option value="">Please Select</option>
                              @foreach ($customers as $customer)
                              @if ($customer->id==$rfqs->customer_id)
                                <option value="{{ $customer->id }}" sales-rep="{{ $customer->sales_rep }}" selected="selected">
                                  {{ $customer->first_name }}
                                </option>
                              @else
                                <option value="{{ $customer->id }}" sales-rep="{{ $customer->sales_rep }}" selected="selected">
                                  {{ $customer->first_name }}
                                </option>
                              @endif
                              @endforeach
                          </select>
                          <span class="text-danger customer" style="display:none;">Customer is required. Please Select</span>
                        </div>
                      <div class="col-sm-4">                        
                        <label for="sales_rep_id">Sales Rep *</label>
                        {!! Form::select('sales_rep_id',$sales_rep, null,['class'=>'form-control select2bs4']) !!}
                        <span class="text-danger sales_rep" style="display:none;">Sales Rep is required. Please Select</span>
                      </div>
                      <div class="col-sm-4">
                        <label for="currency_rate">Currency</label>
                        <select class="form-control no-search select2bs4" name="currency" id="currency_rate">
                          <option currency-rate="0" currency-code="" value="">Please Select</option>
                          @foreach($currencies as $currency)
                            <option currency-rate="{{$currency->exchange_rate}}" currency-code="{{$currency->currency_code}}" value="{{$currency->id}}" @if($rfqs->currency==$currency->id)  selected="selected" @endif {{ (collect(old('currency'))->contains($currency->id)) ? 'selected':'' }}>
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
                  <div class="clearfix"></div>

                  <div class="product-append-sec">
						        <div class="container my-4">
						          <div class="table-responsive">
						            <table class="table">
  						            <thead class="heading-top">
                            <?php $total_products=\App\Models\RFQProducts::TotalDatas($rfqs->id); ?>
  						              <tr>
          						        <th scope="col">#</th>
          						        <th scope="col">Product Name</th>
          						        <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity">{{ $total_products->quantity }}</span>   
                              </th>
          						       {{--  <th scope="col">
                                  Total Price:&nbsp;
                                  <span class="all_rfq_price">{{ $total_products->rfq_price }}</span>  
                              </th> --}}
                              <th>
                                  Total Amount:&nbsp;
                                  <span class="all_amount" id="allAmount">{{ $total_products->sub_total }}</span>
                              </th>
                              <th></th>
  						              </tr>
  						            </thead>
						              <tbody>
                            @foreach ($product_datas as $product)
            						      <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
              								  <td class="expand-button"></td>
                                <?php
                                $total_based_products=\App\Models\RFQProducts::TotalDatas($rfqs->id,$product['product_id']);
                                  $sum_of_retail_qty=$total_based_products->retail_price*$total_based_products->quantity;
                                 ?>
                								<td>{{ $product['product_name'] }}</td>
                								<th>
                                  Quantity: &nbsp;
                                  <span class="total_quantity">{{ $total_based_products->quantity }}</span>
                                </th>
                								{{-- <th>Price: {{ $total_based_products->rfq_price }}</th> --}}
                                <th class="total-head">
                                  <input type="hidden" value="@if($total_based_products->sub_total!=0) {{$total_based_products->sub_total}}
                                    @else {{$sum_of_retail_qty}} @endif" class="get-total">
                                  Total: &nbsp;<span class="total">
                                    @if($total_based_products->sub_total!=0) {{$total_based_products->sub_total}}
                                    @else {{$sum_of_retail_qty}} @endif</span>
                                </th>
                                <td>
                                    <a href="javascript:void(0)" class="btn btn-danger remove-product-row">
                                      <i class="fa fa-trash"></i>
                                    </a>
                                </td>
              							  </tr>
            								  <tr class="hide-table-padding">
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
                                          @if ($product['check_rfq_price_exists'])
                                            <th>Last RFQ Price</th>
                                          @endif
                                          <th>RFQ Price</th>
                                          <th>Quantity</th>
                                          <th>Subtotal</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $rfq_price=$total_amount=$total_quantity=0 ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                          <?php 
                                           $option_count=$product['option_count'];
                                           $variation_details=\App\Models\RFQProducts::VariationPrice($product['product_id'],$variant['variant_id'],$product['rfq_id']);
                                          ?>
                                          <tr class="parent_tr">
                                            <td>
                                              <input type="hidden" name="variant[row_id][]" value="{{$variation_details->id}}">
                                              <input type="hidden" name="variant[product_id][]" value="{{ $product['product_id'] }}" class="product_id">
                                              <input type="hidden" name="variant[id][]" value="{{$variant['variant_id']}}" class="variant_id">
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

                                            <td class="base_price">{{$variant['base_price']}}</td>
                                            <td>
                                              <input type="hidden" name="variant[base_price][]" value="{{$variant['base_price']}}">
                                              <input type="hidden" name="variant[retail_price][]" value="{{$variant['retail_price']}}">
                                              {{$variant['retail_price']}}
                                            </td>
                                            <td>
                                              <input type="hidden" name="variant[minimum_selling_price][]" value="{{$variant['minimum_selling_price']}}">
                                              {{$variant['minimum_selling_price']}}
                                            </td>
                                            @if ($product['check_rfq_price_exists'])
                                            <td>
                                                {{ $variation_details->last_rfq_price }}
                                            </td>
                                            @endif
                                            <td>
                                              <?php $high_value = isset($variation_details['rfq_price'])?$variation_details['rfq_price']:$variant['retail_price']; ?>
                                              <input type="text" name="variant[rfq_price][]" class="form-control rfq_price" value="{{ $high_value }}" autocomplete="off">
                                            </td>
                                            <td>
                                              <div class="form-group">
                                                <?php $quantity=1 ?>
                                                <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" value="{{ $variation_details['quantity'] }}" autocomplete="off">
                                              </div>
                                            </td>  
                                            <td>
                                              <?php $sub_total = $variation_details['quantity']*$high_value; ?>
                                              <div class="form-group">
                                                <span class="sub_total">{{ $sub_total }}</span>
                                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $sub_total }}">
                                              </div>
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$sub_total; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                          <?php $rfq_price +=$high_value; ?>
                                        @endforeach
                                        <tr>
                                          
                                          <td colspan="{{ count($product['options'])+4 }}" class="text-right">Total:</td>
                                          <td class="total_quantity">{{ $total_quantity }}</td>
                                          <td class="total_amount total">{{ $total_amount }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
            								      </div>
                                </td>
								              </tr>
                            @endforeach
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Total</td>
                              <td colspan="2"><span class="all_amount">{{$rfqs->total_amount}}</span></td>
                              <input type="hidden" name="total_amount" id="total_amount_hidden" value="{{$rfqs->total_amount}}">
                            </tr>
                            <tr class="total-calculation"><td colspan="3" class="title">Order Discount</td>
                              <td colspan="2"><span class="order-discount">{{isset($rfqs->order_discount)?$rfqs->order_discount:'0.00'}}</span></td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Order Tax</td>
                              <td id="orderTax" colspan="2">{{isset($rfqs->order_tax_amount)?$rfqs->order_tax_amount:'0.00'}}</td>
                              <input type="hidden" name="order_tax_amount" id="order_tax_amount_hidden" value="{{isset($rfqs->order_tax_amount)?$rfqs->order_tax_amount:0.00}}">
                            </tr>
                            <tr class="total-calculation">
                              <th colspan="3" class="title">Total Amount(SGD)</th>
                              <th id="total_amount_sgd" colspan="2">{{$rfqs->sgd_total_amount}}</th>
                              <input type="hidden" name="sgd_total_amount" id="sgd_total_amount_hidden" value="{{$rfqs->sgd_total_amount}}">
                            </tr>
                            @if(isset($rfqs->currency))
                              <?php $currency='content'; ?>
                            @else
                              <?php $currency='none'; ?>
                            @endif
                            <tr class="total-calculation" id="total_exchange" style="display:{{$currency}}">
                              <th colspan="3" class="title">
                                Total Amount (<span class="exchange-code">{{isset($rfqs->currencyCode->currency_code)?$rfqs->currencyCode->currency_code:''}}</span>)
                              </th>
                              <th>
                                <input type="text" name="exchange_rate" class="form-control" id="toatl_exchange_rate" value="{{$rfqs->exchange_total_amount}}" onkeyup="validateNum(event,this);" autocomplete="off">
                              </th>
                            </tr>
                            <tr><td colspan="5"></td></tr>
						              </tbody>
						            </table>
						          </div>
						        </div>
                  </div>
                
                  <div class="clearfix"></div>
                  <div class="tax-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="purchase_date">Order Tax</label>
                        <select class="form-control no-search select2bs4" name="order_tax" id="order_tax">
                          @foreach($taxes as $tax)
                            <option tax-rate="{{$tax->rate}}" value="{{$tax->id}}" @if($tax->id==$rfqs->order_tax)  selected="selected" @endif {{ (collect(old('order_tax'))->contains($tax->id)) ? 'selected':'' }}>
                              {{$tax->name}} 
                              @if($tax->name=='No Tax') 
                              @else @  {{round($tax->rate,2)}}% 
                              @endif
                            </option>
                          @endforeach
                        </select>

                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_date">Order Discount</label>
                        {!! Form::text('order_discount', isset($rfqs->order_discount)?$rfqs->order_discount:0,['class'=>'form-control','id'=>'order-discount']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_date">Payment Term</label>
                        {!! Form::select('payment_term',$payment_terms,$rfqs->payment_term,['class'=>'form-control no-search select2bs4']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="col-sm-12">
                    <label for="sales_rep_id">Notes</label>
                    {!! Form::textarea('notes',null,['class'=>'form-control summernote']) !!}
                  </div>
                  <br>
                  <div class="form-group">
                    <a href="{{ route('rfq.index') }}" class="btn reset-btn">Cancel</a>
                    <button class="btn save-btn" type="submit">Save</button>
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
    .form-group{display:flex;}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">

      $(document).ready(function($) {
        var sum = 0;
        $('.get-total').each(function() {
          sum += Number($(this).val());
        });
        $('.all_amount').text(sum);
        $('#total_amount_hidden').val(sum);

        var sgdTotal = $('#sgd_total_amount_hidden').val();
        if(sgdTotal==''){
          $('#sgd_total_amount_hidden').val(sum);
          $('#total_amount_sgd').text(sum);
        }
      });

      $(document).on('change', '#customer', function(event) {
        if ($('.vatiant_table').length!=0) {
          ExistingRFQPrice();
        }
      });

      function ExistingRFQPrice() {
            var value_array = [];
            $('.parent_tr').each(function(index, el) {
                var customer_id=$('#customer').val();
                var product_id=$(this).find('.product_id').val();
                var variant_id=$(this).find('.variant_id').val();
                var current_node=$(this);
                $.ajax({
                  url: '{{ url('admin/check-rfq-existing') }}',
                  data: {
                    product_id: product_id,
                    variant_id:variant_id,
                    customer_id:customer_id,
                  }
                })
                .done(function(response) {
                  if (response.price!=null) {
                    current_node.find('.last-rfq').text(response.price);
                    value_array.push(response.price);
                  }
                  else{
                    current_node.find('.last-rfq').text('-');
                  }
                  if (value_array.length>0) {
                    current_node.find('.last-rfq').show();
                    $('#collapse'+product_id+' th.last-rfq').show();
                  }
                  else{
                    current_node.find('.last-rfq').hide();
                    $('#collapse'+product_id+' th.last-rfq').hide();
                  }
                })
                .fail(function() {
                  
                });

            });
      }
      $('#prodct-add-sec').autocomplete({
        source: function( request, response) {
          $.ajax({
            url: "{{ url('admin/rfq-product') }}",
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
            url: "{{ url('admin/rfq-product') }}",
            data: {
              product_search_type: 'product_options',
              product_id:ui.item.value,
              from:'edit'
            },
          })
          .done(function(response) {
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


      $(function ($) {
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });

      $(document).on('keyup', '.stock_qty', function(event) {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
        else{
          var base=$(this).parents('.parent_tr');
          var base_price=base.find('.rfq_price').val();
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
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount);
          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));

          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate,currency);
        }
      });

      $(document).on('keyup', '.rfq_price', function(event) {
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
          console.log(total_quantity);

          $('.collapse.show').find('.total_quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total_quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));

          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate,currency);
        }
      });

      $(document).on('keyup', '.rfq_price', function(event) {
        $('.all_rfq_price').text(SumTotal('.rfq_price'));
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
        if(currency!=0){
          $('#total_exchange').show();
        }else{
          $('#total_exchange').hide();
        }
        if(currencyCode=='SGD'){
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

        if(currencyRate!=0){
          var totalExchangeRate = totalSGD*currencyRate;
          $('#toatl_exchange_rate').val(totalExchangeRate.toFixed(2));
        }else{
          $('#total_exchange').hide();
        }
        $('#total_amount_hidden').val(allAmount);
        $('#order_tax_amount_hidden').val(taxAmount);
        $('#sgd_total_amount_hidden').val(totalSGD);
      }

      $('.rfq-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });

      $(document).on('click', '.save-btn', function(event) {
        if(validate()!=false){
          $('#rfq-form').submit();
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
        }
        if ($("#sales_rep_id").val()=="") {
          $("#sales_rep_id").closest('.form-group').find('span.text-danger.sales_rep').show();
          valid = false;
        }
        if ($("#rfqStatus").val()=="") {
          $("#rfqStatus").closest('.form-group').find('span.text-danger.rfq').show();
          valid = false;
        }
        return valid;
      }
      
      function scroll_to(form){
        $('html, body').animate({
          scrollTop: $(".rfq-form").offset().top
        },1000);
      }
    </script>
  @endpush
@endsection