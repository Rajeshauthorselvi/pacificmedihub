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
                      <input type="text" name="date" class="form-control datetimepicker-input" data-target="#dateFilter" value="{{ $date }}"/>
                      <div class="input-group-append" data-target="#dateFilter" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>
                  <div class="month-list" style="display:none"></div>
                  <div class="current-list">
                    <table id="example2" class="table table-bordered table-striped current-list">
                      <thead>
                        <tr>
                        	<th>Name</th>
                          <th>Department</th>
                          <th>
                            Payments
                            <span title="(Basic Salary + Commission + Target Bonus)" class="ico-help">
                              <i class="fa fa-question-circle"></i>
                            </span>
                          </th>
                          <th>
                            Deductables
                            <span title="(CPF + SDL)" class="ico-help">
                              <i class="fa fa-question-circle"></i>
                            </span></th>
                          <th>Total Salary 
                            <span title="(Payments - Deductables)" class="ico-help">
                              <i class="fa fa-question-circle"></i>
                            </span></th>
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
                            <td>{{$emp['department']}}</td>
                            <td>{{number_format($emp['payment'],2,'.','')}}</td>
                            <td>{{number_format($emp['deduction'],2,'.','')}}</td>
                            <td>{{number_format($emp['total_salary'],2,'.','')}}</td>
                            <td>{{$emp['paid_date']}}</td>
                            <td>
                              <?php $color_code=['Paid'=>'#00a65a','Not Paid'=>'#f0ad4e']?>
                              <span class="badge" style="background:{{ $color_code[$emp['status']] }};color: #fff ">{{ $emp['status'] }}</span>
                            </td>
                            <td>
                              <div class="input-group-prepend">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu">
                                  <a href="{{ route('salary.view',['emp_id'=>base64_encode($emp["id"]),'page'=>'view','date'=>$date]) }}"><li class="dropdown-item">
                                    <i class="fas fa-eye"></i>&nbsp;&nbsp;View</li>
                                  </a>
                                  @if($emp['action']=='Payslip')
                                    <a href="{{ route('pay.slip',['emp_id'=>base64_encode($emp["id"]),'page'=>'payslip','date'=>$date]) }}"><li class="dropdown-item">
                                      <i class="fas fa-file-invoice"></i>&nbsp;&nbsp;Payslip</li></a>
                                  @else
                                  <a href="javascript:void(0);">
                                    <li class="dropdown-item paynow" employee-id="{{$emp['id']}}" data-toggle="modal" data-target="#payment-form"><i class="fas fa-credit-card"></i>&nbsp;&nbsp;Pay Now</li>
                                  </a>
                                  @endif
                                  <a href="{{ route('emp.commission.list',['emp_id'=>base64_encode($emp["id"]),'date'=>$date]) }}"><li class="dropdown-item">
                                    <i class="fas fa-file-invoice-dollar"></i>&nbsp;&nbsp;List Commission</li>
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
      </div>
    </section>
  </div>

  <div class="modal fade" id="payment-form">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Payment Form</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div id="form-block"></div>
            </div>
            
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->


  <style type="text/css">
    .date-filter {position: absolute;left: 550px;width: 150px;z-index: 999;}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">
      $(function() {

        $('#dateFilter').datetimepicker({
          language: 'en',
          format: 'MM-yy'
        });

        $("#dateFilter").on("change.datetimepicker", (date) => {
          var selected_date = $('.datetimepicker-input').val();
          $.ajax({
            url:"{{ url('admin/salary-list') }}/"+selected_date,
            type:"GET",
            
            success: function (response) { 
              //location.reload();
              $('.current-list').css('display','none');
              $('.month-list').css('display','block');
              $('.datetimepicker-input').val(selected_date);
              $('.month-list').html(response);
            }
          });
        })


      });

      $('body').on('click','.paynow',function(){
        var selectedDate = $('.datetimepicker-input').val();
        var empId = $(this).attr('employee-id');
        $.ajax({
          url:"{{ url('admin/payment_form') }}",
          type:"GET",
          data:{emp_id:empId,date:selectedDate},
          success: function (response) { 
            //console.log(data);
            $('#form-block').html(response);
          }
        });
      });

    </script>
  @endpush

@endsection