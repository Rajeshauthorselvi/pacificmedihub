@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
      <li><a href="{{ route('my-profile.index') }}" title="My Profile Page">My Profile</a></li>
			<li><a title="My RFQ">My RFQ</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row customer-page">
		  <div id="column-left" class="col-sm-3 hidden-xs column-left">
		   	<div class="column-block">
		     	<ul class="box-menu treeview-list treeview collapsable" >
		     		<li>
		     			<a class="link" href="{{ route('my-profile.index') }}">
           			<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
              </a>
            </li>
		        <li>
          		<a class="link active" href="{{ route('myrfq.index') }}">
              	<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
              </a>
            </li>
            <li>
            	<a class="link" href="javascript:void(0);">
             		<i class="fas fa-dolly-flatbed"></i>&nbsp;&nbsp;My Orders
             	</a>
            </li>
            <li>
              <a class="link" href="{{ route('wishlist.index') }}">
            		<i class="far fa-heart"></i>&nbsp;&nbsp;My Wishlist
            	</a>
            </li>
            <li>
            	<a class="link" href="javascript:void(0);">
            		<i class="fas fa-street-view"></i>&nbsp;&nbsp;My Address
            	</a>
            </li>
            <li>
            	<a class="link" href="{{route('customer.logout')}}">
            		<i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Logout
            	</a>
            </li>
          </ul>
        </div>
      </div>

		  <div class="col-sm-9">
        <h2>All RFQ</h2>
        <div class="rfq-container">
          @if(count($rfq_datas)!=0)
            @foreach($rfq_datas as $rfq)
              <div class="rfq-block">
                <?php 
                  if($rfq['status']=='Cancelled') $head_class = 'cancelled';
                  else $head_class = '';
                ?>
                <div class="header {{ $head_class }}">
                  <div class="col-sm-4 text-left">
                    <span>Order Date</span>: {{ $rfq['create_date'] }}
                  </div>
                  <div class="col-sm-4 text-center">
                    <span>Status</span>: {{ $rfq['status'] }}
                  </div>
                  <div class="col-sm-4 text-right">
                    <span>RFQ Code</span>: #{{ $rfq['code'] }}
                  </div>
                </div>

                <div class="body {{ $head_class }}">
                  <div class="col-sm-4 text-left">
                    <span>Total Items</span>: {{ $rfq['item_count'] }}
                  </div>
                  <div class="col-sm-4 text-center">
                    <span>Total Qty</span>: {{ $rfq['toatl_qty'] }}
                  </div>
                  <div class="col-sm-4 text-right">
                    <?php $rfq_id = base64_encode($rfq['id']);?>
                    <a href="javascript:void(0);" class="btn comment"><i class="fas fa-comments"></i>&nbsp;Comments</a>&nbsp;&nbsp;
                    <a href="{{ route('myrfq.show',$rfq_id) }}" class="btn view"><i class="far fa-eye"></i>&nbsp;View</a>
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