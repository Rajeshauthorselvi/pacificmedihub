@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Purchase</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">Purchase</a></li>
              <li class="breadcrumb-item active">Edit Purchase</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <!-- Main content -->
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('purchase.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Purchase</h3>
              </div>
              <div class="card-body">
                  {{ Form::model($purchase,['method' => 'PATCH', 'route' =>['purchase.update',$purchase->id]]) }}
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Date *</label>
                          {!! Form::text('purchase_date', null,['class'=>'form-control','readonly'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Purchase Order Number *</label>
                          {!! Form::text('purchase_order_number',null,['class'=>'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('purchase_status',$order_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="col-sm-8">
                        <div class="form-group">
                          <label for="purchase_order_number">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control','id'=>'prodct-add-sec','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Vendor *</label>
                          {!! Form::select('vendor_id',$vendors, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="order-item-sec">

                  <div class="container my-4">
                    <div class="table-responsive">
                      <table class="table">
                          <thead>
                            <?php
                          $total_products=\App\Models\PurchaseProducts::TotalDatas($purchase->id);
                           ?>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>

                              <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity">{{ $total_products->quantity }}</span>   
                              </th>
                              <th>
                                  Total Amount:&nbsp;
                                  <span class="all_amount">{{ $total_products->sub_total }}</span>
                              </th>
                              <th scope="col"></th>
                            </tr>
                           
                          </thead>
                        <tbody>
                           @foreach ($purchase_products as $product)
                           <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                            <td class="expand-button"></td>
                             <?php
                                  $total_based_products=\App\Models\PurchaseProducts::TotalDatas($purchase->id,$product['product_id']);
                               ?>
                              <td>{{ $product['product_name'] }}</th>
                              <td>
                                Quantity:&nbsp;
                                <span class="total-quantity">{{ $total_based_products->quantity }}</span>
                              </td>
                              <td>
                                Total:&nbsp;
                                <span class="total">{{ $total_based_products->sub_total }}</span>
                              </td>
                          </tr>
                          <tr class="hide-table-padding">
                            <td></td>
                            <td colspan="5">
                              <div id="collapse{{ $product['product_id'] }}" class="collapse in p-3">

                      <table class="table table-bordered" style="width: 100%">
                        <thead>
                          <tr>
                            @foreach ($product['options'] as $option)
                              <th>{{ $option }}</th>
                            @endforeach
                            <th>Base Price</th>
                            <th>Retail Price</th>
                            <th>Minimum Selling Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php $total_amount=$total_quantity=$final_price=0 ?>
                       @foreach($product['product_variant'] as $key=>$variant)

                       <?php 
                         $option_count=$product['option_count'];
                         $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase->id);
                       ?>
                        <input type="hidden" name="variant[row_id][]" value="{{ $variation_details->id }}">
                          <tr class="parent_tr">
                            <td>{{$variant['option_value1']}}</td>
                            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                              <td>{{$variant['option_value2']}}</td>
                            @endif
                            @if($option_count==3||$option_count==4||$option_count==5)
                              <td>{{$variant['option_value3']}}</td>
                            @endif
                            @if($option_count==4||$option_count==5)
                              <td>{{$variant['option_value4']}}</td>
                            @endif
                            @if($option_count==5)
                              <td> {{$variant['option_value5']}} </td>
                            @endif
                            <td> {{$variant['base_price']}}
                            </td>
                            <td> {{$variant['retail_price']}}</td>
                            <td><span class="test"> {{$variant['minimum_selling_price']}} </span></td>
                            <td>
                              <div class="form-group">
                                  <input type="text" class="form-control stock_qty" onkeyup="validateNum(event,this);" name="variant[stock_qty][]" value="{{ $variation_details['quantity'] }}">
                              </div>
                            </td>
                         
                            <td>
                               <input type="hidden" name="variant[base_price][]" class="base_price" value="{{$variant['base_price']}}"> 
                              <?php $high_value=$variation_details['base_price']; ?>
                              <div class="form-group">
                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $variation_details['sub_total']  }}">
                                <span class="sub_total">{{ $variation_details['sub_total'] }}</span>
                              </div>
                            </td>
                          </tr>
                          <?php $total_amount +=$variation_details['sub_total']; ?>
                          <?php $total_quantity +=$variation_details['quantity']; ?>
                        @endforeach
                        <tr>
                          <td colspan="{{ count($product['options'])+3 }}" class="text-right">Total:</td>
                          <td><span class="total-quantity">{{ $total_quantity }}</span></td>
                          <td><span class="total">{{ $total_amount }}</span></td>
                        </tr>
                      </tbody>
                      </table>

                              </div>
                            </td>
                          </tr>
                           @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>

                  </div>
                  <div class="col-sm-12 order-total-sec">
{{-- 
                    <div class="panel panel-default">
                      <div class="panel-body">
                        <table class="table table-bordered total-sec">
                          <tr>
                            <td class="all_quantity text-center">
                             <b>Total Quantity: {{ $all_quantity }}</b>
                            </td>
                            <td class="all_total text-center">
                               <b>Grant Total: {{ $all_amount }}</b>
                            </td>
                          </tr>
                        </table>
                      </div>
                      </div>
                    </div> --}}
                  <div class="tax-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Order Tax</label>
                          {!! Form::text('order_tax', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Order Discount</label>
                          {!! Form::text('order_discount', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Term</label>
                          {!! Form::text('payment_term', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Status *</label>
                          <?php $payment_status=[''=>'Please Select',1=>'Paid',2=>'Partly Paid',3=>'Not Paid']; ?>
                          {!! Form::select('payment_status',$payment_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="panel panel-default payment-note-sec">
                    <div class="panel-body">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Reference Number</label>
                          {!! Form::text('payment_reference_no', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Amount</label>
                          {!! Form::text('amount', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Paying By</label>
                          {!! Form::select('paying_by', $payment_method, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Payment Note</label>
                          {!! Form::textarea('payment_note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    </div>
                  </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('purchase.index') }}" class="btn  reset-btn">
                        Cancel
                      </a>
                      <button class="btn save-btn" type="submit">
                        Save
                      </button>

                    </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<style type="text/css">
  .order-total-sec{
    background-color: #f1f1f1;
    padding-bottom: 5px;
    padding-top: 26px;
    padding-right: 20px;
    padding-left: 20px;
    box-shadow: none !important;    
  }
  .total-sec{
    background-color: #fcf8e3;
  }
</style>
@push('custom-scripts')
<script type="text/javascript">

      function validateNum(e , field) {
        var val = field.value;
        var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
        var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
        if (re.test(val)) {

          } else {
              val = re1.exec(val);
              if (val) {
                  field.value = val[0];
              } else {
                  field.value = "";
              }
          }
      }


    $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
          $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });

        $('.collapse').on('hide.bs.collapse', function (e) {
          console.log('Collapse Alert' + e.currentTarget.id);
        })
    });
  
  $(document).on('click', '.remove-item', function(event) {
    $(this).parents('tr').remove();
  });

    $(document).on('keyup', '.stock_qty', function(event) {
      if (/\D/g.test(this.value))
      {
        this.value = this.value.replace(/\D/g, '');
      }
      else{

          var base=$(this).parents('.parent_tr');
          var base_price=base.find('.base_price').val();
          var total_price=base_price*$(this).val();
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);          

          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var attr=$(this).parents('tbody').find('.collapse.show');
          var total_quantity=SumTotal('.collapse.show .stock_qty');
          console.log(total_quantity);

          $('.collapse.show').find('.total-quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total-quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));
      }
    });

function SumTotal(class_name) {
  var sum = 0;
  $(class_name).each(function(){
      sum += parseFloat(this.value);
  });
  return sum;
}

  var path ="{{ url('admin/product-search') }}";
  
    $('#prodct-add-sec').autocomplete({
      source: function( request, response) {
        $.ajax({
          url: "{{ url('admin/product-search') }}",
          data: {
            name: request.term,
            product_search_type:'product'
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {

        var check_length=$('.product_id[value='+ui.item.value+']').length;
        if (check_length>0) {
            alert('This product already exists');
            $(this).val('');
            return false;
        }
         $(this).val('');
        $('.no-match').hide();
        $.ajax({
          url: "{{ url('admin/product-search') }}",
          data: {
            product_search_type: 'options',
            product_id:ui.item.value
          },
        })
        .done(function(response) {
            $('.order-item-sec').append(response)
            SumAllTotal();
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });


        return false;
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });

</script>
@endpush
  <style type="text/css">
    .date-sec .col-sm-4, .tax-sec .col-sm-4 , .payment-note-sec .col-sm-4 {
      float: left;
    }
    .product-sec .col-sm-8,.product-sec .col-sm-4{
      float: left;
    }
  </style>
@endsection