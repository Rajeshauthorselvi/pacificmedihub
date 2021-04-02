@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View RFQ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">RFQ</a></li>
              <li class="breadcrumb-item active">View RFQ</li>
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
          <a href="{{route('rfq.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid rfq show-page">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">View RFQ</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="action_sec">
                    <div class="clearfix"></div>
                    <ul class="list-unstyled">
                      <?php 
                      $disabled="";
                      if ($rfqs->status==13) {
                          $disabled="pointer-events:none;opacity:0.5";
                      }
                      ?>
                      <li>
                        <a href="{{ route('rfq.toOrder',$rfq_id) }}" class="place-order" onclick="return confirm('Are you sure want to Place Order?')" style="{{ $disabled }}">
                          <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                        </a>
                      </li>
                      <li>
                        <a href="{{ url('admin/rfq_pdf/'.$rfq_id) }}" class="pdf"><i class="fa fa-download"></i>&nbsp; PDF</a>
                      </li>
                      <li>
                        <a href="" class="email"><i class="fa fa-envelope"></i>&nbsp; Email</a>
                      </li>
                      <li>
                        <a href="{{ url('admin/rfq-comments/'.$rfq_id) }}" class="comment"><i class="fa fa-comment"></i>&nbsp; Comment</a>
                      </li>
                      <li>
                        <a href="{{ route('rfq.edit',$rfq_id) }}" class="edit" style="{{ $disabled }}">
                          <i class="fa fa-edit"></i>&nbsp; Edit
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('rfq.delete',$rfq_id) }}" class="delete" onclick="return confirm('Are you sure want to delete?')">
                          <i class="fa fa-trash"></i>&nbsp; Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="clearfix"></div>
                  <div class="address-sec col-sm-12">
                    <div class="col-sm-4">
                      <ul class="list-unstyled order-no-sec">
                        <li><h5>RFQ Code: <small>{{ $rfqs->order_no }}</small></h5></li>
                        <li><strong>Date: </strong>{{date('d F, Y',strtotime($rfqs->created_at))}}</li>
                        <li><strong>Status: </strong>{{$rfqs->statusName->status_name}}</li>
                        <li><strong>Sales Rep: </strong>{{isset($rfq->salesrep->emp_name)?$rfq->salesrep->emp_name:''}}</li>
                        @if(isset($rfqs->payTerm->name))
                          <li><strong>Payment Term: </strong>{{$rfqs->payTerm->name}}</li>
                        @endif
                      </ul>
                    </div>
                    <div class="col-sm-8">
                      <div class="address-block">
                        @if(isset($customer_address))
                          <div class="col-sm-6 customer address">
                            <div class="col-sm-2">
                              <span><i class="fas fa-user"></i></span>
                            </div>
                            <div class="col-sm-10">
                              <h4>{{$customer_address->name}}</h4>
                              <p>
                                <span>
                                  {{$customer_address->address_line1}},&nbsp;{{isset($customer_address->address_line2)?$customer_address->address_line2:''}}
                                </span><br>
                                <span>
                                  {{$customer_address->country->name}},&nbsp;{{isset($customer_address->state->name)?$customer_address->state->name:''}}
                                </span><br>
                                <span>
                                  {{isset($customer_address->city->name)?$customer_address->city->name:''}}&nbsp;-&nbsp;{{isset($customer_address->post_code)?$customer_address->post_code:''}}.
                                </span>
                              </p>
                              <p>
                                <span>Tel: {{$customer_address->mobile}}</span><br>
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
                           <?php $total_products=\App\Models\RFQProducts::TotalDatas($rfqs->id); ?>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>
                              <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity">{{ $total_products->quantity }}</span>   
                              </th>
                              {{-- <th scope="col">
                                  Total RFQ Price:&nbsp;
                                  <span class="all_rfq_price">{{ $total_products->rfq_price }}</span>  
                              </th> --}}
                              <th scope="col"></th>
                              <th>
                                  Total Amount:&nbsp;
                                  <span class="all_amount">{{ $total_products->sub_total }}</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($product_datas as $product)
                              <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                <td class="expand-button"></td>
                                <?php
                                  $total_based_products=\App\Models\RFQProducts::TotalDatas($rfqs->id,$product['product_id']);
                                  $sum_of_retail_qty=$total_based_products->retail_price*$total_based_products->quantity;
                                ?>
                                <td>{{ $product['product_name'] }}</td>
                                <th>Quantity: {{ $total_based_products->quantity }}</th>
                                <th>RFQ Price: {{ $total_based_products->rfq_price }}</th>
                                <th class="total-head">Total: <span class="get-total">@if($total_based_products->sub_total!=0) {{$total_based_products->sub_total}}
                                    @else {{$sum_of_retail_qty}} @endif</span></th>
                              </tr>
                              <tr class="hide-table-padding">
                                <td></td>
                                <td colspan="4">
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
                                          @if ($product['check_rfq_price_exists'])
                                            <th>Last RFQ Price</th>
                                          @endif
                                          <th>Quantity</th>
                                          <th>RFQ Price</th>
                                          <th>Subtotal</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $rfq_price=$total_amount=$total_quantity=0 ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                          <?php 
                                            $option_count=$product['option_count'];
                                            $variation_details=\App\Models\RFQProducts::VariationPrice($product['product_id'],$variant['variant_id'],$product['rfq_id']);
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
                                            @if ($product['check_rfq_price_exists'])
                                            <td>
                                                {{ $variation_details->last_rfq_price }}
                                            </td>
                                            @endif
                                            <td>
                                              <?php $high_value = isset($variation_details['rfq_price'])?$variation_details['rfq_price']:$variant['retail_price']; ?>
                                              {{ $high_value }}
                                            </td>
                                            <td>
                                              <?php $sub_total = $variation_details['quantity']*$high_value; ?>
                                              <div class="form-group">{{ $sub_total }}</div>
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$sub_total; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                          <?php $rfq_price +=$high_value; ?>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options'])+4 }}" class="text-right">Total:</td>
                                          <td class="total_quantity">{{ $total_quantity }}</td>
                                          <td class="total_amount">{{ $total_amount }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Total</td>
                              <td><span class="all_amount">{{$rfqs->total_amount}}</span></td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Order Discount</td>
                              <td><span class="order-discount">{{isset($rfqs->order_discount)?$rfqs->order_discount:'0.00'}}</span></td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Order Tax ({{isset($rfqs->oderTax->name)?$rfqs->oderTax->name:'No  Tax'}} @ {{isset($rfqs->oderTax->rate)?$rfqs->oderTax->rate:0.00}}%)</td>
                              <td id="orderTax">{{isset($rfqs->order_tax_amount)?$rfqs->order_tax_amount:0.00}}</td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Delivery Charge</td>
                              <td id="deliveryCharge">{{$rfqs->delivery_charge}}</td>
                            </tr>
                            <tr class="total-calculation">
                              <th colspan="4" class="title">Total Amount(SGD)</th>
                              <th id="total_amount_sgd">{{$rfqs->sgd_total_amount}}</th>
                            </tr>
                            @if(isset($rfqs->currencyCode->currency_code) && $rfqs->currencyCode->currency_code!='SGD')
                              @php $currency = 'contents'; @endphp 
                            @else
                              @php $currency = 'none'; @endphp
                            @endif
                            <tr class="total-calculation" style="display:{{$currency}}">
                              <th colspan="4" class="title">
                                Total Amount (<span class="exchange-code">{{isset($rfqs->currencyCode->currency_code)?$rfqs->currencyCode->currency_code:'SGD'}}</span>)
                              </th>
                              <th colspan="4" id="toatl_exchange_rate">{{$rfqs->exchange_total_amount}}</th>
                            </tr>
                            <tr><td colspan="6"></td></tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="footer-sec col-sm-12">
                      <div class="form-group">
                        <div class="notes-sec col-sm-6">
                          <label>Notes:</label>
                          {!! $rfqs->notes !!}
                        </div>
                        <div class="created-sec col-sm-3 pull-right">
                          Created by : {{ $creater_name }}<br>
                          Date: {{ date('d/m/Y H:i a',strtotime($rfqs->created_at)) }}
                        </div>
                      </div>
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
  @push('custom-scripts')
    <script type="text/javascript">
      $(document).ready(function($) {
        var sum = 0;
        $('.get-total').each(function() {
          sum += Number($(this).text());
        });
        $('.all_amount').text(sum.toFixed(2));
        var orderDiscount = $('.order-discount').text();
        var orderTax = $('#orderTax').text();
        var deliveryCharge = $('#deliveryCharge').text();
        var totalSGD = parseFloat(sum)+parseFloat(orderDiscount)+parseFloat(orderTax)+parseFloat(deliveryCharge);
        $('#total_amount_sgd').text(totalSGD.toFixed(2));
      });
    </script>
  @endpush
@endsection