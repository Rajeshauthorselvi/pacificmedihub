<!DOCTYPE html>
<html>
<body>
	<style type="text/css">
		table{
			border-collapse: collapse;
			font-size: 14px;
		}
		td,th{
			border: 1px solid #000;
			padding: 5px;
		}
	</style>
  <div style="width: 700px;margin: auto;">
  	<h2 style="text-align: center;">Order Summary</h2>
  	<br>
  	<div style="width: 100%">
  		<div style="width: 50%;float: right;text-align: right;"> <strong>Generated Date: </strong>{{ date('d-m-Y') }} </div>
  	</div>
  	<br>
  	<br>
  	<div class="order_nos">
	  	<div>
	  		<div style="width: 14%;float: left;">
		  		<strong>OrderNo: </strong>
	  		</div>
	  		<div style="width: 80%;float: left;">
				@foreach ($order_nos as $key=>$order_no)
			  		{{ $order_no }}@if (!$loop->last),@endif
			  	@endforeach
	  		</div>
	  	</div>
  	</div>
  	<div style="clear: both"></div>
  	<br>
  	<table style="width: 100%;float: left;" width="100%">
  		<tr>
	  		<th style="text-align: left;">S.No</th>
	  		<th style="text-align: left;">Product</th>
	  		<th style="text-align: center;">Quantity</th>
	  		<th style="text-align: center;">Status</th>
  		</tr>
  			<?php $total=0; ?>
		  	@foreach ($product_datas as $key=>$data)
		  	<tr style="padding-top: 20px;clear: both;">
		  		<td style="text-align: left;">{{ $loop->iteration }}</td>
		  		<td style="text-align: left;">{{ $data['product_name'] }}-<b>{{ $data['product_variant'] }}</b></td>
		  		<td style="text-align: center;">{{ $data['total_quantity'] }}</td>
		  		<td style="text-align: center;">
		  			
		  				<img src="http://selvisoftware.in/pacificmedihub/public/theme/images/uncheck.png" width="30px">
		  			{{--  --}}
		  		
		  		</td>
		  	</tr>
		  		<?php $total +=$data['total_quantity']; ?>
		  	@endforeach
		  	{{-- <tr>
		  		<td colspan="2" style="text-align: right;">Total:</td>
		  		<td style="text-align: center;">{{ $total }}</td>
		  	</tr> --}}
  	</table>


  </div>
</body>
</html>

<?php //exit ?>
