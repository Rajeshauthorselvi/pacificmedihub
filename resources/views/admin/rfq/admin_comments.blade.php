@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View RFQ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">RFQ</a></li>
              <li class="breadcrumb-item active">View RFQ</li>
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
      <div class="container-fluid rfq show-page">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">View RFQ</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="action_sec">
                    <div class="clearfix"></div>
                    <ul class="list-unstyled">
                      <li>
                        <a href="{{ route('rfq.toOrder',$rfq_id) }}" class="place-order" onclick="return confirm('Are you sure want to Place Order?')">
                          <i class="fa fa-plus-circle"></i>&nbsp; Place Order
                        </a>
                      </li>
                      <li>
                        <a href="{{ url('admin/rfq_pdf/'.$rfq_id) }}" class="pdf"><i class="fa fa-download"></i>&nbsp; PDF</a>
                      </li>
                      <li>
                        <a href="" class="email"><i class="fa fa-envelope"></i>&nbsp; Email</a>
                      </li>
                      <li>
                        <a href="{{ url('admin/rfq-comments/'.$rfq_id) }}" class="comment"><i class="fa fa-comment"></i>&nbsp; Comment</a>
                      </li>
                      <li>
                        <a href="{{ route('rfq.edit',$rfq_id) }}" class="edit">
                          <i class="fa fa-edit"></i>&nbsp; Edit
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('rfq.delete',$rfq_id) }}" class="delete" onclick="return confirm('Are you sure want to delete?')">
                          <i class="fa fa-trash"></i>&nbsp; Delete
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
                   
                     ?>
                  	@if(Auth::id()==$comment->commented_by && $comment->commented_by_user_type==1)
		                  <!-- Message to the right -->
		                  <div class="direct-chat-msg right">
		                    <div class="direct-chat-infos clearfix">
		                      <span class="direct-chat-name float-right">{{ $user_name->first_name }}</span>
		                      <span class="direct-chat-timestamp float-left">{{ date('d M y H:i:s',strtotime($comment->created_at)) }}</span>
		                    </div>
		                    <!-- /.direct-chat-infos -->
		                    <img class="direct-chat-img" src="{{ asset('theme/images/user_placeholder.png') }}" alt="message user image">
		                    <!-- /.direct-chat-img -->
		                    <div class="direct-chat-text">
		                      {{ $comment->comment }}
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
		                    </div>
		                    <!-- /.direct-chat-text -->
		                  </div>
		                  <!-- /.direct-chat-msg -->
                  	@endif
                  @endforeach


                </div>
                <!--/.direct-chat-messages-->
                <hr>
 				<form action="{{ url('admin/rfq-comments-post') }}" method="post">
 				@csrf

 				  <input type="hidden" name="rfq_id" value="{{ $rfq_id }}">
                  <div class="input-group">
                    <input type="text" name="comment" placeholder="Type Message ..." class="form-control">
                    <span class="input-group-append">
                      <button type="submit" class="btn btn-primary">Send</button>
                    </span>
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
    <style type="text/css">
    	
    	/*.direct-chat-text{
    		width: 20%;
    	}
    	.right .direct-chat-text {
			float: right;
		}*/
    </style>
@endsection