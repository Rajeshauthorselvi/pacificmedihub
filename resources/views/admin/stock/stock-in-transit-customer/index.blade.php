@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock-In-Transit-Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Stock-In-Transit-Customer</li>
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
                <h3 class="card-title">Stock-In-Transit-Customer</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th>Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                        <th>Return Qty</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($returns as $return)
                        <tr>
                          <td>{{ $return['return_date'] }}</td>
                          <td>{{ $return['order_number'] }}</td>
                          <td>{{ $return['customer'] }}</td>
                          <td>{{ $return['quantity'] }}</td>
                          <td>{{ $return['qty_received'] }}</td>
                          <td>{{ $return['return_quantity'] }}</td>
                          <td>
                            <span class="badge" style="background:{{ $return['color_code'] }};color: #fff ">{{ $return['status'] }}</span>
                          </td>
                          <td class="text-center">
                            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('stock_transist_customer','update'))
                              <a href="javascritp:void(0);" class="btn btn-primary">Stock Update</a>
                            @else
                              -
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
@endsection