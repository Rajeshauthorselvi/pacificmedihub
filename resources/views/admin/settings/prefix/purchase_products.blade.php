<?php 
    $status_val=$value_val=$reset_val=null;
    if(isset($prefix['purchase_no'])){
        $order_no_data=unserialize($prefix['purchase_no']);
        $status_val=$order_no_data['status'];
        $value_val=$order_no_data['value'];
        $reset_val=$order_no_data['reset'];
    }
?>
<div class="card-body order-sec">
    {!! Form::open(['route'=>'settings-prefix.store','class'=>'form-horizontal','order-form']) !!}
        @php $class=""; @endphp
        @if ($purchase_count>0)
            @php $class="disabled"; @endphp
        @endif
        <div class="form-group">
            <div class="col-sm-3">
            	<label>Enable *</label>
            	{!! Form::select('status',$status , $status_val,['class'=>'form-control no-search select2bs4',$class]) !!}
                @if($errors->has('status') && $check_error_place=='purchase_no')
                    <span class="text-danger">Status is required</span>
                @endif
            </div>
            <div class="col-sm-3">
            	<label>Purchase Code *</label>
            	{!! Form::text('value', $value_val,['class'=>'form-control','placeholder'=>'PCH [yyyy] [Start No]',$class]) !!}
                {{-- <small>Eg: PCH [yyyy] [Start No]</small> --}}
                @if($errors->has('value') && $check_error_place=='purchase_no')
                    <span class="text-danger">Purchase Code is required</span>
                @endif
            </div>
            <div class="col-sm-3">
            	<label>Reset *</label>
            	{!! Form::select('reset', $reset,$reset_val,['class'=>'form-control no-search select2bs4',$class]) !!}
                @if($errors->has('reset') && $check_error_place=='purchase_no')
                    <span class="text-danger">Reset is required</span>
                @endif
            </div>
            <div class="col-sm-3">
                {!! Form::hidden('from','purchase_no') !!}
            	<button type="submit" class="btn save-btn prefix" {{ $class }}>Save</button>
            </div>
        </div>
    {!! Form::close() !!}
</div>
              