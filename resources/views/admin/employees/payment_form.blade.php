<form method="post" action="{{route('confirm.salary')}}">
	@csrf
	<div class="form-group">
		{!! Form::label('employeeName', 'Employee Name') !!}
		{!! Form::text('emp_name',$name,['class'=>'form-control','id'=>'employeeName','readonly']) !!}
		{!! Form::hidden('emp_id',$id,['class'=>'form-control']) !!}
	</div>
	<div class="form-group" style="display:flex;">
		<div class="col-sm-9" style="padding-left:0">
			{!! Form::label('totalSalary', 'Total Salary') !!}
			{!! Form::text('total_salary',$total_salary,['class'=>'form-control','id'=>'totalSalary','readonly']) !!}
		</div>
		<div class="col-sm-3" style="padding:0;margin-top:30px">
			<button type="button" class="btn btn-info form-contro change-salary">Please Edit</button>
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('salaryMonth', 'Salary Month') !!}
		{!! Form::text('salary_month',$salary_month,['class'=>'form-control','id'=>'salaryMonth','readonly']) !!}
	</div>
	<div class="form-group">
		<button type="button" class="btn reset-btn" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit-btn" class="btn save-btn">Confirm</button>
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
        $('.change-salary').click(function(){
          $('#totalSalary').removeAttr('readonly');
        });
    });
</script>