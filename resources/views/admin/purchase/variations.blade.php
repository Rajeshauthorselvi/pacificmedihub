<div class="clearfix"></div>
<div class="bs-example">
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $product_id }}"><i class="fa fa-plus"></i> {{ $product_name }}</button>                  
                </h2>
            </div>
            <div id="collapse{{ $product_id }}" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">
  
                  <div class="table-responsive">
                    <table  id="variantList" class="table table-striped table-bordered table-hover vatiant_table">
                      <thead>
                        <tr>
                  @foreach ($options as $option)
                    <td class="text-left">{{ $option }}</td>
                  @endforeach
                            
                          <td class="text-left">Base Price</td>
                          <td class="text-left">Retail Price</td>
                          <td class="text-left">Minimum Selling Price</td>
                          <td class="text-left">Quantity</td>
                          <td class="text-left">Sub Total</td>
                          <td class="text-left"></td>
                        </tr>
                      </thead>
                      <tbody>
   @foreach($product_variant as $key=>$variant)
 {{--                        @if ($key==0)
                          <tr>
                            <td class="text-center" colspan="{{ $option_count+6 }}">{{ $product_name }}</td>
                          </tr>
                        @endif --}}
                          <tr>
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
                                <input type="text" class="form-control stock_qty" onkeyup="validateNum(event,this);" name="variant[stock_qty][]" value="1">
                              </div>
                            </td>
                            <td>
                              <div class="form-group">
                                <span class="sub_total">{{ $variant['base_price'] }}</span>
                                <input type="hidden" class="subtotal_hidden" name="variant[sub_total][]" value="{{ $variant['base_price'] }}">
                              </div>
                            </td>
                            <td><a class="btn btn-danger remove-item" variant-id="{{$variant['variant_id']}}" route-url="{{route('delete.variant')}}"><i class="fa fa-trash"></i></a></td>
                          </tr>
                        @endforeach

                      </tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>