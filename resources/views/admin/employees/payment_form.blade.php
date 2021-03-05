<form method="post" action="{{route('confirm.salary')}}">
	@csrf
	<div class="col-sm-12 form-field">
		<div class="form-group col-sm-6">
			{!! Form::label('employeeName', 'Employee Name') !!}
			{!! Form::text('emp_name',$name,['class'=>'form-control','id'=>'employeeName','readonly']) !!}
			{!! Form::hidden('emp_id',$id,['class'=>'form-control']) !!}
			{!! Form::hidden('date',$date,['class'=>'form-control']) !!}
		</div>
		<div class="form-group col-sm-6">
			{!! Form::label('employeeDept', 'Department') !!}
			{!! Form::text('emp_dept',$department,['class'=>'form-control','id'=>'employeeDept','readonly']) !!}
		</div>
	</div>
	<div class="col-sm-12 form-field">
	<div class="form-group" style="display:flex;float: left;">
		<div class="col-sm-12">
			{!! Form::label('payAmount', 'Pay Amount') !!}
			{!! Form::text('pay_amount',$pay_amount,['class'=>'form-control','id'=>'payAmount','readonly','onkeyup'=>'validateNum(event,this);']) !!}
		</div>
		<div class="col-sm-2" style="margin-top:30px">
			<button type="button" class="btn btn-info form-contro change-salary">Edit</button>
		</div>
	</div>
	<div class="clearfix"></div>
	</div>
	<div class="col-sm-12 form-field">
	<div class="form-group col-sm-6">
		{!! Form::label('salaryMonth', 'Salary Month') !!}
		{!! Form::text('salary_month',$salary_month,['class'=>'form-control','id'=>'salaryMonth','readonly']) !!}
	</div>
	<div class="form-group col-sm-6">
		{!! Form::label('payBy', 'Payment Method') !!}
		{!! Form::select('payby',$payment_method,null,['class'=>'form-control','id'=>'payBy']) !!}
	</div>
	</div>
	<div class="form-group">
		{!! Form::label('payBy', 'Remarks') !!}
		<textarea name="payment_notes" class="form-control summernote"></textarea>
	</div>
	<div class="col-sm-12">
		<div class="form-group">
			<button type="button" class="btn reset-btn" data-dismiss="modal">Cancel</button>
	        <button type="submit" id="submit-btn" class="btn save-btn" onclick="return confirm('Are you sure want to Pay Now?');">Confirm</button>
		</div>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
        $('.change-salary').click(function(){
        	var balance_amount = <?php echo $pay_amount; ?>;
          	$('#payAmount').removeAttr('readonly');
          	$('#payAmount').val(parseInt(balance_amount));
        });
        
    });
	$(function ($) {
    	$('.select2bs4').select2({
      		minimumResultsForSearch: -1
    	});
  	});

  	
  	$(document).on('keyup','#payAmount',function(event) {
  		var balance_amount = <?php echo $pay_amount; ?>;
  		var amount = $('#payAmount').val();

    	if ((amount !== '') && (amount.indexOf('.') === -1)) {
            var amount = Math.max(Math.min(amount, parseInt(balance_amount)), -90);
            $('#payAmount').val(amount);
      	}
  	});

</script>

<style type="text/css">
	.form-field .col-sm-6{
		float: left;
	}
</style>