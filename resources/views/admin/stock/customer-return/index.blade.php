@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Customer Returns List</h1>
          </div><!-- /.col -->
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Customer Returns</li>
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
                <ul class="nav nav-tabs flex-nowrap">
                  <li class="nav-item">
                    <a href="{{ route('return.index') }}" class="nav-link" title="Vendor Return List"><i class="fas fa-people-carry"></i> &nbsp;Vendor Return</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('customer-return.index') }}" class="nav-link active" title="Customer Return List"><i class="fas fa-users"></i> &nbsp;Customer Return</a>
                  </li>
                </ul>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th>Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Return Quantity</th>
                        <th>Return Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($returns as $return)
                        <tr>
                          <td>{{ $return['return_date'] }}</td>
                          <td>{{ $return['order_number'] }}</td>
                          <td>{{ $return['customer'] }}</td>
                          <td>{{ $return['return_quantity'] }}</td>
                          <td>
                            <span class="badge" style="background:{{ $return['color_code'] }};color: #fff ">{{ $return['status'] }}</span>
                          </td>
                          <td class="text-center">
                            <a href="{{ route('customer-return.show',$return['return_id']) }}" class="btn btn-primary">
                              <i class="far fa-eye"></i>&nbsp;View</a>
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
    .pull-left{width:80%}
    .nav.nav-tabs.flex-nowrap {border: none;}
    .nav-tabs .nav-item{margin:0 3px;width:21%;text-align:center;}
    .nav-tabs .nav-item .nav-link{border: none;border-radius: 0;background: #ebeff5;}
    .nav-tabs .nav-item .nav-link.active {background: #02abbf;color: #fff;}
  </style>
  @push('custom-scripts')

  @endpush
@endsection