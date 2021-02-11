@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Return</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item active">Add Return</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    <!-- Main content -->
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('return.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                  <h3 class="card-title">Add Return</h3>
              </div>
              <div class="card-body">
                  {{ Form::model($purchase_detail,['method' => 'PATCH', 'route' =>['return.update',$purchase_detail->id]]) }}
    <div class="col-sm-12 product-sec">
  <div class="col-sm-4">
    <div class="form-group">
      <label>Date</label>
      <input class="form-control" readonly value="{{ date('d-m-Y',strtotime($purchase->created_at)) }}">
    </div>
  </div>
  <div class="col-sm-4">
    <div class="form-group">
      <label>Vendor Name</label>
      <input class="form-control" value="{{ $vendor_name }}" readonly>
    </div>
  </div>
</div>

<div class="col-sm-12">
  <table class="table table-bordered">
                          <thead>
                            <?php
                          $total_products=\App\Models\PurchaseProducts::TotalDatas($purchase->id);
                           ?>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>

                           {{--    <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity">{{ $total_products->quantity }}</span>   
                              </th>
                              <th>
                                  Total Amount:&nbsp;
                                  <span class="all_amount">{{ $total_products->sub_total }}</span>
                              </th> --}}
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
                              <td>{{ $product['product_name'] }}</td>
                             {{--  <td>
                                Quantity:&nbsp;
                                <span class="total-quantity">{{ $total_based_products->quantity }}</span>
                              </td>
                              <td>
                                Total:&nbsp;
                                <span class="total">{{ $total_based_products->sub_total }}</span>
                              </td> --}}
                          </tr>
                          <tr class="hide-table-padding">
                            <td></td>
                            <td colspan="5">
                              <div id="collapse{{ $product['product_id'] }}" class="collapse in p-3">

                      <table class="table table-bordered" style="width: 100%">
                        <thead>
              <th>No</th>
                      @foreach ($product['options'] as $option)
                        <th>{{ $option }}</th>
                      @endforeach
              {{-- <th>Base Price</th> --}}
              {{-- <th>Retail Price</th> --}}
              {{-- <th>Minimum Price</th> --}}
              <th>Purchase Price</th>
              <th>Total Quantity</th>
              <th>Reason</th>
              <th>Return Quantity</th>
              <th>Subtotal</th>
            </thead>
                        <tbody>
                        <?php $total_amount=$total_quantity=$final_price=0 ?>
                        <?php $s_no=1; ?>
                       @foreach($product['product_variant'] as $key=>$variant)

                       <?php 
                         $option_count=$product['option_count'];
                         $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase_id);
                         $product_price=\App\Models\PurchaseProducts::ProductPrice($product['product_id'],$variant['variant_id']);

                         //$return_quantity=\App\Models\PurchaseProductReturn::ReturnPrice($product['product_id'],$variant['variant_id'],$purchase_return_id);


                       ?>
                        {{-- <input type="hidden" name="variant[row_id][]" value="{{ $variation_details->id }}"> --}}
                          <tr class="parent_tr">
                            <td>{{ $key+1 }}</td>
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
                            {{-- <td> {{$variant['base_price']}} </td> --}}
                            {{-- <td> {{$variant['retail_price']}}</td> --}}
                            {{-- <td><span class="test"> {{$variant['minimum_selling_price']}} </span></td> --}}
                            <td>
                              <input type="hidden" class="purchase_price" value="{{ $product_price }}">
                              {{ $product_price }}
                            </td>
                            <td>{{ $variation_details['quantity'] }}</td>
                            <td>{{ $variation_details['reason'] }}</td>
                            <td>
                              <?php $max_price=$product_price*$variation_details['issue_quantity']; ?>
                                <input type="text" name="quantity[{{ $variation_details['id'] }}]" class="form-control return_quantity" value="{{ $return_quantity[$variation_details['id']] }}" max-count="{{ $variation_details['quantity'] }}">

                                <input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                                <input type="hidden" name="sub_total[{{ $variation_details['id'] }}]" class="sub_total" value="{{ $return_sub_total[$variation_details['id']]  }}">
                                <input type="hidden" name="product_id[{{ $variation_details['id'] }}]" value="{{ $variant['product_id'] }}">
                              </td>
                              
                              <td>
                                <span class="sub_total_text">
                                  {{ $max_price }}
                                </span>
                              </td>
                          </tr>
                          <?php $total_amount += $return_sub_total[$variation_details['id']] ; ?>
                          <?php $total_quantity +=$return_quantity[$variation_details['id']]; ?>
                        @endforeach
                        <tr>
                          <td colspan="{{ count($product['options'])+4 }}" class="text-right">Total:</td>
                          <td><span class="total-quantity">{{ $total_quantity }}</span></td>
                          <td><span class="total-amount">{{ $total_amount }}</span></td>
                        </tr>
                      </tbody>
                      </table>

                              </div>
                            </td>
                          </tr>
                          <?php $s_no++; ?>
                           @endforeach
                        </tbody>
                      </table>
</div>
<div class="col-sm-12">
  <div class="col-sm-6 pull-left">
    <label>Return Note</label>
    <textarea class="form-control summernote" name="return_notes"> {{ $purchase_detail->return_notes }} </textarea>
  </div>
  <div class="col-sm-6 pull-left">
    <label>Staff Note</label>
    <textarea class="form-control summernote" name="staff_notes"> {{ $purchase_detail->staff_notes }} </textarea>
  </div>
</div>
<div class="clearfix"></div>
<br>
<br>                  <div class="form-group col-sm-12">
                    <a href="{{route('return.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" class="btn save-btn">Save</button>
                  </div>
<style type="text/css">
  .product-sec .col-sm-4{
    float: left;
  }
</style>


                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @push('custom-scripts')
    <script type="text/javascript">
$(document).on("keyup", ".return_quantity", function() {
    var sum = 0;
    $(".sub_total").each(function(){
        sum += +$(this).val();
    });
    $(".total").html(sum);
});
      $(document).on('keyup', '.return_quantity', function(event) {
          
          if (/\D/g.test(this.value)){
            this.value = this.value.replace(/\D/g, '');
            return false
          }
          var current_val=$(this).val();
            var parent=$(this).parents('.parent_tr');
            var base_price=parent.find('.purchase_price').val();
            var sub_total=parseInt(current_val)*parseInt(base_price);
            parent.find('.sub_total').val(sub_total);
            parent.find('.sub_total_text').text(sub_total);

            var all_total=$(this).parents('.table').find('.sub_total_text');
            var all_quantity=$(this).parents('.table').find('.return_quantity');

             var total = 0;
            $.each(all_total, function(index, val) {
               total += parseInt($(this).text());
            });
          $(".total-amount").html(total);


          var quantity = 0;
          $(all_quantity).each(function(){
              quantity += parseInt($(this).val());
          });
          $(".total-quantity").html(quantity);



      });


      //Validate Number
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
      $(function() {
        $('.validateTxt').keydown(function (e) {
          if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault();
          } else {
            var key = e.keyCode;
            if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
              e.preventDefault();
            }
          }
        });
      });
    </script>
  @endpush

@endsection