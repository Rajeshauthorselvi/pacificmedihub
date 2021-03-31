@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
  <div class="container">
    <ul class="items">
      <li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
      <li><a href="{{ route('my-rfq.index') }}" title="My RFQ">My RFQ</a></li>
      <li><a title="My RFQ Comments">Comments</a></li>
    </ul>
  </div>
</div>
@include('flash-message')
<div class="main">
  <div class="container">
    <div class="row customer-page">
      <div id="column-left" class="col-sm-3 hidden-xs column-left">
        @include('front.customer.customer_menu')
      </div>

      <div class="col-sm-9">
        <div class="row">
          <div class="web rfq view-block comment-page">
            
            <div class="action_sec">
              <ul class="list-unstyled" style="margin:0">
                <li style="background-color: #216ea7">
                  <?php 
                    $disabled="";
                    if ($rfq_details->status==13) {
                      $disabled = "pointer-events:none;opacity:0.5";
                    }
                  ?>
                  <a href="javascript:void(0);" class="place-order" onclick="return confirm('Are you sure want to Place Order?')" style="{{ $disabled }}">
                    <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                  </a>
                </li>
                <li style="background-color: #23bf79">
                  <?php $rfqId = base64_encode($rfq_id); ?>
                  <a href="{{ route('my-rfq.show',$rfqId) }}" class="view">
                    <i class="fa fa-eye"></i>&nbsp; View
                  </a>
                </li>
                <li style="background-color: #f6ac50;@if($rfq_details->status==13) display:none; @endif">
                  <a href="{{ route('my-rfq.edit',$rfqId) }}" class="edit">
                    <i class="fa fa-edit"></i>&nbsp; Edit
                  </a>
                </li>
              </ul>
            </div>

            <div class="rfq-info">
              <ul class="list-unstyled">
                <li>Order No: {{ $rfq_details->order_no }}</li>
                <li>Date: {{ date('d/m/Y',strtotime($rfq_details->created_at)) }}</li>
                <li>Status: {{ $rfq_details->statusName->status_name }}</li>
                <li>Sales Rep: {{ $rfq_details->salesrep->emp_name }}</li>
              </ul>
            </div>
        
            

            <div class="conversation-block">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages">
                <!-- Message. Default to the left -->
                @foreach ($comments as $comment)
                  <?php
                    $user_name=\App\Models\RFQComments::GetUserNames($comment->commented_by,$comment->commented_by_user_type);
                    $check_attachment_exists=\App\Models\RFQCommentsAttachments::where('rfq_comment_id',$comment->id)->count();
                  ?>
                   
                	@if(Auth::id()==$comment->commented_by && $comment->commented_by_user_type==3)
  	                <!-- Message to the right -->
  	                <div class="direct-chat-msg right">
  	                  <div class="direct-chat-infos clearfix">
  	                    <span class="direct-chat-name float-right">{{ $user_name->name }}</span>
  	                    <span class="direct-chat-timestamp float-left">{{ date('d M y H:i:s',strtotime($comment->created_at)) }}</span>
  	                  </div>
  	                  <!-- /.direct-chat-infos -->
                      <img class="direct-chat-img" src="{{ asset('theme/images/user_placeholder.png') }}">
                      <!-- /.direct-chat-img -->
  	                  <div class="direct-chat-text">
  	                    {{ $comment->comment }}
  	                    @if($check_attachment_exists>0)
  		                    &nbsp;&nbsp;
  		                    <a href="javascript:void(0)" comment-id="{{ $comment->id }}" class="btn btn-link view-attchment">
  		                    	<i class="fa fa-paperclip"></i>
  		                    </a>
  	                    @endif
  	                  </div>
  	                  <!-- /.direct-chat-text -->
  	                </div>
                  @elseif(Auth::id()!=$comment->commented_by && $comment->commented_by_user_type==1)
                    <!-- /.direct-chat-msg -->
  	                <div class="direct-chat-msg">
  	                  <div class="direct-chat-infos clearfix">
  	                    <span class="direct-chat-name float-left">{{ $user_name->name }}</span>
  	                    <span class="direct-chat-timestamp float-right">{{ date('d M y H:i:s',strtotime($comment->created_at)) }}</span>
  	                  </div>
  	                  <!-- /.direct-chat-infos -->
  	                  <img class="direct-chat-img" src="{{ asset('theme/images/user_placeholder.png') }}" >
  	                  <!-- /.direct-chat-img -->
  	                  <div class="direct-chat-text">
  	                    {{ $comment->comment }} 
  	                    @if ($check_attachment_exists>0)
  		                    &nbsp;&nbsp;
  		                    <a href="javascript:void(0)" comment-id="{{ $comment->id }}" class="btn btn-link view-attchment">
  		                     	<i class="fa fa-paperclip"></i>
  		                    </a>
  	                    @endif
  	                  </div>
  	                  <!-- /.direct-chat-text -->
  	                </div>
  	                <!-- /.direct-chat-msg -->
                  @elseif(Auth::id()!=$comment->commented_by && $comment->commented_by_user_type==2)
                    <!-- /.direct-chat-msg -->
                    <div class="direct-chat-msg emp">
                      <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name float-left">{{ $user_name->emp_name }}</span>
                        <span class="direct-chat-timestamp float-right">{{ date('d M y H:i:s',strtotime($comment->created_at)) }}</span>
                      </div>
                      <!-- /.direct-chat-infos -->
                      <img class="direct-chat-img" src="{{ asset('theme/images/user_placeholder.png') }}" >
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        {{ $comment->comment }} 
                        @if ($check_attachment_exists>0)
                          &nbsp;&nbsp;
                          <a href="javascript:void(0)" comment-id="{{ $comment->id }}" class="btn btn-link view-attchment">
                            <i class="fa fa-paperclip"></i>
                          </a>
                        @endif
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                  @endif
                @endforeach
              </div>
              <!--/.direct-chat-messages-->
  				    <form action="{{ route('my.rfq.comments.post') }}" method="post" files="true" enctype="multipart/form-data">
  				      @csrf
  				      <input type="hidden" name="rfq_id" value="{{ $rfq_id }}">
                <div class="input-group">
                  <input type="text" name="comment" placeholder="Type Message ..." class="form-control">
                  <span class="input-group-append">
                    <button type="button" class="btn btn-info" id="attachment">
                    	<i class="fa fa-paperclip"></i>
                    </button>
                    <button type="submit" class="btn btn-primary">Send</button>
                  </span>
                </div>

			          <!-- Modal -->
 			          <div class="modal fade" id="attachment-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
   			          <div class="modal-dialog" role="document">
  			            <div class="modal-content">
  			              <div class="modal-header">Attach your file</div>
  			              <div class="modal-body">
  			      	        <div class="form-group">
  			      		        <input type="file" name="attachment[]" class="form-control">
  			      	        </div>
  			      	        <div class="append-sec"></div>
  			      	        <div class="text-right">
  			      		        <a href="javascript:void(0)" class="btn btn-primary add-more">
  			      			       <i class="fa fa-plus"></i>
  			      		        </a>
  			      	        </div>
  			              </div>
  			              <div class="modal-footer">
  			                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
  			              </div>
  			            </div>
  			          </div>
  			        </div>                  
              </form>
              <!-- /.direct-chat-pane -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="view-attchment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">Attachments</div>
      <div class="modal-body view-attchment-modal"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> 

@push('custom-scripts')
  <script type="text/javascript">
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
        $(this).ekkoLightbox({
          alwaysShowClose: true
        });
      });

  		$(document).on('click', '#attachment', function(event) {
   			event.preventDefault();
   			$('#attachment-modal').modal('show');
   		});
			$('.direct-chat-messages').animate({
				scrollTop: $('.direct-chat-messages').get(0).scrollHeight
			}, 2000);
			$('.add-more').click(function(event) {
				$('.append-sec').append('<div class="form-group"> <input type="file" name="attachment[]" class="form-control" style="width: 90%;float:left;"> <div style="width: 10%;float: right;" class="text-right"> <a href="javascript:void(0);" class="btn btn-danger remove-item"><i class="fa fa-minus"></i></a></div></div>');
			});

      $(document).on('click','.remove-item',function(event) {
        $(this).parents('.form-group').remove();
      });

			$(document).on('click', '.view-attchment', function(event) {
				event.preventDefault();
				var commentId=$(this).attr('comment-id');
				$.ajax({
          type:'POST',
					url: "{{ route('view.my.rfq.comments.attachment') }}",
          data: {
            "_token": "{{ csrf_token() }}",
            comment_id:commentId
          },
				})
				.done(function(response) {
					$('.view-attchment-modal').html(response);
					$('#data-table').DataTable({"ordering": false,});
					$('#view-attchment').modal('show');
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
			});
    	</script>
    @endpush
@endsection