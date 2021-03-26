@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
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
		   	@include('front.customer.customer_menu')
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
                <tbody id="rfqItems">
                  @php $s_no = 1 @endphp
                  @foreach($rfq_products as $products)
                  <tr>
                    <input type="hidden" class="rfq-item-id" name="rfq_id" value="{{$products['rfq_items_id']}}">
                    <input type="hidden" class="rfq-item-qty" name="quantity" value="">
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
                      <form method="POST" action="{{ route('my-rfq.destroy',$products['rfq_items_id']) }}" id="deleteItem"> @csrf 
                        <input name="_method" type="hidden" value="DELETE">
                        <input name="rfq_id" type="hidden" value="{{ $products['rfq_id'] }}">
                        <button class="btn delete-item" type="button" ><i class="far fa-trash-alt"></i></button>
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
            
            <form action="{{ route('my-rfq.update',$rfq->id) }}" method="post">
              @csrf
              <input name="_method" type="hidden" value="PATCH">
              <div class="footer-sec">
                <label>Note:</label>
                <div class="notes">
                  <textarea class="form-control rfq-notes" name="notes" rows="4">{{ $rfq_data['notes'] }}</textarea>
                </div>
              </div>

              <div class="action-box">
                <a href="{{ route('my-rfq.index') }}" class="btn reset-btn"> Cancel</a>
                <button type="submit" class="btn save-btn"> Save</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>

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

      var current_qty = $(this).parent().find('input').val();

      if(current_qty==0){
        return false;
      }else{
        var rfqItemId = $(this).parents('tr').find('.rfq-item-id').val();
        var curntQty = $(this).parent().find('input').val();

        console.log(rfqItemId,curntQty);

        $.ajax({
          url:"{{ url('updaterfqitem') }}",
          type:"PUT",
          data:{
            "_token": "{{ csrf_token() }}",
            rfq_item_id:rfqItemId,
            qty_count: curntQty
          },
        })
      }

      return false;
    });

    $('.plus').click(function () {
      var $input = $(this).parent().find('input');
      $input.val(parseInt($input.val()) + 1);
      $input.change();

      var rfqItemId = $(this).parents('tr').find('.rfq-item-id').val();
      var curntQty = $(this).parent().find('input').val();

      console.log(rfqItemId,curntQty);

      $.ajax({
        url:"{{ url('updaterfqitem') }}",
        type:"PUT",
        data:{
          "_token": "{{ csrf_token() }}",
          rfq_item_id:rfqItemId,
          qty_count: curntQty
        },
      })
      return false;
    });

    $('.delete-item').on('click',function(){
      var rowCount = $('#rfqItems tr').length;

      if(rowCount==1){
        if(!confirm('Are you sure you want to delete this RFQ request.?')){
          return false;
        }else{
          $('#deleteItem').submit();
        }
      }else{
        if(!confirm('Are you sure you want to delete this Item.?')){
          return false;
        }else{
          $('#deleteItem').submit();
        }
      }
    });

  </script>
@endpush
@endsection