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
			<th>Location Id</th>
		</tr>
	</thead>
	<tbody>
		@foreach($product_variant as $variant)
        <?php  
        	$batch_details=App\Models\Orders::PurchaseBatchInfo($product->id,$variant['variant_id']);
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
            <td>
                @foreach ($batch_details as $key=>$batch)
                  {{ $batch->batch_id }}
                  @if(!$loop->last),@endif
                @endforeach
            </td>
            <td>
                @foreach ($batch_details as $key=>$batch)
                  {{ $batch->location_id }}
                  @if(!$loop->last),@endif
                @endforeach
            </td>
		</tr>
		@endforeach
	</tbody>
</table>
</div>