@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
      <li><a href="{{ route('my-profile.index') }}" title="My Profile Page">My Profile</a></li>
			<li><a href="{{ route('myrfq.index') }}" title="My RFQ">My RFQ</a></li>
      <li><a title="RFQ View">View</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row">
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
        <div class="rfq view-block">
          <div class="action_sec">
            <ul class="list-unstyled">
              <li style="background-color: #216ea7;border-right: 1px solid #227bbb;">
                <a href="javascript:void(0);" class="place-order" onclick="return confirm('Are you sure want to Place Order?')">
                  <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                </a>
              </li>
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
              <li style="background-color: #f6ac50">
                <a href="javascript:void(0);" class="edit">
                  <i class="fa fa-edit"></i>&nbsp; Edit
                </a>
              </li>
            </ul>
          </div>

          <div class="col-sm-12 address-sec">
            <div class="rfq-detail col-sm-4">
              <ul class="list-unstyled">
                <li><strong>RFQ Code : #{{ $rfq->order_no }}</strong></li>
                <li><span>Date: </span>{{ date('d/m/Y - H:i a',strtotime($rfq->created_at)) }}</li>
                <li><span>Status: </span>{{ $rfq->statusName->status_name }}</li>
                <li><span>Sales Rep: </span>{{ isset($rfq->salesrep->emp_name)?$rfq->salesrep->emp_name:'' }}</li>
              </ul>
            </div>
            <div class="address-block col-sm-8">
              <div class="col-sm-6 customer address">
                <div class="col-sm-2 icon">
                  <span><i class="fas fa-user"></i></span>
                </div>
                <div class="col-sm-10 details">
                  <strong>{{$rfq->deliveryAddress->name}}</strong>
                  <p>
                    <span>
                      {{$rfq->deliveryAddress->address_line1}},&nbsp;{{isset($rfq->deliveryAddress->address_line2)?$rfq->deliveryAddress->address_line2:''}}
                    </span><br>
                    <span>
                      {{$rfq->deliveryAddress->country->name}},&nbsp;{{isset($rfq->deliveryAddress->state->name)?$rfq->deliveryAddress->state->name:''}}
                    </span><br>
                    <span>
                      {{isset($rfq->deliveryAddress->city->name)?$rfq->deliveryAddress->city->name:''}}&nbsp;-&nbsp;{{isset($rfq->deliveryAddress->post_code)?$rfq->deliveryAddress->post_code:''}}.
                    </span>
                  </p>
                  <p>
                    <span>Tel: {{$rfq->deliveryAddress->mobile}}</span><br>
                    <span>Email: {{Auth::user()->email}}</span>
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
                <div class="admin address">
                  <div class="icon">
                    <span><i class="far fa-building"></i></span>
                  </div>
                  <div class="details">
                  </div>
                </div>
              @endif
            </div>
          </div>

          <div class="product-sec">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr><th>No.</th><th>Product Name</th><th>Quantity</th><th>RFQ Price</th><th>Sub Total</th></tr>
                </thead>
                <tbody>
                  @php $s_no = 1 @endphp
                  @foreach($rfq_products as $products)
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
                    <td>{{ $products['rfq_price'] }}</td>
                    <td>{{ number_format($products['sub_total'],2,'.','') }}</td>
                  </tr>
                  @php $s_no++ @endphp
                  @endforeach
                  <tr class="outer">
                    <td colspan="4" class="text-right">Total</td><td>{{ $rfq_data['total'] }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="4" class="text-right">Order Discount</td><td>{{ $rfq_data['discount'] }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="4" class="text-right">Order Tax</td><td>{{ $rfq_data['tax'] }}</td>
                  </tr>
                  <tr class="outer grand">
                    <th colspan="4" class="text-right">Total Amount (SGD)</th><th>{{ $rfq_data['grand_total'] }}</th>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
            
          <div class="footer-sec">
            <label>Note:</label>
            <div class="notes">{{ $rfq_data['notes'] }}</div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection