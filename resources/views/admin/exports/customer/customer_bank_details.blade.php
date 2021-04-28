<table>
	<thead>
		<th>CustomerId</th>
		<th>Account Name</th>
		<th>Account Number</th>
		<th>Bank Name</th>
		<th>Bank Branch</th>
		<th>IFSC Code</th>
		<th>Paynow Contact</th>
		<th>Place</th>
		<th>Others</th>
	</thead>
	<tbody>
		@foreach ($all_bank_details as $bank_details)
		@if (isset($bank_details->account_name))
			<tr>
				<td>{{ $bank_details->customer_id }}</td>
				<td>{{ $bank_details->account_name }}</td>
				<td>{{ $bank_details->account_number }}</td>
				<td>{{ $bank_details->bank_name }}</td>
				<td>{{ $bank_details->bank_branch }}</td>
				<td>{{ $bank_details->ifsc_code }}</td>
				<td>{{ $bank_details->paynow_contact }}</td>
				<td>{{ $bank_details->place }}</td>
				<td>{{ $bank_details->others }}</td>
			</tr>
		@endif
		@endforeach
	</tbody>
</table>

