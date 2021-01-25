                        <?php 
                $status_val=$value_val=$reset_val=null;
                if(isset($prefix['employee'])){
                        $order_no_data=unserialize($prefix['employee']);
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
                           @if($errors->has('status') && $check_error_place=='employee')
                              <span class="text-danger">Status is required</span>
                            @endif
                        </div>
                        <div class="col-sm-3">
                                <label>Employee No *</label>
                                {!! Form::text('value', $value_val,['class'=>'form-control','placeholder'=>'ORD-[dd]-[mm]-[yyyy]-[Start No]']) !!}
                                <small>Eg: EMP-[dd]-[mm]-[yyyy]-[Start No]</small>
                           @if($errors->has('value') && $check_error_place=='employee')
                              <span class="
                              break-normaltext-danger">Employee No is required</span>
                            @endif
                        </div>
                        <div class="col-sm-3">
                                <label>Reset *</label>
                                {!! Form::select('reset', $reset,$reset_val,['class'=>'form-control']) !!}
                           @if($errors->has('reset') && $check_error_place=='employee')
                              <span class="text-danger">Reset is required</span>
                            @endif
                        </div>
                        <div class="col-sm-3">
                                {!! Form::hidden('from','employee') !!}
                                <label></label>
                                <br>
                                @php $class=""; @endphp
                                @if ($employee_count>0)
                                    @php $class="disabled"; @endphp
                                @endif
                                <button type="submit" class="btn save-btn" {{ $class }}>Save</button>
                        </div>
                        {!! Form::close() !!}
                </div>
              