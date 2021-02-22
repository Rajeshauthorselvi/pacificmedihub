@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Access Control</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Settings</a></li>
              <li class="breadcrumb-item active">Access Control</li>
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
{{--           <div class="col-md-12 action-controllers ">
            <div class="col-sm-6 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
            </div>
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('access-control.create')}}">
                <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
          </div> --}}
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All List</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>S.No</th>
                        <th>Role Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $s_no=1 ?>
                      @foreach ($roles as $role)
                        <tr>
                          <td>{{ $s_no }}</td>
                          <td>{{ $role->name }}</td>
                          <td class="text-center">
          <?php $check_role_exists=\App\Models\RoleAccessPermission::CheckRecordExists($role->id); ?>
                                @if ($check_role_exists)
                                  <a href="{{route('access-control.edit',$role->id)}}" class="btn btn-primary">
                                      <i class="far fa-edit"></i>&nbsp;&nbsp;Edit
                                  </a>
                                @else
                                  <a href="{{route('access-control.create',['role_id'=>$role->id])}}" class="btn btn-primary">
                                      <i class="far fa-edit"></i>&nbsp;&nbsp;Edit
                                  </a>
                                @endif
                          </td>
                        </tr>
                        <?php $s_no++; ?>
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
        var checkedNum = $('input[name="role-ids"]:checked').length;
        if (checkedNum==0) {
          alert('Please select roles');
        }
        else{
          if (confirm('Are you sure want to delete?')) {
            $('input[name="role-ids"]:checked').each(function () {
              var current_val=$(this).val();
              $.ajax({
                url: "{{ url('admin/access-control/') }}/"+current_val,
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