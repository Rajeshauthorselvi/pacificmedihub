<!DOCTYPE html>
<html>
<body>
  <div style="width: 700px;margin: auto;">
                      <h3>Dear Admin,</h3>
                      <p>
                        Below mentioned products not available in our stock. Please verify and purchase those products
                      </p>

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
                                            Currenct Stock 
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Order Stock
                                          </th>
                                          <th style="font-weight: normal;border-right: 1px solid #000;">
                                            Remaining Need
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
                                              {{ $variant['stock_quantity'] }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['quantity'] }}
                                            </td>
                                            <td style="border: 1px solid #000">
                                              {{ $variation_details['quantity']-$variant['stock_quantity'] }}
                                            </td>
                                          </tr>
                                          <?php $total_quantity +=$variation_details['quantity']; ?>
                                        @endforeach
                                        <tr>
                                        {{--   <td colspan="{{ count($product['options'])+2 }}" style="border: 1px solid #000;text-align: right;">
                                            Total:
                                          </td>
                                          <td  style="border: 1px solid #000">{{ $total_quantity }}</td> --}}
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
                          Created by: 
                         @if(isset($order->createdBy))
                            {{$creater_name}}
                          @endif
                        </div>

  </div>

</body>
</html>

{{-- <?php /exit(); ?> --}}