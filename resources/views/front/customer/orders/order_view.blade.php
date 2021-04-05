@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a href="{{ route('my-orders.index') }}" title="My RFQ">My Orders</a></li>
      <li><a title="Orders View">View</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row">
		  <div id="column-left" class="col-sm-3 hidden-xs column-left">
		   	@include('front.customer.customer_menu')
      </div>
		  <div class="col-sm-9">
        <div class="go-back">
          <a href="{{ route('my-orders.index') }}"><i class="fas fa-angle-left"></i> Back</a>
        </div>
        <div class="order rfq view-block">
          <div class="action_sec">
            @if($order->order_status!=21)
              <ul class="list-unstyled">
                @if($order->order_status==18)
                  <li style="background-color: #f6ac50;border-right: 1px solid #227bbb;">
                    <a href="javascript:void(0);" class="place-order">
                      <i class="fas fa-file-invoice-dollar"></i>&nbsp; Invoice Download
                    </a>
                  </li>
                @endif
                <li style="background-color: #216ea7">
                  <a href="javascript:void(0);" class="pdf">
                    <i class="fa fa-download"></i>&nbsp; PDF
                  </a>
                </li>
                <li style="background-color: #43bfdd">
                  <a href="javascript:void(0);" class="email">
                    <i class="fa fa-envelope"></i>&nbsp; Email
                  </a>
                </li>
                <li style="background-color: #23bf79">
                  <a href="javascript:void(0);" class="comment">
                    <i class="fa fa-comment"></i>&nbsp; Comment
                  </a>
                </li>
              </ul>
            @endif
          </div>

          <div class="col-sm-12 address-sec">
            <div class="rfq-detail col-sm-4">
              <ul class="list-unstyled">
                <li><strong>Order Details</strong></li>
                @if($order->order_status==18)<li><span>Invoice:</span>#{{ $order->invoice_no }}</li>@endif
                <li><span>Order Code:</span>{{ $order->order_no }}</li>
                <li><span>Order Date:</span>{{ date('d/m/Y - H:i a',strtotime($order->created_at)) }}</li>
                @if($order->delivered_at)<li><span>Delivered Date:</span>{{ date('d/m/Y - H:i a',strtotime($order->delivered_at))}}</li>@endif
                <li><span>Order Status:</span>{{ $order->statusName->status_name }}</li>
                <?php 
                  if($order->payment_status==1) $pay_status = 'Paid';
                  elseif($order->payment_status==2) $pay_status = 'Partly Paid';
                  elseif($order->payment_status==3) $pay_status = 'Not Paid';
                ?>
                <li><span>Payment Status:</span>{{ $pay_status }}</li>
              </ul>
            </div>
            <div class="address-block col-sm-8">
           
              <div class="col-sm-6 customer address">
                <div class="col-sm-2 icon">
                  <span><i class="fas fa-user"></i></span>
                </div>
                <div class="col-sm-10 details">
                  <strong>{{$delivery_address->name}}</strong>
                  <p>
                    <span>
                      {{$delivery_address->address_line1}},&nbsp;{{isset($delivery_address->address_line2)?$delivery_address->address_line2:''}}
                    </span><br>
                    <span>
                      {{$delivery_address->country->name}},&nbsp;{{isset($delivery_address->state->name)?$delivery_address->state->name:''}}
                    </span><br>
                    <span>
                      {{isset($delivery_address->city->name)?$delivery_address->city->name:''}}&nbsp;-&nbsp;{{isset($delivery_address->post_code)?$delivery_address->post_code:''}}.
                    </span>
                  </p>
                  <p>
                    <span>Tel: {{$delivery_address->mobile}}</span><br>
                    <span>Email: {{$cus_email}}</span>
                  </p>
                </div>
              </div>
              
              @if(isset($admin_address))
                <div class="col-sm-6 admin address">
                  <div class="col-sm-2 icon">
                    <span><i class="far fa-building"></i></span>
                  </div>
                  <div class="col-sm-10 details">
                    <strong>{{$admin_address->company_name}}</strong>
                    <p>
                      <span>
                        {{$admin_address->address_1}},&nbsp;{{$admin_address->address_2}}
                      </span><br>
                      <span>
                        {{$admin_address->getCountry->name}},&nbsp;{{$admin_address->getState->name}}
                      </span><br>
                      <span>
                        {{$admin_address->getCity->name}}&nbsp;-&nbsp;{{$admin_address->post_code}}.
                      </span>
                    </p>
                    <p>
                      <span>Tel: {{$admin_address->post_code}}</span><br>
                      <span>Email: {{$admin_address->company_email}}</span>
                    </p>
                  </div>
                </div>
              @else
                <div class="col-sm-6 admin address">
                  <div class="col-sm-2 icon">
                    <span><i class="far fa-building"></i></span>
                  </div>
                  <div class="col-sm-10 details">
                  </div>
                </div>
              @endif
            </div>
          </div>

          <div class="product-sec">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr><th>No.</th><th>Product Name</th><th>Quantity</th><th>Price</th><th>Sub Total</th></tr>
                </thead>
                <tbody>
                  @php $s_no = 1 @endphp
                  @foreach($order_products as $products)
                  <tr>
                    <td>{{ $s_no }}</td>
                    <td>
                      <div class="product-details">
                        <div class="name">{{ $products['product_name'] }}</div>
                        <div class="sku">{{ $products['variant_sku'] }}</div>
                        <div class="variant">
                          <p>[
                            @if(isset($products['variant_option1']))
                              <span>{{$products['variant_option1']}}</span> : {{$products['variant_option_value1']}}
                            @endif
                            @if(isset($products['variant_option2']))
                              , <span>{{$products['variant_option2']}}</span> : {{ $products['variant_option_value2']}}
                            @endif
                            @if(isset($products['variant_option3']))
                              , <span>{{$products['variant_option3']}}</span> : {{ $products['variant_option_value3']}}
                            @endif
                            @if(isset($products['variant_option4']))
                              , <span>{{$products['variant_option4']}}</span> : {{ $products['variant_option_value4']}}
                            @endif
                            @if(isset($products['variant_option5']))
                              , <span>{{$products['variant_option5']}}</span> : {{ $products['variant_option_value5']}}
                            @endif
                          ]</p>
                        </div>
                      </div>
                    </td>
                    <td>{{ $products['quantity'] }}</td>
                    <td>{{ number_format($products['final_price'],2,'.','') }}</td>
                    <td>{{ number_format($products['sub_total'],2,'.','') }}</td>
                  </tr>
                  @php $s_no++ @endphp
                  @endforeach
                  <tr class="outer">
                    <td colspan="4" class="text-right">Total</td><td>{{ number_format($order_data['total'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="4" class="text-right">Order Discount</td><td>{{ number_format($order_data['discount'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="4" class="text-right">Order Tax</td><td>{{ number_format($order_data['tax'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer grand">
                    <th colspan="4" class="text-right">Total Amount (SGD)</th><th>{{ number_format($order_data['grand_total'],2,'.','') }}</th>
                  </tr>
                  <tr class="outer">
                    <td colspan="4" class="text-right">Paid Amount (SGD)</td><td>{{ number_format($order_data['paid_amount'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer" style="border-bottom:1px solid #ddd">
                    <th colspan="4" class="text-right">Due Amount (SGD)</th><th>{{ number_format($order_data['due_amount'],2,'.','') }}</th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection