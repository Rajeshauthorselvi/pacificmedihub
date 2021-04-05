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
                  <div class="col-sm-4 text-left">
                    <span>Total Items</span>: {{ $rfq['item_count'] }}
                  </div>
                  <div class="col-sm-4 text-center">
                    <span>Total Qty</span>: {{ $rfq['toatl_qty'] }}
                  </div>
                  <div class="col-sm-4 text-right">
                    <?php $rfq_id = base64_encode($rfq['id']);?>
                    <a href="{{ route('my.rfq.comments',$rfq_id) }}" class="btn comment"><i class="fas fa-comments"></i>&nbsp;Comments</a>&nbsp;&nbsp;
                    <a href="{{ route('my-rfq.show',$rfq_id) }}" class="btn view"><i class="far fa-eye"></i>&nbsp;View</a>
                  </div>
                </div>
              </div>
            @endforeach
          @else
            <div class="no-data">Not data found.</div>
          @endif  
        </div>
      </div>
    </div>
  </div>
</div>

@endsection