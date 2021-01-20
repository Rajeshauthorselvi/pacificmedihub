<table class="table table-bordered">
	<thead>
            <tr>
                @foreach($get_options as $option)
                    <th>{{$option}}</th>
                @endforeach
                <th>Base Price</th>
                <th>Retail Price</th>
                <th>Minimum Selling Price</th>
                <th>Stock Qty</th>
                <th>Vendor</th>
                <th>Order By</th>
                <th>Display</th>
                <th>Remove</th>
            </tr>
            <tbody>
            	
            </tbody>
	</thead>
</table>