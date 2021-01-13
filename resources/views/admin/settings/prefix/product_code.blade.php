                        <?php 
                $status_val=$value_val=$reset_val=null;
                if(isset($prefix['product_code'])){
                        $order_no_data=unserialize($prefix['product_code']);
                        $status_val=$order_no_data['status'];
                        $value_val=$order_no_data['value'];
                        $reset_val=$order_no_data['reset'];
                }
                ?>
                <div class="card-body order-sec">
                        {!! Form::open(['route'=>'settings.store','class'=>'form-horizontal','order-form']) !!}
                        <div class="col-sm-3">
                                <label>Enable *</label>
                                {!! Form::select('status',$status , $status_val,['class'=>'form-control']) !!}
                           @if($errors->has('status') && $check_error_place=='product_code')
                              <span class="text-danger">Status is required</span>
                            @endif
                        </div>
                        <div class="col-sm-3">
                                <label>Order No *</label>
                                {!! Form::text('value', $value_val,['class'=>'form-control','placeholder'=>'ORD-[dd]-[mm]-[yyyy]-[Start No]']) !!}
                           @if($errors->has('value') && $check_error_place=='product_code')
                              <span class="text-danger">Order No is required</span>
                            @endif
                        </div>
                        <div class="col-sm-3">
                                <label>Reset *</label>
                                {!! Form::select('reset', $reset,$reset_val,['class'=>'form-control']) !!}
                           @if($errors->has('reset') && $check_error_place=='product_code')
                              <span class="text-danger">Reset is required</span>
                            @endif
                        </div>
                        <div class="col-sm-3">
                                {!! Form::hidden('from','product_code') !!}
                                <label></label>
                                <br>
                                <button type="submit" class="btn save-btn">Save</button>
                        </div>
                        {!! Form::close() !!}
                </div>
              