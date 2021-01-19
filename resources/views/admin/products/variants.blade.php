<table class="list" id="variantList">
	<thead>
		<tr>
			@foreach($get_option as $option)
			<th>
				{{$option}}
			</th>
			@endforeach
			<th>Base Price</th>
			<th>Retail Price</th>
			<th>Minimum Selling Price</th>
			<th>Stock Qty</th>
			<th>Vendor</th>
			<th>Order By</th>
			<th>Display</th>
		</tr>
	</thead>
	<tbody>
	<?php $count=1; ?>
	@foreach($option_values as $key => $option_value)
		<?php
			$vendor_id = $vendors[$key]->id;
			$vendor_name = $vendors[$key]->name;
		?>
		@foreach($option_value as $k => $options)
			<?php 
				$option_id1 = $options->opt_id1;
				$option_value_id1 = $options->opt_val_id1;
				$option1 = $options->opt_val1;
				if($option_count==2||$option_count==3){
					$option_id2 = $options->opt_id2;
					$option_value_id2 = $options->opt_val_id2;
					$option2 = $options->opt_val2;	
				}
				if($option_count==3){
					$option_id3 = $options->opt_id3;
					$option_value_id3 = $options->opt_val_id3;
					$option3 = $options->opt_val3;	
				}


				if($k<=0){
					$base_price_row_id = 'base_price_row1';
					$retail_price_row_id = 'retail_price_row1';
					$minimum_price_row_id = 'minimum_price_row1';
					$stock_qty_row_id = 'stock_qty_row1';

					$base_price_row_class = 'base_price_row1';
					$retail_price_row_class = 'retail_price_row1';
					$minimum_price_row_class = 'minimum_price_row1';
					$stock_qty_row_class = 'stock_qty_row1';
				}else{
					$base_price_row_class = 'base_price_row';
					$retail_price_row_class = 'retail_price_row';
					$minimum_price_row_class = 'minimum_price_row';
					$stock_qty_row_class = 'stock_qty_row';

					$base_price_row_id = 'base_price_row';
					$retail_price_row_id = 'retail_price_row';
					$minimum_price_row_id = 'minimum_price_row';
					$stock_qty_row_id = 'stock_qty_row';
				}

			?>
			<tr>
				<td>
					<input type="hidden" name="variant[option_id1][]" value="{{$option_id1}}">
					<input type="hidden" name="variant[option_value_id1][]" value="{{$option_value_id1}}">
					{{$option1}}
				</td>
				@if($option_count==2||$option_count==3)
					<td>
						<input type="hidden" name="variant[option_id2][]" value="{{$option_id2}}">
						<input type="hidden" name="variant[option_value_id2][]" value="{{$option_value_id2}}">
						{{$option2}}
					</td>
				@endif
				@if($option_count==3)
					<td>
						<input type="hidden" name="variant[option_id3][]" value="{{$option_id3}}">
						<input type="hidden" name="variant[option_value_id3][]" value="{{$option_value_id3}}">
						{{$option3}}
					</td>
				@endif
				<td>
					<div class="form-group">
                        <input type="text" class="form-control {{$base_price_row_class}}" id="{{$base_price_row_id}}" onkeyup="validateNum(event,this);" name="variant[base_price][]">
                    </div>
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control {{$retail_price_row_class}}" id="{{$retail_price_row_id}}" onkeyup="validateNum(event,this);" name="variant[retail_price][]">
                	</div>
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control {{$minimum_price_row_class}}" id="{{$minimum_price_row_id}}" onkeyup="validateNum(event,this);" name="variant[minimum_price][]">
                	</div>
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control {{$stock_qty_row_class}}" id="{{$stock_qty_row_id}}" onkeyup="validateNum(event,this);" name="variant[stock_qty][]">
                	</div>
                </td>
                <td>
                	<input type="hidden" name="variant[vendor_id][]" value="{{$vendor_id}}">
					{{$vendor_name}}
                </td>
                <td>
                	<div class="form-group">
                		<input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[display_order][]" value="{{$count}}">
                	</div>
                </td>
                <td>
                	<div class="form-group">
                		<select class="form-control select2bs4" name="variant[display][]">
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

<script type="text/javascript">
	$('#base_price_row1').keyup(function(){
        $('.base_price_row').val(this.value);
    });
    $('#retail_price_row1').keyup(function(){
        $('.retail_price_row').val(this.value);
    });
    $('#minimum_price_row1').keyup(function(){
        $('.minimum_price_row').val(this.value);
    });
    $('#stock_qty_row1').keyup(function(){
        $('.stock_qty_row').val(this.value);
    });
</script>