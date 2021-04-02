@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Purchase</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">Purchase</a></li>
              <li class="breadcrumb-item active">Purchase Details</li>
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
          <a href="{{route('purchase.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid purchase show-page">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Purchase Details</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="action_sec">
                    <ul class="list-unstyled">
                      <li>
                        <a href="{{ url('admin/puruchase_pdf/'.$purchase->id) }}" class="pdf">
                          <i class="fa fa-download"></i>&nbsp; PDF
                        </a>
                      </li>
                      <li>
                        <a href="" class="email"><i class="fa fa-envelope"></i>&nbsp; Email</a>
                      </li>
                      <li>
                        <a href="{{ route('purchase.edit',$purchase->id) }}" class="edit">
                          <i class="fa fa-edit"></i>&nbsp; Edit
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('purchase.destroy',$purchase->id) }}" class="delete" onclick="return confirm('Are you sure want to delete?')">
                          <i class="fa fa-trash"></i>&nbsp; Delete
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="address-sec col-sm-12">
                    <div class="col-sm-4">
                      <ul class="list-unstyled order-no-sec">
                        <li><h5>Purchase Code: <small>{{ $purchase->purchase_order_number }}</small></h5></li>
                        <li><strong>Date: </strong>{{date('d F, Y - h:i a',strtotime($purchase->purchase_date))}}</li>
                        <li><strong>Status: </strong>{{$purchase->statusName->status_name}}</li>
                        @if(isset($purchase->payTerm->name))
                          <li><strong>Payment Term: </strong>{{$purchase->payTerm->name}}</li>
                        @endif
                      </ul>
                    </div>
                    <div class="col-sm-8">
                      <div class="address-block">
                        @if(isset($admin_address))
                          <div class="col-sm-6 customer address">
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
                          <div class="col-sm-6 customer address">
                            <div class="col-sm-2 icon">
                              <span><i class="far fa-building"></i></span>
                            </div>
                            <div class="col-sm-10">
                            </div>
                          </div>
                        @endif
                        @if(isset($admin_address))
                          <div class="col-sm-6 admin address">
                            <div class="col-sm-2">
                              <span><i class="fas fa-people-carry"></i></span>
                            </div>
                            <div class="col-sm-10">
                              <h4>{{$vendor_address->name}}</h4>
                              <p>
                                <span>
                                  {{$vendor_address->address_line1}},&nbsp;{{isset($vendor_address->address_line2)?$vendor_address->address_line2:''}}
                                </span><br>
                                <span>
                                  {{$vendor_address->getCountry->name}},&nbsp;{{isset($vendor_address->getState->name)?$vendor_address->getState->name:''}}
                                </span><br>
                                <span>
                                  {{isset($vendor_address->getCity->name)?$vendor_address->getCity->name:''}}&nbsp;-&nbsp;{{isset($vendor_address->post_code)?$vendor_address->post_code:''}}.
                                </span>
                              </p>
                              <p>
                                <span>Tel: {{$vendor_address->contact_number}}</span><br>
                                <span>Email: {{$vendor_address->email}}</span>
                              </p>
                            </div>
                          </div>
                        @else
                          <div class="col-sm-6 admin address">
                            <div class="col-sm-2 icon">
                              <span><i class="fas fa-people-carry"></i></span>
                            </div>
                            <div class="col-sm-10">
                            </div>
                          </div>
                        @endif
                      </div>
                    </div>
                  </div>
                  
                  <div class="order-item-sec">
                    <div class="container my-4">
                      <div class="table-responsive">
                        <table class="table">
                          <thead class="heading-top">
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
                                <td>{{ $product['product_name'] }}</td>
                                <td>
                                  Quantity:&nbsp;
                                  <span class="total-quantity">{{ $total_based_products->quantity }}</span>
                                </td>
                                <td class="total-head">
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
                                          <th>Base Price</th>
                                          <th>Retail Price</th>
                                          <th>Minimum Selling Price</th>
                                          <th>Quantity</th>
                                          <th>Subtotal</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $total_amount=$total_quantity=$final_price=0; ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                          <?php 
                                            $option_count=$product['option_count'];
                                            $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase->id);
                                          ?>
                                          <input type="hidden" name="variant[row_id][]" value="{{$variation_details->id}}">
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
                                            <td> {{$variant['base_price']}}
                                            </td>
                                            <td> {{$variant['retail_price']}}</td>
                                            <td><span class="test"> {{$variant['minimum_selling_price']}} </span></td>
                                            <td>
                                              {{ $variation_details['quantity'] }}
                                            </td>
                                            <td>
                                              <div class="form-group">
                                                <span class="sub_total">{{ $variation_details['sub_total'] }}</span>
                                              </div>
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$variation_details['sub_total']; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options'])+3 }}" class="text-right">Total:</td>
                                          <td><span class="total-quantity">{{ $total_quantity }}</span></td>
                                          <td><span class="total">{{ $total_amount }}</span></td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Total</td>
                              <td><span class="all_amount">{{ $purchase->total_amount }}</span></td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Order Discount</td>
                              <td><span class="order-discount">{{$purchase->order_discount}}</span></td>
                            </tr>
                            <tr class="total-calculation">
                              <td colspan="3" class="title">Order Tax</td>
                              <td id="orderTax">{{$purchase->order_tax_amount}}</td>
                            </tr>
                            <tr class="total-calculation">
                              <th colspan="3" class="title">Total Amount(SGD)</th>
                              <th id="total_amount_sgd">{{$purchase->sgd_total_amount}}</th>
                            </tr>
                            <tr><td colspan="5"></td></tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <div class="footer-sec col-sm-12">
                    <div class="notes-sec col-sm-6">
                      <label>Notes:</label>
                      {!! $purchase->note !!}
                    </div>

                    <div class="created-sec col-sm-3 pull-right">
                      Created by : 
                      @if(isset($purchase->createdBy))
                        {{$creater_name}}
                      @endif
                      <br>
                      Date: {{ date('d/m/Y H:i a',strtotime($purchase->purchase_date)) }}
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