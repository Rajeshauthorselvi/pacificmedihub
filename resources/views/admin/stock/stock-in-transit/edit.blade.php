@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock-In-Transit</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item active">Stock-In-Transit</li>
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
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('stock-in-transit.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                  <h3 class="card-title">Edit</h3>
              </div>
              <div class="card-body">
                  {{ Form::model($purchase,['method' => 'PATCH', 'route' =>['stock-in-transit.update',$purchase->id]]) }}
                  {!! Form::hidden('vendor_id',$purchase->vendor_id) !!}
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Date</label>
                          {!! Form::text('purchase_date', null,['class'=>'form-control','readonly'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Purchase Order Number</label>
                          {!! Form::text('purchase_order_number',null,['class'=>'form-control','readonly'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('purchase_status',$order_status, null,['class'=>'form-control no-search select2bs4 order_status']) !!}
                        </div>
                    </div>
                  </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Vendor *</label>
                          {!! Form::select('vendor_id',$vendors, null,['class'=>'form-control select2bs4','style'=>'pointer-events:none','readonly'=>true,'style'=>'pointer-events:none']) !!}
                        </div>
                    </div>
                  <div class="product-sec col-sm-12">
                    <label>Order Items</label>
  <div class="container my-4">
                    <div class="table-responsive">
                      <table class="table">
                        <tbody>
                           @foreach ($purchase_products as $product)
                           <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                            <td class="expand-button"></td>
                              <td>{{ $product['product_name'] }}</th>
                        
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
                            <th>Qty Ordered<br><small>(A)</small></th>
                            <th class="quantity-info">Qty Received<br><small>(B)</small></th>
                            <th class="quantity-info" width="22%">Damaged Quantity<br><small>(C)</small></th>
                            <th class="quantity-info">Missed Quantity<br><small>(A-B=D)</small></th>
                            <th class="quantity-info">Stock Quantity<br><small>(A-C-D)</small></th>
                            <th>Batch Id</th>
                            <th>Expiry Date</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $total_amount=$total_quantity=$final_price=0 ?>
                       @foreach($product['product_variant'] as $key=>$variant)
                       <?php 
                         $option_count=$product['option_count'];
                         $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase->id);
                         $return_total=\App\Models\PurchaseProducts::ReturnTotal($purchase->id,$variation_details->id);
                         $stock_total=\App\Models\PurchaseProducts::StockTotal($purchase->id,$variation_details->id);
                         $replace_total=\App\Models\PurchaseProducts::ReplaceTotal($purchase->id,$variation_details->id);

                       ?>
                        <input type="hidden" name="variant[row_id][]" value="{{ $variation_details->id }}">
                        <input type="hidden" name="variant[variant_id][]" value="{{ $variant['variant_id'] }}">
                        <input type="hidden" name="variant[product_id][]" value="{{ $product['product_id'] }}">
                          <tr class="parent_tr">
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
                            </td>
                            <td>
                              {{ $variation_details['quantity'] }}
                              <?php 
                              $received=isset($received_quantity[$variation_details->id])?$received_quantity[$variation_details->id]:0;
                               $balance_quantity=$variation_details['quantity'];
                               $balance_quantity=$balance_quantity-$stock_total;

                               ?>
                                <input type="hidden" class="total_quantity" value="{{ $balance_quantity }}">
                               <br>
                               @if ($received>0)
                                 <small>
                                   <a href="javascript:void(0)" class="show-history" purchase-variant-id="{{ $variation_details->id }}" purchase-id="{{ $purchase->id }}" product-id="{{ $product['product_id'] }}" product-variant="{{ $variant['variant_id'] }}">({{ $stock_total }} Received)</a>
                                 </small>
                               @endif
                            </td>
                            <td class="quantity-info">
                              <div class="form-group">
                                  <input type="text" name="variant[qty_received][]" value="0" class="form-control received_quantity" autocomplete="off">
                              </div>
                            </td>
                            <td class="quantity-info">
                              <div class="form-group">
                              {!! Form::hidden('base_price',$variation_details->base_price,['class'=>'base_price']) !!}
                                  <input type="text" name="variant[damaged_qty][]" value="{{ isset($variation_details['damage_quantity'])?$variation_details['damage_quantity']:0 }}" class="form-control damaged_quantity" autocomplete="off">
                              </div>
                              <div class="form-group">
                                <input type="radio" name="variant[goods_type][{{ $key }}]" value="2" id="goods_replace_{{ $key }}" class="goods_replace goods_type">
                                <label for="goods_replace_{{ $key }}"><small>Goods Replace</small></label>
                                &nbsp;
                                <input type="radio" name="variant[goods_type][{{ $key }}]" value="1" id="goods_return_{{ $key }}" class="goods_return goods_type">
                                <label for="goods_return_{{ $key }}"><small>Goods Return</small></label>
                              </div>

                              <input type="hidden" class="total_amount_hidden" value="">
                              
                            </td>
                            <td class="quantity-info">
                              <div class="form-group">
                                  <input type="text" name="variant[missed_qty][]" value="{{ isset($variation_details['missed_quantity'])?$variation_details['missed_quantity']:0 }}" class="form-control missed_quantity" readonly>
                              </div>
                            </td>
                            <td class="quantity-info">
                              <div class="form-group">
                                  <input type="text" name="variant[stock_quantity][]" value="0" class="form-control stock_quantity" readonly>
                              </div>
                            </td>
                            <?php 

                              $batch_details=\App\Models\PurchaseProducts::BatchInfo($purchase->id,$product['product_id'],$variant['variant_id']);

                            ?>
                            <td width="20%">
                              <input type="text" name="variant[batch_id][]" class="form-control" value="{{ isset($batch_details->batch_id)?$batch_details->batch_id:'' }}">
                            </td>
                            <td width="20%">
                              <input type="text" name="variant[expiry_date][]" class="form-control date-picker" value="{{ isset($batch_details->expiry_date)?date('d-m-Y',strtotime($batch_details->expiry_date)):'' }}">
                            </td>
                          </tr>
                        @endforeach
   
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
                  <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('stock_notes', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    <?php $tax_amount=isset($tax_details)?$tax_details->rate:0 ?>
                    {!! Form::hidden('tax_amount',$tax_amount,['id'=>'tax_amount']) !!}
                    {!! Form::hidden('order_discount',$purchase->order_discount,['id'=>'order-discount']) !!}


                    <input type="hidden" name="total_amount" id="total_amount">
                    <input type="hidden" name="order_tax_amount" id="order_tax">
                    <input type="hidden" name="sgd_total_amount" id="sgd_total">


                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('stock-in-transit.index') }}" class="btn  reset-btn">
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

<div class="modal fade" id="stock-history" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Stock History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
          <div class="modal-body stock-history"></div>
    </div>
  </div>
</div>
<style type="text/css">
  input[type="radio"] {
  vertical-align: middle;
}
</style>
  @push('custom-scripts')
    <script type="text/javascript">
      $('.date-picker').datepicker({dateFormat: 'dd-mm-yy'});
      $(function ($) {
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });


      $(document).on('click', '.show-history', function(event) {
        event.preventDefault();
        var purchase_variant_id=$(this).attr('purchase-variant-id');
        var product_variant=$(this).attr('product-variant');
        var purchase_id=$(this).attr('purchase-id');
        var product_id=$(this).attr('product-id');
        $.ajax({
          url: '{{ url('admin/purchase-stock-history') }}',
          type: 'POST',
          data: {
            '_token':"{{ csrf_token() }}",
            purchase_variant_id:purchase_variant_id,
            purchase_id:purchase_id,
            product_id:product_id,
            product_variant:product_variant
          }
        })
        .done(function(response) {
          $('.stock-history').html(response);
           $('#stock-history').modal('show');
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
      });

      @if ($purchase->purchase_status==2 || $purchase->purchase_status==4)
        $('.quantity-info').show();
      @else
        $('.quantity-info').hide();
      @endif
      $(document).on('change', '.order_status', function(event) {
        if ($(this).val()==2 || $(this).val()==4) {
          $('.quantity-info').show();
        }
        else{
          $('.quantity-info').hide();
        }
      });


      $(document).on('keyup', '.received_quantity', function(event) {
        event.preventDefault();
          var total_quantity=$(this).parents('.parent_tr').find('.total_quantity').val();
          var current_field_val=$(this).val();
          if ((current_field_val !== '') && (current_field_val.indexOf('.') === -1)) {
                var current_field_val=Math.max(Math.min(current_field_val, parseInt(total_quantity)), -90);
                $(this).val(current_field_val);
          }
          var missed_quantity=total_quantity-current_field_val;
          $(this).parents('.parent_tr').find('.missed_quantity').val(missed_quantity);

         var damaged_quantity=$(this).parents('.parent_tr').find('.damaged_quantity').val(); 

         var stock_quantity=current_field_val-damaged_quantity;
         $(this).parents('.parent_tr').find('.stock_quantity').val(stock_quantity); 
         

      });

      $(document).on('keyup', '.damaged_quantity', function(event) {

          var current_field_val=$(this).val();
          var total_received=$(this).parents('.parent_tr').find('.received_quantity').val();
          if ((current_field_val !== '') && (current_field_val.indexOf('.') === -1)) {
                var current_field_val=Math.max(Math.min(current_field_val, parseInt(total_received)), -90);
                $(this).val(current_field_val);
          }
          var received_quantity=$(this).parents('.parent_tr').find('.received_quantity').val(); 
          var stock_quantity=received_quantity-current_field_val;
         $(this).parents('.parent_tr').find('.stock_quantity').val(stock_quantity);

        /*Activete Goods Replace*/
          var check_goods_return =$(this).closest('.quantity-info').find('.goods_return').prop("checked");
          var check_goods_replace =$(this).closest('.quantity-info').find('.goods_replace').prop("checked");
          if (check_goods_return==false && check_goods_replace==false && current_field_val>0) {
            $(this).closest('.quantity-info').find('.goods_replace').prop('checked',true);
          }
          else if(current_field_val==0){
            $(this).closest('.quantity-info').find('.goods_replace').prop('checked',false);
            $(this).closest('.quantity-info').find('.goods_return').prop('checked',false);
            $(this).closest('.quantity-info').find('.total_amount_hidden').val(0);
          }
        /*Activete Goods Replace*/
        
        var goods_val=$(this).closest('.quantity-info').find('.goods_type:checked').val();
        if (goods_val==1) {
          var quantity = current_field_val;
          var base_price=$(this).prev('.base_price').val();
          var all_amount=parseInt(quantity)*parseInt(base_price);
          $(this).closest('.quantity-info').find('.total_amount_hidden').val(all_amount);
          
          SumAllValue();
        }
      });
      function SumAllValue() {
        var total_amount=0;
        $('.total_amount_hidden').each(function(i, obj) {
          if(!isNaN(this.value) && this.value.length!=0) 
            {
              total_amount += parseInt(this.value);            
            }      
        });
        var tax_rate=$('#tax_amount').val();
        overallCalculation(total_amount,tax_rate);
      }

      $(document).on('change', '.goods_type', function(event) {
        if ($(this).val()==1) {
          var tax_rate=$('#tax_amount').val();
          var base_price=$(this).parents().find('.base_price').val();
          var quantity=$(this).parents().find('.damaged_quantity').val();
          var all_amount=parseInt(quantity)*parseInt(base_price);
          $(this).closest('.quantity-info').find('.total_amount_hidden').val(all_amount);
          SumAllValue();
        }
        else{
            $(this).closest('.quantity-info').find('.total_amount_hidden').val(0);
             SumAllValue();
        }
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
        $('#orderTax').val(taxAmount);
        $('#total_amount').val(allAmount);
        $('#order_tax').val(taxAmount);
        $('#sgd_total').val(totalSGD);
      }

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