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
                        <label for="order_no">RFQ No *</label>
                        {!! Form::text('order_no',null,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Status *</label>
                        {!!Form::select('status',$order_status, null,['class'=>'form-control no-search select2bs4'])!!}
                      </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="customer_id">Customer *</label>
                        {!! Form::select('customer_id',$customers, null,['class'=>'form-control select2bs4','id'=>'customer']) !!}
                      </div>
                      <div class="col-sm-4">                        
                        <label for="sales_rep_id">Sales Rep *</label>
                        {!! Form::select('sales_rep_id',$sales_rep, null,['class'=>'form-control select2bs4']) !!}
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <label for="product">Products *</label>
                        {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec','readonly']) !!}
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
                                  <span class="all_amount">{{ $total_products->sub_total }}</span>
                              </th>
  						              </tr>
  						            </thead>
						              <tbody>
                            @foreach ($product_datas as $product)
            						      <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
              								  <td class="expand-button"></td>
                                <?php
                                $total_based_products=\App\Models\RFQProducts::TotalDatas($rfqs->id,$product['product_id']);
                                 ?>
                								<td>{{ $product['product_name'] }}</td>
                								<td>Quantity: &nbsp;<span class="total_quantity">{{ $total_based_products->quantity }}</span></td>
                								{{-- <th>Price: {{ $total_based_products->rfq_price }}</th> --}}
                                <td class="total-head">Total: &nbsp;<span class="total">{{ $total_based_products->sub_total }}</span></td>
              							  </tr>
            								  <tr class="hide-table-padding">
								                <td></td>
								                <td colspan="4">
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
                                              <input type="hidden" name="variant[id][]" value="{{$variant['variant_id']}}">
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
                                            <td>
                                              <?php $high_value=$variation_details['rfq_price']; ?>
                                              <input type="text" name="variant[rfq_price][]" class="form-control rfq_price" value="{{ $high_value }}">
                                            </td>
                                            <td>
                                              <div class="form-group">
                                                <?php $quantity=1 ?>
                                                <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" value="{{ $variation_details['quantity'] }}">
                                              </div>
                                            </td>  
                                            <td>
                                              <div class="form-group">
                                                <span class="sub_total">{{ $variation_details['sub_total'] }}</span>
                                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{$variation_details['sub_total']}}">
                                              </div>
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$variation_details['sub_total']; ?>
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
                              <td colspan="3" class="title">Total</td><td>0.00</td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Order Discount</td><td>0.00</td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Order Tax</td><td>0.00</td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Total Amount(SGD)</td><td>0.00</td>
                            </tr>
                            <!-- <tr class="total-calculation">
                              <td colspan="3" class="title">Total Amount(USD)</td><td>0.00</td>
                            </tr> -->
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
                        <select class="form-control no-search select2bs4" name="order_tax">
                          @foreach($taxes as $tax)
                            <option value="{{$tax->id}}" @if($tax->id==$rfqs->order_tax)  selected="selected" @endif {{ (collect(old('order_tax'))->contains($tax->id)) ? 'selected':'' }}>
                              {{$tax->name}} 
                              @if($tax->tax_type=='p') @  {{round($tax->rate,2)}}% 
                              @elseif($tax->name=='No Tax') 
                              @else @  {{number_format((float)$tax->rate,2,'.','')}} 
                              @endif
                            </option>
                          @endforeach
                        </select>

                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_date">Order Discount</label>
                        {!! Form::text('order_discount', $rfqs->order_discount,['class'=>'form-control']) !!}
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

  $('.rfq-form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
      e.preventDefault();
      return false;
    }
  });

  </script>
@endpush
 @endsection