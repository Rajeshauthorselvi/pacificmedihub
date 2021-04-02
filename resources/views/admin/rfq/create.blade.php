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
            <h1 class="m-0">Add RFQ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">RFQ</a></li>
              <li class="breadcrumb-item active">Add RFQ</li>
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
                <h3 class="card-title">Add RFQ</h3>
              </div>
              <div class="card-body">
                {!! Form::open(['route'=>'rfq.store','method'=>'POST','class'=>'rfq-form' ,'id'=>'form-validate']) !!}
                  <div class="date-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="date">Date *</label>
                        <input type="text" class="form-control" name="created_at" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                      </div>
                      <div class="col-sm-4">
                        <label for="order_no">RFQ Code *</label>
                        {!! Form::text('order_no',$rfq_id,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="rfqStatus">Status *</label>
                        {!! Form::select('status',$order_status, null,['class'=>'form-control no-search select2bs4','id'=>'rfqStatus']) !!}
                        <span class="text-danger rfq" style="display:none;">Status is required. Please Select</span>
                      </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="customer_id">Customer *</label>
                        <select class="form-control select2bs4" name="customer_id" id="customer">
                          <option value="">Please Select</option>
                            @foreach ($customers as $customer)
                              <option value="{{ $customer['id'] }}" sales-rep="{{ $customer['sales_rep'] }}" address="{{ $customer['address_id'] }}">
                                {{ $customer['name'] }}
                              </option>
                            @endforeach
                        </select>
                        <span class="text-danger customer" style="display:none;">Customer is required. Please Select</span>
                      </div>
                      <div class="col-sm-8">
                        <label for="deliveryAddress">Delivery Address *</label>
                        <select class="form-control no-search select2bs4" id="deliveryAddress" name="del_add_id"></select>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="sales_rep_id">Sales Rep *</label>
                        {!! Form::select('sales_rep_id',$sales_rep, null,['class'=>'form-control select2bs4','id'=>'sales_rep_id']) !!}
                        <span class="text-danger sales_rep" style="display:none;">Sales Rep is required. Please Select</span>
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_date">Payment Term</label>
                        {!! Form::select('payment_term',$payment_terms,null,['class'=>'form-control no-search select2bs4']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="currency_rate">Currency</label>
                        <select class="form-control no-search select2bs4" name="currency" id="currency_rate">
                          @foreach($currencies as $currency)
                            <option currency-rate="{{$currency->exchange_rate}}" currency-code="{{$currency->currency_code}}" value="{{$currency->id}}" @if($currency->is_primary==1)  selected="selected" @endif {{ (collect(old('currency'))->contains($currency->id)) ? 'selected':'' }}>
                              {{$currency->currency_code}} - {{$currency->currency_name}}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <label for="product">Products *</label>
                        {!!Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec'])!!}
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="product-append-sec"></div>
                  <div class="clearfix"></div>
                  <hr>

                  <div class="tax-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="purchase_date">Delivery Methods *</label>
                        {!! Form::hidden('free_delivery_amount',$free_delivery_target,['class'=>'free_delivery_amount']) !!}
                        {!! Form::hidden('delivery_charge',$free_delivery,['class'=>'del_charge_hidden']) !!}
                        <select class="form-control no-search " id="delivery-methods" name="delivery_method_id">
                          @foreach($delivery_methods as $method)
                            <option value="{{ $method->id }}" attr-fee="{{ $method->amount }}" attr-target="{{ $method->target_amount }}">
                              {{ $method->delivery_method }}
                            </option>
                          @endforeach
                        </select>
                        <span class="text-danger delivery_method" style="display:none;">Delivery Method is required. Please Select</span>
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_date">Order Tax</label>
                        <select class="form-control no-search select2bs4" name="order_tax" id="order_tax">
                          @foreach($taxes as $tax)
                            <option tax-rate="{{$tax->rate}}" value="{{$tax->id}}" @if($tax->name=='No Tax')  selected="selected" @endif {{ (collect(old('order_tax'))->contains($tax->id)) ? 'selected':'' }}>
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
                        {!! Form::text('order_discount', 0,['class'=>'form-control','id'=>'order-discount','autocomplete'=>'off','onkeyup'=>'validateNum(event,this);']) !!}
                      </div>
                    </div>
                  </div>

                  <div>
                    <input type="hidden" name="total_amount" id="total_amount_hidden">
                    <input type="hidden" name="order_tax_amount" id="order_tax_amount_hidden">
                    <input type="hidden" name="sgd_total_amount" id="sgd_total_amount_hidden">
                  </div>

                  <div class="col-sm-12">
                    <label for="sales_rep_id">Notes</label>
                    {!! Form::textarea('notes',null,['class'=>'form-control summernote']) !!}
                  </div>
                  <div class="clearfix"></div> 
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
      $(document).ready(function() {
        $('#delivery-methods option[value="3"]').hide();
      });
      $(document).on('change','#delivery-methods', function(event) {
          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
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
          overallCalculation(all_amount,tax_rate,currency);
      });
      $(document).on('change', '#customer', function(event) {
        var sales_rep = $('#customer option:selected').attr('sales-rep');
        var customerId = $('#customer option:selected').val();
        var addressId = $('#customer option:selected').attr('address');
        if (sales_rep) {
          $('#sales_rep_id').val(sales_rep).change();
        }
        else{
          $('#sales_rep_id').val('').change();
        }
        $('#deliveryAddress').removeAttr('readonly');
          $.ajax({
            url: "{{ url('admin/get-delivery-address') }}/"+customerId,
            type:'GET',
            success:function(data){
              if(data){
                $("#deliveryAddress").empty();
                $.each(data,function(key,value){
                  var select_address="";
                  if(addressId == key) { var select_address = "selected" }
                  $("#deliveryAddress").append('<option value="'+key+'" '+select_address+'>'+value+'</option>');
                });
                $('#deliveryAddress').selectpicker('refresh');           
              }else{
                $("#deliveryAddress").empty();
              }
            }
          });
        

        if ($('.vatiant_table').length!=0) {
          ExistingRFQPrice();
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

      $(document).on('click', '.remove-item', function(event) {
        $(this).parents('.parent_tr').remove();
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
              product_id:ui.item.value
            },
          })
          .done(function(response) {
            if ($('.vatiant_table').length==0) {
              var currencyCode = $('option:selected', '#currency_rate').attr("currency-code");
              createTable(currencyCode);
              if(currencyCode!='SGD'){
                $('#total_exchange').show();
              }
            }
            $('.parent_tbody').append(response);

            $('.all_quantity').text(SumTotal('.stock_qty'));
            $('.all_rfq_price').text(SumTotal('.rfq_price'));
            $('.all_amount').text(SumTotal('.subtotal_hidden'));

            var sum_inside_total=$('#collapse'+ui.item.value+' .stock_qty');
            $('.quantity_'+ui.item.value).text(SumTotal('#collapse'+ui.item.value+' .stock_qty'));
            $('.total_'+ui.item.value).text(SumTotal('#collapse'+ui.item.value+' .subtotal_hidden'));
            $('.rfq_'+ui.item.value).text(SumTotal('#collapse'+ui.item.value+' .rfq_price'));

            ExistingRFQPrice();

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
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount.toFixed(2));
          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden').toFixed(2));

          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
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
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount.toFixed(2));

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden').toFixed(2));

          var currency = $('option:selected', '#currency_rate').attr("currency-rate");
          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate,currency);
        }
      });

      $(document).on('keyup', '.rfq_price', function(event) {
        $('.all_rfq_price').text(SumTotal('.rfq_price').toFixed(2));
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
        var deliveryCharge = $('#deliveryCharge').text();
        $('#orderTax').text(taxAmount);
        var calculateSGD = parseFloat(allAmount)+parseFloat(taxAmount)+parseFloat(deliveryCharge);
        var totalSGD = parseFloat(calculateSGD)-parseFloat(discount);
        $('#total_amount_sgd').text(totalSGD.toFixed(2));
        var totalExchangeRate = totalSGD*currencyRate;
        $('#toatl_exchange_rate').val(totalExchangeRate.toFixed(2));

        $('#total_amount_hidden').val(allAmount);
        $('#order_tax_amount_hidden').val(taxAmount);
        $('#sgd_total_amount_hidden').val(totalSGD);
        $('.del_charge_hidden').val(deliveryCharge);
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
            // data +='<th>Total Price:&nbsp;<span class="all_rfq_price"></span></th>';
            data +='<th>Total Amount:&nbsp<span class="all_amount" id="allAmount"></span></th>'; 
            data +='<th></th>'
            data +='</tr>';
            data +='</thead>';
            data +='<tbody class="parent_tbody">';
            data +='</tbody>';
            data +='<tr class="total-calculation"><td colspan="3" class="title">Total</td>';
            data +='<td colspan="2"><span class="all_amount"></span></td></tr>';
            data +='<tr class="total-calculation"><td colspan="3" class="title">Order Discount</td>';
            data +='<td colspan="2"><span class="order-discount">0.00</span></td></tr>';
            data +='<tr class="total-calculation"><td colspan="3" class="title">Order Tax</td>';
            data +='<td colspan="2" id="orderTax">0.00</td></tr>';
            data +='<tr class="total-calculation"><td colspan="3" class="title">Delivery Charge</td>';
            data +='<td colspan="2" id="deliveryCharge">0.00</td></tr>';
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
  
      $('.rfq-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });

      $(document).on('click', '.save-btn', function(event) {
        
        var check_variants_exists=$('.vatiant_table').length;
        if (check_variants_exists==0) {
          alert('Product is required. Please Select');
          event.preventDefault();
        }

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
        if($('#total_amount_hidden').val()==""||$('#total_amount_hidden').val()==0)
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