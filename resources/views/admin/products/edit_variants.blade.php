<div class="table-responsive">
	@foreach($removed_vendors_id as $removed)
		<input type="hidden" name="variant[removed_vendor][]" value="{{$removed}}">
	@endforeach
<table class="list" id="variantList">
	<thead>
		<tr>
			<th class="vendor-name">Vendor</th>
			@foreach($options as $option)
			<th class="option-head">
				{{$option->option_name}}
			</th>
			@endforeach
			<th class="variant-sku">SKU</th>
			<th class="input-box">Base Price</th>
			<th class="input-box">Retail Price</th>
			<th class="input-box">Minimum Selling Price</th>
			<th class="input-box">Stock Qty</th>
			<th class="input-box">Order By</th>
			<th class="input-box">Display</th>
		</tr>
	</thead>
	<tbody>
	<?php $count=1;?>
	@foreach($option_values as $key => $option_value)
		<?php
			$vendor_id = $vendors[$key]->id;
			$vendor_name = $vendors[$key]->name;
			$vendor_code = str_replace('VEN','', $vendors[$key]->code);
			$vendor_code = str_replace(date('Y'),'', $vendor_code);
		?>
		@foreach($option_value as $k => $options)
			<?php 
				
				$sku_prefix = $product_code.'-'.$vendor_code;

				$option_id1 = $options->optionID1;
				$option_value_id1 = $options->optionValueID1;
				$option1 = $options->optionValue1;
					
				$sku = $sku_prefix.'-'.$options->optionValueCode1;

				if($option_count==2||$option_count==3||$option_count==4||$option_count==5){
					$option_id2 = $options->optionID2;
					$option_value_id2 = $options->optionValueID2;
					$option2 = $options->optionValue2;	

					$sku = $sku_prefix.'-'.$options->optionValueCode1.'-'.$options->optionValueCode2;
				}
				if($option_count==3||$option_count==4||$option_count==5){
					$option_id3 = $options->optionID3;
					$option_value_id3 = $options->optionValueID3;
					$option3 = $options->optionValue3;	

					$sku = $sku_prefix.'-'.$options->optionValueCode1.'-'.$options->optionValueCode2.'-'.$options->optionValueCode3;
				}
				if($option_count==4||$option_count==5){
					$option_id4 = $options->optionID4;
					$option_value_id4 = $options->optionValueID4;
					$option4 = $options->optionValue4;	

					$sku = $sku_prefix.'-'.$options->optionValueCode1.'-'.$options->optionValueCode2.'-'.$options->optionValueCode3.'-'.$options->optionValueCode4;
				}
				if($option_count==5){
					$option_id5 = $options->optionID5;
					$option_value_id5 = $options->optionValueID5;
					$option5 = $options->optionValue5;	

					$sku = $sku_prefix.'-'.$options->optionValueCode1.'-'.$options->optionValueCode2.'-'.$options->optionValueCode3.'-'.$options->optionValueCode4.'-'.$options->optionValueCode5;
				}

				if($count==1){
					$base_price_row_id = 'base_price_row1';
					$retail_price_row_id = 'retail_price_row1';
					$minimum_price_row_id = 'minimum_price_row1';
					$stock_qty_row_id = 'stock_qty_row1';
				}else{
							$base_price_row_id 	  = 'base_price_row child_base_price';
							$retail_price_row_id  = 'retail_price_row child_retail_price';
							$minimum_price_row_id = 'minimum_price_row child_minimum_price';
							$stock_qty_row_id     = 'stock_qty_row child_stock_qty';
				}
			?>
			<tr>
				@if($data_from=="edit")
					<input type="hidden" name="new_variant[{{ $vendor_id }}][id][]" value="0">
				@endif
				<td>
                	<input type="hidden" name="new_variant[{{ $vendor_id }}][vendor_id][]" class="vendor_id" value="{{$vendor_id}}">
					{{$vendor_name}}
                </td>
				<td>
					<input type="hidden" name="new_variant[{{ $vendor_id }}][option_id1][]" value="{{$option_id1}}">
					<input type="hidden" name="new_variant[{{ $vendor_id }}][option_value_id1][]" value="{{$option_value_id1}}">
					{{$option1}}
				</td>
				@if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
					<td>
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_id2][]" value="{{$option_id2}}">
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_value_id2][]" value="{{$option_value_id2}}">
						{{$option2}}
					</td>
				@endif
				@if($option_count==3||$option_count==4||$option_count==5)
					<td>
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_id3][]" value="{{$option_id3}}">
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_value_id3][]" value="{{$option_value_id3}}">
						{{$option3}}
					</td>
				@endif
				@if($option_count==4||$option_count==5)
					<td>
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_id4][]" value="{{$option_id4}}">
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_value_id4][]" value="{{$option_value_id4}}">
						{{$option4}}
					</td>
				@endif
				@if($option_count==5)
					<td>
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_id5][]" value="{{$option_id5}}">
						<input type="hidden" name="new_variant[{{ $vendor_id }}][option_value_id5][]" value="{{$option_value_id5}}">
						{{$option5}}
					</td>
				@endif
				<td>
					<input type="hidden" name="new_variant[{{ $vendor_id }}][sku][]" value="{{ $sku }}">
					{{ $sku }}
				</td>
				<td>
					<div class="form-group">
                        <input type="text" class="form-control base_price_row {{$base_price_row_id}}" id="{{$base_price_row_id}}" onkeyup="validateNum(event,this);" name="new_variant[{{ $vendor_id }}][base_price][]">
                    </div>
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control retail_price_row {{$retail_price_row_id}}" id="{{$retail_price_row_id}}" onkeyup="validateNum(event,this);" name="new_variant[{{ $vendor_id }}][retail_price][]">
                	</div>
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control minimum_price_row {{$minimum_price_row_id}}" id="{{$minimum_price_row_id}}" onkeyup="validateNum(event,this);" name="new_variant[{{ $vendor_id }}][minimum_price][]">
                	</div>
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control stock_qty_row {{$stock_qty_row_id}}" id="{{$stock_qty_row_id}}" onkeyup="validateNum(event,this);" name="new_variant[{{ $vendor_id }}][stock_qty][]">
                	</div>
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control" onkeyup="validateNum(event,this);" name="new_variant[{{ $vendor_id }}][display_order][]" value="{{$count}}">
                	</div>
                </td>
                <td>
                	<div class="form-group">
                		<select class="form-control display select2bs4" name="new_variant[{{ $vendor_id }}][display][]">
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                	</div>
                </td>
			</tr>
			<input type="hidden" value="{{$count++}}">
		@endforeach
	@endforeach
	
	</tbody>
</table>

</div>
<style type="text/css">
	#variantList{width: 100%}
	#variantList .input-box{width: 100px}
</style>
<script type="text/javascript">
	$('#base_price_row1').keyup(function(){
		var check_attr=$(this).attr('data');
		if (typeof check_attr !== 'undefined' && check_attr !== false) {
	        return false;
		}
		else{
			$('.base_price_row').val(this.value);
		}
    });
    $('#retail_price_row1').keyup(function(){

		var check_attr=$(this).attr('data');
		if (typeof check_attr !== 'undefined' && check_attr !== false) {
	        return false;
		}
		else{
			$('.retail_price_row').val(this.value);
		}

    });
    $('#minimum_price_row1').keyup(function(){
		var check_attr=$(this).attr('data');
		if (typeof check_attr !== 'undefined' && check_attr !== false) {
	        return false;
		}
		else{
			$('.minimum_price_row').val(this.value);
		}
    });
    $('#stock_qty_row1').keyup(function(){
		var check_attr=$(this).attr('data');
		if (typeof check_attr !== 'undefined' && check_attr !== false) {
	        return false;
		}
		else{
			$('.stock_qty_row').val(this.value);
		}
    });

    $('.child_base_price').keyup(function(event) {
    	$('#base_price_row1').attr('data', 'changed');
    });
    $('.child_retail_price').keyup(function(event) {
    	$('#retail_price_row1').attr('data', 'changed');
    });
    $('.child_minimum_price').keyup(function(event) {
    	$('#minimum_price_row1').attr('data', 'changed');
    });
    $('.child_stock_qty').keyup(function(event) {
    	$('#stock_qty_row1').attr('data', 'changed');
    });
</script>