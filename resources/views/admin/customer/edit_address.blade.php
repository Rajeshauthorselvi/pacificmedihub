<form method="post" action="{{route('save.address')}}" class="edit-address">
	@csrf
	<div class="form-group" style="display:flex;">
		<div class="col-sm-6" style="padding-left:0">
			{!! Form::label('name', 'Name *') !!}
			{!! Form::text('name',$name,['class'=>'form-control','id'=>'name']) !!}
			{!! Form::hidden('add_id',$id,['class'=>'form-control']) !!}
            {!! Form::hidden('cus_id',$cus_id,['class'=>'form-control']) !!}
            <span class="text-danger name" style="display:none">Name is required</span>
		</div>

		<div class="col-sm-6" style="padding:0;">
			{!! Form::label('mobile', 'Contact No *') !!}
			{!! Form::text('mobile',$mobile,['class'=>'form-control','id'=>'mobile']) !!}
            <span class="text-danger mobile" style="display:none">Contact Number is required</span>
		</div>
	</div>
	
	<div class="form-group">
		{!! Form::label('address1', 'Address Line 1 *') !!}
		{!! Form::text('address1',$address_line1,['class'=>'form-control','id'=>'address1']) !!}
        <span class="text-danger address1" style="display:none">Address Line 1 is required</span>
	</div>
	<div class="form-group">
		{!! Form::label('address2', 'Address Line 2') !!}
		{!! Form::text('address2',$address_line2,['class'=>'form-control','id'=>'address2']) !!}
	</div>
	<div class="form-group" style="display:flex;">
		<div class="col-sm-6" style="padding-left:0">
			{!! Form::label('postcode', 'Post Code *') !!}
			{!! Form::text('postcode',$post_code,['class'=>'form-control','id'=>'postcode']) !!}
            <span class="text-danger postcode" style="display:none">Post Code is required</span>
		</div>

		<div class="col-sm-6" style="padding:0;">
			{!! Form::label('country', 'Country *') !!}
			{!! Form::select('country_id',$countries,$country_id,['class'=>'form-contol select2bs4', 'id'=>'country']) !!}
            <span class="text-danger country" style="display:none">Country is required</span>
		</div>
	</div>
	<div class="form-group" style="display:flex;">
		<div class="col-sm-6" style="padding-left:0">
			{!! Form::label('state', 'State *') !!}
			<select name="state_id" class="form-control select2bs4" id="state"></select>
            <span class="text-danger state" style="display:none">State is required</span>
		</div>
		<div class="col-sm-6" style="padding:0;">
			{!! Form::label('city', 'City *') !!}
			<select name="city_id" class="form-control select2bs4" id="city"></select>
            <span class="text-danger city" style="display:none">City is required</span>
		</div>
	</div>
	<div class="form-group">
		<button type="button" class="btn reset-btn" data-dismiss="modal">Cancel</button>
        <button type="submit" id="submit-btn" class="btn save-btn">Save</button>
	</div>
</form>
<script type="text/javascript">
 	$(function () {
    	$('body').find('.select2bs4').select2({
      		theme: 'bootstrap4'
    	})
  	});
	//Get State
    $(document).ready(function(){
        var country_id = "<?php echo json_decode($country_id); ?>";
        getState(country_id);
    });
    $('#country').change(function() {
    	getState($(this).val());
    })
    function getState(countryID){
        var state_id = "<?php echo json_decode($state_id); ?>";      
        if(countryID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-state-list')}}?country_id="+countryID,
            success:function(res){  
              if(res){
                $("#state").empty();
                $("#state").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_state="";
                  if(state_id == key) { var select_state = "selected" }
                  $("#state").append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
                });
                $('#state').selectpicker('refresh');           
              }else{
                $("#state").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#state").empty();        
        }      
    }

    //Get City
    $(document).ready(function(){
        var state_id = "<?php echo json_decode($state_id); ?>";
        getCity(state_id);
    });
    $('#state').change(function() {
        getCity($(this).val());
    })
    function getCity(stateID){
        var city_id = "<?php echo json_decode($city_id); ?>";   
        if(stateID){
          $.ajax({
            type:"GET",
            dataType: 'json',
            url:"{{url('admin/get-city-list')}}?state_id="+stateID,
            success:function(res){  
              if(res){
                $("#city").empty();
                $("#city").append('<option selected value=""> ---Select--- </option>');
                $.each(res,function(key,value){
                  var select_city="";
                  if(city_id == key) { var select_city = "selected" }
                  $("#city").append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
                });
                $('#city').selectpicker('refresh');           
              }else{
                $("#city").empty();
              }
            },
            error: function(res) { alert(res.responseText) }
          });
        }else{
          $("#city").empty();        
        }      
    }

    $(document).on('click', '#submit-btn', function(){
        var valid=true;
        if ($("#name").val()=="") {
            $("#name").closest('.form-group').find('span.text-danger.name').show();
            valid = false;
        }
        if ($("#mobile").val()=="") {
            $("#mobile").closest('.form-group').find('span.text-danger.mobile').show();
            valid = false;
        }
        if ($("#address1").val()=="") {
            $("#address1").closest('.form-group').find('span.text-danger.address1').show();
            valid = false;
        }
        if ($("#postcode").val()=="") {
            $("#postcode").closest('.form-group').find('span.text-danger.postcode').show();
            valid = false;
        }
        if ($("#Country").val()=="") {
            $("#Country").closest('.form-group').find('span.text-danger.country').show();
            valid = false;
        }
        if ($("#state").val()=="") {
            $("#state").closest('.form-group').find('span.text-danger.state').show();
            valid = false;
        }
        if ($("#city").val()=="") {
            $("#city").closest('.form-group').find('span.text-danger.city').show();
            valid = false;
        }
        return valid;

        if(valid==false){
            return false
        }else{
            $('.edit-address').submit();
            return true
        }
    });
        
</script>