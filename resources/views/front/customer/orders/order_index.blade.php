@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="My Orders">My Orders</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row customer-page">
		  <div id="column-left" class="col-sm-3 hidden-xs column-left">
		   	@include('front.customer.customer_menu')
      </div>

		  <div class="col-sm-9">
        <h2>All Orders</h2>
        <div class="rfq-container">
          @if(count($order_data)!=0)
            @foreach($order_data as $order)
          
              <div class="order rfq-block">
                <div class="header">
                  <div class="col-sm-4 text-left">
                    <span>Order Code</span>: #{{ $order['code'] }}
                  </div>
                  <div class="col-sm-4 text-center">
                    <span>Status</span>: 
                    {{-- Return Status --}}
                  @if (isset($order['order_return']['order_return_status']) && $order['order_return']['order_return_status']==22)
                    <span class="badge" style="background:#f0ad4e;color:#fff;padding: 5px">
                      Return Request
                    </span>
                  @elseif(isset($order['order_return']['order_return_status']) && $order['order_return']['order_return_status']==24)
                    <span class="badge badge-info" style="padding: 5px">
                      Return Request Approved
                    </span>
                  @elseif(isset($order['order_return']['order_return_status']) && $order['order_return']['order_return_status']==2 && $order['toatl_qty']==0)
                    <span class="badge badge-info" style="padding: 5px;background:#f0ad4e;">
                       Goods Returned
                    </span>
                    {{-- Return Status --}}
                  @else
                  {{-- Order Status --}}
                   
                    @if ($order['status_id']==18 || $order['status_id']==19 || $order['status_id']==13 || $order['status_id']==17)
                      <span class="badge" style="background:{{ $order['color_code'] }};color:#fff;padding: 5px">
                      {{ $order['status'] }}
                      </span> 
                    @elseif ($order['status_id']==15 || $order['status_id']==14)
                      <span class="badge" style="background:#5bc0de;color:#fff;padding: 5px">
                        Assigned for Shipment
                      </span>
                    @endif
                  {{-- Order Status --}}
                  @endif
                    
                  </div>
                  <div class="col-sm-4 text-right">
                    @if(isset($order['delivered_at'])&& $order['delivered_at']!=null)
                      <span>Delivered Date</span>: {{ date('d/m/Y',strtotime($order['delivered_at'])) }}
                    @endif
                  </div>
                </div>
                <?php 
                  if($order['payment_status']==1) $pay_status = 'Paid';
                  elseif($order['payment_status']==2) $pay_status = 'Partly Paid';
                  elseif($order['payment_status']==3) $pay_status = 'Not Paid';
                ?>
                <div class="body">
                  <div class="col-sm-5 text-left">
                    <span>Order Date</span>: {{ $order['create_date'] }}<br>
                    <span>Payment Method</span>: {{ $pay_status }}
                  </div>
                  <div class="col-sm-4 text-left">
                    <span>Total Items</span>: {{ $order['item_count'] }}&nbsp;&nbsp;
                    <span>Total Qty</span>: {{ $order['toatl_qty'] }}<br>
                    {{-- @if($order['order_return'])
                      <span>Return Status</span>: <span class="badge" style="background:{{$order['order_return']['statusName']['color_codes']}};color:#fff;padding: 5px">{{ $order['order_return']['statusName']['status_name'] }}</span>
                    @endif --}}
                  </div>
                  <div class="col-sm-3 text-right">
                    <?php $order_id = base64_encode($order['id']);?>
                    @if($order['days_count']<=14 && $order['status_id']==13)
                      @if(!isset($order['order_return']['order_return_status']) || $order['order_return']['order_return_status']==4)
                        <a href="{{ route('myorder.return.index',$order_id) }}" class="btn comment return"><i class="fas fa-reply-all"></i>&nbsp;Return</a>&nbsp;&nbsp;
                      @endif
                    @endif
                    <a href="{{ route('my-orders.show',$order_id) }}" class="btn view"><i class="far fa-eye"></i>&nbsp;View</a>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="no-data">Not data found.</div>
          @endif  
        </div>
        <div class="category-page-wrapper fotnav">
          <div class="result-inner">
            Showing {{($pagination['firstItem'])}} to {{($pagination['lastItem'])}} of {{ $pagination['total']}} ({{ $pagination['currentpage'] }} Pages)
          </div>
          <div class="pagination-inner">
            {{ $pagination['links'] }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection