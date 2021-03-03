<h6><label>Product Name:&nbsp;</label>{{ $product_name }}</h6>
<table class="table table-bordered">
	<thead>
		<th>Created Date</th>
        @foreach ($options as $option)
             <th>{{ $option }}</th>
        @endforeach
        <th>Qty Ordered</th>
		<th>Qty Received</th>
		<th>Damaged Quantity</th>
		<th>Missed Quantity</th>
		<th>Stock Quantity</th>
	</thead>
	<tbody>
		<?php $return_total=0; ?>
		@foreach ($histories as $key=>$history)
		@if ($key==0)
		<tr>
				<td>{{ date('d-m-Y H:i A',strtotime($purchase_datas)) }}</td>
				@foreach($product_variants as $key=>$variant)
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
                 @endforeach
                 <td>{{ $purchase_product_details->quantity }}</td>
                 <td>0</td>
                 <td>0</td>
                 <td>0</td>
                 <td>0</td>
		</tr>
		@endif

			<tr>
				<td>{{ $history['created_at'] }}</td>
				@foreach($product_variants as $key=>$variant)
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
                 @endforeach
				<td>{{ ($purchase_product_details->quantity)-($return_total) }}</td>
				<td>{{ $history['qty_received'] }}</td>
				<td>
					{{ $history['damage_quantity'] }}
					<small>
						@if ($history['goods_type']==1)
							(Goods Return)
						@elseif	($history['goods_type']==2)
							(Goods Replace)
						@endif
					</small>
				</td>
				<td>{{ $history['missed_quantity'] }}</td>
				<td>{{ $history['stock_quantity'] }}</td>
			</tr>
			<?php
				if ($history['goods_type']==1){
					$return_total +=$history['damage_quantity'];
				}
			?>
		@endforeach
	</tbody>
</table>



