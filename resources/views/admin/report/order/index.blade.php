@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Orders Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Order Report</li>
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
                <h3 class="card-title">Report</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <div class="panel panel-primary">
                     <div class="panel-body">
                      <form action="{{ route('report-order.store') }}" method="POST">
                        @csrf
                        <div class="col-sm-12 filters">
                          <div class="col-sm-2">
                            <div class="form-group">
                              <label>Customer:</label>
                              {!! Form::select('filter_customer',$all_customers,Request::get('filter_customer'), ['class'=>'form-control']) !!}
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-group">
                              <label>Date:</label>
                              <input type="text" name="filter_date" class="form-control" id="reservation" value="{{  Request::get('filter_date') }}"> 
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-group">
                              <label>Order Value:</label>
                              <input type="text" name="filter_value" class="form-control" value="{{  Request::get('filter_value') }}"> 
                            </div>
                          </div>
                          <div class="col-sm-2">
                          <div class="form-group">
                              <label>Order Status:</label>
                              {!! Form::select('filter_status',$order_status, Request::get('filter_status'),['class'=>'form-control']) !!}
                            </div>
                          </div>
                          <div class="col-sm-2">
                          <div class="form-group">
                               <label></label>
                               <br>
                               <button class="btn btn-success" type="submit">
                                  <i class="fa fa-filter"></i> Filter
                               </button>
                          </div>
                          </div>
                        </div>
                      </form>
                     </div>
                  </div>
                  <div class="clearfix"></div>
                  <table id="report-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Ordered Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Sales Rep</th>
                        <th>Order Total</th>
                        <th>Order Status</th>
                        <th>Delivery Status</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_orders as $orders)
                            <tr>
                              <td>{{ date('m-d-Y',strtotime($orders->created_at)) }}</td>
                              <td>
                                <a href="{{ route('orders.show',[$orders->id]) }}" target="_blank">
                                  {{ $orders->order_no }}
                                </a>
                              </td>
                              <td>{{ $orders->customer->name }}</td>
                              <td>{{ $orders->salesrep->emp_name }}</td>
                              <td>{{ $orders->exchange_total_amount }}</td>
                              <td>
                                <span class="badge" style="background: {{ $orders->statusName->color_codes }};color: #fff">
                                  {{  $orders->statusName->status_name  }}
                                </span>
                              <td>
                                @if (isset($orders->deliveryStatus['status_name']))
                                <span class="badge" style="background: {{ $orders->deliveryStatus['color_codes'] }};color: #fff">
                                {{ $orders->deliveryStatus['status_name'] }}
                               </span>
                               @else
                               <span class="badge" style="background: #5bc0de;color: #fff">
                               Not Delivered
                              </span>
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
  <style type="text/css">
    .filters .col-sm-2{
      float: left;
    }
  </style>

  @push('custom-scripts')
  <script type="text/javascript">
    $('.date-picker').datepicker({dateFormat: 'dd-mm-yy'});
    $('#report-table').dataTable({
      "lengthChange": false
    });

  </script>
  @endpush

@endsection