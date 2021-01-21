@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Salary List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Salary List</li>
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
                <h3 class="card-title">Employee's Salary List</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="date-filter">
                    <div class="input-group date" id="dateFilter" data-target-input="nearest">
                      <input type="text" name="date" class="form-control datetimepicker-input" data-target="#dateFilter" value="{{old('date')}}"/>
                      <div class="input-group-append" data-target="#dateFilter" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th>Name</th>
                        <th>Basic Salary</th>
                        <th>HRA</th>
                        <th>DA</th>
                        <th>Conveyance</th>
                        <th>ESI</th>
                        <th>PF</th>
                        <th>Total Salary</th>
                        <th>Paid Date</th>
                      	<th>Status</th>
                      	<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach($employee_salary as $emp)
                        <tr>
                          <input type="hidden" name="emp_id" value="{{$emp['id']}}">
                          <td>{{$emp['name']}}</td>
                          <td>{{$emp['basic_salary']}}</td>
                          <td>{{$emp['hra']}}</td>
                          <td>{{$emp['da']}}</td>
                          <td>{{$emp['conveyance']}}</td>
                          <td>{{$emp['esi']}}</td>
                          <td>{{$emp['pf']}}</td>
                          <td>{{$emp['toatl_salary']}}</td>
                          <td>{{$emp['paid_date']}}</td>
                          <td>{{$emp['status']}}</td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                
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
    .date-filter {position: absolute;left: 550px;width: 150px;z-index: 999;}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">
      //Date range picker
      $(function() {
        $('#dateFilter').datetimepicker({
            format: 'MM/yy'
        });
      });
    </script>
  @endpush

@endsection