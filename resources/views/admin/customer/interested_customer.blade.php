@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Interested Customers</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Interested Customers</li>
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
              	<h4>Interested Customers</h4>
              </div>
              <div class="card">
                <div class="card-body">
              		<table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No.</th>
                        <th>Customer Code</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no=1; ?>
                    	@foreach ($interested_customers as $customer)
                    		<tr>
                          <td>{{ $no }}</td>
                    			<td>{{$customer->email}}</td>
                    			<td>{{ date('d/m/Y', strtotime($customer->created_at)) }}</td>
                    		</tr>
                        <?php $no++; ?>
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