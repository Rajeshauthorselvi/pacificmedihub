<tr>
  <td colspan="4" style="padding:0">
    <table class="table table-bordered" style="width: 100%">
      <tr class="accordion-toggle collapsed" id="accordion{{ $product_id }}" data-toggle="collapse" data-parent="#accordion{{ $product_id }}" href="#collapse{{ $product_id }}">
        <td class="expand-button" style="width:2rem;"></td>
        <td colspan="{{ count($options)+7 }}" class="prod-name">{{ $product_name }}</td>
        <td style="width: 5%;">
          <a href="javascript:void(0)" class="btn btn-danger remove-product-row"><i class="fa fa-trash"></i></a>
        </td>
      </tr>

      <tbody id="collapse{{ $product_id }}" class="collapse in p-3">
        <tr>
          <th></th>
          @foreach ($options as $option)
            <th>{{ $option }}</th>
          @endforeach
          <th class="width">Base Price</th>
          <th class="width">Retail Price</th>
          <th class="width">Minimum Selling Price</th>
          <th class="width">Price</th>
          <th class="width">Discount
            <div class="discount-type">
              <div class="icheck-info d-inline">
                <input type="radio" name="variant[discount_type][{{ $product_id }}]" checked id="percentage{{ $product_id }}" class="dis-type" value="percentage"><label for="percentage{{ $product_id }}">%</label>
              </div>&nbsp;&nbsp;&nbsp;
              <div class="icheck-info d-inline">
                <input type="radio" class="dis-type" name="variant[discount_type][{{ $product_id }}]" id="amount{{ $product_id }}" value="amount"><label for="amount{{ $product_id }}">$</label>
              </div>
            </div>
          </th>
          <th class="width">Discount Price <br><small>(a)</small></th>
          <th class="width">QTY <br><small>(b)</small></th>
          <th class="width">Total <br><small>(a x b)</small></th>
        </tr>
        <?php $total_amount=$total_quantity=0 ?>
        @foreach($product_variant as $key=>$variant)
          <tr class="parent_tr">
            <td></td>
            <input type="hidden" name="variant[product_id][]" value="{{ $product_id }}" class="product_id">
            <input type="hidden" name="variant[id][]" value="{{$variant['variant_id']}}" class="variant_id">
            <td>
              <input type="hidden" name="variant[option_id1][]" value="{{$variant['option_id1']}}">
              <input type="hidden" name="variant[option_value_id1][]" value="{{$variant['option_value_id1']}}">
              {{$variant['option_value1']}}
            </td>
            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
              <td>
                <input type="hidden" name="variant[option_id2][]" value="{{$variant['option_id2']}}">
                <input type="hidden" name="variant[option_value_id2][]" value="{{$variant['option_value_id2']}}">
                {{$variant['option_value2']}}
              </td>
            @endif
            @if($option_count==3||$option_count==4||$option_count==5)
              <td>
                <input type="hidden" name="variant[option_id3][]" value="{{$variant['option_id3']}}">
                <input type="hidden" name="variant[option_value_id3][]" value="{{$variant['option_value_id3']}}">
                {{$variant['option_value3']}}
              </td>
            @endif
            @if($option_count==4||$option_count==5)
              <td>
                <input type="hidden" name="variant[option_id4][]" value="{{$variant['option_id4']}}">
                <input type="hidden" name="variant[option_value_id4][]" value="{{$variant['option_value_id4']}}">
                {{$variant['option_value4']}}
              </td>
            @endif
            @if($option_count==5)
              <td>
                <input type="hidden" name="variant[option_id5][]" value="{{$variant['option_id5']}}">
                <input type="hidden" name="variant[option_value_id5][]" value="{{$variant['option_value_id5']}}">
                {{$variant['option_value5']}}
              </td>
            @endif
            <td class="base_price">{{$variant['base_price']}}</td>
            <td>
              <input type="hidden" name="variant[base_price][]" value="{{$variant['base_price']}}">
              <input type="hidden" name="variant[retail_price][]" value="{{$variant['retail_price']}}">
              {{$variant['retail_price']}}
            </td>
            <td>
              <input type="hidden" name="variant[minimum_selling_price][]" class="minimum-price" value="{{$variant['minimum_selling_price']}}">
              {{$variant['minimum_selling_price']}}
            </td>
            <td>
              <?php 
                $high_value=max($variant['minimum_selling_price'],$variant['base_price'],$variant['retail_price']);
                $dummy_value=0;
                $quantity=0;
              ?>
              <input type="text" name="variant[rfq_price][]" class="form-control rfq_price" autocomplete="off" value="{{$high_value}}">
            </td>
            <td>
              <input type="text" name="variant[discount_value][]" class="form-control discount-value" autocomplete="off" value="{{$dummy_value}}">
            </td>
            <td>
              <span class="dis-price">0</span>
              <input type="hidden" name="variant[final_price][]" class="form-control dis-price" value="0">
            </td>
            <td>
              <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" autocomplete="off" value="{{$quantity}}">
            </td>
            <td>
              <span class="sub_total">0.00</span>
              <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="0">
            </td>
          </tr>
        @endforeach
        <tr>
          <td colspan="{{ count($options)+3 }}">
            <input type="text" class="form-control" name="product_description[{{ $product_id }}]" placeholder="Notes">
          </td>
          <td colspan="4" class="text-right">Total's :</td>
          <td class="total_quantity">0</td>
          <td class="total_amount total">
            0.00
            <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="0">
          </td>
        </tr>
      </tbody>
    </table>
  </td>
</tr>