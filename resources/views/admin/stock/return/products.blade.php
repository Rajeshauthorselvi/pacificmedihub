<div class="col-sm-12 product-sec">
	<div class="col-sm-4">
		<div class="form-group">
			<label>Date</label>
			<input class="form-control" readonly value="{{ date('d-m-Y',strtotime($purchase->purchase_date)) }}">
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label>Vendor Name</label>
			<input class="form-control" value="{{ $vendor_name }}" readonly>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label>Return Status</label>
			{!! Form::select('return_status',$status,null,['class'=>'form-control']) !!}
		</div>
	</div>
</div>
<div class="clearfix"></div>
<div class="col-sm-12">

                  <div class="container my-4">
                    <div class="table-responsive">
                      <table class="table">
                          <thead>
                            <?php
                          $total_products=\App\Models\PurchaseProducts::TotalDatas($purchase->id);
                           ?>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>

                           {{--    <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity">{{ $total_products->quantity }}</span>   
                              </th>
                              <th>
                                  Total Amount:&nbsp;
                                  <span class="all_amount">{{ $total_products->sub_total }}</span>
                              </th> --}}
                              <th scope="col"></th>
                            </tr>
                           
                          </thead>
                        <tbody>
                           @foreach ($purchase_products as $product)
                           <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                            <td class="expand-button"></td>
                             <?php
                                  $total_based_products=\App\Models\PurchaseProducts::TotalDatas($purchase->id,$product['product_id']);
                               ?>
                              <td>{{ $product['product_name'] }}</td>
                             {{--  <td>
                                Quantity:&nbsp;
                                <span class="total-quantity">{{ $total_based_products->quantity }}</span>
                              </td>
                              <td>
                                Total:&nbsp;
                                <span class="total">{{ $total_based_products->sub_total }}</span>
                              </td> --}}
                          </tr>
                          <tr class="hide-table-padding">
                            <td></td>
                            <td colspan="5">
                              <div id="collapse{{ $product['product_id'] }}" class="collapse in p-3">

                      <table class="table table-bordered" style="width: 100%">
                        <thead>
							<th>No</th>
			                @foreach ($product['options'] as $option)
			                	<th>{{ $option }}</th>
			                @endforeach
							{{-- <th>Base Price</th> --}}
							{{-- <th>Retail Price</th> --}}
							{{-- <th>Minimum Price</th> --}}
							<th>Purchase Price</th>
							<th>Total Quantity</th>
							<th>Reason</th>
							<th>Return Quantity</th>
							<th>Subtotal</th>
						</thead>
                        <tbody>
                        <?php $total_amount=$total_quantity=$final_price=0 ?>
                        <?php $s_no=1; ?>

                       @foreach($product['product_variant'] as $key=>$variant)

                       <?php 
                         $option_count=$product['option_count'];
                         $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase->id);
                         $product_price=\App\Models\PurchaseProducts::ProductPrice($product['product_id'],$variant['variant_id']);
                       ?>
                        <input type="hidden" name="variant[row_id][]" value="{{ $variation_details->id }}">
                          <tr class="parent_tr">
                          	<td>{{ $key+1 }}</td>
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
                            {{-- <td> {{$variant['base_price']}} </td> --}}
                            {{-- <td> {{$variant['retail_price']}}</td> --}}
                            {{-- <td><span class="test"> {{$variant['minimum_selling_price']}} </span></td> --}}
                            <td>
                            	<input type="hidden" class="purchase_price" value="{{ $product_price }}">
                            	{{ $product_price }}
                            </td>
                            <td>{{ $variation_details['quantity'] }}</td>
                            <td>{{ $variation_details['reason'] }}</td>
                            <td>
                            	<?php $max_price=$product_price*$variation_details['issue_quantity']; ?>
                              	<input type="text" name="quantity[{{ $variation_details['id'] }}]" class="form-control return_quantity" value="{{ $variation_details['issue_quantity'] }}" max-count="{{ $variation_details['quantity'] }}" max-count="{{ $variation_details['quantity'] }}">
                              	<input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                              	<input type="hidden" name="sub_total[{{ $variation_details['id'] }}]" class="sub_total" value="{{ $product_price*$variation_details['issue_quantity'] }}">
                              	<input type="hidden" name="product_id" value="{{ $variant['product_id'] }}">
                              </td>
                              
                              <td>
                              	<span class="sub_total_text">
                              		{{ $max_price }}
                              	</span>
                              </td>
                          </tr>
                          <?php $total_amount +=$max_price; ?>
                          <?php $total_quantity +=$variation_details['issue_quantity']; ?>
                        @endforeach
                        <tr>
                          <td colspan="{{ count($product['options'])+4 }}" class="text-right">Total:</td>
                          <td><span class="total-quantity">{{ $total_quantity }}</span></td>
                          <td><span class="total-amount">{{ $total_amount }}</span></td>
                        </tr>
                      </tbody>
                      </table>

                              </div>
                            </td>
                          </tr>
                          <?php $s_no++; ?>
                           @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>

                  </div>
<div class="clearfix"></div>
<br>
<br>
<style type="text/css">
	.product-sec .col-sm-4{
		float: left;
	}
</style>

