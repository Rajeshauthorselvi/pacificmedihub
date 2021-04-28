@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Import Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customer</a></li>
              <li class="breadcrumb-item active">Import Customer</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{!!  $error  !!}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <!-- Main content -->
    <section class="content">

            <div class="col-md-12 action-controllers ">
              <div class="col-sm-6 pull-left">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item active">
                    <a href="{{route('customers.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
                  </li>
                </ol>
            </div>
            <div class="col-sm-6 text-right pull-right">
            <a href="{{ url('admin/customer-export') }}" class="btn btn-info">
              <i class="fa fa-download"></i> Download All Customer
            </a>
            <a href="{{ url('admin/customer-sample-sheet') }}" class="btn btn-info">
              <i class="fa fa-download"></i> Download Sample Sheet
            </a>
            </div>
          </div>
          <div class="clearfix"></div>
      <div class="container-fluid toggle-tabs">
        <div class="row">
          <div class="col-md-12">
          	{!! Form::open(['url'=>'admin/customer-import-post','method'=>'POST','files'=>true]) !!}
          	  <div class="card card-outline card-primary">
	              <div class="card-header">
	                <h3 class="card-title">Import New Customers By Excel</h3>
	              </div>
	              <div class="card-body">
                   <div class="alert alert-info col-sm-4">
                      Note: Please start customer id from <b>{{ $last_customer_id+1 }}</b>
                   </div>
	              		<div class="form-group">
	              			<input type="file" name="customer_import" accept=".xls,xlsx" required="">
	              		</div>
	              		<div class="submit-sec">
	              			<button type="submit" class="btn btn-success">
	              				Import Customers
	              			</button>
	              		</div>
	              </div>
         	  </div>
          	{!! Form::close() !!}
          	</div>
          </div>
      </div>
  </section>
</div>
@endsection