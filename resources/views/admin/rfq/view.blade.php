@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List RFQ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">RFQ</a></li>
              <li class="breadcrumb-item active">List RFQ</li>
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
      <div class="container-fluid rfq-show-page">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All RFQ</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="action_sec">
                    <div class="clearfix"></div>
                    <ul class="list-unstyled">
                      <li>
                        <a href="{{ route('orders.create',['rfq_id'=>$rfq_id]) }}" class="place-order">
                          <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                        </a>
                      </li>
                      <li>
                        <a href="" class="pdf"><i class="fa fa-download"></i>&nbsp; PDF</a>
                      </li>
                      <li>
                        <a href="" class="email"><i class="fa fa-envelope"></i>&nbsp; Email</a>
                      </li>
                      <li>
                        <a href="" class="comment"><i class="fa fa-comment"></i>&nbsp; Comment</a>
                      </li>
                      <li>
                        <a href="{{ route('rfq.edit',[$rfq_id]) }}" class="edit">
                          <i class="fa fa-edit"></i>&nbsp; Edit
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('rfq.delete',[$rfq_id]) }}" class="delete" onclick="return confirm('Are you sure want to delete?')">
                          <i class="fa fa-trash"></i>&nbsp; Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="clearfix"></div>
                  <div class="address-sec col-sm-12">
                    <div class="col-sm-4">
                      <ul class="list-unstyled order-no-sec">
                        <li><h5>Order No: </h5></li>
                        <li><strong>Date: </strong></li>
                        <li><strong>Status: </strong></li>
                        <li><strong>Sales Rep: </strong></li>
                      </ul>
                    </div>
                    <div class="col-sm-8">
                      <div class="address-block">
                        <div class="col-sm-6 customer address">
                          <div class="col-sm-2">
                            <span><i class="fas fa-user"></i></span>
                          </div>
                          <div class="col-sm-10">
                            <h4>{{$customer_address->first_name}} {{$customer_address->last_name}}</h4>
                            <p>
                              <span>
                                {{$customer_address->address->address_line1}},&nbsp;{{$customer_address->address->address_line2}}
                              </span><br>
                              <span>
                                {{$customer_address->address->country->name}},&nbsp;{{$customer_address->address->state->name}}
                              </span><br>
                              <span>
                                {{$customer_address->address->city->name}}&nbsp;-&nbsp;{{$customer_address->address->post_code}}.
                              </span>
                            </p>
                            <p>
                              <span>Tel: {{$customer_address->address->mobile}}</span><br>
                              <span>Email: {{$customer_address->email}}</span>
                            </p>
                          </div>
                        </div>
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
                              $total_products=\App\Models\RFQProducts::TotalDatas($rfq_id);
                            ?>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>
                              <th scope="col">
                                  Total Quantity:&nbsp;
                                  <span class="all_quantity">{{ $total_products->quantity }}</span>   
                              </th>
                              <th scope="col">
                                  Total RFQ Price:&nbsp;
                                  <span class="all_rfq_price">{{ $total_products->rfq_price }}</span>  
                              </th>
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
                                    $total_based_products=\App\Models\RFQProducts::TotalDatas($rfq_id,$product['product_id']);
                                  ?>
                                <td>{{ $product['product_name'] }}</td>
                                <th>Quantity: {{ $total_based_products->quantity }}</th>
                                <th>RFQ Price: {{ $total_based_products->rfq_price }}</th>
                                <th class="total-head">Total: {{ $total_based_products->sub_total }}</th>
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
                                          <th>Quantity</th>
                                          <th>RFQ Price</th>
                                          <th>Subtotal</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $total_amount=$total_quantity=0 ?>
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
                                            <td>
                                              <?php $high_value=$variation_details['rfq_price']; ?>
                                              {{ $high_value }}
                                            </td>
                                            <td>
                                              <div class="form-group">{{ $variation_details['sub_total'] }}</div>
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$variation_details['sub_total']; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
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
                              <td colspan="4" class="title">Total</td><td>0.00</td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Order Discount</td><td>0.00</td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Order Tax</td><td>0.00</td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="4" class="title">Total Amount(SGD)</td><td>0.00</td>
                            </tr>
                            <!-- <tr class="total-calculation">
                              <td colspan="4" class="title">Total Amount(USD)</td><td>0.00</td>
                            </tr> -->
                            <tr><td colspan="6"></td></tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="tax-sec">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="purchase_date">Order Tax</label>
                          <select class="form-control no-search select2bs4" name="order_tax">
                            @foreach($taxes as $tax)
                              <option value="{{$tax->id}}" @if($tax->name=='No Tax')  selected="selected" @endif {{ (collect(old('order_tax'))->contains($tax->id)) ? 'selected':'' }}>
                                {{$tax->name}} 
                                @if($tax->tax_type=='p') @  {{round($tax->rate,2)}}% 
                                @elseif($tax->name=='No Tax') 
                                @else @  {{number_format((float)$tax->rate,2,'.','')}} 
                                @endif
                              </option>
                            @endforeach
                          </select>

                        </div>
                        <div class="col-sm-4">
                          <label for="purchase_date">Order Discount</label>
                          {!! Form::text('order_discount', null,['class'=>'form-control']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="purchase_date">Payment Term</label>
                          {!! Form::select('payment_term',$payment_terms,null,['class'=>'form-control no-search select2bs4']) !!}
                        </div>
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
                          Created by : <br>Date: {{ date('d/m/Y H:i:s',strtotime($rfqs->created_at)) }}
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
@endsection