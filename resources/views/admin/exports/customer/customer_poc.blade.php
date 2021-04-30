<table>
	<thead>
		<tr>
			<th>CustomerId</th>
			<th>Name</th>
			<th>Email</th>
			<th>Phone No</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($all_poc as $customer_poc)
			@if (isset($customer_poc->CustomerPoc))
				@foreach ($customer_poc->CustomerPoc as $poc)
					<tr>
						<td>{{ $poc->customer_id }}</td>
						<td>{{ $poc->name }}</td>
						<td>{{ $poc->email }}</td>
						<td>{{ $poc->contact_number }}</td>
					</tr>
				@endforeach
			@endif
		@endforeach
	</tbody>
</table>

