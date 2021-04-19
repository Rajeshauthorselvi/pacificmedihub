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
        <form action="{{ route('my-rfq.update',$rfq->id) }}" method="post" id="rfqForm">
          @csrf
          <input name="_method" type="hidden" value="PATCH">
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
                    <tr><th>No.</th><th>Product Name</th><th class="text-center" style="width:18%">Quantity</th><th>Remove</th></tr>
                  </thead>
                  <tbody id="rfqItems">
                    @php $s_no = 1 @endphp
                    @foreach($rfq_products as $products)
                    <tr>
                      <input type="hidden" class="rfq-item-id" name="item[id][]" value="{{$products['rfq_items_id']}}">
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
                      <td>
                        <div class="number">
                          <input type="hidden" class="final-price" value="{{ $products['final_price'] }}">
                          <input type="hidden" name="item[sub_total][]" class="total" value="{{ $products['sub_total'] }}">
                          <span class="minus">-</span><input type="text" name="item[qty][]" class="qty-count" value="{{ $products['quantity'] }}" /><span class="plus">+</span>
                        </div>
                      </td>
                      <td>
                        <button class="btn delete-item" type="button" ><i class="far fa-trash-alt"></i></button>
                      </td>
                    </tr>
                    @php $s_no++ @endphp
                    @endforeach
                  </tbody>
                </table>
                <input type="hidden" class="sub-total" name="total_amount" value="{{ $rfq_data['total'] }}">
                <input type="hidden" class="grand-total" name="grand_total" value="{{ $rfq_data['grand_total'] }}">
                <input type="hidden" class="delivery-charge" name="delivery_charge" value="{{ $rfq_data['delivery_charge'] }}">
                <input type="hidden" class="tax" value="{{ $rfq_data['tax'] }}">
              </div>
            </div>
          
            <div class="address-sec">
              <div class="address-container col-sm-12">
                <div class="col-sm-6">
                  <div class="address-block" style="margin-right:5px;">
                    <h5>Delivery Address</h5>
                    <select class="form-control" id="delAddress" name="delivery_add">
                      @foreach($all_address as $del_add)
                        <option value="{{ $del_add->id }}" @if($del_add->id==$delivery->id) selected @endif>{{ $del_add->name }}, {{ $del_add->address_line1 }} {{ $del_add->address_line2 }}</option>
                      @endforeach
                      {{-- <option value="new">Add New</option> --}}
                    </select>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="address-block" style="margin-left:5px;">
                    <h5>Billing Address</h5>
                    <select class="form-control" id="billAddress" name="billing_add">
                      @foreach($all_address as $bill_add)
                        <option value="{{ $bill_add->id }}" @if($bill_add->id==$billing->id) selected @endif>{{ $bill_add->name }}, {{ $bill_add->address_line1 }} {{ $bill_add->address_line2 }}</option>
                      @endforeach
                      {{-- <option value="new">Add New</option> --}}
                    </select>
                  </div>
                </div>
              </div>

              <div class="delivery-method">
                <h5>Delivery Method</h5>
                <div class="form-group clearfix">
                  @foreach($delivery_method as $method)
                    <div class="icheck-info d-inline">
                      <input type="radio" name="delevery_method" @if($rfq->delivery_method_id==$method->id||$method->id==1)  checked @endif id="radioInfo{{$method->id}}" value="{{$method->id}}">
                      <label for="radioInfo{{$method->id}}">{{$method->delivery_method}}</label>
                    </div>
                  @endforeach
                </div>
              </div>
              <div class="footer-sec">
                <label>Note:</label>
                <div class="notes">
                  <textarea class="form-control rfq-notes" name="notes" rows="4">{{ $rfq_data['notes'] }}</textarea>
                </div>
              </div>

              <div class="action-box">
                <a href="{{ route('my-rfq.index') }}" class="btn reset-btn"> Cancel</a>
                <button type="button" class="btn save-btn"> Save</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


@push('custom-scripts')
  <script type="text/javascript">
    $(function ($) {
      $('#delAddress').select2();
    });
    $(function ($) {
      $('#billAddress').select2();
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
      var $input = $(this).parent().find('.qty-count');
      var count = parseInt($input.val()) - 1;
      count = count < 1 ? 1 : count;
      $input.val(count);
      $input.change();
      return false;
    });

    $('.plus').click(function () {
      var $input = $(this).parent().find('.qty-count');
      $input.val(parseInt($input.val()) + 1);
      $input.change();
      return false;
    });

    $('.save-btn').on('click',function(){
      $('#rfqForm').submit();
    });

    $('.delete-item').on('click',function(){
      var rowCount = $('#rfqItems tr').length;
      var rfqItemId = $(this).parent().parent().find('.rfq-item-id').val();
      if(rowCount==1){
        if(!confirm('Are you sure you want to delete this RFQ request.?')){
          return false;
        }else{
          deleteData(rfqItemId);
        }
      }else{
        if(!confirm('Are you sure you want to delete this Item.?')){
          return false;
        }else{
          deleteData(rfqItemId);
        }
      }
    }); 

    function deleteData(itemId)
    {
      var rfqId = <?php echo $rfq->id; ?>;
      $.ajax({
        url: "{{ url('my-rfq') }}/"+itemId,
        type: 'DELETE',
        data:{"_token": "{{ csrf_token() }}",rfq_id:rfqId}
      })
      .done(function(data) {
        if(data['redirect']==0){
          location.reload();
        }else{
          window.location.href = "{{ route('my-rfq.index') }}";
        }
      })
      .fail(function() {
        console.log("error");
      });
    }

    $(document).on('keyup', '.qty-count', function(event) {
      var qty         = $(this).val();
      var final_price = $(this).parent().find('.final-price').val();
      var total       = qty*final_price;
      $(this).parent().find('.total').val(total);
      var total_amount = SumTotal();
      var tax_rate = $('.tax').val();
      var deliverCharge = $('.delivery-charge').val();
      var grandTotalAmount = overallCalculation(total_amount,tax_rate,deliverCharge);
      console.log(total_amount,grandTotalAmount);
      $('.sub-total').val(total_amount);
      $('.grand-total').val(grandTotalAmount);
    });

    function SumTotal() {
      var sum = 0;
      $('.qty-count').parents('tbody').find('.total').each(function(){
        inputVal = this.value;
        sum += parseFloat(inputVal);
      });

      return sum;
    }

    function overallCalculation(total_amount,tax_rate,deliverCharge)
    {
      var grandTotal = parseFloat(total_amount)+parseFloat(tax_rate)+parseFloat(deliverCharge);
      return grandTotal;
    }

  </script>
@endpush
@endsection