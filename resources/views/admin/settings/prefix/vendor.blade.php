<?php 
    $status_val=$value_val=$reset_val=null;
    if(isset($prefix['vendor'])){
        $order_no_data=unserialize($prefix['vendor']);
        $status_val=$order_no_data['status'];
        $value_val=$order_no_data['value'];
        $reset_val=$order_no_data['reset'];
    }
?>
<div class="card-body order-sec">
    {!! Form::open(['route'=>'settings-prefix.store','class'=>'form-horizontal','order-form']) !!}
        @php $class=""; @endphp
        @if ($vendor_count>0)
            @php $class="disabled"; @endphp
        @endif
        <div class="form-group">
            <div class="col-sm-3">
                <label>Enable *</label>
                {!! Form::select('status',$status , $status_val,['class'=>'form-control no-search select2bs4',$class]) !!}
                @if($errors->has('status') && $check_error_place=='vendor')
                    <span class="text-danger">Status is required</span>
                @endif
            </div>
            <div class="col-sm-3">
                <label>Vendor Code *</label>
                {!! Form::text('value', $value_val,['class'=>'form-control','placeholder'=>'VEN [yyyy] [Start No]',$class]) !!}
                {{-- <small>Eg: VEN [yyyy] [Start No]</small> --}}
                @if($errors->has('value') && $check_error_place=='vendor')
                    <span class="text-danger">Vendor Code is required</span>
                @endif
            </div>
            <div class="col-sm-3">
                <label>Reset *</label>
                {!! Form::select('reset', $reset,$reset_val,['class'=>'form-control no-search select2bs4',$class]) !!}
                @if($errors->has('reset') && $check_error_place=='vendor')
                  <span class="text-danger">Reset is required</span>
                @endif
            </div>
            <div class="col-sm-3">
                {!! Form::hidden('from','vendor') !!}
                <button type="submit" class="btn save-btn prefix" {{ $class }}>Save</button>
            </div>
        </div>
    {!! Form::close() !!}
</div>
              