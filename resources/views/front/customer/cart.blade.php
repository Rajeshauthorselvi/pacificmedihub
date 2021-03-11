@extends('front.layouts.default')
@section('front_end_container')
	
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="My Cart Page">My Cart</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="cart-block">
					<h3>My Cart</h3>
					<table class="table-responsive cart-table">
						<thead>
							<tr><th style="width:50%">Product Name</th><th style="width:25%">SKU</th><th>Qty</th><th>Action</th></tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<img src="{{ asset('theme/images/products/placeholder.jpg') }}">
									<span>Safeshield 3 Layer Surgical Disposable Face Mask</span>
								</td>
								<td><span>PMH0000003-00002-1PACK-BANANA</span></td>
								<td>
									<div class="number">
										<span class="minus">-</span><input type="text" class="qty-count" value="1"/>
										<span class="plus">+</span>
									</div>
								</td>
								<td>
									<a><i class="far fa-trash-alt"></i></a>&nbsp;&nbsp;
									<a><i class="far fa-heart"></i></a>
								</td>
							</tr>
							<tr>
								<td>
									<img src="{{ asset('theme/images/products/placeholder.jpg') }}">
									<span>Safeshield 3 Layer Surgical Disposable Face Mask</span>
								</td>
								<td><span>PMH0000003-00002-1PACK-BANANA</span></td>
								<td>
									<div class="number">
										<span class="minus">-</span><input type="text" class="qty-count" value="1"/>
										<span class="plus">+</span>
									</div>
								</td>
								<td>
									<a><i class="far fa-trash-alt"></i></a>&nbsp;&nbsp;
									<a><i class="far fa-heart"></i></a>
								</td>
							</tr>
							<tr>
								<td>
									<img src="{{ asset('theme/images/products/placeholder.jpg') }}">
									<span>Safeshield 3 Layer Surgical Disposable Face Mask</span>
								</td>
								<td><span>PMH0000003-00002-1PACK-BANANA</span></td>
								<td>
									<div class="number">
										<span class="minus">-</span><input type="text" class="qty-count" value="1"/>
										<span class="plus">+</span>
									</div>
								</td>
								<td>
									<a><i class="far fa-trash-alt"></i></a>&nbsp;&nbsp;
									<a><i class="far fa-heart"></i></a>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="action-block">
						<div class="col-sm-4" style="text-align:left;padding-left:0">
							<a class="btn shopping">Continue Shopping</a>
						</div>
						<div class="col-sm-4">
							<a class="btn update">Update Shopping Cart</a>
						</div>
						<div class="col-sm-4" style="text-align:right;padding-right:0">
							<a class="btn checkout">Proceed to RFQ</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	.cart-block .cart-table {margin: 20px 0 30px}
	.cart-block .cart-table thead tr th, .cart-block .cart-table tbody tr td {
    padding: 15px;
    border: 1px solid #ddd;
    width: 12%;
}
.cart-block .cart-table tbody tr td img {
    position: relative;
    width: 75px;
    padding: 10px;
}
.cart-block .cart-table .number {
    margin: 0;
}
.cart-block .cart-table .number .minus, .cart-block .cart-table .number .plus {
    width: 25px;
    height: 30px;
    padding: 3px;
}
.cart-block .cart-table .number input{
	width: 35px;
	height: 30px;
	font-size:16px;
}
.action-block {
    display: flex;
    margin-bottom: 30px;
    text-align: center;
}
.action-block .btn {
    border: 1px solid #ddd;
    border-radius: 0;
    background: #efefef;
    padding: 10px 20px;
    width: 70%;
}
</style>
@endsection