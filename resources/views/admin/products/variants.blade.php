<div class="table-responsive">
	
	@foreach($removed_vendors_id as $removed)
		<input type="hidden" name="removed_vendor[id][]" value="{{$removed}}">
	@endforeach
	@if(count($options)>0)
		<table class="list" id="variantList">
			<thead>
				<tr>
					@foreach($options as $option)
					<th class="option-head">
						{{$option->option_name}}
					</th>
					@endforeach
					<th class="input-box">Base Price</th>
					<th class="input-box">Retail Price</th>
					<th class="input-box">Minimum Selling Price</th>
					<th class="input-box">Stock Qty</th>
					<th class="vendor-name">Vendor</th>
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
				?>
				@foreach($option_value as $k => $options)
					<?php 
						$option_id1 = $options->optionID1;
						$option_value_id1 = $options->optionValueID1;
						$option1 = $options->optionValue1;
						if($option_count==2||$option_count==3||$option_count==4||$option_count==5){
							$option_id2 = $options->optionID2;
							$option_value_id2 = $options->optionValueID2;
							$option2 = $options->optionValue2;	
						}
						if($option_count==3||$option_count==4||$option_count==5){
							$option_id3 = $options->optionID3;
							$option_value_id3 = $options->optionValueID3;
							$option3 = $options->optionValue3;	
						}
						if($option_count==4||$option_count==5){
							$option_id4 = $options->optionID4;
							$option_value_id4 = $options->optionValueID4;
							$option4 = $options->optionValue4;	
						}
						if($option_count==5){
							$option_id5 = $options->optionID5;
							$option_value_id5 = $options->optionValueID5;
							$option5 = $options->optionValue5;	
						}

						if($k<=0){
							$base_price_row_id = 'base_price_row1';
							$retail_price_row_id = 'retail_price_row1';
							$minimum_price_row_id = 'minimum_price_row1';
							$stock_qty_row_id = 'stock_qty_row1';
						}else{
							$base_price_row_id = 'base_price_row';
							$retail_price_row_id = 'retail_price_row';
							$minimum_price_row_id = 'minimum_price_row';
							$stock_qty_row_id = 'stock_qty_row';
						}
					?>
					<tr>
						@if($data_from=="edit")
							<input type="hidden" name="variant[id][]" value=0>
						@endif
						<td>
							<input type="hidden" name="variant[option_id1][]" value="{{$option_id1}}">
							<input type="hidden" name="variant[option_value_id1][]" value="{{$option_value_id1}}">
							{{$option1}}
						</td>
						@if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
							<td>
								<input type="hidden" name="variant[option_id2][]" value="{{$option_id2}}">
								<input type="hidden" name="variant[option_value_id2][]" value="{{$option_value_id2}}">
								{{$option2}}
							</td>
						@endif
						@if($option_count==3||$option_count==4||$option_count==5)
							<td>
								<input type="hidden" name="variant[option_id3][]" value="{{$option_id3}}">
								<input type="hidden" name="variant[option_value_id3][]" value="{{$option_value_id3}}">
								{{$option3}}
							</td>
						@endif
						@if($option_count==4||$option_count==5)
							<td>
								<input type="hidden" name="variant[option_id4][]" value="{{$option_id4}}">
								<input type="hidden" name="variant[option_value_id4][]" value="{{$option_value_id4}}">
								{{$option4}}
							</td>
						@endif
						@if($option_count==5)
							<td>
								<input type="hidden" name="variant[option_id5][]" value="{{$option_id5}}">
								<input type="hidden" name="variant[option_value_id5][]" value="{{$option_value_id5}}">
								{{$option5}}
							</td>
						@endif
						<td>
							<div class="form-group">
		                        <input type="text" class="form-control base_price_row" id="{{$base_price_row_id}}" onkeyup="validateNum(event,this);" name="variant[base_price][]">
		                    </div>
		                </td>
		                <td>
		                	<div class="form-group">
		                		<input type="text" class="form-control retail_price_row" id="{{$retail_price_row_id}}" onkeyup="validateNum(event,this);" name="variant[retail_price][]">
		                	</div>
		                </td>
		                <td>
		                	<div class="form-group">
		                		<input type="text" class="form-control minimum_price_row" id="{{$minimum_price_row_id}}" onkeyup="validateNum(event,this);" name="variant[minimum_price][]">
		                	</div>
		                </td>
		                <td>
		                	<div class="form-group">
		                		<input type="text" class="form-control stock_qty_row" id="{{$stock_qty_row_id}}" onkeyup="validateNum(event,this);" name="variant[stock_qty][]">
		                	</div>
		                </td>
		                <td>
		                	<input type="hidden" name="variant[vendor_id][]" class="vendor_id" value="{{$vendor_id}}">
							{{$vendor_name}}
		                </td>
		                <td>
		                	<div class="form-group">
		                		<input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[display_order][]" value="{{$count}}">
		                	</div>
		                </td>
		                <td>
		                	<div class="form-group">
		                		<select class="form-control display select2bs4" name="variant[display][]">
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
	@endif
</div>
<style type="text/css">
	#variantList{width: 100%}
	#variantList .input-box{width: 100px}
</style>
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

    $(function ($) {
        $('body').find('.display.select2bs4').select2({
          minimumResultsForSearch: -1
        });
    });
</script>