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
  	<h3>Ordered Summary:</h3>
  	<h4>Generated Date: {{ date('d-m-Y') }}</h4>
  	<h4>Orderno :</h4>
  	@foreach ($order_nos as $key=>$order_no)
  		{{ $order_no }}@if (!$loop->last),@endif
  	@endforeach
  	<br>
  	<h4>Product: </h4>
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
		  		<td style="text-align: left;">{{ $data['product_name'] }}-{{ $data['product_variant'] }}</td>
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

<?php 	//exit ?>
