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
                    <span>Status</span>: <span class="badge" style="background:{{$order['color_code']}};color:#fff;padding: 5px">{{ $order['status'] }}</span>
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
                  <div class="col-sm-3 text-center">
                    <span>Total Items</span>: {{ $order['item_count'] }}<br>
                    <span>Total Qty</span>: {{ $order['toatl_qty'] }}
                  </div>
                  <div class="col-sm-4 text-right">
                    <?php $order_id = base64_encode($order['id']);?>
                    <a href="javascript:void(0);" class="btn comment"><i class="fas fa-comments"></i>&nbsp;Comments</a>&nbsp;&nbsp;
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