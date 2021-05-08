<div class="table-responsive">
<table class="table table-bordered">
	<thead>
		<tr>
			<th>Vendor</th>
			@foreach($get_options as $option)
				<th class="option-head">{{ $option }}</th>
			@endforeach
			<th>SKU</th>
			<th>Base Price</th>
			<th>Retail Price</th>
			<th>Minimum Selling Price</th>
			<th>Stock Qty</th>
			<th>Batch Id</th>
			<th>Expiry Date</th>
			<th>Location Id</th>
		</tr>
	</thead>
	<tbody>
		@foreach($product_variant as $variant)
        <?php  
        	$batch_details=App\Models\Orders::PurchaseBatchInfo($product->id,$variant['variant_id']);
            	$batch_id=$expiry_date=$location_id=array();
            	foreach ($batch_details as $key=>$batch){
            		array_push($batch_id, $batch->batch_id);
            		array_push($expiry_date, $batch->expiry_date);
            		array_push($location_id, $batch->location_id);
            	}
            	$batch_id=(count($batch_id)>0)?implode(',',array_filter($batch_id)):'-';
            	$expiry_date=(count($expiry_date)>0)?implode(',',array_filter($expiry_date)):'-';
            	$location_id=(count($location_id)>0)?implode(',',array_filter($location_id)):'-';
        ?>
		<tr>
            <td><div class="form-group"> {{$variant['vendor_name']}}</div></td>
            <td> <div class="form-group"> {{$variant['option_value1']}} </div> </td>
            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
              <td> <div class="form-group"> {{$variant['option_value2']}} </div> </td>
            @endif
            @if($option_count==3||$option_count==4||$option_count==5)
              <td> <div class="form-group"> {{$variant['option_value3']}} </div> </td>
            @endif
            @if($option_count==4||$option_count==5)
              <td> <div class="form-group"> {{$variant['option_value4']}} </div> </td>
            @endif
            @if($option_count==5)
              <td> <div class="form-group"> {{$variant['option_value5']}} </div> </td>
            @endif
            <td> <div class="form-group"> {{$variant['sku']}} </div> </td>
            <td> <div class="form-group">{{$variant['base_price']}}</div> </td>
            <td> <div class="form-group">{{$variant['retail_price']}}</div> </td>
            <td> <div class="form-group">{{$variant['minimum_selling_price']}}</div> </td>
            <td> <div class="form-group">{{$variant['stock_quantity']}}</div> </td>
            <td> {{ $batch_id }} </td>
            <td> {{ $expiry_date }} </td>
            <td> {{ $location_id }} </td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>