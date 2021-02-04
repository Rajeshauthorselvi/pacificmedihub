@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock-In-Transit</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item active">Stock-In-Transit</li>
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
          <a href="{{route('stock-in-transit.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                  <h3 class="card-title">Edit</h3>
              </div>
              <div class="card-body">
                  {{ Form::model($purchase,['method' => 'PATCH', 'route' =>['stock-in-transit.update',$purchase->id]]) }}
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Date</label>
                          {!! Form::text('purchase_date', null,['class'=>'form-control','readonly'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Purchase Order Number</label>
                          {!! Form::text('purchase_order_number',null,['class'=>'form-control','readonly'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('purchase_status',$order_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Vendor *</label>
                          {!! Form::select('vendor_id',$vendors, null,['class'=>'form-control','style'=>'pointer-events:none','readonly'=>true]) !!}
                        </div>
                    </div>
                  <div class="product-sec col-sm-12">
                    <label>Order Items</label>
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
                            <th>Qty Ordered</th>
                            <th>Qty Received</th>
                            <th>Issue Qty</th>
                            <th>Reason</th>
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
                            </td>
                            <td>{{ $variation_details['quantity'] }}</td>
                            <td>
                              <div class="form-group">
                                  <input type="text" name="variant[qty_received][]" value="{{ isset($variation_details['qty_received'])?$variation_details['qty_received']:$variation_details['quantity'] }}" class="form-control">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                  <input type="text" name="variant[issued_qty][]" value="{{ isset($variation_details['issue_quantity'])?$variation_details['issue_quantity']:0 }}" class="form-control">
                              </div>
                            </td>
                            <td>
                                <input type="text" name="variant[reason][]" value="{{ isset($variation_details['reason'])?$variation_details['reason']:'-' }}" class="form-control">
                              </td>
                          </tr>
                          <?php $total_amount +=$variation_details['sub_total']; ?>
                          <?php $total_quantity +=$variation_details['quantity']; ?>
                        @endforeach
                        <tr>
                          <td colspan="{{ count($product['options'])+1 }}" class="text-right">Total:</td>
                          <td class="total_quantity">{{ $total_quantity }}</td>
                          <td class="total_amount">{{ $total_amount }}</td>
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
                  <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('stock_notes', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('stock-in-transit.index') }}" class="btn  reset-btn">
                        Cancel
                      </a>
                      <button class="btn save-btn" type="submit">
                        Submit
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

  @push('custom-scripts')
    <script type="text/javascript">
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
  <style type="text/css">
    .date-sec .col-sm-4, .tax-sec .col-sm-4 , .payment-note-sec .col-sm-4 {
      float: left;
    }
    .product-sec .col-sm-8,.product-sec .col-sm-4{
      float: left;
    }
  </style>  
@endsection