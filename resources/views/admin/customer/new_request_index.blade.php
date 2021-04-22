@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">New Customer Request</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customers</a></li>
              <li class="breadcrumb-item active">New Customer Request</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid customer">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
              	<ul class="nav nav-tabs flex-nowrap">
                  <li class="nav-item">
                    <a href="{{ route('customers.index') }}" class="nav-link" title="Active Customer List"><i class="fas fa-users"></i> &nbsp;Active Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('new.customer') }}" class="nav-link active" title="New Customer Requests List"><i class="fas fa-user-plus"></i> &nbsp;New Requests</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('reject.customer') }}" class="nav-link" title="Rejected Customer List"><i class="fas fa-user-times"></i> &nbsp;Rejected Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('blocked.customer') }}" class="nav-link" title="Blocked Customer List"><i class="fas fa-user-lock"></i> &nbsp;Blocked Customers</a>
                  </li>
                </ul>
              </div>
              
              <div class="card">
                <div class="card-body">
                	<table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Customer Code</th>
                        <th>Company Name</th>
                        <th>Parent Company</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach ($new_request as $request)
                    		<tr>
                          <th><a href="{{route('customers.show',$request->id)}}">{{$request->customer_no}}</a></th>
                    			<td>{{$request->name}}</td>
                    			<td>
                            <?php
                              if($request->parent_company==0){
                                $parent = '-';
                              }else{
                                $parent = $request->ParentCompany($request->parent_company);
                              }
                             ?>
                            {{ isset($parent)?$parent:'-' }}
                          </td>
                    			<td>{{ $request->email }}</td>
                    			<td>{{ $request->contact_number }}</td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                @if((Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','update')))
                                  <a href="{{route('customers.edit',$request->id)}}?from=approve"><li class="dropdown-item">
                                    <i class="fas fa-user-check"></i>&nbsp;&nbsp;Approve</li>
                                  </a>
                                  <a href="{{ route('reject.block',['id'=>$request->id,'data'=>'reject']) }}" onclick="return confirm('Are you sure you want to reject?');"><li class="dropdown-item">
                                    <i class="fas fa-user-times"></i>&nbsp;&nbsp;Reject</li>
                                  </a>
                                @endif
                              </ul>
                            </div>
                          </td>
                    		</tr>
                    	@endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

	<style type="text/css">
		.customer .pull-left{width:80%}
		.customer .nav.nav-tabs.flex-nowrap {border: none;}
		.customer .nav-tabs .nav-item{margin:0 3px;width:21%;text-align:center;}
		.customer .nav-tabs .nav-item .nav-link{border: none;border-radius: 0;background: #ebeff5;}
		.customer .nav-tabs .nav-item .nav-link.active {background: #02abbf;color: #fff;}
	</style>

 @endsection