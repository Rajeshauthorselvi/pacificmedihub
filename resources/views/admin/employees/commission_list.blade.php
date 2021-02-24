@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Salary</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('salary.list',$date)}}">Salary</a></li>
              <li class="breadcrumb-item active">Salary Details</li>
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
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('salary.list',$date)}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid toggle-tabs">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Salary Details</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="details-box">
                      <div class="employee-details">
                        <div class="title"><b>Employee Name</b></div>:<div class="details">{{$employee->emp_name}}</div>
                      </div>
                      <div class="employee-details">
                        <div class="title"><b>Department</b></div>:<div class="details">{{$employee->department->dept_name}}
                        </div>
                      </div>
                      <?php $com_date = date_create('01-'.$date); ?>
                      <div class="employee-details">
                        <div class="title"><b>Month of Commission</b></div>:<div class="details">{{date_format($com_date,"M Y")}}</div>
                      </div>
                      
                    </div>
                    <div class="table-responsive">
                      <table class="commission table">
                        <thead class="heading-top">
                          <tr>
                            <th>#</th>
                            <th>Order Code</th>
                            <th>Total Amount</th>
                            <th>Product Commission</th>
                            <th>Target Commission</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($order_data as $order)
                            <tr>
                              <td>{{$count++}}</td>
                              <td class="text-right">
                                <a href="{{ route('orders.show',$order['order_id']) }}" target="blank">
                                  {{$order['order_code']}}
                                </a>
                              </td>
                              <td class="text-right amount">{{$order['total_amount']}}</td>
                              <td class="prod_com text-right">{{$order['product_commission']}}</td>
                              <td class="tar_com text-right">{{$order['target_commission']}}</td>
                            </tr>
                          @endforeach
                          <tr class="total">
                            <th class="text-right" colspan="2">Total</th>
                            <th class="text-right"><span class="total_amount"></span></th>
                            <th class="text-right"><span class="total_prod_com"></span></th>
                            <th class="text-right"><span class="total_tar_com"></span></th>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="overall text-center">
                      <h5><storang class="overall_commission"></storang></h5>
                    </div>

                  </div>
                </div>
             
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  @push('custom-scripts')
   <script type="text/javascript">
     $(document).ready(function() { 

        var amount = 0;
        $('.amount').each(function() {
          amount += parseFloat($(this).text());
        });
        $('.total_amount').text(amount.toFixed(2));

        var prod_com = 0;
        $('.prod_com').each(function() {
          prod_com += parseFloat($(this).text());
        });
        $('.total_prod_com').text(prod_com.toFixed(2));
        
        var tar_com = 0;
        $('.tar_com').each(function() {
          tar_com += parseFloat($(this).text());
        });
        $('.total_tar_com').text(tar_com.toFixed(2));
        
        var overall = 0;
        var product_commission = $('.total_prod_com').text();        
        var target_commission  = $('.total_tar_com').text();
        overall = parseFloat(product_commission)+parseFloat(target_commission);
        $('.overall_commission').text('Total Commission ='+overall.toFixed(2));
     });
   </script>
  @endpush
@endsection