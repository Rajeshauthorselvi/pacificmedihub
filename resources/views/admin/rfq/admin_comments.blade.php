@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">RFQ Message</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">RFQ</a></li>
              <li class="breadcrumb-item active">RFQ Message</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('rfq.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid rfq comment-page">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">RFQ Message</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="rfq-info">
                    <ul>
                      <li>Order No: {{ $rfq_details->order_no }}</li>
                      <li>Date: {{ date('d/m/Y',strtotime($rfq_details->created_at)) }}</li>
                      <li>Status: {{ $rfq_details->statusName->status_name }}</li>
                      <li>Sales Rep: {{ $rfq_details->salesrep->emp_name }}</li>
                    </ul>
                  </div>
                  <br>
                  <br>
                  <div class="action_sec">
                    <div class="clearfix"></div>

                     <div class="clearfix"></div>
                    <ul class="list-unstyled">
                      <li>
                 <?php 
                      $disabled="";
                      if ($rfq_details->status==13) {
                          $disabled="pointer-events:none;opacity:0.5";
                      }
                      ?>
                        <a href="{{ route('rfq.toOrder',$rfq_id) }}" class="place-order" onclick="return confirm('Are you sure want to Place Order?')" style="{{ $disabled }}">
                          <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                        </a>
                      </li>
                      <li>
                        <a href="{{ url('admin/rfq/'.$rfq_id) }}" class="view">
                          <i class="fa fa-eye"></i>&nbsp; View
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('rfq.edit',$rfq_id) }}" class="edit">
                          <i class="fa fa-edit"></i>&nbsp; Edit
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="clearfix"></div>
                  <hr>
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages">
                  <!-- Message. Default to the left -->
                  @foreach ($comments as $comment)
                               <?php
                    $user_name=\App\Models\RFQComments::GetUserNames($comment->commented_by,$comment->commented_by_user_type);
                    $check_attachment_exists=\App\Models\RFQCommentsAttachments::where('rfq_comment_id',$comment->id)->count();
                     ?>
                   
                  	@if(Auth::id()==$comment->commented_by && $comment->commented_by_user_type==1)
		                  <!-- Message to the right -->
		                  <div class="direct-chat-msg right">
		                    <div class="direct-chat-infos clearfix">
		                      <span class="direct-chat-name float-right">{{ $user_name->name }}</span>
		                      <span class="direct-chat-timestamp float-left">{{ date('d M y H:i:s',strtotime($comment->created_at)) }}</span>
		                    </div>
		                    <!-- /.direct-chat-infos -->
		                    <img class="direct-chat-img" src="{{ asset('theme/images/user_placeholder.png') }}" alt="message user image">
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
		                
                  @else
  <!-- /.direct-chat-msg -->
		                  <div class="direct-chat-msg">
		                    <div class="direct-chat-infos clearfix">
		                      <span class="direct-chat-name float-left">{{ $user_name->emp_name }}</span>
		                      <span class="direct-chat-timestamp float-right">{{ date('d M y H:i:s',strtotime($comment->created_at)) }}</span>
		                    </div>
		                    <!-- /.direct-chat-infos -->
		                    <img class="direct-chat-img" src="{{ asset('theme/images/user_placeholder.png') }}" alt="message user image">
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
                <hr>
 				<form action="{{ url('admin/rfq-comments-post') }}" method="post" files="true" enctype="multipart/form-data">
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
    </section>
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
    <style type="text/css">
.direct-chat-messages {
  scrollbar-width: thin;
}

    </style>
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
				var comment_id=$(this).attr('comment-id');
				$.ajax({
					url: '{{ url('admin/view-rfq-comments-attachment/') }}/'+comment_id,
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
    <style type="text/css">

    	/*.direct-chat-text{
    		width: 20%;
    	}
    	.right .direct-chat-text {
			float: right;
		}*/
    </style>
@endsection