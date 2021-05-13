@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="My RFQ">My RFQ</a></li>
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
        <h2>All RFQ</h2>
        <div class="rfq-container">
          @if(count($rfq_datas)!=0)
            @foreach($rfq_datas as $rfq)
              <div class="rfq-block">
                <?php 
                  $color_code = '';
                  $status='';
                  if($rfq['status']==22) { $status = 'Request'; $color_code = '#f0ad4e'; }
                  elseif($rfq['status']==23) { $status = 'Pending Approval'; $color_code = '#ea8327'; }
                  elseif($rfq['status']==24) { $status = 'Approved'; $color_code = '#00a65a'; }
                  elseif($rfq['status']==25) { $status = 'Quoted'; $color_code = '#5bc0de'; }
                  elseif($rfq['status']==21) { $status = 'Rejected'; $color_code = '#dd4b39'; }
                  elseif($rfq['status']==10) { $status = 'Order Placed'; $color_code = '#00a65a'; }
                  elseif($rfq['status']==11) { $status = 'Cancelled'; $color_code = '#dd4b39'; }
                ?>
                <div class="header">
                  <div class="col-sm-4 text-left">
                    <span>Order Date</span>: {{ $rfq['create_date'] }}
                  </div>
                  <div class="col-sm-4 text-center">
                    <span>Status</span>: <span class="badge" style="background:{{$color_code}};color:#fff;padding: 5px">{{ $status }}</span>
                  </div>
                  <div class="col-sm-4 text-right">
                    <span>RFQ Code</span>: #{{ $rfq['code'] }}
                  </div>
                </div>

                <div class="body">
                  <div class="col-sm-5 text-left">
                    <span>Sales Rep</span>: {{ $rfq['sales_rep'] }}<br>
                    <span>Delivery Method</span>: {{ $rfq['delivery_method'] }}
                  </div>
                  <div class="col-sm-2 text-center">
                    <span>Total Items</span>: {{ $rfq['item_count'] }}<br>
                    <span>Total Qty</span>: {{ $rfq['toatl_qty'] }}
                  </div>
                  <div class="col-sm-5 text-right">
                    <?php $rfq_id = base64_encode($rfq['id']);?>
                    @if($rfq['status']!=11)
                      <a href="{{ route('my.rfq.comments',$rfq_id) }}" class="btn comment"><i class="fas fa-comments"></i>&nbsp;Message</a>
                    @endif
                    <a href="{{ route('my-rfq.show',$rfq_id) }}" class="btn view"><i class="far fa-eye"></i>&nbsp;View</a>
                    @if($rfq['status']==25)
                      <a href="{{ route('my-rfq.edit',$rfq_id) }}" class="btn view"><i class="far fa-edit"></i>&nbsp;Edit</a>
                    @endif
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