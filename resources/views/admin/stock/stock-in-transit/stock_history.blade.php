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
		@foreach ($histories as $parent_key=>$history)

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
				<td>
					@if ($parent_key==0)
						{{ $history['qty_received'] }}
					@else
						{{ ($histories[0]['qty_received'])-($return_total) }}
					@endif
				</td>
				<td>
					@if ($parent_key==0)
					0
					@else
					{{ ($history['qty_received']) }}
					@endif
				</td>
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
				if ($history['goods_type']==1 && $parent_key!=0){
					$return_total +=$history['damage_quantity'];
				}
			?>
		@endforeach
	</tbody>
</table>



