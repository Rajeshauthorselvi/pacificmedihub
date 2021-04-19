@extends('front.layouts.default')
@section('front_end_container')
<div class="login-page">
	<div class="login-box">
  		<div class="card">
    		<div class="card-body code-block" style="display:block;">
      			<div class="login-header">
        			<h4>Forgot Password</h4>
        			<p>Please enter your registerd email. A unique code will send your email, to verify.</p>
      			</div>
        		@include('flash-message')
			    <div class="form-group mb-3">
			      	<label>Email</label>
			       	<input type="email" class="form-control email" name="email">
			       	<span class="text-danger email" style="display:none;">Please enter valid Email</span>
			    </div>
			    <div class="row">
			        <div class="col-12">
				        <button type="button" class="btn btn-primary btn-block get-code">Get Code</button>
			        </div>
			    </div>
    		</div>

    		<div class="card-body reset-block" style="display:none;">
    			<form action="{{ route('reset.password') }}" method="get">
    				@csrf
	      			<div class="login-header">
	        			<h4>Rest Password</h4>
	        			<p>Please check your email. A unique code sent your email.</p>
	      			</div>
	        		@include('flash-message')
				    <div class="form-group mb-3">
				      	<label>Enter Your Code</label>
				       	<input type="text" class="form-control input-code" name="code" autocomplete="off">
				       	<span class="text-danger code" style="display:none;">Code Not Matching</span>
				       	<input type="hidden" class="get-email" name="email">
				    	<input type="hidden" class="check-code">
				    </div>
				    <div class="after-verify">
					    <div class="form-group mb-3">
					      	<label>New Password</label>
					       	<input type="password" class="form-control new-pwd" name="new_pwd">
					    </div>
					    <div class="form-group mb-3">
					      	<label>Confirm Password</label>
					       	<input type="password" class="form-control con-pwd" name="con_pwd">
					       	<span id='erromessage' class="text-danger" style="display:none">Not Matching</span>
					    </div>
					    <div class="row">
					        <div class="col-12">
						        <button type="submit" class="btn btn-primary btn-block save-change">Save</button>
					        </div>
					    </div>
					</div>
      			</form>
	    	</div>
      	</div>
    </div>
</div>

<style type="text/css">
	.after-verify{
		pointer-events: none;
		opacity: 0.3;
	}
</style>

@push('custom-scripts')
	<script type="text/javascript">
		$(document).on({
        	ajaxStart: function(){
            	$('#overlay').fadeIn(300);　
        	},
        	ajaxStop: function(){ 
            	$('#overlay').fadeOut(300); 
        	}    
    	});

		$(document).on('click', '.get-code', function(event) {
			var emailID = $('.email').val();

			if(emailID!=''){
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	    		if( !emailReg.test( emailID ) ) {
	        		$('.text-danger.email').show();
	    		} else {
	    			$('.text-danger.email').hide();
	    			$.ajax({
						url: '{{ url('reset-password') }}',
						//type: 'POST',
						data: {
							//"_token": "{{ csrf_token() }}",
							email: emailID
						},
					})
					.done(function(data) {
						console.log(data);
						if(data['is_exist']==true && data['status']==true){
							$('.code-block').css('display','none');
							$('.reset-block').css('display','block');
							$('.check-code').val(data['code']);
							$('.get-email').val(data['email']);
						}else if(data['is_exist']==false && data['status']==false){
							alert(data['message']);
						}else if(data['is_exist']==true && data['status']==false){
							alert(data['message']);
						}
						
					})
					.fail(function() {
						console.log("error");
					})
	    		}
    		}else{
				alert('Please, enter email first.!')
			}
		});

		$(document).on('keyup','.input-code',function() {
			var checkLength = $(this).val().length;
			if(checkLength==6){
				if($(this).val()==$('.check-code').val()){
					$(this).css({'pointer-events':'none','opacity':'0.3'});
					$('.after-verify').css({'pointer-events':'auto','opacity':'1'});
					$('.text-danger.code').hide();
				}else{
					$('.text-danger.code').show();
				}
			}
		});

		$('.new-pwd, .con-pwd').on('keyup',function(){
			
			if ($('.new-pwd').val() == $('.con-pwd').val()) {
		        $('#erromessage').hide(); 
		        $('.save-change').css('pointer-events','auto');
		        $('.save-change').css('opacity','1');
		    }else{
		        $('.save-change').css('pointer-events','none');
		        $('.save-change').css('opacity','.5');
		        $('#erromessage').show(); 
		    }
		});
	</script>
@endpush
@endsection