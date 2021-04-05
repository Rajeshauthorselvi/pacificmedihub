  <div style="width: 700px;margin: auto;">
                    <div class="header" style="width: 100%;text-align:center;">
                        <img src="http://selvisoftware.in/pacificmedihub/front/img/pacificmedihub_logo.png">
                        <br> <br>
                    </div>
                    <div class="address-sec">
                      <div style="width:30%;float: left;">
                        <ul class="list-unstyled order-no-sec"  style="font-size: 14px;padding-left: 0;list-style: none;">
                          <li><strong>Purchase Code : </strong>{{ $purchase->purchase_order_number }}</li>
                          <li><strong>Date : </strong>{{date('d F, Y - h:i a',strtotime($purchase->purchase_date))}}</li>
                          <li><strong>Status : </strong>{{$purchase->statusName->status_name}}</li>
                          @if(isset($purchase->payTerm->name))
                            <li><strong>Payment Term : </strong>{{$purchase->payTerm->name}}</li>
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
                      
                      @if(isset($admin_address))
                          <div class="admin address" style="width: 40%; float:right;">
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
                    <div style="clear: both;"></div>
                    <hr>
                        <table class="table" width="100%">
                            <tbody>
                              <?php $count=1; ?>
                            @foreach ($purchase_products as $key=>$product)
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
                                            Base Price
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Retail Price
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Minimum Selling Price
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Quantity
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Subtotal
                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody style="font-size: 14px;">
                                        <?php $total_amount=$total_quantity=$final_price=0; ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                          <?php 
                                            $option_count=$product['option_count'];
                                            $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase->id);
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
                                               {{$variant['base_price']}}
                                            </td>
                                            <td style="border: 1px solid #000">
                                               {{$variant['retail_price']}}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              <span class="test"> {{$variant['minimum_selling_price']}} </span>
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['quantity'] }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['sub_total'] }}
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$variation_details['sub_total']; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach
                                        <tr>
                                          <td colspan="{{ count($product['options'])+3 }}" style="border: 1px solid #000;text-align: right;">
                                            Total:
                                          </td>
                                          <td  style="border: 1px solid #000">{{ $total_quantity }}</td>
                                          <td style="border: 1px solid #000;border-collapse: collapse;">{{ $total_amount }}</td>
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
                        <table style="width: 50%;float: right;padding: 4px;">
                            <tr class="total-calculation first_calculation">
                              <td style="text-align: right;">Total :</td>
                              <td style="font-size: 14px;padding-left: 20px;">&nbsp;&nbsp;{{ $purchase->total_amount }}</td>
                            </tr>
                            <tr class="total-calculation">
                              <td  style="font-size: 14px;text-align: right;" class="title">
                                Order Discount : 
                              </td>
                              <td style="font-size: 14px;padding-left: 20px;">
                                &nbsp;&nbsp;{{$purchase->order_discount}}
                              </td>
                            </tr>
                            <tr class="total-calculation">
                              <td style="font-size: 14px;text-align: right;">Order Tax : </td>
                              <td  style="font-size: 14px;padding-left: 20px;">&nbsp;&nbsp;{{$purchase->order_tax_amount}}</td>
                            </tr>
                            <tr class="total-calculation" style="font-weight: bold;">
                              <td style="font-size: 14px;text-align: right;">Total Amount(SGD) : </td>
                              <td  style="font-size: 14px;padding-left: 20px;">&nbsp;&nbsp;{{$purchase->sgd_total_amount}}</td>
                            </tr>
                        </table>
                        <div style="clear: both;"></div>
                        <hr>
                        <div class="order_by" style="float:right;">
                          Ordered by: 
                         @if(isset($purchase->createdBy))
                            {{$creater_name}}
                          @endif
                        </div>
                        <div style="clear: both;"></div>
                        <div class="stamp" style="float: right;padding-top: 80px">
                          Stamp & Signature
                        </div>
                        <div ></div>

  </div>