<table>
	<thead>
		<th>CustomerId</th>	
		<th>Name</th>
		<th>Contact No</th>
		<th>Address Line1</th>
		<th>Address Line 2</th>
		<th>Country	State</th>
		<th>City</th>
		<th>Post Code</th>
		<th>Latitude</th>
		<th>Longitude</th>
		<th>Is Default</th>
	</thead>
	<tbody>
		@foreach ($all_address as $address)
				<tr>
					<td>{{ $address->customer_id }}</td>
					<td>{{ $address->name }}</td>
					<td>{{ $address->mobile }}</td>
					<td>{{ $address->address_line1 }}</td>
					<td>{{ $address->address_line2 }}</td>
					<td>{{ $address->country->name }}</td>
					<td>{{ isset($address->state->name)?$address->state->name:'' }}</td>
					<td>{{ isset($address->city->name)?$address->city->name:'' }}</td>
					<td>{{ $address->latitude }}</td>
					<td>{{ $address->latitude }}</td>
					<td>{{ ($address->address_type==0)?'Yes':'No' }}</td>
				</tr>
		@endforeach
	</tbody>
</table>

