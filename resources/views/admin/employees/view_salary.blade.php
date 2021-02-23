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
              <li class="breadcrumb-item"><a href="{{route('salary.list')}}">Salary</a></li>
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
          <a href="{{route('salary.list')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
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
                        <div class="title"><b>Department</b></div>:<div class="details">{{$employee->department->dept_name}}</div>
                      </div>
                      <div class="employee-details">
                        <div class="title"><b>Date of Payment</b></div>:<div class="details">{{$paid_date}}</div>
                      </div>
                    </div>
                    <div class="profile-image">
                       {{-- <?php 
                          if(!empty($employee->emp_image)){$image = "theme/images/employees/".$employee->emp_image;}
                          else {$image = "theme/images/no_image.jpg";}
                        ?>
                      <img class="img-employee" title="Employee Profile Image" src="{{asset($image)}}"> --}}
                    </div>
                    <div class="table-responsive">
                      <table class="salary table">
                        <thead class="heading-top">
                          <tr>
                            <th>PayMents</th>
                            <th>Deductables</th>
                            <th>Contribution</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <div class="block">
                                <div class="title">Basic Salary</div> 
                                <div class="value">{{ number_format($base_salary,2,'.','') }}</div>
                              </div>

                              <div class="block">
                                <div class="title">Commission</div>   
                                <div class="value">{{ number_format($commission,2,'.','') }}</div>
                              </div>

                              <div class="block">
                                <div class="title">Target Bonus</div> 
                                <div class="value">{{ number_format($target_commissions,2,'.','') }}</div>
                              </div>

                            </td>
                            <td>
                              <div class="block">
                                <div class="title">CPF </div>
                                <div class="value">{{ number_format($cpf,2,'.','') }}</div>
                              </div>
                              <div class="block">
                                <div class="title">SDL </div>
                                <div class="value">{{ number_format($sdl,2,'.','') }}</div>
                              </div>
                            </td>
                            <td>
                              <div class="block">
                                <div class="title">Employer CPF </div>
                                <div class="value">{{ number_format($employer_cpf,2,'.','') }}</div>
                              </div>
                            </td>
                          </tr>
                          <tr class="all-totals">
                            <td>
                              <div class="block">
                                <div class="title"><b>Total (A) </b></div>
                                <div class="value"><b>{{ number_format($payment_total,2,'.','') }}</b></div>
                              </div>
                            </td>
                            <td>
                              <div class="block">
                                <div class="title"><b>Total (B) </b></div>
                                <div class="value"><b>{{ number_format($deduction_total,2,'.','') }}</b></div>
                              </div>
                            </td>
                            <td>
                              <div class="block">
                                <div class="title"><b>Total</b></div> 
                                <div class="value"><b>{{ number_format($employer_cpf,2,'.','') }}</b></div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <div class="net-salary">
                        <div class="title"><b>Net Salary (A - B)</b></div>
                        <div class="value"><b>{{ number_format($new_salary,2,'.','') }}</b></div>
                      </div>
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
@endsection