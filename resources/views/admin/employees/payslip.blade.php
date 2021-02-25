@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Pay Slip</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('salary.list',$date)}}">Salary</a></li>
              <li class="breadcrumb-item active">Pay Slip</li>
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
          <a href="{{route('salary.list',$date)}}" class="back"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <a href="javascript:void(0);" rel="noopener" target="_blank" class="btn btn-primary btnPrint"> 
        <i class="fas fa-print"></i> Print
      </a>
      <div class="container-fluid toggle-tabs">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="print-area">
                      <div class="company-details">
                        <img class="logo-img" title="Logo Image" src="{{asset('theme/images/logo.png')}}">
                        <div class="address-box">
                          <div>{{$address->address_1}},&nbsp;{{$address->address_2}}</div>
                          <div>
                            {{$address->getCountry->name}},&nbsp;{{$address->getState->name}},&nbsp;
                            {{$address->getCity->name}}&nbsp;-&nbsp;{{$address->post_code}}.
                          </div>
                          <div>
                            Tel: {{$address->post_code}},&nbsp;&nbsp;&nbsp;Email: {{$address->company_email}}
                          </div>
                        </div>
                      </div>

                      
                        <table class="payslip table">
                          <thead>
                            <tr>
                              <th>EMPLOYEE PAYSLIP</th>
                              <th>{{$salary_month}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>Name of Employee</td><td>{{$employee->emp_name}}</td>
                            </tr>
                            <tr>
                              <td>Department of Employee</td><td>{{$employee->department->dept_name}}</td>
                            </tr>
                            <tr>
                              <td>Month of Payment</td><td>{{$salary_month}}</td>
                            </tr>
                            <tr class="break-bold"></tr>
                            <tr>
                              <th>Payments (A)</th><td></td>
                            </tr>
                            <tr>
                              <td>Basic Salary</td><td>{{ number_format($base_salary,2,'.','') }}</td>
                            </tr>
                            <tr>
                              <td>Commission</td><td>{{ number_format($commission,2,'.','') }}</td>
                            </tr>
                            <tr>
                              <td>Target Bonus</td><td>{{ number_format($target_commissions,2,'.','') }}</td>
                            </tr>
                            <tr>
                              <td></td><th class="total">{{ number_format($payment_total,2,'.','') }}</th>
                            </tr>
                            <tr class="break"></tr>
                            <tr>
                              <th>Deductables (B)</th><td></td>
                            </tr>
                            <tr>
                              <td>CPF</td><td>{{ number_format($cpf,2,'.','') }}</td>
                            </tr>
                            <tr>
                              <td>SDL</td><td>{{ number_format($sdl,2,'.','') }}</td>
                            </tr>
                            <tr>
                              <td></td><th class="total">{{ number_format($deduction_total,2,'.','') }}</th>
                            </tr>
                            <tr class="break"></tr>
                            <tr>
                              <th>Contribution</th><td></td>
                            </tr>
                            <tr>
                              <td>Employer CPF</td><td>{{ number_format($employer_cpf,2,'.','') }}</td>
                            </tr>
                            <tr>
                              <td></td><th class="total">{{ number_format($employer_cpf,2,'.','') }}</th>
                            </tr>
                            <tr class="break-bold"></tr>
                            <tr>
                              <th>Net Salary (A - B)</th><th>{{ number_format($new_salary,2,'.','') }}</th>
                            </tr>
                            <tr class="break-bold"></tr>
                          </tbody>
                        </table>
                      

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

  <style type="text/css" media="print">
    @page {
      size: auto;   /* auto is the initial value */
      margin: 0;  /* this affects the margin in the printer settings */
    }
  </style>


  <style type="text/css">
    
  </style>

  <script src="{{asset('theme/dist/js/printer/jquery.min.js')}}"></script>
  <script type="text/javascript">
    $('.btnPrint').click(function(){
      $('.print-area').css('margin',0);
      $('.back').css('display','none');
      window.print();
      $('.print-area').css('margin','auto');
      $('.back').css('display','block');
      return false;
    });    
  </script>

@endsection