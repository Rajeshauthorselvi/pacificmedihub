@extends('front.layouts.default')
@section('front_end_container')
<div class="register-page">
	<div class="register-box">
  		<div class="card">
    		<div class="card-body">
      			<div class="register-header">
        			<h4>New Customer</h4>
        			<p>We will verify your details and send you access details to login.</p>
      			</div>
      			<form action="{{ route('store.new.customer') }}" method="post" id="requestForm">
        			@csrf
        			@include('flash-message')

			        <div class="form-group mb-3">
			        	<label>Name *</label>
			          	<input type="text" class="form-control" name="name">
			          	<span class="text-danger name" style="display:none">Name is required</span>
			        </div>

			        <div class="form-group mb-3">
				        <div class="left-column">
				        	<label>Email *</label>
				          	<input type="email" class="form-control" name="email">
				          	<span class="text-danger email" style="display:none">Email is required</span>
				          	<span class="text-danger email_validate" style="display:none">Please enter valid Email Address</span>
				        </div>
				        <div class="right-column">
				        	<label>Contact Number *</label>
				          	<input type="text" class="form-control" name="contact" onkeyup="validateNum(event,this);">
				          	<span class="text-danger contact" style="display:none">Contact is required</span>
				        </div>
			        </div>

			        <div class="form-group mb-3">
				        <label>Company Name *</label>
				        <input type="text" class="form-control" name="company_name">
				        <span class="text-danger company_name" style="display:none">Company Name is required</span>
			        </div>

			        <div class="form-group mb-3">
			        	<label>Address *</label>
			          	<textarea class="form-control" row="3" name="company_address" style="height:auto;"></textarea>
			          	<span class="text-danger company_address" style="display:none">Address is required</span>
			        </div>

			        <div class="form-group mb-3">
			        	<div class="left-column">
			        		<label>Country *</label>
				          	{!! Form::select('country_id',$countries,null,['class'=>'form-contol select2bs4 required add_country_id', 'id'=>'address_country']) !!}
				          	<span class="text-danger add_country_id" style="display:none">Country is required</span>
			        	</div>
			        	<div class="right-column">
				        	<label>State </label>
				        	 <select name="state_id" class="form-control select2bs4 add_state_id" id="address_state"></select>
				        </div>
			        </div>
			        <div class="form-group mb-5">
			        	<div class="left-column">
			        		<label>City</label>
				          	<select name="city_id" class="form-control select2bs4 add_city_id" id="address_city"></select>
			        	</div>
			        	<div class="right-column">
				        	<label>Post Code </label>
				        	<input type="text" class="form-control" name="company_name">	
				        </div>
			        </div>

			        <div class="form-group mb-4 condition">
		        		<div class="icheck-info d-inline">
            				<input type="checkbox" id="TnC">
            				<label for="TnC">I agree to the <span>Terms & Conditions</span></label>
          				</div>
		      		</div>
			        <div class="row">
			          <div class="col-12">
			            <button type="button" class="btn btn-primary btn-block">SEND</button>
			          </div>
			        </div>
      			</form>
    		</div>
  		</div>
	</div>
</div>

@push('custom-scripts')
	<script type="text/javascript">
		$(function ($) {
      		$('.select2bs4').select2();
    	});

		$(document).ready(function($) {
			
		  	$('#address_country').on('change',function() {
		      var country_id = $(this).val();
		      getState(country_id);
		    });

		    function getState(countryID){
		      var state_id = "{{old('State')}}" ;        
		      if(countryID){
		        $.ajax({
		          type:"GET",
		          dataType: 'json',
		          url:"{{url('get-state')}}?country_id="+countryID,
		          success:function(res){  
		            if(res){
		              $("#address_state").empty();
		              $("#address_state").append('<option selected value=""> ---Select--- </option>');
		              $.each(res,function(key,value){
		                var select_state="";
		                if(state_id == key) { var select_state = "selected" }
		                $("#address_state").append('<option value="'+key+'" '+select_state+'>'+value+'</option>');
		              });
		              $('#address_state').selectpicker('refresh');           
		            }else{
		              $("#address_state").empty();
		            }
		          },
		          error: function(res) { console.log(res.responseText) }
		        });
		      }else{
		        $("#address_state").empty();        
		      }      
		    }

		    //Get City
		    $('#address_state').change(function() {
		      var state_id = $(this).val();
		      getCity(state_id);
		    })
		    function getCity(stateID){
		      var city_id = "{{old('State')}}" ;        
		      if(stateID){
		        $.ajax({
		          type:"GET",
		          dataType: 'json',
		          url:"{{url('get-city')}}?state_id="+stateID,
		          success:function(res){  
		            if(res){
		              $("#address_city").empty();
		              $("#address_city").append('<option selected value=""> ---Select--- </option>');
		              $.each(res,function(key,value){
		                var select_city="";
		                if(city_id == key) { var select_city = "selected" }
		                $("#address_city").append('<option value="'+key+'" '+select_city+'>'+value+'</option>');
		              });
		              $('#address_city').selectpicker('refresh');           
		            }else{
		              $("#address_city").empty();
		            }
		          },
		          error: function(res) { console.log(res.responseText) }
		        });
		      }else{
		        $("#address_city").empty();        
		      }      
		    }


		    function validateForm(){
		        var valid=true;
		        if ($("[name='name']").val()=="") {
		            $("[name='name']").closest('.form-group').find('span.text-danger.name').show();
		            valid = false;
		        }else{
		        	$("[name='name']").closest('.form-group').find('span.text-danger.name').hide();
		        }
		        if ($("[name='email']").val()=="") {
		            $("[name='email']").closest('.form-group').find('span.text-danger.email').show();
		            valid = false;
		        }else{
		        	$("[name='email']").closest('.form-group').find('span.text-danger.email').hide();
		        }
		        if(!validateEmail($("[name='email']").val())){
		          $("[name='email']").closest('.form-group').find('span.text-danger.email_validate').show();
		          valid=false;
		        }else{
		          $("[name='email']").closest('.form-group').find('span.text-danger.email_validate').hide();
		        }
		        if ($("[name='contact']").val()=="") {
		            $("[name='contact']").closest('.form-group').find('span.text-danger.contact').show();
		            valid = false;
		        }else{
		        	$("[name='contact']").closest('.form-group').find('span.text-danger.contact').hide();
		        }
		        if ($("[name='company_name']").val()=="") {
		            $("[name='company_name']").closest('.form-group').find('span.text-danger.company_name').show();
		            valid = false;
		        }else{
		        	$("[name='company_name']").closest('.form-group').find('span.text-danger.company_name').hide();
		        }
		        if ($("[name='company_address']").val()=="") {
		            $("[name='company_address']").closest('.form-group').find('span.text-danger.company_address').show();
		            valid = false;
		        }else{
		        	$("[name='company_address']").closest('.form-group').find('span.text-danger.company_address').hide();
		        }
		        if ($("#address_country").val()=="") {
		            $("#address_country").closest('.form-group').find('span.text-danger.add_country_id').show();
		            valid = false;
		        }else{
		        	$("#address_country").closest('.form-group').find('span.text-danger.add_country_id').hide();
		        }
		        return valid;
		    }
		    
		    function validateEmail($email) {
			    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			    return emailReg.test( $email );
			}

		    $('.btn-block').on('click',function(event) {
		      	if(validateForm()){
		      		var $check = $('#TnC').is(':checked');
		      		if($check){
		      			$('#requestForm').submit();
		      		}else{
		      			$('.icheck-info').css('border','1px dotted #ec3a3a');
		      			return false;
		      		}
		      	}else{
		      		return false;
		      	}
		    });

		});
	</script>
@endpush
@endsection