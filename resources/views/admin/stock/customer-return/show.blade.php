@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Customer Return</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item active">Customer Return</li>
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
          <a href="{{route('customer-return.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Return Details</h3>
              </div>
              <div class="card-body">
                <div class="date-sec">
                  <div class="form-group">
                    <div class="col-sm-4">
                      <label for="return">Date</label>
                      <input type="hidden" name="return_id" value="{{ $return->id }}">
                      {!! Form::text('return_date', $return->created_at,['class'=>'form-control','readonly'=>true]) !!}
                    </div>
                    <div class="col-sm-4">
                      <label for="order_number">Order Number</label>
                      {!! Form::text('order_number',$return->orderData->order_no,['class'=>'form-control','readonly'=>true]) !!}
                      <input type="hidden" name="order_id" value="{{ $return->orderData->id }}">
                    </div>
                    <div class="col-sm-4">
                      <label for="customer">Customer *</label>
                      {!! Form::text('customer',$return->customer->name,['class'=>'form-control','readonly'=>true]) !!}
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="product-sec col-sm-12">
                      <label>Order Items</label>
                      <div class="container my-4">
                        <div class="table-responsive">
                          <table class="table">
                            <tbody>
                            @foreach ($return_products as $product)
                              <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                <td class="expand-button"></td>
                                <td>{{ $product['product_name'] }}</td>
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
                                          <th class="quantity-info">Return Qty</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($product['product_variant'] as $keyv=> $variant)
                                          <?php 
                                            $option_count = $product['option_count'];
                                            $variation_details=\App\Models\OrderProducts::VariationPrice($product['product_id'],$variant['variant_id'],$return->order_id);
                                            $return_details=\App\Models\CustomerOrderReturnProducts::qtyData($product['product_id'],$variant['variant_id'],$return->order_id);
                                          ?>
                                          @if (isset($return_details))
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
                                            <td>{{ $return_details['return_quantity'] }}</td>
                                          </tr>
                                          @endif
                                        @endforeach
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
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <label for="purchase_date">Customer Note</label>
                    {!! Form::textarea('stock_notes', null,['class'=>'form-control','rows'=>4,'readonly'=>true]) !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
               

  <style type="text/css">
    .form-group{display:flex;}
  </style>

@endsection