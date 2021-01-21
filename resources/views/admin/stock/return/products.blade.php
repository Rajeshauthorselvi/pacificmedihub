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
</div>

<div class="col-sm-12">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>No</th>
				<th>Description</th>
                @foreach ($options as $option)
                	<th>{{ $option }}</th>
                @endforeach
				<th>Base Price</th>
				<th>Retail Price</th>
				<th>Minimum Price</th>
				<th>Sold Quantity</th>
				<th>Return Quantity</th>
				<th>Subtotal</th>
			</tr>
		</thead>
		<tbody>
			<?php $s_no=1; ?>
			@foreach ($product_datas as $product)
			<tr>
				<td>{{ $s_no }}</td>
				<td>{{ $product['product_name'] }}</td>
                              @if (isset($product['option_value_id1']))
                                <td>{{ $product['option_value_id1'] }}</td>
                              @endif
                              @if (isset($product['option_value_id2']))
                                <td>{{ $product['option_value_id2'] }}</td>
                              @endif
                              @if (isset($product['option_value_id3']))
                                <td>{{ $product['option_value_id3'] }}</td>
                              @endif
                              @if (isset($product['option_value_id4']))
                                <td>{{ $product['option_value_id4'] }}</td>
                              @endif
                              @if (isset($product['option_value_id5']))
                                <td>{{ $product['option_value_id5'] }}</td>
                              @endif
                              <td>
                              	{{ $product['base_price'] }}
                              	<input type="hidden" class="base_price" value="{{ $product['base_price'] }}">
                              </td>
                              <td>{{ $product['retail_price'] }}</td>
                              <td>{{ $product['minimum_selling_price'] }}</td>
                              <td>{{ $product['quantity'] }}</td>
                              <td>
                              	<input type="text" name="quantity[{{ $product['product_purchase_id'] }}]" class="form-control return_quantity" value="0">
                              	<input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                              	<input type="hidden" name="sub_total[{{ $product['product_purchase_id'] }}]" class="sub_total" value="0">
                              	<input type="hidden" name="product_id" value="{{ $product['product_id'] }}">
                              </td>
                              <td class="total-text">0</td>
			</tr>
			<?php $s_no++; ?>
			@endforeach
		</tbody>
	</table>
</div>
<div class="col-sm-12">
	<div class="col-sm-6 pull-left">
		<label>Return Note</label>
		<textarea class="form-control summernote"></textarea>
	</div>
	<div class="col-sm-6 pull-left">
		<label>Staff Note</label>
		<textarea class="form-control summernote"></textarea>
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

