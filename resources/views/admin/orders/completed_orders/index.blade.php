@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Delivery Assign</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Delivery Assign</li>
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
                <h3 class="card-title">All Delivery Assign</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered" >
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
                      <?php 
                      $check_quantity=\App\Models\Orders::CheckQuantity($order->id);

                      $disabled_stock_notify="";
                        if(isset($order->deliveryPerson) ){
                          $disabled_stock_notify="pointer-events:none;opacity:0.5";
                        }
                        
                        if (isset($check_quantity[0]) && $check_quantity[0]=="yes") {
                            $class_bg="background:#ffedb9 !important";
                        }
                        else{
                          $class_bg="";
                        }

                      ?>
                        <tr style="{{ $class_bg }}">
                          <td>{{ date('m/d/Y',strtotime($order->created_at)) }}</td>
                          <td>
                            {{ isset($order->delivered_at)?date('m/d/Y g:i A',strtotime($order->delivered_at)):'-' }}
                          </td>
                          <td><a href="{{route('delivery-assign.show',$order->id)}}">{{ $order->order_no }}</a></td>
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
                                <a href="{{route('delivery-assign.show',$order->id)}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                @if ($order->delivery_status==16)
                                  @php
                                    $disable_status="pointer-events:none;opacity:0.5";
                                  @endphp
                                @else
                                  @php
                                    $disable_status="";
                                  @endphp
                                @endif
                                <a href="{{route('delivery-assign.edit',$order->id)}}" style="{{ $disable_status }}">
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

                                <a href="{{ route('verify-stock.show',[$order->id]) }}">
                                  <li class="dropdown-item"  style="{{ $disabled_stock_notify }}">
                                  <i class="fa fa-check-circle"></i>&nbsp;&nbsp;Verify Stock
                                </li>
                                </a>
                                <a href="{{ route('verify-stock.index',['order_id'=>$order->id]) }}" target="_blank" style="{{$disabled_stock_notify}}">
                                  <li class="dropdown-item" >
                                  <i class="fa fa-user"></i>&nbsp;&nbsp;Notify Admin
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