  <tr class="accordion-toggle collapsed collapse{{ $product_id }}" id="accordion{{ $product_id }}" data-toggle="collapse" data-parent="#accordion{{ $product_id }}" href="#collapse{{ $product_id }}">
    <td class="expand-button"></td>
    <td>{{ $product_name }}</td>
    <td>
      <a href="javascript:void(0)" class="btn btn-danger remove-product-row"><i class="fa fa-trash"></i></a>
    </td>
  </tr>
  <tr class="hide-table-padding" class="test">
    <td colspan="4">
      <div id="collapse{{ $product_id }}" class="collapse in p-3">
        <table class="table table-bordered" style="width: 100%">
          <thead>
            <tr>
              @foreach ($options as $option)
                <th>{{ $option }}</th>
              @endforeach
              <th>Total Quantity</th>
              <th>Quantity</th>
            </tr>
          </thead>
          <tbody>
            <?php $total_amount=$total_quantity=0 ?>
            @foreach($product_variant as $key=>$variant)
              {{-- @if ($key==0)
                <tr>
                  <td class="text-center" colspan="{{ $option_count+6 }}">{{ $product_name }}</td>
                </tr>
              @endif --}}
              <tr class="parent_tr">
                <input type="hidden" name="variant[product_id][]" value="{{ $product_id }}" class="product_id">
                <input type="hidden" name="variant[variant_id][]" value="{{$variant['variant_id']}}">
                <td>
                  <div class="form-group">
           
                    {{$variant['option_value1']}}
                  </div>
                </td>
                @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                  <td>
                    <div class="form-group">
                      {{$variant['option_value2']}}
                    </div>
                  </td>
                @endif
                @if($option_count==3||$option_count==4||$option_count==5)
                  <td>
                    <div class="form-group">
                      {{$variant['option_value3']}}
                    </div>
                  </td>
                @endif
                @if($option_count==4||$option_count==5)
                  <td>
                    <div class="form-group">
                      {{$variant['option_value4']}}
                    </div>
                  </td>
                @endif
                @if($option_count==5)
                  <td>
                    <div class="form-group">
                      {{$variant['option_value5']}}
                    </div>
                  </td>
                @endif
                <td>
                  <input type="hidden" class="total-avail-quantity" value="{{ $variant['stock_quantity'] }}">
                  <input type="text" name="variant[total_avalible_quantity][]" readonly class="form-control total-quantity" value="{{ $variant['stock_quantity'] }}">
                </td>
                <td>
                  <div class="form-group">
                    <?php $quantity=0 ?>
                    <input type="text" class="form-control stock_qty"  name="variant[stock_qty][]" value="0">
                  </div>
                </td>
              </tr>
              <?php $total_amount +=$variant['base_price']; ?>
              <?php $total_quantity +=$quantity; ?>
            @endforeach
          </tbody>
        </table>
      </div>
    </td>
  </tr>