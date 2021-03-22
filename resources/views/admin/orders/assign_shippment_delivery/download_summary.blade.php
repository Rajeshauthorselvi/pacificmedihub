<!DOCTYPE html>
<html>
<body>
	<style type="text/css">
		table{
			border-collapse: collapse;
		}
		td,th{
			border: 1px solid #000;
			padding: 5px;
		}
	</style>
  <div style="width: 700px;margin: auto;">
  	<h2 style="text-align: center;">Ordered Summary</h2>
  	<div> <strong>Generated Date: </strong>{{ date('d-m-Y') }} </div>
  	<br>
  	<div class="order_nos">
	  	<div>
	  		<strong>Orderno: </strong>
			@foreach ($order_nos as $key=>$order_no)
		  		{{ $order_no }}@if (!$loop->last),@endif
		  	@endforeach
	  	</div>
  	</div>
  	<br>
  	<div style="clear: both"></div>
  	<strong>Product: </strong>
  	<div style="clear: both"></div>
  	<br>
  	<table style="width: 100%;float: left;" width="100%">
  		<tr>
	  		<th style="text-align: left;">S.No</th>
	  		<th style="text-align: left;">Product</th>
	  		<th style="text-align: center;">Quantity</th>
  		</tr>
  			<?php $total=0; ?>
		  	@foreach ($product_datas as $key=>$data)
		  	<tr style="padding-top: 20px;clear: both;">
		  		<td style="text-align: left;">{{ $loop->iteration }}</td>
		  		<td style="text-align: left;">{{ $data['product_name'] }}-<b>{{ $data['product_variant'] }}</b></td>
		  		<td style="text-align: center;">{{ $data['total_quantity'] }}</td>
		  	</tr>
		  		<?php $total +=$data['total_quantity']; ?>
		  	@endforeach
		  	<tr>
		  		<td colspan="2" style="text-align: right;">Total:</td>
		  		<td style="text-align: center;">{{ $total }}</td>
		  	</tr>
  	</table>


  </div>
</body>
</html>

<?php //exit ?>
