@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Rejected Customers</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customer</a></li>
              <li class="breadcrumb-item active">Rejected Customers</li>
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 action-controllers ">
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','delete'))
              <div class="col-sm-6 text-left pull-left">
                <a href="javascript:void(0)" class="btn btn-danger delete-all">
                  <i class="fa fa-trash"></i> Delete (selected)
                </a>
                <a href="{{ route('customers.index') }}" class="btn btn-primary">
                  <i class="fas fa-users"></i> All Customers
                </a>
              </div>
            @endif
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Rejected Customers</h3>
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
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach ($all_customers as $customer)
                    		<tr>
                    			<td>
                    				<input type="checkbox" name="customer_ids" value="{{ $customer->id }}">
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
                            @if(Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','delete'))
                              <a href="#">
                                <form method="POST" action="{{ route('customers.destroy',$customer->id) }}">@csrf 
                                  <input name="_method" type="hidden" value="DELETE">
                                  <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                </form>
                              </a>
                            @endif
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