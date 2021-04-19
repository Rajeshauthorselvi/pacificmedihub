@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a href="{{ route('my-rfq.index') }}" title="My RFQ">My RFQ</a></li>
      @if($data_from=='child')<li><a href="{{ route('child.rfq.index') }}" title="My RFQ">Child RFQ</a></li>@endif
      <li><a title="RFQ View">View</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
@if(Session::has('approval'))
  <div class="alert alert-info alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong>Your Request Sent successfully for Approval.!</strong>
  </div>
@endif
@if(Session::has('approved'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong>RFQ Approved successfully.!</strong>
  </div>
@endif
@if(Session::has('disapproved'))
  <div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button> 
    <strong>RFQ Disapproved successfully.!</strong>
  </div>
@endif
<div class="main">
	<div class="container">
		<div class="row">
		  <div id="column-left" class="col-sm-3 hidden-xs column-left">
		   	@include('front.customer.customer_menu')
      </div>
		  <div class="col-sm-9">
        <div class="go-back">
          <a href="@if($data_from=='child'){{ route('child.rfq.index') }}@else {{ route('my-rfq.index') }} @endif"><i class="fas fa-angle-left"></i> Back</a>
        </div>
        <div class="rfq view-block">
          <div class="action_sec">
            @if($rfq->status!=21 && $data_from!='child')
            <?php $rfq_id = base64_encode($rfq->id); ?>
              <ul class="list-unstyled">
                <li style="background-color: #216ea7;border-right: 1px solid #227bbb;@if($rfq->status==22||$rfq->status==23||$rfq->status==10) display:none; @endif">
                  @if(($check_parent->parent_company!=0)&&($rfq->send_approval==0))
                    <a href="{{ route('send.rfq.approval',$rfq_id) }}" class="place-order" onclick="return confirm('Are you sure want to Send Approval?')">
                    <i class="fa fa-plus-circle"></i>&nbsp; Send Approval
                    </a>
                  @elseif(($check_parent->parent_company!=0)&&($rfq->approval_status==1))
                    <a href="{{ route('my.rfq.order',$rfq_id) }}" class="place-order" onclick="return confirm('Are you sure want to Place Order?')">
                    <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                    </a>
                  @else
                    <a href="{{ route('my.rfq.order',$rfq_id) }}" class="place-order" onclick="return confirm('Are you sure want to Place Order?')">
                      <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                    </a>
                  @endif
                </li>
                <li style="background-color: #216ea7">
                  <a href="{{ route('my.rfq.pdf',$rfq->id) }}" class="pdf">
                    <i class="fa fa-download"></i>&nbsp; PDF
                  </a>
                </li>
                <li style="background-color: #43bfdd">
                  <a href="javascript:void(0);" class="email">
                    <i class="fa fa-envelope"></i>&nbsp; Email
                  </a>
                </li>
                <li style="background-color: #23bf79">
                  <a href="{{ route('my.rfq.comments',$rfq_id) }}" class="comment">
                    <i class="fa fa-comment"></i>&nbsp; Comment
                  </a>
                </li>
                <li style="background-color: #f6ac50;@if($rfq->status==22||$rfq->status==23||$rfq->status==24||$rfq->status==10) display:none; @endif">
                  <a href="{{ route('my-rfq.edit',$rfq_id) }}" class="edit">
                    <i class="fa fa-edit"></i>&nbsp; Edit
                  </a>
                </li>
              </ul>
            @endif
          </div>
          <div class="col-sm-12 address-sec">
            <div class="rfq-detail col-sm-4">
              <ul class="list-unstyled">
                <li><strong>RFQ Code : #{{ $rfq->order_no }}</strong></li>
                <li><span>Date: </span>{{ date('d/m/Y - H:i a',strtotime($rfq->created_at)) }}</li>
                <li><span>Sales Rep: </span>{{ isset($rfq->salesrep->emp_name)?$rfq->salesrep->emp_name:'' }}</li>
                @if(isset($rfq->delivery_method_id))
                  <li><span>Delivery Method</span>: {{ $rfq->deliveryMethod->delivery_method }}</li>
                @endif
                <?php 
                  if($rfq['status']==22) { $status = 'Request'; $color_code = '#f0ad4e'; }
                  elseif($rfq['status']==23) { $status = 'Pending Approval'; $color_code = '#ea8327'; }
                  elseif($rfq['status']==24) { $status = 'Quoted'; $color_code = '#5bc0de'; }
                  elseif($rfq['status']==25) { $status = 'Quoted'; $color_code = '#5bc0de'; }
                  elseif($rfq['status']==21) { $status = 'Rejected'; $color_code = '#dd4b39'; }
                  elseif($rfq['status']==10) { $status = 'Order Placed'; $color_code = '#00a65a'; }
                ?>
                <li><span>Status</span>: <span class="badge" style="background:{{$color_code}};color:#fff;padding: 5px">{{ $status }}</span></li>
                <?php 
                  $approvalStatus='';
                  if($rfq->approval_status==1) { $approvalStatus = 'Approved'; $color_code = '#00a65a'; }
                  elseif($rfq->approval_status==2) { $approvalStatus = 'Disapproved'; $color_code = '#ef4156'; }
                ?>
                @if($check_parent->parent_company!=0 && $rfq->send_approval !=0)
                  <br>
                  <li><span>Approval Status:</span> <span class="badge" style="background:{{$color_code}};color:#fff;padding: 5px">{{ $approvalStatus }}</span></li>
                @endif
              </ul>
              @if($data_from=='child'&& $rfq->send_approval !=0 && $rfq->approval_status==0)
                <div class="action-btns">
                  <div class="left"><a class="approved" state="1" id={{ $rfq->id }}>Approve</a></div>
                  <div class="right"><a class="disapproved" state="2" id={{ $rfq->id }}>Disapprove</a></div>
                </div>
              @endif
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
                  <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th class="text-center width">Price</th>
                    <th class="text-center width">Discount</th>
                    <th class="text-center width">Discount Price <br><small>(a)</small></th>
                    <th class="text-center width">QTY <br><small>(b)</small></th>
                    <th class="text-center width">Total <br><small>(a x b)</small></th>
                </thead>
                <tbody>
                  @php $s_no = 1 @endphp
                  @foreach($rfq_products as $products)
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
                    <td class="text-center">{{ number_format($products['rfq_price'],2,'.','') }}</td>
                    <td class="text-center">@if($discount_type[$products['product_id']]=='amount') $ @endif {{ $products['discount_value'] }} @if($discount_type[$products['product_id']]=='percentage') % @endif </td>
                    <td class="text-center">{{ number_format($products['final_price'],2,'.','') }}</td>
                    <td class="text-center">{{ $products['quantity'] }}</td>
                    <td class="text-center">{{ number_format($products['sub_total'],2,'.','') }}</td>
                  </tr>
                  @php $s_no++ @endphp
                  @endforeach
                  <tr class="outer">
                    <td colspan="6" class="text-right">Sub Total</td>
                    <td class="text-center">{{ number_format($rfq_data['total'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="6" class="text-right">Order Tax</td>
                    <td class="text-center">{{ number_format($rfq_data['tax'],2,'.','') }}</td>
                  </tr>
                  <tr class="outer">
                    <td colspan="6" class="text-right">Delivery Charge @if(isset($rfq->deliveryMethod))({{ $rfq->deliveryMethod->delivery_method }}) @endif</td>
                    <td class="text-center">{{ number_format($rfq->delivery_charge,2,'.','') }}</td>
                  </tr>
                  <tr class="outer grand">
                    <th colspan="6" class="text-right">Total Amount (SGD)</th>
                    <th class="text-center">{{ number_format($rfq_data['grand_total'],2,'.','') }}</th>
                  </tr>
                  @if($rfq_data['exchange_amount']!=null && ($rfq_data['grand_total']!=$rfq_data['exchange_amount']))
                    <tr class="outer grand">
                      <th colspan="6" class="text-right">Total Amount ({{$rfq_data['currency_code']}})</th>
                      <th colspan="6" class="text-center">{{$rfq_data['exchange_amount']}}</th>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
            
          <div class="footer-sec">
            <label>Note:</label>
            <div class="notes">{!! $rfq_data['notes'] !!}</div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
  .text-center.width{width:60px}
</style>
@push('custom-scripts')
  <script type="text/javascript">
    $('.approved,.disapproved').on('click',function(){
      var status = $(this).attr('state');
      var RFQID = $(this).attr('id');
      if(!confirm('Are you sure.?')) {return false};
      console.log(status,RFQID);
      $.ajax({
        url:"{{ route('response.child.rfq') }}",
        type:"POST",
        data:{
          "_token": "{{ csrf_token() }}",
          rfq_id:RFQID,
          rfq_status: status,
        },
      })
      .done(function() {
        console.log("success");
        location.reload();
      })
      .fail(function() {
        console.log("error");
      })
    });
    
  </script>
@endpush
@endsection