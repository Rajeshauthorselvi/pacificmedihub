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
                        {!!Form::select('status',$order_status, 25,['class'=>'form-control no-search select2bs4'])!!}
                        <span class="text-danger rfq" style="display:none;">Status is required. Please Select</span>
                      </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="customer_id">Customer *</label>
                        {!! Form::select('customer_id',$customers, null,['class'=>'form-control select2bs4','id'=>'customer','disabled']) !!}
                        {!! Form::hidden('customer_id',$rfqs->customer_id,['class'=>'form-control','readonly']) !!}
                        <span class="text-danger customer" style="display:none;">Customer is required. Please Select</span>
                      </div>
                      <div class="col-sm-4">
                        <label for="deliveryAddress">Delivery Address *</label>
                         {!! Form::select('del_add_id',$del_address,  $rfqs->delivery_address_id,['class'=>'form-control no-search select2bs4','id'=>'deliveryAddress']) !!}
                      </div>
                      <div class="col-sm-4">                        
                        <label for="sales_rep_id">Sales Rep *</label>
                        {!! Form::select('sales_rep_id',$sales_rep, null,['class'=>'form-control select2bs4']) !!}
                        <span class="text-danger sales_rep" style="display:none;">Sales Rep is required. Please Select</span>
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
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>
                              <th scope="col">
                                Total Quantity:&nbsp;
                                <span class="all_quantity">{{ $total_products['total_qty'] }}</span>   
                              </th>
                              <th>
                                Total Amount:&nbsp;
                                <span class="all_amount" id="allAmount">{{ $total_products['total_amount'] }}</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody class="parent_tbody">
                            @foreach ($product_datas as $product)
                              <tr class="append-data-row">
                                <td colspan="4" style="padding:0">
                                  <table class="table table-bordered" style="width: 100%">
                                    <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                      <td class="expand-button" style="width:2rem;"></td>

                                      <td colspan="{{ count($product['options'])+8 }}" class="prod-name">{{ $product['product_name'] }}</td>
                                      <td style="width: 5%;">
                                        <a href="javascript:void(0)" class="btn btn-danger remove-product-row"><i class="fa fa-trash"></i></a>
                                      </td>
                                    </tr>

                                    <tbody id="collapse{{ $product['product_id'] }}" class="collapse in p-3">
                                      <tr>
                                        <th></th>
                                        @foreach ($product['options'] as $option)
                                          <th>{{ $option }}</th>
                                        @endforeach
                                        <th class="width">Base Price</th>
                                        <th class="width">Retail Price</th>
                                        <th class="width">Minimum Selling Price</th>
                                        @if ($product['check_rfq_price_exists'])
                                          <th class="width">Last RFQ Price</th>
                                        @endif
                                        <th class="width">RFQ Price <br><small>(a)</small></th>
                                        <th class="width">QTY <br><small>(b)</small></th>
                                        <th class="width">Discount
                                          <div class="discount-type">
                                            <div class="icheck-info d-inline">
                                              <input type="radio" name="variant[discount_type][{{ $product['product_id'] }}]" @if($discount_type[$product['product_id']]=='percentage') checked @endif id="percentage{{ $product['product_id'] }}" class="dis-type" value="percentage"><label for="percentage{{ $product['product_id'] }}">%</label>
                                            </div>&nbsp;&nbsp;&nbsp;
                                            <div class="icheck-info d-inline">
                                              <input type="radio" class="dis-type" name="variant[discount_type][{{ $product['product_id'] }}]" @if($discount_type[$product['product_id']]=='amount') checked @endif id="amount{{ $product['product_id'] }}" value="amount"><label for="amount{{ $product['product_id'] }}">$</label>
                                            </div>
                                          </div>
                                        </th>
                                        <th class="width">Discount Price <br><small>(c)</small></th>
                                        <th class="width">Total <br><small>(a x b)</small></th>
                                        <th class="width">Subtotal <br><small>(b x c)</small></th>
                                      </tr>
                                      <?php $total_amount=$total_quantity=0 ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                          <?php 
                                           $option_count=$product['option_count'];
                                           $variation_details=\App\Models\RFQProducts::VariationPrice($product['product_id'],$variant['variant_id'],$product['rfq_id']);
                                          ?>
                                          <tr class="parent_tr">
                                            <td></td>
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
                                              <input type="hidden" class="minimum-price" name="variant[minimum_selling_price][]" value="{{$variant['minimum_selling_price']}}">
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
                                              <?php $quantity=1; ?>
                                              <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" autocomplete="off" value="{{ $variation_details['quantity'] }}">
                                            </td>
                                            <td>
                                              <input type="text" name="variant[discount_value][]" class="form-control discount-value" autocomplete="off" value="{{ (int)$variation_details['discount_value'] }}">
                                            </td>
                                            <td>
                                              <span class="dis-price">{{ $variation_details['final_price'] }}</span>
                                              <input type="hidden" name="variant[final_price][]" class="form-control dis-price" value="{{ $variation_details['final_price'] }}">
                                            </td>
                                            <td>
                                              <span class="price">{{ $variation_details['total_price'] }}</span>
                                              <input type="hidden" name="variant[price][]" class="form-control price" value="{{ $variation_details['total_price'] }}">
                                            </td>
                                            <td>
                                              <?php $sub_total =  $variation_details['sub_total']; ?>
                                              <span class="sub_total">{{ $variation_details['sub_total'] }}</span>
                                              <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $sub_total }}">
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$sub_total; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options'])+3 }}">
                                            <input type="text" class="form-control" name="product_description[{{ $product['product_id'] }}]" placeholder="Notes" value="{{ isset($product_description_notes[$product['product_id']])?$product_description_notes[$product['product_id']]:'' }}">
                                          </td>
                                          <td colspan="2" class="text-right">Total Qty:</td>
                                          <td class="total_quantity">{{ $total_quantity }}</td>
                                          <td colspan="3" class="text-right">Grand Total:</td>
                                          <td class="total_amount">{{ $total_amount }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  
                  <div class="additional-sec container">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="purchase_date">Delivery Methods *</label>
                        {!! Form::hidden('free_delivery_amount',$free_delivery_target,['class'=>'free_delivery_amount']) !!}
                        {!! Form::hidden('delivery_charge',$rfqs->delivery_charge,['class'=>'del_charge_hidden']) !!}
                        <select class="form-control no-search " id="delivery-methods" name="delivery_method_id">
                          @foreach($delivery_methods as $method)
                            @if ($rfqs->delivery_method_id==$method->id)
                              <option value="{{ $method->id }}" attr-fee="{{ $method->amount }}" attr-target="{{ $method->target_amount }}" selected="selected">
                                {{ $method->delivery_method }}
                              </option>
                            @else
                              <option value="{{ $method->id }}" attr-fee="{{ $method->amount }}" attr-target="{{ $method->target_amount }}">
                                {{ $method->delivery_method }}
                              </option>
                            @endif
                          @endforeach
                        </select>
                        <span class="text-danger delivery_method" style="display:none;">Delivery Method is required. Please Select</span>
                      </div>

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
                        <label for="purchase_date">Payment Term</label>
                        {!! Form::select('payment_term',$payment_terms,$rfqs->payment_term,['class'=>'form-control no-search select2bs4']) !!}
                      </div>
                    </div>
                  
                    <div class="summery-block">
                      <h5>Summery</h5>
                      <table>
                        <tbody>
                          <tr class="total-calculation">
                            <td class="title">Total</td>
                            <td><span class="all_amount">{{$total_products['total_amount']}}</span></td>
                          </tr>
                          <tr class="total-calculation">
                            <td class="title">Order Tax</td>
                            <td id="orderTax">{{$order_tax}}</td>
                            <input type="hidden" name="order_tax_amount" id="order_tax_amount_hidden" value="{{$order_tax}}">
                          </tr>
                            <tr class="total-calculation">
                              <td class="title">Delivery Charge</td>
                            <td id="deliveryCharge">{{$delivery_charge}}</td>
                          </tr>
                            <tr class="total-calculation">
                              <?php $sgd_total = $total_products['total_amount']+$discount_amt+$order_tax+$delivery_charge; ?>
                              <input type="hidden" name="sgd_total_amount" id="sgd_total_amount_hidden" value="{{$sgd_total}}">
                              <th  class="title">Total Amount(SGD)</th>
                            <th id="total_amount_sgd">{{$sgd_total}}</th>
                          </tr>
                            <td style="display:inline-flex;">
                              <label for="currency_rate">Currency</label>&nbsp;&nbsp;
                              <select class="form-control no-search select2bs4" name="currency" id="currency_rate">
                                <option value="">Please Select</option>
                                @foreach($currencies as $currency)

                                @if(isset($rfqs->currency) && $rfqs->currency==$currency->id)
                                  <option currency-rate="{{$currency->exchange_rate}}" currency-code="{{$currency->currency_code}}" value="{{$currency->id}}" @if($rfqs->currency==$currency->id)  selected="selected" @endif {{ (collect(old('currency'))->contains($currency->id)) ? 'selected':'' }}>
                                    {{$currency->currency_code}} - {{$currency->currency_name}}
                                  </option>
                                @elseif(!isset($rfqs->currency))
                                  <option currency-rate="{{$currency->exchange_rate}}" currency-code="{{$currency->currency_code}}" value="{{$currency->id}}" @if($currency->id==3)  selected="selected" @endif {{ (collect(old('currency'))->contains($currency->id)) ? 'selected':'' }}>
                                      {{$currency->currency_code}} - {{$currency->currency_name}}
                                  </option>
                                @else
                                  <option currency-rate="{{$currency->exchange_rate}}" currency-code="{{$currency->currency_code}}" value="{{$currency->id}}" {{ (collect(old('currency'))->contains($currency->id)) ? 'selected':'' }}>
                                    {{$currency->currency_code}} - {{$currency->currency_name}}
                                  </option>
                                @endif
                                @endforeach
                              </select>
                            </td>
                            <td>
                              <?php 
                                if(!isset($rfqs->currency)){
                                    $total=$sgd_total;
                                }
                                else{
                                   $total=$rfqs->exchange_total_amount;
                                }
                              ?>
                              <input type="text" name="exchange_rate" class="form-control" id="toatl_exchange_rate" value="{{$total}}" onkeyup="validateNum(event,this);" autocomplete="off">
                            </td>
                        </tbody>
                      </table>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <label for="sales_rep_id">Notes</label>
                        {!! Form::textarea('notes',null,['class'=>'form-control summernote']) !!}
                      </div>
                    </div>
                  </div>

                 
                  <div class="form-group">
                    <div class="col-sm-12">
                      <a href="{{ route('rfq.index') }}" class="btn reset-btn">Cancel</a>
                      <button class="btn save-btn" type="submit">Save</button>
                    </div>
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
    .summery-block {
      margin: 1.5rem 0;
      position: relative;
      width: 50%;
      left: 50%;
    }
    .summery-block h5 {
      font-weight: bold;
    }
    .summery-block table tr td,.summery-block table tr th {
      width: 300px;
      border: 1px solid #eee;
      padding: 10px;
    }
    .summery-block .total-calculation .title{text-align:left;border-color:#eee}
    th.width{width:90px;}
    .prod-name {
      width: 175px;
    }
  </style>

  @push('custom-scripts')
    <script type="text/javascript">
      $(function ($) {
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });

      $(document).on('click','.accordion-toggle',function(){
        $('.parent_tbody').find('.accordion-toggle').addClass('accordion-toggle collapsed');
        $('.parent_tbody').find('.collapse.show').removeClass('show');
      });

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
            $('.append-data-row:last').after(response);
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
        if(!confirm('Are you sure to remove it.?')){
          return false;
        }else{
          event.preventDefault();
          var curr_tr_quantity=$(this).parents('tbody').next('tbody').find('.total_quantity').text();
          var curr_tr_total=$(this).parents('tbody').next('tbody').find('.total_amount').text();
          $(this).parents('tbody').parents('table').parents('td').parents('tr').remove();
          
          var all_amount = $('#allAmount').text();
          var all_quantity = $('.all_quantity').text();
          var balance_amount=parseInt(all_amount)-parseInt(curr_tr_total);
          var balance_quantity=parseInt(all_quantity)-parseInt(curr_tr_quantity);
          $('.all_amount').text(balance_amount);
          $('.all_quantity').text(balance_quantity);
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(balance_amount,tax_rate);
          currencyCalculation();
        }
      });

      $(document).on('change', '.rfq_price', function(event) {
        var minimum_price = $(this).parents('.parent_tr').find('.minimum-price').val();
        var qty           = $(this).parents('.parent_tr').find('.stock_qty').val();
        var discountType = $(this).parents('.parent_tr').parent().find('.discount-type').find('input[type=radio]:checked').val();
        var discount     = $(this).parents('.parent_tr').find('.discount-value').val();

        var current_price = $(this).val();
        if(current_price==''){
          $(this).val(minimum_price);
          if(discountType=='amount'){
            dis_price = current_price-discount;
          }else if(discountType=='percentage'){
            dis_price = (current_price - (current_price * discount/100));
          }
          $(this).parents('.parent_tr').find('.dis-price').val(dis_price)
          $(this).parents('.parent_tr').find('.price').val(minimum_price*qty);
          $(this).parents('.parent_tr').find('.dis-price').text(dis_price)
          $(this).parents('.parent_tr').find('.price').text(minimum_price*qty);
        }else{
          if ((current_price !== '') && (current_price.indexOf('.') === -1)) {
            var current_price = Math.max(Math.max(current_price, parseInt(minimum_price)), -90);
            $(this).val(current_price);
            if(discountType=='amount'){
              dis_price = current_price-discount;
            }else if(discountType=='percentage'){
              dis_price = (current_price - (current_price * discount/100));
            }
            $(this).parents('.parent_tr').find('.dis-price').val(dis_price);
            $(this).parents('.parent_tr').find('.price').val(current_price*qty);
            $(this).parents('.parent_tr').find('.dis-price').text(dis_price);
            $(this).parents('.parent_tr').find('.price').text(current_price*qty);
          }
        }
        var total_quantity = SumTotal('.collapse.show .stock_qty');
        $('.collapse.show').find('.total_quantity').text(total_quantity);

        var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
        var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
        $('.collapse.show').find('.total_amount').text(total_amount.toFixed(2));

        $('.all_quantity').text(SumAllTotal('.total_quantity'));
        $('.all_amount').text(SumAllTotal('.total_amount'));

        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        overallCalculation(all_amount,tax_rate);
        currencyCalculation();
      });

      $(document).on('keyup', '.rfq_price', function(event) {
        if(/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
          var current_price  = $(this).val();
          var qty            = $(this).parents('.parent_tr').find('.stock_qty').val();
          var discountType   = $(this).parents('.parent_tr').parent().find('.discount-type').find('input[type=radio]:checked').val();
          var discount     = $(this).parents('.parent_tr').find('.discount-value').val();
          var dis_price = current_price;
          if(discountType=='amount'){
            dis_price = current_price-discount;
          }else if(discountType=='percentage'){
            dis_price = (current_price - (current_price * discount/100));
          }
          var subTotal = singeSubtotal(qty,dis_price);
          if(qty==0||qty==''){
            $(this).parents('.parent_tr').find('.dis-price').val(0);
            $(this).parents('.parent_tr').find('.dis-price').text(0);
          }else{
            $(this).parents('.parent_tr').find('.dis-price').val(dis_price);
            $(this).parents('.parent_tr').find('.dis-price').text(dis_price);
          }
          $(this).parents('.parent_tr').find('.price').val(current_price*qty);
          $(this).parents('.parent_tr').find('.price').text(current_price*qty);
          $(this).parents('.parent_tr').find('.sub_total').text(subTotal.toFixed(2));
          $(this).parents('.parent_tr').find('.subtotal_hidden').val(subTotal);
            
          var total_quantity = SumTotal('.collapse.show .stock_qty');
          $('.collapse.show').find('.total_quantity').text(total_quantity);

          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total_amount').text(total_amount.toFixed(2));

          $('.all_quantity').text(SumAllTotal('.total_quantity'));
          $('.all_amount').text(SumAllTotal('.total_amount'));

          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate);
          currencyCalculation();
      });

      
      $(document).on('click','.dis-type',function() {
        if(!confirm("Are you sure want to change discount type.?")){
          return false;
        }else{
          $('.collapse.show').find('.price').val(0);
          $('.collapse.show').find('.price').text(0);
          $('.collapse.show').find('.stock_qty').val(0);
          $('.collapse.show').find('.discount-value').val(0);
          $('.collapse.show').find('.dis-price').val(0);
          $('.collapse.show').find('.dis-price').text(0);
          $('.collapse.show').find('.sub_total').text('0.00');
          $('.collapse.show').find('.subtotal_hidden').val(0);
          $('.collapse.show').find('.total_quantity').text(0);
          $('.collapse.show').find('.total_amount').text('0.00');
          $('.all_quantity').text(SumAllTotal('.total_quantity'));
          $('.all_amount').text(SumAllTotal('.total_amount'));
          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate);
          currencyCalculation();
        }
      });

      $(document).on('keyup', '.discount-value', function(event) {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
        else{
          /*if($(this).val()==''){
            $(this).val(0);
          }*/
          var discountType = $(this).parents('.parent_tr').parent().find('.discount-type').find('input[type=radio]:checked').val();
          var price = $(this).parents('.parent_tr').find('.rfq_price').val();
          var discount = $(this).val();
          var final_amount = price;
          var qty = $(this).parents('.parent_tr').find('.stock_qty').val();
          if(discountType=='amount'){
            final_amount = price-discount;
          }else if(discountType=='percentage'){
            final_amount = (price - (price * discount/100));
          }
          var subTotal = singeSubtotal(qty,final_amount);
          $(this).parents('.parent_tr').find('.dis-price').val(final_amount);
          $(this).parents('.parent_tr').find('.dis-price').text(final_amount);
          $(this).parents('.parent_tr').find('.sub_total').text(subTotal.toFixed(2));
          $(this).parents('.parent_tr').find('.subtotal_hidden').val(subTotal);

          $(this).parents('.parent_tr').find('.price').val(price*qty);
          $(this).parents('.parent_tr').find('.price').text(price*qty);
          $(this).parents('.parent_tr').find('.sub_total').text(subTotal.toFixed(2));
          $(this).parents('.parent_tr').find('.subtotal_hidden').val(subTotal);
            
          var total_quantity = SumTotal('.collapse.show .stock_qty');
          $('.collapse.show').find('.total_quantity').text(total_quantity);

          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total_amount').text(total_amount.toFixed(2));

          $('.all_quantity').text(SumAllTotal('.total_quantity'));
          $('.all_amount').text(SumAllTotal('.total_amount'));

          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate);
          currencyCalculation();
        }
      });

      $(document).on('keyup', '.stock_qty', function(event) {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
        else{
          /*if($(this).val()==''){
            $(this).val(0);
          }*/
          var qty          = $(this).val();
          var real_price   = $(this).parents('.parent_tr').find('.rfq_price').val();
          var price        = qty*real_price;
          var discountType = $(this).parents('.parent_tr').parent().find('.discount-type').find('input[type=radio]:checked').val();
          var discount     = $(this).parents('.parent_tr').find('.discount-value').val();
          var dis_price=real_price;
          if(discountType=='amount'){
            dis_price = real_price-discount;
          }else if(discountType=='percentage'){
            dis_price = (real_price - (real_price * discount/100));
          }
          var subTotal = singeSubtotal(qty,dis_price);
          if(qty==0||qty==''){
            $(this).parents('.parent_tr').find('.dis-price').val(0);
            $(this).parents('.parent_tr').find('.dis-price').text(0);
          }else{
            $(this).parents('.parent_tr').find('.dis-price').val(dis_price);
            $(this).parents('.parent_tr').find('.dis-price').text(dis_price);
          }
          $(this).parents('.parent_tr').find('.price').val(price);
          $(this).parents('.parent_tr').find('.price').text(price);
          $(this).parents('.parent_tr').find('.sub_total').text(subTotal.toFixed(2));
          $(this).parents('.parent_tr').find('.subtotal_hidden').val(subTotal);
            
          var total_quantity = SumTotal('.collapse.show .stock_qty');
          $('.collapse.show').find('.total_quantity').text(total_quantity);

          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total_amount').text(total_amount.toFixed(2));

          $('.all_quantity').text(SumAllTotal('.total_quantity'));
          $('.all_amount').text(SumAllTotal('.total_amount'));

          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          var free_del_amount=$('.free_delivery_amount').val();
          var del_fees = $('#delivery-methods option:selected').attr('attr-fee');
          var del_type = $('#delivery-methods option:selected').val();

          if ( parseInt(all_amount) >= parseInt(free_del_amount)) {
            $('#delivery-methods option[value="3"]').show();
          }
          else{
            $('#delivery-methods').prop('selectedIndex',0);
            $('#delivery-methods option[value="3"]').hide();
          }

          if (del_fees!=0) {
            $('#deliveryCharge').text(del_fees);
          }else{
            $('#deliveryCharge').text('0.00');
          }
          overallCalculation(all_amount,tax_rate);
          currencyCalculation();
        }
      });


      $(document).ready(function() {
        $('#delivery-methods option[value="3"]').hide();
        var del_fees = $('#delivery-methods option:selected').attr('attr-fee');
        $('#deliveryCharge').text(del_fees);
      });

      $(document).on('change','#delivery-methods', function(event) {
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        var free_del_amount=$('.free_delivery_amount').val();
        var del_fees = $('#delivery-methods option:selected').attr('attr-fee');
        var del_type = $('#delivery-methods option:selected').val();
        
        if (del_fees!=0) {
          $('#deliveryCharge').text(del_fees);
        }else{
          $('#deliveryCharge').text('0.00');
        }
        overallCalculation(all_amount,tax_rate);
        currencyCalculation();
      });

      $(document).on('change', '#order_tax', function() {
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', this).attr("tax-rate");
        overallCalculation(all_amount,tax_rate);
        currencyCalculation();
      });


      $(document).on('change', '#currency_rate', function() {
        currencyCalculation();
      });

      $('.dis-price').on('keypress',function(e){
        if(e.which === 8 && $.inArray(e.target.tagName, inputTags) === -1)
        e.preventDefault();
      });

      function singeSubtotal(qty,final_price){
        var subTotal = final_price*qty;
        return subTotal;
      }

      function SumAllTotal(class_name) {
        var sum = 0;
        $(class_name).each(function(){
          var inputVal = '';
          if($(this).text()==''){
            inputVal = parseFloat(0);
          }else{
            inputVal = $(this).text();
          }
          sum += parseFloat(inputVal);
        });
        return sum;
      }

      function SumTotal(class_name) {
        var sum = 0;
        $(class_name).each(function(){
          var inputVal = '';
          if(this.value==''){
            inputVal = parseFloat(0);
          }else{
            inputVal = this.value;
          }
          sum += parseFloat(inputVal);
        });
        return sum;
      }

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
                current_node.find('.rfq_price').val(response.price);
                current_node.find('.last_rfq_price_val').val(response.price);
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

      function overallCalculation(all_amount,tax_rate){
        var tax = tax_rate/100;
        var calculatTax = tax*all_amount;
        var taxAmount = calculatTax.toFixed(2);
        var deliveryCharge = $('#deliveryCharge').text();
        $('#orderTax').text(taxAmount);
        var calculateSGD = parseFloat(all_amount)+parseFloat(taxAmount)+parseFloat(deliveryCharge);
        var totalSGD = parseFloat(calculateSGD);
        $('#total_amount_sgd').text(totalSGD.toFixed(2));
        $('#total_amount_hidden').val(all_amount);
        $('#order_tax_amount_hidden').val(taxAmount);
        $('#sgd_total_amount_hidden').val(totalSGD);
        $('.del_charge_hidden').val(deliveryCharge);
      }      
    
      function currencyCalculation() {
        var currency = $('option:selected', '#currency_rate').attr("currency-rate");
        var all_amount = $('#total_amount_sgd').text();
        var totalExchangeRate = all_amount*currency;
        $('#toatl_exchange_rate').val(totalExchangeRate.toFixed(2));
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
        if($('#sgd_total_amount_hidden').val()==""||$('#sgd_total_amount_hidden').val()==0)
        {
          alert('Please enter minimum Quantity');
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