
      <tr class="accordion-toggle collapsed" id="accordion{{ $product_id }}" data-toggle="collapse" data-parent="#accordion{{ $product_id }}" href="#collapse{{ $product_id }}">
      <td class="expand-button"></td>
      <td>{{ $product_name }}</td>
      <td>Quantity:&nbsp;<span class="quantity_{{ $product_id }}"></span></td>
      <td>Price:&nbsp;<span class="rfq_{{ $product_id }}"></span></td>
      <td>Total:&nbsp;<span class="total_{{ $product_id }}"></span></td>

      </tr>
      <tr class="hide-table-padding" class="test">
      <td></td>
      <td colspan="4">
      <div id="collapse{{ $product_id }}" class="collapse in p-3">
        <table class="table table-bordered" style="width: 100%">
          <thead>
            <tr>
              @foreach ($options as $option)
                <th>{{ $option }}</th>
              @endforeach
              <th>Base Price</th>
              <th>Retail Price</th>
              <th>Minimum Selling Price</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
                        <?php $total_amount=$total_quantity=0 ?>
   @foreach($product_variant as $key=>$variant)
 {{--                        @if ($key==0)
                          <tr>
                            <td class="text-center" colspan="{{ $option_count+6 }}">{{ $product_name }}</td>
                          </tr>
                        @endif --}}
                          <tr class="parent_tr">

                            <input type="hidden" name="variant[product_id][]" value="{{ $product_id }}" class="product_id">
                            <input type="hidden" name="variant[id][]" value="{{$variant['variant_id']}}">
                            <td>
                              <div class="form-group">
                                <input type="hidden" name="variant[option_id1][]" value="{{$variant['option_id1']}}">
                                <input type="hidden" name="variant[option_value_id1][]" value="{{$variant['option_value_id1']}}">
                                {{$variant['option_value1']}}
                              </div>
                            </td>
                            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id2][]" value="{{$variant['option_id2']}}">
                                  <input type="hidden" name="variant[option_value_id2][]" value="{{$variant['option_value_id2']}}">
                                  {{$variant['option_value2']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==3||$option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id3][]" value="{{$variant['option_id3']}}">
                                  <input type="hidden" name="variant[option_value_id3][]" value="{{$variant['option_value_id3']}}">
                                  {{$variant['option_value3']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==4||$option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id4][]" value="{{$variant['option_id4']}}">
                                  <input type="hidden" name="variant[option_value_id4][]" value="{{$variant['option_value_id4']}}">
                                  {{$variant['option_value4']}}
                                </div>
                              </td>
                            @endif
                            @if($option_count==5)
                              <td>
                                <div class="form-group">
                                  <input type="hidden" name="variant[option_id5][]" value="{{$variant['option_id5']}}">
                                  <input type="hidden" name="variant[option_value_id5][]" value="{{$variant['option_value_id5']}}">
                                  {{$variant['option_value5']}}
                                </div>
                              </td>
                            @endif
                            <td class="base_price">
                {{$variant['base_price']}}
                            </td>
                            <td>
                <input type="hidden" name="variant[base_price][]" value="{{$variant['base_price']}}">
                <input type="hidden" name="variant[retail_price][]" value="{{$variant['retail_price']}}">

                {{$variant['retail_price']}}
                            </td>
                            <td>
                <input type="hidden" name="variant[minimum_selling_price][]" value="{{$variant['minimum_selling_price']}}">
                              {{$variant['minimum_selling_price']}}
                            </td>
                            <td>
                              <div class="form-group">
                                <?php $quantity=0 ?>
                                <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" value="{{ $quantity }}">
                              </div>
                            </td>
                            <td>
                              <?php $high_value=max($variant['minimum_selling_price'],$variant['base_price'],$variant['retail_price']) ?>
                              <input type="text" name="variant[rfq_price][]" class="form-control rfq_price" value="{{ $high_value }}">
                            </td>
                            <td>
                              <div class="form-group">
                                <!-- <span class="sub_total">{{ $high_value }}</span>
                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $high_value }}"> -->
                                <span class="sub_total">0</span>
                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="0">
                              </div>
                            </td>
                          </tr>
                          <?php $total_amount +=$high_value; ?>
                          <?php $total_quantity +=$quantity; ?>
                        @endforeach
                        <tr>
                          <td colspan="{{ count($options)+4 }}" class="text-right">Total:</td>
                          <td class="total_quantity">{{ $total_quantity }}</td>
                          <td class="total_amount">{{ $total_amount }}</td>
                        </tr>
                      </tbody>
        </table>
      </div>
      </td>
      </tr>
