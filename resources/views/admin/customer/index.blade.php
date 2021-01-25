@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Create Customers</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">List Customer</a></li>
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
          <div class="col-md-12 action-controllers">
            <a class="btn add-new" href="{{route('customer.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
            </a>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Customers</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th></th>
                        <th>Customer Name</th>
                        <th>Company Name</th>
                        <th>Parent Company</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Total Orders</th>
                        <th>Total Orders Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach ($all_customers as $customer)
                    		<tr>
                    			<td>
                    				<input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}">
                    			</td>
                    			<td> {{ $customer->first_name.' '.$customer->last_name }} </td>
                    			<td></td>
                    			<td></td>
                    			<td>{{ $customer->email }}</td>
                    			<td>{{ $customer->contact_number }}</td>
                    			<td></td>
                    			<td>{{ ($customer->status==1)?'Approved':'Disapproved' }}</td>
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


 @endsection