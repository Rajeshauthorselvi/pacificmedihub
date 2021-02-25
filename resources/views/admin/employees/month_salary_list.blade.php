
    <table id="monthSalary" class="table table-bordered table-striped">
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
            </span>
          <th>Total Salary 
            <span title="(Payments - Deductables)" class="ico-help">
              <i class="fa fa-question-circle"></i>
            </span>
          <th>Paid Amount</th>
          <th>Balance</th>
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
            <td>{{number_format($emp['paid_amount'],2,'.','')}}</td>
            <td class="balance">
              {{number_format($emp['balance_amount'],2,'.','')}}
            </td>
            <td>
              <?php $color_code=['Paid'=>'#00a65a','Not Paid'=>'#f0ad4e','Partly Paid'=>'#5bc0de']?>
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
 
<script type="text/javascript">
  $(function () {
    $('#monthSalary').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": true,
        "responsive": true,
      });
  });
</script>