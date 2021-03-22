@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
      <li><a href="{{ route('my-profile.index') }}" title="My Profile Page">My Profile</a></li>
			<li><a href="{{ route('my-rfq.index') }}" title="My RFQ">My RFQ</a></li>
      <li><a title="RFQ Edit">Edit</a></li>
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
          		<a class="link active" href="{{ route('my-rfq.index') }}">
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
        <div class="rfq edit view-block">
  
          <div class="rfq-detail">
            <ul class="list-unstyled">
              <li><strong>RFQ Code : #{{ $rfq->order_no }}</strong></li>
              <li><span>Date: </span>{{ date('d/m/Y - H:i a',strtotime($rfq->created_at)) }}</li>
              <li><span>Status: </span>{{ $rfq->statusName->status_name }}</li>
              <li><span>Sales Rep: </span>{{ isset($rfq->salesrep->emp_name)?$rfq->salesrep->emp_name:'' }}</li>
            </ul>
          </div>

          <div class="product-sec">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr><th>No.</th><th>Product Name</th><th class="text-center">Quantity</th><th>Remove</th></tr>
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
                    <td>
                      <div class="number">
                        <span class="minus">-</span><input type="text" class="qty-count" value="{{ $products['quantity'] }}" /><span class="plus">+</span>
                      </div>
                    </td>
                    <td>
                      <form method="POST" action="{{ route('my-rfq.destroy',$products['rfq_items_id']) }}"> @csrf 
                        <input name="_method" type="hidden" value="DELETE">
                        <input name="rfq_id" type="hidden" value="{{ $products['rfq_id'] }}">
                        <button class="btn" type="submit" onclick="return confirm('Are you sure, you want to delete this item?');"><i class="far fa-trash-alt"></i></button>
                      </form>
                    </td>
                  </tr>
                  @php $s_no++ @endphp
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          
          <div class="address-sec">
            <div class="address-container col-sm-12">
              <div class="col-sm-6">
                <div class="address-block" style="margin-right:5px;">
                <h4><i class="fas fa-truck"></i> Delivery Address</h4>
                <h6>{{ $delivery->name }}</h6>
                <div class="address-box">
                  <div class="show-address">{{ $delivery->address_line1 }}  {{ $delivery->address_line2 }}</div>
                </div>
                <div class="contact">
                  <span>Contact Number </span>: {{ $delivery->mobile }}
                </div>
                <a class="btn save-btn" id="changeDelivery">Change</a>
                </div>
              </div>
          
              <div class="col-sm-6">
                <div class="address-block" style="margin-left:5px;">
                <h4><i class="fas fa-file-invoice-dollar"></i> Billing Address</h4>
                <h6>{{ $billing->name }}</h6>
                <div class="address-box">
                  <div class="show-address">{{ $billing->address_line1 }}  {{ $billing->address_line2 }}</div>
                </div>
                <div class="contact">
                  <span>Contact Number </span>: {{ $billing->mobile }}
                </div>
                <a class="btn save-btn" id="changeBilling">Change</a>
                </div>
              </div>

              <input type="hidden" id="addID">
              <input type="hidden" id="addType">
              <input type="hidden" id="rfqID" value="{{ $rfq->id }}">

              <div class="change-address">
                <h5>Change <span class="change-name"></span> Address</h5>
                <select class="form-control select2bs4" id="allAddress">
                  <option value="" selected>Please Select Address</option>
                  @foreach($all_address as $address)
                    <option value="{{ $address->id }}">{{ $address->name }}, {{ $address->address_line1 }} {{ $address->address_line2 }}</option>
                  @endforeach
                  {{-- <option value="new">Add New</option> --}}
                </select>
                <a class="btn reset-btn" id="cancelChange">Close</a>
                <a class="btn save-btn" id="saveChange">Save</a>
              </div>

            </div>
            
            <div class="footer-sec">
              <label>Note:</label>
              <div class="notes">
                <textarea class="form-control" name="notes" rows="4">{{ $rfq_data['notes'] }}</textarea>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <style type="text/css">
    .rfq.edit.view-block .product-sec .number {
      margin: 0;
      text-align: center;
    }
   .rfq.edit.view-block .product-sec .number .minus, .rfq.edit.view-block .product-sec .number .plus {
      width: 30px;
      height: 35px;
      padding:5px;
    }
    .rfq.edit.view-block .product-sec .number input{ 
      height: 35px;
      width: 50px;
      font-size: 18px;
    }
    .rfq.edit.view-block .address-sec .footer-sec {
    width: 100%;
    float: left;
}
  .rfq.edit.view-block .address-sec .address-block {
    padding: 1rem;
    height: 250px;
}
.rfq.edit.view-block .address-block .btn.save-btn {
    margin-top: 10px;
}
.rfq.edit.view-block .address-container .change-address{
  display: none;
  width: 100%;
  float: left;
  margin-top: 1rem;
}
.rfq.edit.view-block .address-container .change-address a {
    margin-top: 10px;
}
  </style>

@push('custom-scripts')
  <script type="text/javascript">

    $(function ($) {
      $('.select2bs4').select2();
    });

    $('#changeDelivery').on('click',function(){
      $('.change-address').show();
      $('.change-name').text('Delivery');
      $('#addType').val(1);
      $('#allAddress').val(null).trigger("change");
    });

    $('#changeBilling').on('click',function(){
      $('.change-address').show();
      $('.change-name').text('Billing');
      $('#addType').val(2);
      $('#allAddress').val(null).trigger("change");
    });

    $('#cancelChange').on('click',function(){
      $('.change-address').hide();
      $('.change-name').text('');
    });

    $('#allAddress').on('change', function(event) {
      event.preventDefault();
      var adderssID = $('option:selected', this).val();
      $('#addID').val(adderssID);
    });

    $('#saveChange').on('click',function(){
      var getAddID = $('#addID').val();
      var getAddType = $('#addType').val();
      var getRFQID =$('#rfqID').val();

      if(getAddID==""||getAddID==null){
        alert('Please select address.!');
        return false;
      }

      $.ajax({
        url:"{{ route('change.address') }}",
        type:"POST",
        data:{
          "_token": "{{ csrf_token() }}",
          address_id:getAddID,
          address_type:getAddType,
          rfq_id:getRFQID
        },
      }).done(function(data) {
        window.location.reload();
      })

    });

    $(".qty-count").on('keyup',function(e) {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
        if(this.value==''){
          $(this).val(1); 
        }
    });

    $('.minus').click(function () {
      var $input = $(this).parent().find('input');
      var count = parseInt($input.val()) - 1;
      count = count < 1 ? 1 : count;
      $input.val(count);
      $input.change();
      return false;
    });
    $('.plus').click(function () {
      var $input = $(this).parent().find('input');
      $input.val(parseInt($input.val()) + 1);
      $input.change();
      return false;
    });

  </script>
@endpush
@endsection