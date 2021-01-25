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
              <li class="breadcrumb-item"><a href="{{route('product.index')}}">Products</a></li>
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
          <a href="{{route('options.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
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
      <tr>
        <th>No</th>
        <th>Description</th>
                @foreach ($options as $option)
                  <th>{{ $option }}</th>
                @endforeach
        <th>Base Price</th>
        <th>Retail Price</th>
        <th>Minimum Price</th>
        <th>Sold Quantity</th>
        <th>Return Quantity</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php $s_no=1; ?>
      @foreach ($product_datas as $product)
      <tr>
        <td>{{ $s_no }}</td>
        <td>{{ $product['product_name'] }}</td>
                              @if (isset($product['option_value_id1']))
                                <td>{{ $product['option_value_id1'] }}</td>
                              @endif
                              @if (isset($product['option_value_id2']))
                                <td>{{ $product['option_value_id2'] }}</td>
                              @endif
                              @if (isset($product['option_value_id3']))
                                <td>{{ $product['option_value_id3'] }}</td>
                              @endif
                              @if (isset($product['option_value_id4']))
                                <td>{{ $product['option_value_id4'] }}</td>
                              @endif
                              @if (isset($product['option_value_id5']))
                                <td>{{ $product['option_value_id5'] }}</td>
                              @endif
                              <td>
                                {{ $product['base_price'] }}
                                <input type="hidden" class="base_price" value="{{ $product['base_price'] }}">
                              </td>
                              <td>{{ $product['retail_price'] }}</td>
                              <td>{{ $product['minimum_selling_price'] }}</td>
                              <td>{{ $product['quantity'] }}</td>
                              <td>
                                <input type="text" name="quantity[{{ $product['product_purchase_id'] }}]" class="form-control return_quantity" value="{{ $return_quantity[$product['product_purchase_id']] }}">
                                <input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                                <input type="hidden" name="sub_total[{{ $product['product_purchase_id'] }}]" class="sub_total" value="{{ $return_subtotal[$product['product_purchase_id']] }}">
                                <input type="hidden" name="product_id" value="{{ $product['product_id'] }}">
                              </td>
                              <td class="total-text">{{ $return_subtotal[$product['product_purchase_id']] }}</td>
      </tr>
      <?php $s_no++; ?>
      @endforeach
      <tr>
        <td colspan="9" class="text-right">Total</td>
        <td class="total_val">{{ array_sum($return_subtotal) }}</td>
      </tr>
      <tr>
        <td colspan="9" class="text-right">Total Return Amount</td>
        <td class="total_val">{{ array_sum($return_subtotal) }}</td>
      </tr>
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
    $(".total_val").html(sum);
});
      $(document).on('keyup', '.return_quantity', function(event) {
          
          var current_val=$(this).val();
          var parent=$(this).parents('tr');
          var base_price=parent.find('.base_price').val();

          var sub_total=current_val*base_price;
          parent.find('.sub_total').val(sub_total);
          parent.find('.total-text').text(sub_total);
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