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
                    <ul class="list-unstyled" style="@if($rfqs->status==11) display:none; @endif">
                      @if($rfqs->status==25 || $rfqs->status==24)
                        <li>
                          <a href="{{ route('rfq.toOrder',$rfq_id) }}" class="place-order" onclick="return confirm('Are you sure want to Place Order?')">
                            <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                          </a>
                        </li>
                      @endif
                      <li>
                        <a href="{{ url('admin/rfq_pdf/'.$rfq_id) }}" class="pdf"><i class="fa fa-download"></i>&nbsp; PDF</a>
                      </li>
                      <li>
                        <a href="" class="email"><i class="fa fa-envelope"></i>&nbsp; Email</a>
                      </li>
                      <li>
                        <a href="{{ url('admin/rfq-comments/'.$rfq_id) }}" class="comment"><i class="fa fa-comment"></i>&nbsp; Message</a>
                      </li>
                      
                      @if($rfqs->status!=23 && $rfqs->status!=10)
                        <li>
                          <a href="{{ route('rfq.edit',$rfq_id) }}" class="edit">
                            <i class="fa fa-edit"></i>&nbsp; Edit
                          </a>
                        </li>
                      @endif
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
                        <?php 
                          if($rfqs->statusName->id==24){
                            $color_codes = "#5bc0de";
                            $status = "Quoted";
                          }else{
                            $color_codes = $rfqs->statusName->color_codes;
                            $status = $rfqs->statusName->status_name;
                          }
                        ?>
                        <li><h5>RFQ Code: <small>{{ $rfqs->order_no }}</small></h5></li>
                        <li><strong>Date: </strong>{{date('d F, Y',strtotime($rfqs->created_at))}}</li>
                        <li><strong>Status: </strong>{{ ($rfqs->statusName->id==10)?'Order Placed':$status }}</li>
                        <li><strong>Sales Rep: </strong>{{isset($rfqs->salesrep->emp_name)?$rfqs->salesrep->emp_name:''}}</li>
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
                              <h4>{{$admin_address->name}}</h4>
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
                           <?php $total_products=\App\Models\RFQProducts::allAmount($rfqs->id); ?>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col" colspan="2">Product Name</th>
                              <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity">{{ $total_products['total_qty'] }}</span>   
                              </th>
                              <th>
                                  Sub Total:&nbsp;
                                  <span class="all_amount">{{ $total_products['total_amount'] }}</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($product_datas as $product)
                              <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                <td class="expand-button"></td>
                                <?php
                                  $total_based_products=\App\Models\RFQProducts::totalAmount($rfqs->id,$product['product_id']);
                                ?>
                                <td colspan="2">{{ $product['product_name'] }}</td>
                                <th class="text-center">Quantity: {{ $total_based_products['qty']}}</th>
                                <th class="total-head">Total: <span class="get-total">{{$total_based_products['amount']}}
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
                                          <th class="width">Base Price</th>
                                          <th class="width">Retail Price</th>
                                          <th class="width">Minimum Selling Price</th>
                                          @if ($product['check_rfq_price_exists'])
                                            <th class="width">Last RFQ Price</th>
                                          @endif
                                          <th class="width">RFQ Price</th>
                                          <th class="width">Discount
                                            <div class="discount-type">
                                              <div class="icheck-info d-inline">
                                                <input type="radio" class="dis-type" @if($discount_type[$product['product_id']]=='percentage') checked @endif value="percentage" disabled><label for="percentage">%</label>
                                              </div>&nbsp;&nbsp;&nbsp;
                                              <div class="icheck-info d-inline">
                                                <input type="radio" class="dis-type" @if($discount_type[$product['product_id']]=='amount') checked @endif id="amount" value="amount" disabled><label for="amount">$</label>
                                              </div>
                                            </div>
                                          </th>
                                          <th class="width">Discount Price <br><small>(a)</small></th>
                                          <th class="width">QTY <br><small>(b)</small></th>
                                          <th class="width">Total <br><small>(a x b)</small></th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $rfq_price=$total_amount=$total_quantity=$last_rfq_pr=0 ?>
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
                                            @if ($product['check_rfq_price_exists'])
                                              <td>
                                                  {{ $variation_details->last_rfq_price }}
                                                  <?php $last_rfq_pr=1; ?>
                                              </td>
                                            @endif
                                            <td>
                                              <?php $rfq_price = isset($variation_details['rfq_price'])?$variation_details['rfq_price']:$variant['minimum_selling_price']; ?>
                                              {{ $rfq_price }}
                                            </td>
                                            <td>
                                              <div class="form-group">{{ (int)$variation_details['discount_value'] }}</div>
                                            </td>
                                            <td>
                                              <div class="form-group">{{ $variation_details['final_price'] }}</div>
                                            </td>
                                            <td>
                                              <div class="form-group">{{ $variation_details['quantity'] }}</div>
                                            </td>
                                            <td>
                                              <div class="form-group">{{ $variation_details['sub_total'] }}</div>
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$variation_details['sub_total']; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options'])+2 }}">
                                            <input type="text" class="form-control" name="product_description[{{ $product['product_id'] }}]" placeholder="Notes" value="{{ isset($product_description_notes[$product['product_id']])?$product_description_notes[$product['product_id']]:'' }}">
                                          </td>
                                          <td colspan="{{ $last_rfq_pr+4 }}" class="text-right">Total's :</td>
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
                              <td colspan="4" class="title">Sub Total</td>
                              <td><span class="all_amount">{{number_format($rfqs->total_amount,2,'.','')}}</span></td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Order Tax ({{isset($rfqs->oderTax->name)?$rfqs->oderTax->name:'No Tax'}} @ {{isset($rfqs->oderTax->rate)?$rfqs->oderTax->rate:0.00}}%)</td>
                              <td id="orderTax">{{number_format($order_tax,2,'.','')}}</td>
                            </tr>

                            <tr class="total-calculation">
                              <td colspan="4" class="title">Delivery Charge ({{ isset($rfqs->deliveryMethod->delivery_method)?$rfqs->deliveryMethod->delivery_method:'-' }})</td>
                              <td id="deliveryCharge">{{$delivery_charge}}</td>
                            </tr>
                            <tr class="total-calculation">
                              <th colspan="4" class="title">Total Amount(SGD)</th>
                              <th id="total_amount_sgd">{{number_format($rfqs->sgd_total_amount,2,'.','')}}</th>
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
                              <th colspan="4" id="toatl_exchange_rate">{{number_format($rfqs->exchange_total_amount,2,'.','')}}</th>
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
  <style type="text/css">
    th.width{width:90px;}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">
      $(document).ready(function($) {
        var sum = 0;
        $('.get-total').each(function() {
          sum += Number($(this).text());
        });
        $('.all_amount').text(sum.toFixed(2));
        var orderTax = $('#orderTax').text();
        var deliveryCharge = $('#deliveryCharge').text();
        var totalSGD = parseFloat(sum)+parseFloat(orderTax)+parseFloat(deliveryCharge);
        $('#total_amount_sgd').text(totalSGD.toFixed(2));
      });
    </script>
  @endpush
@endsection