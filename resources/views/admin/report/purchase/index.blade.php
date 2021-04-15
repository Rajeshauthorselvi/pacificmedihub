@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Purchase Report</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Purchase Report</li>
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
                <h3 class="card-title">Purchase Report</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        {{-- <th><input type="checkbox" class="select-all"></th> --}}
                        <th>PO Date</th>
                        <th>PO Code</th>
                        <th>Vendor</th>
                        {{-- <th>View Quantities</th> --}}
                        <th>Grand Total</th>
                        {{-- <th>Paid</th> --}}
                        {{-- <th>Balance</th> --}}
                        <th>PO Status</th>
                        {{-- <th>Payment Status</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          {{-- <td><input type="checkbox" name="currency_id" value="{{ $order['purchase_id'] }}"></td> --}}
                          <td>{{ date('d-m-Y',strtotime($order['purchase_date'])) }}</td>
                          <td>
                            <a href="{{ route('purchase.show',[$order['purchase_id']]) }}" class="btn btn-link">
                              {{ $order['po_number'] }}
                            </a>
                          </td>
                          <td>{{ $order['vendor'] }}</td>
  {{--                         <td class="text-center">
                            <a href="javascript:void(0)" class="btn btn-primary show_products" purchase-id="{{ $order['purchase_id'] }}">
                              <i class="fa fa-eye"></i>
                            </a>
                          </td> --}}
                          <td class="total_amount">{{ $order['sgd_total_amount'] }}</td>
                          <?php 
                              $balance_amount=\App\Models\PaymentHistory::FindPendingBalance($order['purchase_id'],$order['sgd_total_amount'],1);
                            ?>
    {{--                       <td>{{ $balance_amount['paid_amount'] }}</td>
                          <td class="balance">
                            {{ number_format($balance_amount['balance_amount'],2,'.','') }}
                          </td> --}}
                          <td>
                            <span class="badge" style="background:{{ $order['color_code'] }};color: #fff ">{{ $order['order_status'] }}</span>
                          </td>
    {{--                       <td>
                            <?php $color_code=[1=>'#00a65a',2=>'#5bc0de',3=>'#f0ad4e']?>
                            <?php $payment_status=[0=>'',1=>'Paid',2=>'Partly Paid',3=>'Not Paid']; ?>
                            <span class="badge" style="background:{{ $color_code[$order['p_status']] }};color: #fff ">{{ $payment_status[$order['p_status']] }}</span>
                          </td> --}}
                          
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

  @push('custom-scripts')
    <script type="text/javascript">
      
    </script>
  @endpush
@endsection