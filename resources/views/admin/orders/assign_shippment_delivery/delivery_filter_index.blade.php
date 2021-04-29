
                  <table id="data-table" class="table table-bordered">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Region/Postcode</th>
                        <th>Ordered Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Order Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                      <?php $order=$order['orders'] ?>
                      <?php 
                      $check_quantity=\App\Models\Orders::CheckQuantity($order->id);
                        $disabled_stock_notify=$disabled_edit="";
                        if(isset($order->deliveryPerson) ){
                          $disabled_stock_notify="pointer-events:none;opacity:0.5";
                        }
                        if (isset($check_quantity[0]) && $check_quantity[0]=="yes") {
                            $class_bg="background:#ffc1c1 !important";
                            $low_stock="yes";
                        }
                        else{
                          $class_bg="";
                          $low_stock="no";
                        }
                        if ($order->order_status==11) {
                          $disabled_edit="pointer-events:none;opacity:0.5";
                        }
                       ?>
                        <tr style="{{ $class_bg }}">
                            <?php 
                        $check_if_load=\App\Models\OrderHistory::CheckIfLoad($order->id);
                        if ($check_if_load) {
                          $check_type="loaded";
                        }
                        else{
                          $check_type="notloaded"; 
                        }
                        ?>

                          <td>
                            <input type="checkbox"  name="orders_ids" class="orders_ids" value="{{$order->id}}">
                            <input type="hidden" class="order-loaded_{{ $order->id }}" value="{{ $check_type }}"> 
                          </td>

                          <?php $region=\App\Models\Orders::GetRegion($order->address->post_code); ?>
                          <td>{{ isset($region)?$region.'/'.$order->address->post_code:$order->address->post_code }}</td>
                          <td>{{ date('m/d/Y',strtotime($order->created_at)) }}</td>
                          <td><a href="{{route($show_route,$order->id)}}">{{ $order->order_no }}</a></td>
                          <td>{{ $order->customer->name }}</td>
                          <td>
                            <span class="badge" style="background: {{ $order->statusName->color_codes }};color: #fff">
                            {{  $order->statusName->status_name  }}
                            </span>
                          </td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="{{route($show_route,$order->id)}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('delivery_order','update'))
                                  @if ($low_stock!="yes")
                                  <a href="{{route($edit_route,$order->id)}}"  style="{{ $disabled_edit }}">
                                    <li class="dropdown-item">
                                      <i class="far fa-edit"></i>&nbsp;&nbsp;Edit
                                    </li>
                                  </a>
                                  @endif
                                @endif
                               <a href="{{ url('admin/cop_pdf/'.$order->id) }}"><li class="dropdown-item">
                                  <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;Download as PDF
                                 </li></a>
                                <a href="{{ url('admin/cop_print/'.$order->id) }}" target="_blank">
                                  <li class="dropdown-item" >
                                  <i class="fa fa-print"></i>&nbsp;&nbsp;Print
                                </li>
                                </a>
                                @if (isset($check_quantity[0]) && $check_quantity[0]=="yes") 
                                 <a href="{{ route('verify-stock.show',[$order->id]) }}">
                                  <li class="dropdown-item"  style="{{ $disabled_stock_notify }}">
                                  <i class="fa fa-check-circle"></i>&nbsp;&nbsp;Verify Stock
                                </li>
                                </a>
                                <a href="{{ route('verify-stock.index',['order_id'=>$order->id]) }}" target="_blank" style="{{$disabled_stock_notify}}">
                                  <li class="dropdown-item" >
                                  <i class="fa fa-user"></i>&nbsp;&nbsp;Notify Admin
                                </li>
                                </a>
                                @endif
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $order->address->latitude.','.$order->address->longitude }}&dir_action=navigate" target="_blank">
                                   <li class="dropdown-item">
                                     <i class="fa fa-map-marker"></i>&nbsp;&nbsp;View On Map
                                   </li>
                                 </a>
                                  </ul>
                                </div>
                              </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  