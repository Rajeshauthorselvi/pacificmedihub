@extends('admin.layouts.master')
@section('main_container')
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
              <li class="breadcrumb-item"><a href="{{route('new-orders.index')}}">Purchase</a></li>
              <li class="breadcrumb-item active">Purchase</li>
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
          <a href="{{url('admin/order-low-stock/'.$order_id)}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Purchase</h3>
              </div>
              <div class="card-body">
                <form action="{{route('purchase.store')}}" method="post" class="purchase-form">
                  @csrf
                  <div class="date-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Date *</label>
                        {!! Form::text('purchase_date', date('d-m-Y'),['class'=>'form-control','readonly'=>true]) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Purchase Code *</label>
                        {!! Form::text('purchase_order_number',$purchase_code,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Status *</label>
                        {!! Form::select('purchase_status',$order_status, 1,['class'=>'form-control read-only select2bs4']) !!}
                        {!! Form::hidden('purchase_status',1,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="product-sec">
                    <div class="form-group">
                      <div class="col-sm-8">
                        <label for="prodct-add-sec">Products *</label>
                        {!! Form::text('product',null, ['class'=>'form-control','id'=>'prodct-add-sec']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="vendorId">Vendor *</label>
                        
                        {!! Form::select('vendor_id',$vendors,array_key_last($vendors),['class'=>'form-control select2bs4','id'=>'vendorId']) !!}
                        <span class="text-danger vendor" style="display:none;">Status is required. Please Select</span>
                      </div>
                    </div>
                  </div>

                  <div class="order-item-sec">
                    <div class="container my-4">
                      <div class="table-responsive">
                        <table class="table">
                          <thead class="heading-top">
                            
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>
                              <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity"></span>   
                              </th>
                              <th>
                                  Total Amount:&nbsp;
                                  <span class="all_amount" id="allAmount"></span>
                              </th>
                              <th scope="col"></th>
                            </tr>
                          </thead>
                          <tbody class="parent_tbody">
                            @foreach ($purchase_products as $product)
                              <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                <td class="expand-button"></td>
                                
                                  <td>{{ $product['product_name'] }}</td>
                                  <td>
                                    Quantity:&nbsp;
                                    <span class="total-quantity"></span>
                                  </td>
                                  <td class="total-head">
                                    Total:&nbsp;
                                    <span class="total"></span>
                                  </td>
                                  <td>
                                    <a href="javascript:void(0)" class="btn btn-danger remove-product-row">
                                      <i class="fa fa-trash"></i>
                                    </a>
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
                                          <th>Quantity</th>
                                          <th>Subtotal</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $total_amount=$total_quantity=$final_price=0 ?>
                                        {{-- {{ dd($product['product_variant'],$product) }} --}}
                                        @foreach($product['product_variant'] as $key=>$variant)
                                        <?php $option_count=$product['option_count']; ?>
                                          
                                          <input type="hidden" name="variant[row_id][]" value="{{$variant['variant_id']}}">
                                          <input type="hidden" name="variant[product_id][]" value="{{ $product['product_id'] }}" class="product_id">
                                          <tr class="parent_tr">
                
                <input type="hidden" name="variant[variant_id][]" value="{{$variant['variant_id']}}">
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
                <td> {{$variant['base_price']}} </td>
                <td>
                  <input type="hidden" name="variant[base_price][]" class="base_price" value="{{$variant['base_price']}}">
                  <input type="hidden" name="variant[retail_price][]" value="{{$variant['retail_price']}}">
                  {{$variant['retail_price']}}
                </td>
                <td>
                  <input type="hidden" name="variant[minimum_selling_price][]" value="{{$variant['minimum_selling_price']}}">
                  {{$variant['minimum_selling_price']}}
                </td>
                <td>
                  <div class="form-group">
                    <?php $quantity=0 ?>
                    <input type="text" class="form-control stock_qty"  name="variant[stock_qty][]" value="0" remaining-quantity="{{ $balance_quantities[$product['product_id']][$variant['variant_id']] }}">
                  </div>
                </td>
                <td>
                  <div class="form-group">
                    <span class="sub_total">0</span>
                    <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="0">
                  </div>
                </td>
              </tr>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options'])+3 }}" class="text-right">Total:</td>
                                          <td><span class="total-quantity">0</span></td>
                                          <td><span class="total">0.00</span></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            @endforeach

                            <tr class="total-calculation first-calculation-tr">
                              <td colspan="3" class="title">Total</td>
                              <td><span class="all_amount">0.00</span></td>
                              <input type="hidden" name="total_amount" id="total_amount_hidden" value="">
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Order Discount</td>
                              <td><span class="order-discount">0.00</span></td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Order Tax</td>
                              <td id="orderTax">0.00</td>
                              <input type="hidden" name="order_tax_amount" id="order_tax_amount_hidden" value="">
                            </tr>
                            <tr class="total-calculation">
                              <th colspan="3" class="title">Total Amount(SGD)</th>
                              <th id="total_amount_sgd">0.00</th>
                              <input type="hidden" name="sgd_total_amount" id="sgd_total_amount_hidden" value="">
                            </tr>
                            <tr><td colspan="5"></td></tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="tax-sec">
                    <div class="form-group">
                      <div class="col-sm-3">
                        <label for="purchase_date">Order Tax</label>
                        <select class="form-control no-search select2bs4" name="order_tax" id="order_tax">
                          @foreach($taxes as $tax)
                            <option tax-rate="{{$tax->rate}}" value="{{$tax->id}}"  {{ (collect(old('order_tax'))->contains($tax->id)) ? 'selected':'' }}>
                              {{$tax->name}} 
                              @if($tax->name=='No Tax') 
                              @else @  {{round($tax->rate,2)}}% 
                              @endif
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-3">
                        <label for="order-discount">Order Discount</label>
                        {!! Form::text('order_discount', 0,['class'=>'form-control','id'=>'order-discount','autocomplete'=>'off','onkeyup'=>'validateNum(event,this);']) !!}
                      </div>
                      <div class="col-sm-3">
                        <label for="purchase_date">Payment Term</label>
                        {!! Form::select('payment_term',$payment_terms,null,['class'=>'form-control no-search select2bs4']) !!}
                      </div>
                      <div class="col-sm-3">
                        <label for="payment_status">Payment Status *</label>
                        <?php $payment_status=[''=>'Please Select',1=>'Paid',2=>'Partly Paid',3=>'Not Paid']; ?>
                        {!! Form::select('payment_status',$payment_status,3,['class'=>'form-control no-search select2bs4','id'=>'payment_status']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="panel panel-default payment-note-sec">
                    <div class="panel-body" style="display: none ">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="purchase_date">Payment Reference Number</label>
                          {!! Form::text('payment_reference_no', null,['class'=>'form-control']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="purchase_date">Amount</label>
                          {!! Form::text('amount', null,['class'=>'form-control']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="purchase_date">Paying By</label>
                          {!! Form::select('paying_by', $payment_method, null,['class'=>'form-control select2bs4']) !!}
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
                      {!! Form::textarea('note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                    </div>
                  </div>
                  <div class="col-sm-12 submit-sec">
                    <a href="{{ route('new-orders.index') }}" class="btn  reset-btn">Cancel</a>
                    <button class="btn save-btn" type="submit">Save</button>
                  </div>
                </form>
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

      $(document).on('click', '.remove-product-row', function(event) {
        event.preventDefault();
        $(this).closest('tr').next('tr').remove();
        $(this).closest('tr').remove();

        var currenct_tr=$(this).parents('tr');
        var current_total_tr=currenct_tr.find('.total').text();
        var all_amount = $('#allAmount').text();
        var balance_amount=parseInt(all_amount)-parseInt(current_total_tr);

        var all_quantity = $('.all_quantity').text();
        var current_total_qn_tr=currenct_tr.find('.total-quantity').text();

        var balance_quantity=parseInt(all_quantity)-parseInt(current_total_qn_tr);

        $('.all_amount').text(balance_amount);
        $('.all_quantity').text(balance_quantity);
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        overallCalculation(balance_amount,tax_rate);
        
      });
      
      $(function ($) {
        $('.read-only.select2bs4').select2({
          disabled: true
        });
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });
      

      var path ="{{ url('admin/product-search') }}";
  
      $('#prodct-add-sec').autocomplete({
        source: function( request, response) {
          $.ajax({
            url: "{{ url('admin/product-search') }}",
            data: {
              name: request.term,
              product_search_type:'product',
              vendor_id:$('.vendors').val()
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
              product_id:ui.item.value,
              from:'edit'
            },
          })
          .done(function(response) {
            // $('.hide-table-padding:last').after(response);
            $('.first-calculation-tr').before(response);
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

        $('.collapse').on('hide.bs.collapse', function (e) {
          console.log('Collapse Alert' + e.currentTarget.id);
        })
      });
  
      $(document).on('click', '.remove-item', function(event) {
        $(this).parents('tr').remove();
      });

      $('.stock_qty').each(function(index, el) {
        var remain_quantity=$(this).attr('remaining-quantity');

        $(this).val(remain_quantity);
          var base=$(this).parents('.parent_tr');
          var base_price=base.find('.base_price').val();
          var total_price=base_price*$(this).val();
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);          

          var attr_id=$(this).parents('tbody').find('.collapse:first').attr('id');
          var attr=$(this).parents('tbody').find('.collapse:first');
          var total_quantity=SumTotal('.collapse:first .stock_qty');

          $('.collapse:first').find('.total-quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total-quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse:first').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));

          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate);

      });

      function StockQuantityKeyUp($this) {
          var base=$this.parents('.parent_tr');
          var base_price=base.find('.base_price').val();
          var total_price=base_price*$this.val();
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);          

          var attr_id=$this.parents('tbody').find('.collapse.show').attr('id');
          var attr=$this.parents('tbody').find('.collapse.show');
          var total_quantity=SumTotal('.collapse.show .stock_qty');

          $('.collapse.show').find('.total-quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total-quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));

          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate);
      }
      $(document).on('keyup', '.stock_qty', function(event) {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
        else{
            StockQuantityKeyUp($(this));
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
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        overallCalculation(all_amount,tax_rate);
      });

      $(document).on('change', '#order_tax', function() {
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', this).attr("tax-rate");
        overallCalculation(all_amount,tax_rate);
      });

      function overallCalculation(all_amount,tax_rate){
        var allAmount    = all_amount;
        var taxRate      = tax_rate;
        var tax          = taxRate/100;
        var calculatTax  = tax*allAmount;
        var taxAmount    = calculatTax.toFixed(2);
        var discount     = $('#order-discount').val();
        var calculateSGD = parseFloat(allAmount)+parseFloat(taxAmount);
        var totalSGD     = parseFloat(calculateSGD)-parseFloat(discount);


        $('#orderTax').text(taxAmount);
        $('#total_amount_sgd').text(totalSGD.toFixed(2));
        $('#total_amount_hidden').val(allAmount);
        $('#order_tax_amount_hidden').val(taxAmount);
        $('#sgd_total_amount_hidden').val(totalSGD);
      }

      $(document).on('change', '#payment_status', function() {
        var paymentStatus = $('option:selected', this).val();
        if((paymentStatus==1) || (paymentStatus==2)){
          $('.panel-body').show();
        }else{
          $('.panel-body').hide();
        }
      });

      $('.purchase-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });

      $(document).on('click', '.save-btn', function(event) {
        event.preventDefault();
         var check_length=$('.product_id').length;

         if (check_length==0) {
          alert('Please select products');
         }

        if(validate()!=false && check_length>0){
          $('.purchase-form').submit();
        }else{
          scroll_to();
          return false;
        }


      });

      function validate(){
        var valid=true;
        if ($("#vendorId").val()=="") {
          $("#vendorId").closest('.form-group').find('span.text-danger.vendor').show();
          valid = false;
        }else{
          $("#vendorId").closest('.form-group').find('span.text-danger.vendor').hide();
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
          scrollTop: $(".purchase-form").offset().top
        },1000);
      }

    </script>

  @endpush
@endsection