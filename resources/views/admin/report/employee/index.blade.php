@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Employee Reports</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Employee Reports</li>
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
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Employee Reports</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th>Code</th>
                      	<th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Contact No</th>
                      	<th>Published</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach($employees as $emp)
                        <tr>
                          <td><a href="{{route('employees.show',$emp->id)}}">{{$emp->emp_id}}</a></td>
                          <td>{{$emp->emp_name}}</td>
                          <td>{{$emp->department->dept_name}}</td>
                          <td>{{ $emp->email }}</td>
                          <td>{{ $emp->emp_mobile_no }}</td>
                          <?php
                            if($emp->status==1){$status = "fa-check";}
                            else{$status = "fa-ban";}
                          ?>
                          <td><i class="fas {{$status}}"></i></td>
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