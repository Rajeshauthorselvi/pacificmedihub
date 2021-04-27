@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Customers</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Customers</li>
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
           <div class="col-md-12 action-controllers ">
              @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','delete'))
            <div class="col-sm-6 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
            </div>
              @endif
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','create'))
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('customers.create')}}">
                <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
              <a href="{{ url('admin/customer-import') }}" class="btn btn-info">
                <i class="fa fa-file-import"></i>&nbsp; Import Customers
              </a>
            </div>
            @endif
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
              	<ul class="nav nav-tabs flex-nowrap">
                  <li class="nav-item">
                    <a href="{{ route('customers.index') }}" class="nav-link active" title="Active Customer List"><i class="fas fa-users"></i> &nbsp;Active Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('new.customer') }}" class="nav-link" title="New Customer Requests List"><i class="fas fa-user-plus"></i> &nbsp;New Requests</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('reject.customer') }}" class="nav-link" title="Rejected Customer List"><i class="fas fa-user-times"></i> &nbsp;Rejected Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('blocked.customer') }}" class="nav-link" title="Blocked Customer List"><i class="fas fa-user-lock"></i> &nbsp;Blocked Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('interested.customers') }}" class="nav-link" title="Interested Customer List"><i class="fas fa-user-tie"></i> &nbsp;Interested Customers</a>
                  </li>
                </ul>
              </div>
              <div class="card">
                <div class="card-body">
              		<table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th><input type="checkbox" class="select-all"></th>
                        <th>Customer Code</th>
                        <th>Company Name</th>
                        <th>Parent Company</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Total Orders</th>
                        <th>Total Orders Amount</th>
                        <th>Published</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach ($all_customers as $customer)
                    		<tr>
                    			<td>
                    				@if($customer->appoved_status!=1)
                    					<input type="checkbox" name="customer_ids" value="{{ $customer->id }}">
                    				@endif
                    			</td>
                          <th><a href="{{route('customers.show',$customer->id)}}">{{$customer->customer_no}}</a></th>
                    			<td>{{$customer->name}}</td>
                    			<td>
                            <?php
                              if($customer->parent_company==0){
                                $parent = '-';
                              }else{
                                $parent = $customer->ParentCompany($customer->parent_company);
                              }
                             ?>
                            {{ isset($parent)?$parent:'-' }}
                          </td>
                    			<td>{{ $customer->email }}</td>
                    			<td>{{ $customer->contact_number }}</td>
                    			<td>
                            {{($customer->TotalOrders($customer->id))?$customer->TotalOrders($customer->id):0}}
                          </td>
                          <td>
                            {{($customer->TotalOrderAmount($customer->id))?$customer->TotalOrderAmount($customer->id):'0.00'}}
                          </td>
                          <?php
                            if($customer->status==1){$status = "fa-check";}
                            else{$status = "fa-ban";}
                          ?>
                          <td><i class="fas {{$status}}"></i></td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                @if ($customer->appoved_status==3)
                                  <a href="{{route('customers.show',$customer->id)}}"><li class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp;&nbsp;View</li>
                                  </a>
                                @endif
                                @if ($customer->appoved_status==3 && (Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','update')))
	                                <a href="{{route('customers.edit',$customer->id)}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
	                                @if($customer->status!=0)
	                                	<a href="{{route('reject.block',['id'=>$customer->id,'data'=>'block'])}}" onclick="return confirm('Are you sure you want to block?');"><li class="dropdown-item"><i class="fas fa-lock"></i>&nbsp;&nbsp;Block</li></a>
	                                @else
	                                	<a href="{{route('reject.block',['id'=>$customer->id,'data'=>'unblock'])}}" onclick="return confirm('Are you sure you want to unblock?');"><li class="dropdown-item"><i class="fas fa-unlock"></i></i>&nbsp;&nbsp;Unblock</li></a>
	                                @endif
                                @endif
                                @if ($customer->appoved_status==3 && (Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','delete')))
                                  <a href="#"><li class="dropdown-item">
                                    <form method="POST" action="{{ route('customers.destroy',$customer->id) }}">@csrf 
                                      <input name="_method" type="hidden" value="DELETE">
                                      <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                    </form>
                                  </li></a>
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

  @push('custom-scripts')
	  <script type="text/javascript">
	    $('.select-all').change(function() {
	      if ($(this). prop("checked") == true) {
	        $('input:checkbox').prop('checked',true);
	      }
	      else{
	        $('input:checkbox').prop('checked',false);
	      }
	    });


	    $('.delete-all').click(function(event) {
	      var checkedNum = $('input[name="customer_ids"]:checked').length;
	      if (checkedNum==0) {
	        alert('Please select customer');
	      }
	      else{
	        if (confirm('Are you sure want to delete?')) {
	          $('input[name="customer_ids"]:checked').each(function () {
	            var current_val=$(this).val();
	            $.ajax({
	              url: "{{ url('admin/customers/') }}/"+current_val,
	              type: 'DELETE',
	              data:{
	               "_token": $("meta[name='csrf-token']").attr("content")
	              }
	            })
	            .done(function() {
	               location.reload(); 
	            })
	            .fail(function() {
	              console.log("Ajax Error :-");
	            });
	          });
	        }
	      }
	    });
	  </script>
  @endpush
 @endsection