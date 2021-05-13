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
                  <a href="{{ route('my.order.pdf',$order->id) }}" class="pdf">
                    <i class="fa fa-download"></i>&nbsp; PDF
                  </a>
                </li>
                <li style="background-color: #43bfdd">
                  <a href="javascript:void(0);" class="email">
                    <i class="fa fa-envelope"></i>&nbsp; Email
                  </a>
                </li>
                @if($order_data['days_count']<=14 && $order->order_status==13) 
                  @if(!isset($order_data['order_return']['order_return_status'])||$order_data['order_return']['order_return_status']==4)
                    <li style="background-color: #23bf79">
                      <a href="{{ route('myorder.return.index',base64_encode($order->id)) }}" class="comment">
                        <i class="fas fa-reply-all"></i>&nbsp; Return
                      </a>
                    </li>
                  @endif
                @endif
              </ul>
            @endif
          </div>

          <div class="col-sm-12 address-sec">
            <div class="rfq-detail col-sm-4">
              <ul class="list-unstyled">
                <li><strong>Order Details</strong></li>
                @if($order->order_status==18)<li><span>Invoice:</span>#{{ $order->invoice_no }}</li>@endif
                <li><span>Order Code:</span>{{ $order->order_no }}</li>
                <li><span>Order Date:</span>{{ date('d/m/Y',strtotime($order->created_at)) }}</li>
                @if($order->approximate_delivery_date)<li><span>Delivery Date:</span>{{ date('d/m/Y',strtotime($order->approximate_delivery_date))}}</li>@endif
                <li>
                  <span>Status</span>: 
                  @if (isset($order_data['order_return']['order_return_status']) && $order_data['order_return']['order_return_status']==22)
                    <span class="badge" style="background:#f0ad4e;color:#fff;padding: 5px">
                      Return Request
                    </span>
                  @elseif(isset($order_data['order_return']['order_return_status']) && $order_data['order_return']['order_return_status']==24)
                    <span class="badge badge-info" style="padding: 5px">
                      Return Request Approved
                    </span>
                  @else
                   <span class="badge" style="background:{{ $order['color_code'] }};color:#fff;padding: 5px">
                    {{ $order['status'] }}
                  </span>
                  @endif
                </li>
                <?php 
                  if($order->payment_status==1) $pay_status = 'Paid';
                  elseif($order->payment_status==2) $pay_status = 'Partly Paid';
                  elseif($order->payment_status==3) $pay_status = 'Not Paid';
                ?>
                <li><span>Payment Status:</span>{{ $pay_status }}</li>
{{--                 <br>
                @if(isset($order_data['order_return']['order_return_status']))
                  <li>
                    <span>Return Status</span>: <span class="badge" style="background:{{$order_data['order_return']['statusName']['color_codes']}};color:#fff;padding: 5px">{{ $order_data['order_return']['statusName']['status_name'] }}</span>
                  </li>
                @endif --}}
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
                    @if(isset($delivery_address->mobile))
                      <span>Tel: {{$delivery_address->mobile}}</span><br>
                    @endif
                    @if(isset($cus_email))
                      <span>Email: {{$cus_email}}</span>
                    @endif
                  </p>
                </div>
              </div>
              
              @if(isset($admin_address))
                <div class="col-sm-6 admin address">
                  <div class="col-sm-2 icon">
                    <span><i class="far fa-building"></i></span>
                  </div>
                  <div class="col-sm-10 details">
                    <strong>{{$admin_address->name}}</strong>
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
                      @if(isset($admin_address->contact_number))
                        <span>Tel: {{$admin_address->contact_number}}</span><br>
                      @endif
                      @if(isset($admin_address->email))
                        <span>Email: {{$admin_address->email}}</span>
                      @endif
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
                  <th>No.</th>
                    <th>Product Name</th>
                    <th class="text-center width">Price</th>
                    <th class="text-center width">Discount</th>
                    <th class="text-center width">Discount Price <br><small>(a)</small></th>
                    <th class="text-center width">QTY <br><small>(b)</small></th>
                    <th class="text-center width">Return Quantity</th>

                    <th class="text-center width">Total <br><small>(a x b)</small></th>
                </thead>
                <tbody>
                  @php $s_no = 1 @endphp
                  @foreach($order_products as $products)
                    <?php 

                    $order_returns=\App\Models\CustomerOrderReturnProducts::ReturnProducts($order->id,$products['product_id'],$products['product_variation_id']);

                    ?>
                  <tr>
                    <td>{{ $s_no }}</td>
                    <td>
                      <div class="product-details">
                        <div class="name" title="{{ $products['product_name'] }}">{{ Str::limit($products['product_name'],40) }}</div>
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
                    <td class="text-center">{{ number_format($products['price'],2,'.','') }}</td>
                    <td class="text-center">@if($products['discount_type']=='amount') $ @endif {{ $products['discount_value'] }} @if($products['discount_type']=='percentage') % @endif </td>
                    <td class="text-center">{{ number_format($products['final_price'],2,'.','') }}</td>
                    <td class="text-center">{{ $products['quantity'] }}</td>
                    <td>
                      @if (isset($order_returns->return_quantity) || isset($order_returns->damage_quantity))
                        {{  $order_returns->return_quantity+$order_returns->damage_quantity  }}
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-center">{{ number_format($products['sub_total'],2,'.','') }}</td>
                  </tr>
                  @php $s_no++ @endphp
                  @endforeach
                  <tr class="outer">
                    <td colspan="6" class="text-right">Total</td><td>{{ number_format($order_data['total'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="6" class="text-right">Order Tax</td><td>{{ number_format($order_data['tax'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="6" class="text-right">Delivery Charge @if(isset($order->deliveryMethod))({{ $order->deliveryMethod->delivery_method }}) @endif</td>
                    <td>{{number_format($order->delivery_charge,2,'.','')}}</td>
                  </tr>
                  <tr class="outer grand">
                    <th colspan="6" class="text-right">Total Amount (SGD)</th><th>{{ number_format($order_data['sgd_total'],2,'.','') }}</th>
                  </tr>
                  <tr class="outer">
                    <td colspan="6" class="text-right">Paid Amount (SGD)</td><td>{{ number_format($order_data['paid_amount'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer" style="border-bottom:1px solid #ddd">
                    <th colspan="6" class="text-right">Due Amount (SGD)</th><th>{{ number_format($order_data['due_amount'],2,'.','') }}</th>
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