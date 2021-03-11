<!DOCTYPE html>
<html>
<head>
  <style type="text/css">
    @media print {
        table {
            border-collapse: unset;
        }
    }
  </style>
</head>
<body>
  <div style="width: 700px;margin: auto;">
                    <div class="address-sec">
                      <div style="width:30%;float: left;">
                        <ul class="list-unstyled order-no-sec"  style="font-size: 14px;padding-left: 0;list-style: none;">
                          <li><strong>Order Code : </strong>{{ $order->order_no }}</li>
                          <li><strong>Date : </strong>{{date('d F, Y - h:i a',strtotime($order->created_at))}}</li>
                          <li><strong>Order Status : </strong>{{$order->statusName->status_name}}</li>
                          <li><strong>Delivery Status : </strong>{{ isset($order->deliveryStatus->status_name)?$order->deliveryStatus->status_name:'-' }}</li>
                          @if(isset($purchase->payTerm->name))
                            <li><strong>Payment Term : </strong>{{$order->payTerm->name}}</li>
                          @endif
                        </ul>
                      </div> 
                      <div style="width: 70%;float: left; font-size: 14px">
                        @if(isset($admin_address))
                          <div class="customer address" style="width: 43%;float:left;padding-right: 7%;">
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
                        @else
                          <div class="col-sm-6 customer address">
                            <div class="col-sm-2 icon">
                              <span><i class="far fa-building"></i></span>
                            </div>
                            <div class="col-sm-10">
                            </div>
                          </div>
                        @endif
                      
                      @if(isset($customer_address))
                          <div class="admin address" style="width: 40%; float:right;">
                            <div class="col-sm-2">
                              <span><i class="fas fa-people-carry"></i></span>
                            </div>
                            <div class="col-sm-10">
                              <h4>{{$customer_address->name}}</h4>
                              <p>
                                <span>
                                  {{$customer_address->address_line1}},&nbsp;{{isset($customer_address->address_line2)?$customer_address->address_line2:''}}
                                </span><br>
                                <span>
                                  {{$customer_address->country->name}},&nbsp;{{isset($customer_address->country->name)?$customer_address->country->name:''}}
                                </span><br>
                                <span>
                                  {{isset($customer_address->city->name)?$customer_address->city->name:''}}&nbsp;-&nbsp;{{isset($customer_address->post_code)?$customer_address->post_code:''}}.
                                </span>
                              </p>
                              <p>
                                <span>Tel: {{$customer_address->contact_number}}</span><br>
                                <span>Email: {{$customer_address->email}}</span>
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
                    <div style="clear: both;"></div>
                    <hr>
                        <table class="table" width="100%">
                            <tbody>
                              <?php $count=1; ?>
                            @foreach ($product_data as $key=>$product)
                              <?php 
                                if ($count!=1) {
                                  $class_name="tr_top_padding";
                                $style="padding-top:18px;display: inline-block;";
                                }
                                else{
                                  $class_name="";
                                  $style="";
                                }
                               ?>
                              <tr class="{{ $class_name }}" {{ $style }}>
                                @if ($count!=1)
                                  <td>{{ $count.'. '.$product['product_name'] }} </td>
                                @else
                                  <td>{{ $count.'. '.$product['product_name'] }} </td>
                                @endif
                                
                                <?php $count++; ?>
                              </tr>
                              <tr>
                                <td colspan="5">
                                  <div> 
                                    <table class="table table-bordered inside-table" style="width: 100%;border-collapse: collapse; border: 1px solid #000;">
                                      <thead style="background: #eeeeee;font-size: 13px;border-right: 1px solid #000;">
                                        <tr>
                                          @foreach ($product['options'] as $option)
                                            <th style="font-weight: normal;border-right: 1px solid #000;">
                                              {{ $option }}
                                            </th>
                                          @endforeach
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Quantity
                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody style="font-size: 14px;">
                                        <?php $total_amount=$total_quantity=$final_price=0; ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                            <?php 
                                              $option_count=$product['option_count'];
                                              $variation_details=\App\Models\OrderProducts::VariationPrice($product['product_id'],$variant['variant_id'],$order->id);
                                            ?>
                                          <tr class="parent_tr">
                                            <td style="border: 1px solid #000">{{$variant['option_value1']}}</td>
                                            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                                              <td style="border: 1px solid #000">{{$variant['option_value2']}}</td>
                                            @endif
                                            @if($option_count==3||$option_count==4||$option_count==5)
                                              <td style="border: 1px solid #000">{{$variant['option_value3']}}</td>
                                            @endif
                                            @if($option_count==4||$option_count==5)
                                              <td style="border: 1px solid #000">{{$variant['option_value4']}}</td>
                                            @endif
                                            @if($option_count==5)
                                              <td style="border: 1px solid #000">
                                                 {{$variant['option_value5']}} 
                                              </td>
                                            @endif
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['quantity'] }}
                                            </td>
                                          </tr>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options']) }}" style="border: 1px solid #000;text-align: right;">
                                            Total:
                                          </td>
                                          <td  style="border: 1px solid #000">{{ $total_quantity }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </td>
                              </tr>

                            @endforeach
                          </tbody>
                        </table>
                        <div style="clear: both;"></div>
                        <div style="clear: both;"></div>
                        <hr>
                        <div class="order_by" style="float:right;">
                          Ordered by: 
                         @if(isset($order->createdBy))
                            {{$creater_name}}
                          @endif
                        </div>
                        <div style="clear: both;"></div>
                        <div class="stamp" style="float: right;padding-top: 80px">
                          Stamp & Signature
                        </div>
                        <div ></div>

  </div>

  <script type="text/javascript">
      window.onload = function() { window.print(); }
  </script>
</body>
</html>