@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route($back_route)}}">Order List</a></li>
              <li class="breadcrumb-item active">Edit Order</li>
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
          <a href="{{route($back_route)}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">View Order</h3>
              </div>
              <div class="card">
                <div class="card-body">
                    <div class="date-sec">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="date">Date *</label>
                          <input type="text" class="form-control" name="created_at" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                        <div class="col-sm-4">
                          <label for="order_no">Order Code *</label>
                          {!! Form::text('order_no',null,['class'=>'form-control','readonly']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="status">Order Status *</label>
                          {!! Form::select('order_status',$order_status, $order->order_status,['class'=>'form-control no-search ','disabled']) !!}
                        </div>
                      </div>
                    </div>

                    <div class="product-sec">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="customer_id">Customer *</label>
                            {!! Form::select('customer_id',$customers,  $order->customer->id,['class'=>'form-control','id'=>'customer','disabled']) !!}
                            {!! Form::hidden('customer_id',$order->customer_id,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger customer" style="display:none;">Customer is required. Please Select</span>
                        </div>
                        <div class="col-sm-4">
                          <label for="sales_rep_id">Sales Rep *</label>
                          {!! Form::select('sales_rep_id',$sales_rep,$order->salesrep->id,['class'=>'form-control','id'=>'sales_rep_id','disabled']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="sales_rep_id">Delivery Person *</label>

                          {!! Form::select('delivery_person_id',$delivery_persons,$order->delivery_person_id,['class'=>'form-control','id'=>'delivery_person_id','disabled']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="delivery-date">
                      <div class="form-group">
                      <?php $approximate_delivery_date=($order->approximate_delivery_date!="")?date('d/m/Y',strtotime($order->approximate_delivery_date)):""; ?>

                          <div class="col-sm-4">
                            {!! Form::label('doj', 'Delivery Date *') !!}
                            <input type="text" name="delivery_date" class="form-control date-picker" value="{{ $approximate_delivery_date }}" readonly />
                            </div>
                          </div>
                        </div>
                          
                        </div>

                    <div class="product-sec">
                      <div class="container my-4">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="heading-top">
                              <?php $total_products=\App\Models\OrderProducts::TotalDatas($order->id); ?>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Name</th>
                                <th scope="col"></th>
                                
                                <th scope="col"></th>
                                <th>
                                    Total Quantity:&nbsp;
                                    <span class="all_quantity">{{ $total_products->quantity }}</span>   
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($product_datas as $product)
                                <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                  <td class="expand-button"></td>
                                  <?php
                                    $total_based_products=\App\Models\OrderProducts::TotalDatas($order->id,$product['product_id']);
                                  ?>
                                  <td>{{ $product['product_name'] }}</td>
                                  <th></th>
                                  <th></th>
                                  <th class="total-head">
                                    Quantity: &nbsp;
                                    <span class="total_quantity">{{ $total_based_products->quantity }}</span>
                                  </th>
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
                                            <th>Quantity</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php $total_amount=$total_quantity=$final_price=0 ?>
                                          @foreach($product['product_variant'] as $key=>$variant)
                                            <?php 
                                              $option_count=$product['option_count'];
                                              $variation_details=\App\Models\OrderProducts::VariationPrice($product['product_id'],$variant['variant_id'],$order->id);
                                            ?>
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
                                              <td>
                                                <div class="form-group">{{ $variation_details['quantity'] }}</div>
                                              </td>
                                            </tr>
                                            <?php $total_quantity +=$variation_details['quantity']; ?>
                                          @endforeach
                                          <tr>
                                            <td colspan="{{count($product['options'])}}" class="text-right">Total:</td>
                                            <td class="total_quantity">{{ $total_quantity }}</td>
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
                      <div class="col-sm-12">
                    <div class="form-group">
                    <div class="col-sm-12">
                        <label for="purchase_date">Note</label>
                        {!! Form::textarea('notes', null,['class'=>'form-control summernote','disabled']) !!}
                      </div>
                    </div>
                      </div>
                    </div>


                    <div class="form-group">
                      <div class="col-sm-12 submit-sec">
                        <a href="{{ route($back_route) }}" class="btn  reset-btn">Cancel</a>
                        <button class="btn save-btn" type="submit">Save</button>
                      </div>
                    </div>
                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</div>       
  <style type="text/css">
    .form-group{display:flex;}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">
      $('.summernote').summernote('disable');
    </script>
  @endpush
@endsection