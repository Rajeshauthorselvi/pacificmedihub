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
          <form action="{{ route('my-orders.store') }}" method="post" id="returnForm">
          @csrf
            <div class="col-sm-12 address-sec">
              <div class="rfq-detail col-sm-4">
                <ul class="list-unstyled">
                  <li><strong>Order Details</strong></li>
                  @if($order->order_status==18)<li><span>Invoice:</span>#{{ $order->invoice_no }}</li>@endif
                  <li><span>Order Code:</span>{{ $order->order_no }}</li>
                  <li><span>Order Date:</span>{{ date('d/m/Y',strtotime($order->created_at)) }}</li>
                  <li><span>Delivered Date:</span>{{ date('d/m/Y',strtotime($order->order_completed_at))}}</li>
                  <br>
                  @if(isset($order_return))
                    <li>
                      <span>Return Status</span>: <span class="badge" style="background:{{$order_return['statusName']['color_codes']}};color:#fff;padding: 5px">{{ $order_return['statusName']['status_name'] }}</span>
                    </li>
                  @endif
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
                        @if(isset($admin_address->company_email))
                          <span>Email: {{$admin_address->company_email}}</span>
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

            <div class="product-sec" style="margin-bottom:1.5rem">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <th>No.</th>
                    <th style="width:50%">Product Name</th>
                    <th class="text-center width">Qty Ordered <br><small>(a)</small></th>
                    <th class="text-center width">Qty Received <br><small>(b)</small></th>
                    <th class="text-center width">Damaged Qty</th>
                    <th class="text-center width">Missed Qty <br><small>(a - b)</small></th>
                    <th class="text-center width">Return Qty <br><small>(c)</small></th>
                    <th class="text-center width">Qty in Hand <br><small>(b - c)</small></th>
                  </thead>
                  <tbody>
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    @php $s_no = 1 @endphp
                    @foreach($order_products as $products)
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
                        <input type="hidden" name="item[order_product_id][]" value="{{ $products['product_id'] }}">
                      </td>
                      <td class="text-center"><span class="qty">{{ $products['quantity'] }}</span></td>
                      <td class="text-center">
                        <input type="text" name="item[recived_qty][]" class="form-control recived-qty" value="{{ $products['qty_received'] }}">
                      </td>
                      <td class="text-center">
                        <input type="text" name="item[damaged_qty][]" class="form-control damaged-qty" value="{{ $products['damaged_qty'] }}">
                      </td>
                      <td class="text-center">
                        <input type="text" name="item[missed_qty][]" class="form-control missed-qty" value="{{ $products['missed_qty'] }}" readonly>
                      </td>
                      <td class="text-center">
                        <input type="text" name="item[return_qty][]" class="form-control return-qty" value="{{ $products['return_qty'] }}" readonly>
                      </td>
                      <td class="text-center">
                        <input type="text" name="item[stock_qty][]" class="form-control stock-qty" value="{{ $products['stock_qty'] }}" readonly>
                      </td>
                    </tr>
                    @php $s_no++ @endphp
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <div class="footer-sec" style="width:100%;margin-bottom:1.5rem;">
              <label>Note:</label>
              <div class="notes">
                <textarea class="form-control rfq-notes" name="notes" rows="4"></textarea>
              </div>
            </div>
            <div class="action-box" style="margin-bottom:2rem;">
              <a href="{{ route('my-orders.index') }}" class="btn reset-btn"> Cancel</a>
              <button type="button" class="btn save-btn"> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@push('custom-scripts')
<script type="text/javascript">
  $(document).on('change', '.recived-qty', function(event) {
    if(this.value==''){
        $(this).val(0); 
      }
      var recivedQty = $(this).val();
      var orderedQty = $(this).parents('tr').find('.qty').text();
      if(parseInt(orderedQty)<parseInt(recivedQty)){
        $(this).val(orderedQty);
      }
      $(this).trigger('keyup');
  });
  $(document).on('change', '.damaged-qty', function(event) {
    if(this.value==''){
        $(this).val(0); 
      }
      var damagedQty = $(this).val();
      var recivedQty = $(this).parents('tr').find('.recived-qty').val();
      if(parseInt(recivedQty)<parseInt(damagedQty)){
        $(this).val(recivedQty);
      }
      $(this).trigger('keyup');
  });
  $(document).ready(function() {
    $('.recived-qty').on('keyup',function(){
      if(this.value==''){
        $(this).val(0); 
      }
      if (/\D/g.test(this.value))
      {
        this.value = this.value.replace(/\D/g, '');
      }
      var recivedQty = $(this).val();
      var orderedQty = $(this).parents('tr').find('.qty').text();
      var damagedQty = $(this).parents('tr').find('.damaged-qty').val();
      var missedQty  = parseInt(orderedQty)-parseInt(recivedQty);
      var stockQty   = parseInt(recivedQty)-parseInt(damagedQty);
      $(this).parents('tr').find('.missed-qty').val(missedQty);
      $(this).parents('tr').find('.return-qty').val(damagedQty);
      $(this).parents('tr').find('.stock-qty').val(stockQty);
    });

    $('.damaged-qty').on('keyup',function(){
      if(this.value==''){
        $(this).val(0); 
      }
      if (/\D/g.test(this.value))
      {
        this.value = this.value.replace(/\D/g, '');
      }
      var recivedQty = $(this).parents('tr').find('.recived-qty').val();
      var orderedQty = $(this).parents('tr').find('.qty').text();
      var damagedQty = $(this).val();
      var missedQty  = parseInt(orderedQty)-parseInt(recivedQty);
      var stockQty   = parseInt(recivedQty)-parseInt(damagedQty);
      $(this).parents('tr').find('.missed-qty').val(missedQty);
      $(this).parents('tr').find('.return-qty').val(damagedQty);
      $(this).parents('tr').find('.stock-qty').val(stockQty);
    });
  });

  

  $(document).on('click', '.save-btn', function(event) {
    var sum = 0;
    $('.recived-qty').each(function(index, el) {
      inputVal = $(this).val();
      sum += parseFloat(inputVal);
    });
    if(sum!=0){
      $('#returnForm').submit();
    }else{
      alert("Please enter recived Quantity's");
      return false;
    }
  });

</script>
@endpush
@endsection