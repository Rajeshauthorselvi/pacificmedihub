@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Employees</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Employees</li>
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
          <div class="col-md-12 action-controllers ">
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('employee','delete'))
            <div class="col-sm-6 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
            </div>
            @endif
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('employee','create'))
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('employees.create')}}">
                <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
            @endif
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Employees</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                      	<th>Code</th>
                      	<th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Contact No</th>
                      	<th>Published</th>
                      	<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    	@foreach($employees as $emp)
                        <tr>
                          <td><input type="checkbox" value="{{ $emp->id }}" name="emp-ids"></td>
                          <td><a href="{{route('employees.show',$emp->id)}}">{{$emp->emp_id}}</a></td>
                          <td>{{$emp->emp_name}}</td>
                          <td>{{$emp->department->dept_name}}</td>
                          <td>{{ $emp->email }}</td>
                          <td>{{ $emp->emp_mobile_no }}</td>
                          <?php
                            if($emp->status==1){$status = "fa-check";}
                            else{$status = "fa-ban";}
                          ?>
                          <td><i class="fas {{$status}}"></i></td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="{{route('employees.show',$emp->id)}}"><li class="dropdown-item">
                                  <i class="fas fa-eye"></i>&nbsp;&nbsp;View</li>
                                </a>
                                @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('employee','update')) 
                                <a href="{{route('employees.edit',$emp->id)}}"><li class="dropdown-item">
                                  <i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li>
                                </a>
                                @endif
                                @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('employee','delete')) 
                                <a href="#"><li class="dropdown-item">
                                  <form method="POST" action="{{ route('employees.destroy',$emp->id) }}">@csrf 
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                  </form></li>
                                </a>
                                @endif
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

  @push('custom-scripts')
  <script type="text/javascript">
  	 $('.select-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('input:checkbox').prop('checked',true);
        }
        else{
          $('input:checkbox').prop('checked',false);
        }
    });


      $('.delete-all').click(function(event) {
        var checkedNum = $('input[name="emp-ids"]:checked').length;
        if (checkedNum==0) {
          alert('Please select employees');
        }
        else{
          if (confirm('Are you sure want to delete?')) {
            $('input[name="emp-ids"]:checked').each(function () {
              var current_val=$(this).val();
              $.ajax({
                url: "{{ url('admin/employees/') }}/"+current_val,
                type: 'DELETE',
                data:{
                 "_token": $("meta[name='csrf-token']").attr("content")
                }
              })
              .done(function() {
                 location.reload(); 
              })
              .fail(function() {
                console.log("Ajax Error :-");
              });
            });
          }

          
        }
        
      });
  </script>
  @endpush
@endsection