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
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">Purchase</a></li>
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
              	{!! Form::model($rfqs,['method' => 'PATCH', 'route' =>['rfq.update',$rfqs->id]]) !!}
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="date">Date *</label>
                          {!! Form::text('created_at',null,['class'=>'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="order_no">RFQ No *</label>
                          {!! Form::text('order_no',null,['class'=>'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('status',$order_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="product">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="customer_id">Customer *</label>
                          {!! Form::select('customer_id',$customers, null,['class'=>'form-control','id'=>'customer']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="sales_rep_id">Sales Rep *</label>
                          {!! Form::select('sales_rep_id',$sales_rep, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="product-append-sec">

						<div class="container my-4">
						 <hr>
						<div class="table-responsive">
						  <table class="table">
						    <thead>
                  <?php
                $total_products=\App\Models\RFQProducts::TotalDatas($rfqs->id);
                 ?>
						      <tr>
						        <th scope="col">#</th>
						        <th scope="col">Product Name</th>
						        <th scope="col">
                        Total Quantity:&nbsp;
                        <span class="all_quantity">{{ $total_products->quantity }}</span>   
                    </th>
						        <th scope="col">
                        Total RFQ Price:&nbsp;
                        <span class="all_rfq_price">{{ $total_products->rfq_price }}</span>  
                    </th>
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
								<td>{{ $product['product_name'] }}</th>
								<th>Quantity: {{ $total_based_products->quantity }}</th>
								<th>RFQ Price: {{ $total_based_products->rfq_price }}</th>
                <th>Total: {{ $total_based_products->sub_total }}</th>

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
                            <th>Quantity</th>
                            <th>RFQ Price</th>
                            <th>Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $total_amount=$total_quantity=0 ?>
   @foreach($product['product_variant'] as $key=>$variant)

   <?php 

   $option_count=$product['option_count'];

   $variation_details=\App\Models\RFQProducts::VariationPrice($product['product_id'],$variant['variant_id'],$product['rfq_id']);

   ?>

                          <tr class="parent_tr">
                            <td>
                            <input type="hidden" name="variant[row_id][]" value="{{ $variation_details->id }}">
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
                                <?php $quantity=1 ?>
                                <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" value="{{ $variation_details['quantity'] }}">
                              </div>
                            </td>
                            <td>
                              <?php $high_value=$variation_details['rfq_price']; ?>
                              <input type="text" name="variant[rfq_price][]" class="form-control rfq_price" value="{{ $high_value }}">
                            </td>
                            <td>
                              <div class="form-group">
                                <span class="sub_total">{{ $variation_details['sub_total'] }}</span>
                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $high_value }}">
                              </div>
                            </td>
                          </tr>
                          <?php $total_amount +=$variation_details['sub_total']; ?>
                          <?php $total_quantity +=$variation_details['quantity']; ?>
                        @endforeach
                        <tr>
                          <td colspan="{{ count($product['options'])+4 }}" class="text-right">Total:</td>
                          <td class="total_quantity">{{ $total_quantity }}</td>
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
                 <div class="clearfix"></div>
                  <div class="col-sm-12">
                     <div class="form-group">
                          <label for="sales_rep_id">Notes</label>
                        {!! Form::textarea('notes',null,['class'=>'form-control summernote']) !!}
                    </div>
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
    .date-sec .col-sm-4, .product-sec .col-sm-4 {
      float: left;
    }
tr.hide-table-padding>td {
  padding: 0;
}
	.expand-button {
	  position: relative;
	}

	.accordion-toggle .expand-button:after
	{
	  position: absolute;
	  left:.75rem;
	  top: 50%;
	  transform: translate(0, -50%);
	  content: '-';
	}
	.accordion-toggle.collapsed .expand-button:after
	{
	  content: '+';
	}
</style>
@push('custom-scripts')
  <script type="text/javascript">
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
  </script>
@endpush
 @endsection