<table>
	<thead>
		<tr>
			<th>CustomerId</th>
			<th>Company UEN</th>
			<th>Company Name</th>
			<th>Parent Company Name</th>
			<th>Login Email</th>
			<th>Contact No</th>
			<th>Address Line1</th>
			<th>Address Line2</th>
			<th>Country</th>
			<th>State</th>
			<th>City</th>
			<th>Post Code</th>
			<th>Sales Rep</th>
			<th>Company GST</th>
			<th>Published</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($all_customerss as $customer)
			<tr>
				<td>{{ $customer->id }}</td>
				<td>{{ $customer->company_uen }}</td>
				<td>{{ $customer->name }}</td>
				<td>{{ isset($customer->getParentConpany->name)?$customer->getParentConpany->name:'' }}</td>
				<td>{{ $customer->email }}</td>
				<td>{{ $customer->contact_number }}</td>
				<td>{{ $customer->address_1 }}</td>
				<td>{{ $customer->address_2 }}</td>
				<td>{{ isset($customer->getCountry->name)?$customer->getCountry->name:'' }}</td>
				<td>{{ isset($customer->getState->name)?$customer->getState->name:'' }}</td>
				<td>{{ isset($customer->getCity->name)?$customer->getCity->name:'' }}</td>
				<td>{{ $customer->post_code }}</td>
				<td>{{ isset($customer->getSalesRep->emp_name)?$customer->getSalesRep->emp_name:'' }}</td>
				<td>{{ $customer->company_gst }}</td>
				<td>{{ ($customer->status==1)?'Yes':'No' }}</td>
			</tr>
		@endforeach
	</tbody>
</table>

