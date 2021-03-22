<!DOCTYPE html>
<html>
<body>
  <div style="width: 700px;margin: auto;">
  	<h3>Ordered Summary:</h3>
                        <table class="table" width="100%">
                            <tbody>
                            	@foreach ($summary_report as $order_no=>$product_data)
                                    <?php $count=1; ?>
                              @if ($count==1)
                            	<tr style="padding-top: 20px;">
                              @else
                              	<tr>
                              @endif
                            		<td colspan="2">Order No: <b>{{ $order_no }}</b><br></td>
                            	</tr>
                            @foreach ($product_data as $key=>$product)
                              <?php 
                                if ($count!=1) {
                                  $class_name="tr_top_padding";
                                $style="padding-top:18px;display:inline-block;";
                                }
                                else{
                                  $class_name="";
                                  $style="";
                                }
                               ?>
                               @if ($count!=1)
	                              <tr class="{{ $class_name }}">
	                            @else
	                              <tr class="{{ $class_name }}">
                               @endif
                                  <td>{{ $count.'. '.$product['product_name'] }} </td>
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
                                              $variation_details=\App\Models\OrderProducts::VariationPrice($product['product_id'],$variant['variant_id'],$product['order_id']);
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
                                  @if($loop->last)
                                  	<hr>
                                  @endif

                                </td>
                              </tr>

                            @endforeach
                            	@endforeach
                          </tbody>
                        </table>
                        <div style="clear: both;"></div>
                        <div class="stamp" style="float: right;padding-top: 80px">
                          Stamp & Signature
                        </div>
                        <div ></div>

  </div>
</body>
</html>
