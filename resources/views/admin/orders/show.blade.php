@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('orders.index')}}">Order List</a></li>
              <li class="breadcrumb-item active">Order Details</li>
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
          <a href="{{route('orders.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Order Details</h3>
              </div>
              <a href="{{route('orders.edit',$order->id)}}" class="btn emp-edit"><i class="far fa-edit"></i>&nbsp;Edit</a>
              <div class="card">
                <div class="card-body">
                  {!! Form::model($order,['method' => 'PATCH', 'route' =>['orders.update',$order->id]]) !!}
                    <div class="clearfix"></div>
                    <div class="date-sec">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="date">Date *</label>
                          <input type="text" class="form-control" name="created_at" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="order_no">Order No *</label>
                          {!! Form::text('order_no',null,['class'=>'form-control','readonly']) !!}
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="status">Status *</label>
                          {!! Form::select('order_status',$order_status, null,['class'=>'form-control select2bs4','disabled']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="product-sec">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="product">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec','readonly']) !!}
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="customer_id">Customer *</label>
                          {!! Form::select('customer_id',$customers,  null,['class'=>'form-control select2bs4','id'=>'customer','disabled','style'=>'pointer-events:none']) !!}
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="sales_rep_id">Sales Rep *</label>
                          {!! Form::select('sales_rep_id',$sales_rep,null,['class'=>'form-control select2bs4','disabled']) !!}
                        </div>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="product-sec">
                      <div class="container my-4">
                        <div class="table-responsive">
                          <table class="table">
                            <thead>
                              <?php
                                $total_products=\App\Models\OrderProducts::TotalDatas($order->id);
                              ?>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Name</th>

                                <th scope="col">
                                    Total Quantity:&nbsp;
                                    <span class="all_quantity">{{ $total_products->quantity }}</span>   
                                </th>
                                <th scope="col">
                                    Price:&nbsp;
                                    <span class="all_rfq_price">{{ $total_products->final_price  }}</span>  
                                </th>
                                <th>
                                    Total Amount:&nbsp;
                                    <span class="all_amount">{{ $total_products->sub_total }}</span>
                                </th>
                                <th scope="col"></th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($product_datas as $product)
                                <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                  <td class="expand-button"></td>
                                  <?php
                                    $total_based_products=\App\Models\OrderProducts::TotalDatas($order->id,$product['product_id']);
                                  ?>
                                  <th>{{ $product['product_name'] }}</th>
                                  <th>Quantity: {{ $total_based_products->quantity }}</th>
                                  <th>Price: {{ $total_based_products->final_price }}</th>
                                  <th>Total: {{ $total_based_products->sub_total }}</th>
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
                                            <th>Price</th>
                                            <th>Subtotal</th>
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
                                              <td class="base_price"> {{$variant['base_price']}} </td>
                                              <td> {{$variant['retail_price']}}</td>
                                              <td> {{$variant['minimum_selling_price']}} </td>
                                              <td>
                                                <div class="form-group">{{ $variation_details['quantity'] }}</div>
                                              </td>
                                              <td>
                                                <?php $high_value=$variation_details['final_price']; ?>
                                                {{ $high_value }}
                                              </td>
                                              <td>
                                                <div class="form-group">{{ $variation_details['sub_total'] }}</div>
                                              </td>
                                            </tr>
                                            <?php $total_amount +=$variation_details['sub_total']; ?>
                                            <?php $final_price +=$variation_details['final_price']; ?>
                                            <?php $total_quantity +=$variation_details['quantity']; ?>
                                          @endforeach
                                          <tr>
                                            <td colspan="{{ count($product['options'])+3 }}" class="text-right">Total:</td>
                                            <td class="total_quantity">{{ $total_quantity }}</td>
                                            <td class="final_price">{{ $final_price }}</td>
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
                          <table class="table table-bordered">
                              <tr>
                                <td class="text-right">Total: </td>
                                <td>{{ $total_products['sub_total'] }}</td>
                              </tr>
                              <tr>
                                <td class="text-right">Order Discount: </td>
                                <td>{{ isset($order->discount)?$order->discount:'0.00' }}</td>
                              </tr>
                              <tr>
                                <td class="text-right">Order Tax: </td>
                                <td>{{ isset($order->order_tax)?$order->order_tax:'0.00' }}</td>
                              </tr>
                              <tr>
                                <td class="text-right">Total Amount(SGD): </td>
                                <td>{{ $total_products['sub_total'] }}</td>
                              </tr>
                          </table>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                    <div class="panel panel-default payment-note-sec">
                      <div class="panel-body">
                      <div class="col-sm-4">
                          <div class="form-group">
                            <label for="purchase_date">Payment Reference Number</label>
                            {!! Form::text('payment_ref_no', null,['class'=>'form-control','readonly']) !!}
                          </div>
                      </div>
                      <div class="col-sm-4">
                          <div class="form-group">
                            <label for="purchase_date">Amount</label>
                            {!! Form::text('paid_amount', null,['class'=>'form-control','readonly']) !!}
                          </div>
                      </div>
                      <div class="col-sm-4">
                          <div class="form-group">
                            <label for="purchase_date">Paying By</label>
                            {!! Form::select('paying_by', $payment_method, null,['class'=>'form-control select2bs4','disabled']) !!}
                          </div>
                      </div>
                      <div class="col-sm-4">
                          <div class="form-group">
                            <label for="purchase_date">Payment Status *</label>
                            <?php $payment_status=[''=>'Please Select',1=>'Paid',2=>'Partly Paid',3=>'Not Paid']; ?>
                            {!! Form::select('payment_status',$payment_status, null,['class'=>'form-control select2bs4','disabled']) !!}
                          </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-sm-12">
                          <div class="form-group">
                            <label for="purchase_date">Payment Note</label>
                            {!! Form::textarea('payment_note', null,['class'=>'form-control summernote1']) !!}
                          </div>
                      </div>

                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('notes', null,['class'=>'form-control summernote2']) !!}
                        </div>
                    </div>
                  {!! Form::close() !!}
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
    .footer-sec .col-sm-6{float: left;}
    .notes-sec,.created-sec {background-color: #f6f6f6;padding: 15px;}
    .date-sec .col-sm-4, .product-sec .col-sm-4 {float: left;}
    .order-no-sec {line-height: 38px;font-size: 18px;}
    .action_sec li {float: left;width: 16.6%;text-align: center;}
    .action_sec li a {float: left;width: 100%;color: #fff;padding: 8px;}
    .place-order,.pdf{background-color:#3471a8;border-right: 1px solid #5cbfdd;}
    .email{background-color:#5cbfdd;}
    .comment{background-color:#48bb77;}
    .edit{background-color:#efab4f;}
    .delete{background-color:#d85450;}
    .address-sec {padding: 10px}
  </style>

  @push('custom-scripts')
    <script type="text/javascript">
      $('.summernote1').summernote('disable');
      $('.summernote2').summernote('disable');
    </script>
  @endpush
@endsection