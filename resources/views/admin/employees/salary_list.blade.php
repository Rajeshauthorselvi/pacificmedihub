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
                      <input type="text" name="date" class="form-control datetimepicker-input" data-target="#dateFilter" value="{{old('date')}}"/>
                      <div class="input-group-append" data-target="#dateFilter" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th>Name</th>
                        <th colspan="4">Payments</th>
                        <th colspan="3">Deductables</th>
                        <th colspan="2">Employer Contribution</th>
                        <th>Total Salary</th>
                        <th>Paid Date</th>
                      	<th>Status</th>
                      	<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                          <td></td>
                          <td>Basic Salary</td>
                          <td>Commission</td>
                          <td>Bonus</td>
                          <td>Claims/Others</td>

                          <td>CPF</td>
                          <td>SDL</td>
                          <td>Others</td>

                          <td>Employer CPF</td>
                          <td>Others</td>
                          
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>

                        </tr>
                    	@foreach($employee_salary as $emp)
                        <tr>
                          <input type="hidden" name="emp_id" value="{{$emp['id']}}">
                          <td>{{$emp['name']}}</td>

                          <td>{{$emp['basic_salary']}}</td>
                          <td></td>
                          <td></td>
                          <td></td>

                          <td>{{$emp['self_cpf']}}</td>
                          <td>{{$emp['sdl']}}</td>
                          <td></td>

                          <td>{{$emp['emp_cpf']}}</td>
                          <td></td>
                          
                          
                          <td>{{$emp['total_salary']}}</td>
                          <td>{{$emp['paid_date']}}</td>
                          <td>
                            <span style="background: @if($emp['status']=='Paid') #3287c7 @else #fb9a19 @endif ;color:#fff">
                              {{$emp['status']}}
                            </span>
                          </td>
                          <td>
                            @if($emp['action']=='Payslip')
                              <a href="#" class="btn btn-info">Payslip</a>
                            @else
                              <button type="button" class="btn btn-success paynow" employee-id="{{$emp['id']}}" data-toggle="modal" data-target="#payment-form">Pay Now</button>
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
      //Date range picker
      $(function() {
        $('#dateFilter').datetimepicker({
            format: 'MM/yy'
        });
      });

      $('.paynow').click(function(){
        var empId = $(this).attr('employee-id');
        $.ajax({
          url:"{{ url('admin/payment_form') }}?emp_id="+empId,
          type:"GET",
          success: function (data) { 
            //console.log(data);
            $('#form-block').html(data);
          }
        });
      });

    </script>
  @endpush

@endsection