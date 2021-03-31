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
              <li class="breadcrumb-item"><a href="{{route($back_route)}}">Order List</a></li>
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
          <a href="{{route($back_route)}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid orders show-page">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Order Details</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="action_sec">
                    <ul class="list-unstyled">
                      <li>
                        <a href="{{ url('admin/cop_pdf/'.$order->id) }}" class="pdf"><i class="fa fa-download"></i>&nbsp; PDF</a>
                      </li>
                      <li>
                        <a href="" class="email"><i class="fa fa-envelope"></i>&nbsp; Email</a>
                      </li>
                      <li>
                        <a href="{{ route($edit_route,$order->id) }}" class="edit">
                          <i class="fa fa-edit"></i>&nbsp; Edit
                        </a>
                      </li>
                      <li>
                        <a href="{{ route($delete_route,$order->id) }}" class="delete" onclick="return confirm('Are you sure want to delete?')">
                          <i class="fa fa-trash"></i>&nbsp; Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="address-sec col-sm-12">
                    <div class="col-sm-4">
                      <ul class="list-unstyled order-no-sec">
                        <li><h5>Order Code: <small>{{ $order->order_no }}</small></h5></li>
                        <li><strong>Date: </strong>{{date('d F, Y',strtotime($order->created_at))}}</li>
                        <li><strong>Status: </strong>{{$order->statusName->status_name}}</li>
                        <li><strong>Sales Rep: </strong>{{$order->salesrep->emp_name}}</li>
                        @if(isset($order->payTerm->name))
                          <li><strong>Payment Term: </strong>{{$order->payTerm->name}}</li>
                        @endif
                      </ul>
                    </div>
                    <div class="col-sm-8">
                      <div class="address-block">
                        @if(isset($admin_address))
                          <div class="col-sm-6 customer address">
                            <div class="col-sm-2">
                              <span><i class="fas fa-user"></i></span>
                            </div>
                            <div class="col-sm-10">
                              <h4>{{$customer_address->name}} {{$customer_address->last_name}}</h4>
                              <p>
                                <span>
                                  {{$customer_address->address->address_line1}},&nbsp;{{isset($customer_address->address->address_line2)?$customer_address->address->address_line2:''}}
                                </span><br>
                                <span>
                                  {{$customer_address->address->country->name}},&nbsp;{{isset($customer_address->address->state->name)?$customer_address->address->state->name:''}}
                                </span><br>
                                <span>
                                  {{isset($customer_address->address->city->name)?$customer_address->address->city->name:''}}&nbsp;-&nbsp;{{isset($customer_address->address->post_code)?$customer_address->address->post_code:''}}.
                                </span>
                              </p>
                              <p>
                                <span>Tel: {{$customer_address->address->mobile}}</span><br>
                                <span>Email: {{$customer_address->email}}</span>
                              </p>
                            </div>
                          </div>
                        @else
                          <div class="col-sm-6 customer address">
                            <div class="col-sm-2 icon">
                              <span><i class="fas fa-user"></i></span>
                            </div>
                            <div class="col-sm-10">
                            </div>
                          </div>
                        @endif
                        @if(isset($admin_address))
                          <div class="col-sm-6 admin address">
                            <div class="col-sm-2 icon">
                              <span><i class="far fa-building"></i></span>
                            </div>
                            <div class="col-sm-10">
                              <h4>{{$admin_address->company_name}}</h4>
                              <p>
                                <span>
                                  {{$admin_address->address_1}},&nbsp;{{$admin_address->address_2}}
                                </span><br>
                                <span>
                                  {{$admin_address->getCountry->name}},&nbsp;{{$admin_address->getState->name}}
                                </span><br>
                                <span>
                                  {{$admin_address->getCity->name}}&nbsp;-&nbsp;{{$admin_address->post_code}}.
                                </span>
                              </p>
                              <p>
                                <span>Tel: {{$admin_address->post_code}}</span><br>
                                <span>Email: {{$admin_address->company_email}}</span>
                              </p>
                            </div>
                          </div>
                        @else
                          <div class="col-sm-6 admin address">
                            <div class="col-sm-2 icon">
                              <span><i class="far fa-building"></i></span>
                            </div>
                            <div class="col-sm-10">
                            </div>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="container my-4">
                      <div class="table-responsive">
                        <table class="table">
                          <thead class="heading-top">
                            <?php
                              $total_products=\App\Models\OrderProducts::TotalDatas($order->id);
                            ?>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>
                              <th scope="col"></th>
                              {{-- <th scope="col">
                                  Price:&nbsp;
                                  <span class="all_rfq_price">{{ $total_products->final_price  }}</span>  
                              </th> --}}
                              <th scope="col"></th>
                              <th></th>
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
                                <th>Quantity: {{ $total_based_products->quantity }}</th>
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
                                          <?php $total_amount +=$variation_details['sub_total']; ?>
                                          <?php $final_price +=$variation_details['final_price']; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options']) }}" class="text-right">Total:</td>
                                          <td class="total_quantity">{{ $total_quantity }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                            @if($order->currencyCode->currency_code!='SGD')
                              @php $currency = 'contents'; @endphp 
                            @else
                              @php $currency = 'none'; @endphp
                            @endif
                            <tr class="total-calculation" id="total_exchange" style="display:{{$currency}}">
                              <th colspan="4" class="title">
                                Total Amount (<span class="exchange-code">{{$order->currencyCode->currency_code}}</span>)
                              </th>
                              <th colspan="4" id="toatl_exchange_rate">{{$order->exchange_total_amount}}</th>
                            </tr>
                            <tr><td colspan="6"></td></tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="footer-sec col-sm-12">
                    <div class="notes-sec col-sm-6">
                      <label>Notes:</label>
                      {!! $order->notes !!}
                    </div>
                    <div class="created-sec col-sm-3 pull-right">
                      Created by : {{$creater_name}}<br>
                      Date: {{ date('d/m/Y H:i a',strtotime($order->created_at)) }}
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