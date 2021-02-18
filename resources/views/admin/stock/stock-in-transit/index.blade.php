@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock-In-Transit</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Stock-In-Transit</li>
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
                <h3 class="card-title">Stock-In-Transit</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th>Date</th>
                        <th>Purchase Order No</th>
                        <th>Vendor</th>
                        <th>Qty Ordered</th>
                        <th>Qty Received</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                          <tr>
                          <td>{{ date('d-m-Y',strtotime($order['purchase_date'])) }}</td>
                          <td>{{ $order['po_number'] }}</td>
                          <td>{{ $order['vendor'] }}</td>
                          <td>{{ $order['quantity'] }}</td>
                          <td>{{ $order['qty_received'] }}</td>
                          <td><span class="badge" style="background:{{ $order['color_code'] }};color: #fff ">{{ $order['status'] }}</span></td>
                          <td>
                            
                                    <a href="{{route('stock-in-transit.edit',$order['purchase_id'])}}" class="btn btn-primary">Stock Update</a>
             
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