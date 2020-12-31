<table>
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

		@foreach($option_values as $options)
			<tr>
				<td>{{$get_option[$options['option_id']]}}</td>
				<td>{{$options['option_value']}}</td>
			</tr>
		@endforeach

	</tbody>
</table>