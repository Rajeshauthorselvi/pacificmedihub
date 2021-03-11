@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List Orders</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">List Orders</li>
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
                <h3 class="card-title">List Orders</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Ordered Date</th>
                        <th>Delivered Date</th>
                        <th>Order Code</th>
                        <th>Customer</th>
                        <th>Delivery Person</th>
                        <th>Order Status</th>
                        <th>Delivery Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($completed_orders as $order)
                        <tr>
                          <td>{{ date('m/d/Y',strtotime($order->created_at)) }}</td>
                          <td>
                            {{ isset($order->delivered_at)?date('d M Y g:i A',strtotime($order->delivered_at)):'-' }}
                          </td>
                          <td><a href="{{route('completed-orders.show',$order->id)}}">{{ $order->order_no }}</a></td>
                          <td>{{ $order->customer->first_name }}</td>
                          <td>
                            @if(isset($order->deliveryPerson))
                              {{ $order->deliveryPerson->emp_name }}
                            @else
                              -
                            @endif
                          </td>
                          <td>
                            <span class="badge" style="background: {{ $order->statusName->color_codes }};color: #fff">
                            {{  $order->statusName->status_name  }}
                            </span>
                          </td>
                          <td>
                            @if (isset($order->deliveryStatus))
                              <span class="badge" style="background: {{ $order->deliveryStatus->color_codes }};color: #fff">
                              {{  $order->deliveryStatus->status_name  }}
                              </span>
                            @else
                              -
                            @endif
                          </td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="{{route('completed-orders.show',$order->id)}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;Show</li></a>
                                @if ($order->delivery_status==16)
                                  @php
                                    $disable_status="pointer-events:none;opacity:0.5";
                                  @endphp
                                @else
                                  @php
                                    $disable_status="";
                                  @endphp
                                @endif
                                <a href="{{route('completed-orders.edit',$order->id)}}" style="{{ $disable_status }}">
                                  <li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li>
                                </a>
                                <a href="{{ url('admin/cop_pdf/'.$order->id) }}"><li class="dropdown-item">
                                  <i class="fa fa-file-pdf"></i>&nbsp;&nbsp;Download as PDF
                                 </li></a>
                                <a href="{{ url('admin/cop_print/'.$order->id) }}" target="_blank">
                                  <li class="dropdown-item" >
                                  <i class="fa fa-print"></i>&nbsp;&nbsp;Print
                                </li>
                                </a>
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
    .form-group{display:flex;}
    .disabled{pointer-events: none;opacity: 0.5;}
  </style>

@endsection