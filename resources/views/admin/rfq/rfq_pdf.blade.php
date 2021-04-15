  <div style="width: 700px;margin: auto;">
                    <div class="address-sec">
                      <div style="width:30%;float: left;">
                        <ul class="list-unstyled order-no-sec"  style="font-size: 14px;padding-left: 0;list-style: none;">
                        <li><h5>RFQ Code: <small>{{ $rfqs->order_no }}</small></h5></li>
                        <li><strong>Date: </strong>{{date('d F, Y',strtotime($rfqs->created_at))}}</li>
                        <li><strong>Status: </strong>{{$rfqs->statusName->status_name}}</li>
                        <li><strong>Sales Rep: </strong>{{$rfqs->salesrep->emp_name}}</li>
                        @if(isset($rfqs->payTerm->name))
                          <li><strong>Payment Term: </strong>{{$rfqs->payTerm->name}}</li>
                        @endif
                      </ul>
                      </div> 
                      <div style="width: 70%;float: left; font-size: 14px">
                        @if(isset($admin_address))
                          <div class="customer address" style="width: 43%;float:left;padding-right: 7%;">
                              <h4>{{$customer_address->name}}</h4>
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
                            @foreach ($product_datas as $key=>$product)
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
                                            RFQ Price <br><small>(a)</small>
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Quantity
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Discount
                                            @if($discount_type[$product['product_id']]=='percentage')
                                              (%)P
                                            @else
                                              ($)A
                                            @endif
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Discount Price <br><small>(c)</small>
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Total <br><small>(a x b)</small>
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Subtotal
                                          </th>
                                        </tr>
                                      </thead>
                                      <tbody style="font-size: 14px;">
                                        <?php $total_amount=$total_quantity=$final_price=$last_rfq_pr=0; ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                          <?php 
                                            $option_count=$product['option_count'];
                                            $variation_details=\App\Models\RFQProducts::VariationPrice($product['product_id'],$variant['variant_id'],$rfqs->id);
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
                                              <?php $rfq_price = isset($variation_details['rfq_price'])?$variation_details['rfq_price']:$variant['minimum_selling_price'];
                                              $last_rfq_pr=1;
                                               ?>
                                              {{ $rfq_price }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['quantity'] }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ (int)$variation_details['discount_value'] }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['final_price'] }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['total_price'] }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['sub_total'] }}
                                            </td>
                                          </tr>
                                          <?php $total_amount +=$variation_details['sub_total']; ?>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach

                                        <tr>
                                          <td colspan="{{ count($product['options'])+2 }}" style="border: 1px solid #000;">
                                              {{ isset($product_description_notes[$product['product_id']])?$product_description_notes[$product['product_id']]:'' }}
                                          </td>
                                          <td colspan="{{ $last_rfq_pr+1 }}" style="border: 1px solid #000;text-align: right;">
                                            Total Qty:
                                          </td>
                                          <td  style="border: 1px solid #000">{{ $total_quantity }}</td>
                                          <td colspan="3"   style="border: 1px solid #000; text-align:right;">
                                            Grand Total:
                                          </td>
                                          <td style="border: 1px solid #000;border-collapse: collapse;">{{ $total_amount }}</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </td>
                              </tr>

                            @endforeach
                     
                      
                            <tr><td colspan="6"></td></tr>
                          </tbody>
                        </table>
                        <table style="width: 100%;border-collapse: collapse; border: 1px solid #000;">
   <tr>
                              <td colspan="4"style="border: 1px solid #000;text-align: right;">
                                Total
                              </td>
                              <td style="border: 1px solid #000;border-collapse: collapse;">
                                <span class="all_amount">{{number_format($rfqs->total_amount,2,'.','')}}</span>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="4" style="border: 1px solid #000;text-align: right;">
                                Order Tax ({{isset($rfqs->oderTax->name)?$rfqs->oderTax->name:'No Tax'}} @ {{isset($rfqs->oderTax->rate)?$rfqs->oderTax->rate:0.00}}%)
                              </td>
                              <td style="border: 1px solid #000;border-collapse: collapse;">
                                {{number_format($order_tax,2,'.','')}}
                              </td>
                            </tr>
                            <tr>
                              <td colspan="4" style="border:1px solid #000;border-collapse: collapse;text-align: right;">
                                Delivery Charge
                              </td>
                              <td style="border:1px solid #000;border-collapse: collapse;">
                                {{$delivery_charge}}
                              </td>
                            </tr>
                            <tr>
                              <th colspan="4" style="border:1px solid #000;border-collapse: collapse; text-align: right;">
                                Total Amount(SGD)
                              </th>
                              <th style="border:1px solid #000;border-collapse: collapse;text-align: left;">
                                {{number_format($rfqs->sgd_total_amount,2,'.','')}}
                              </th>
                            </tr>
                            @if(isset($rfqs->currencyCode->currency_code) && $rfqs->currencyCode->currency_code!='SGD')
                              @php $currency = 'contents'; @endphp 
                            @else
                              @php $currency = 'none'; @endphp
                            @endif
                            <tr class="total-calculation" style="display:{{$currency}}">
                              <td colspan="4" style="border:1px solid #000;border-collapse: collapse;">
                                Total Amount (<span class="exchange-code">{{isset($rfqs->currencyCode->currency_code)?$rfqs->currencyCode->currency_code:'SGD'}}</span>)
                              </td>
                              <td colspan="4" style="border:1px solid #000;border-collapse: collapse;">
                               {{number_format($rfqs->exchange_total_amount,2,'.','')}}
                              </td>
                            </tr>
                        </table>
                        <div style="clear: both;"></div>
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

  <?php //exit(); ?>