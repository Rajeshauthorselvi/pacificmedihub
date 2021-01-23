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
          <div class="col-md-12 action-controllers ">
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
          </div>
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
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Role Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($roles as $role)
                        <tr>
                          <td><input type="checkbox" name="role-ids" value="{{ $role->id }}"></td>
                          <td>{{ $role->name }}</td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="{{route('access-control.show',$role->id)}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                <a href="{{route('access-control.edit',$role->id)}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                <a href="#"><li class="dropdown-item">
                                  <form method="POST" action="{{ route('access-control.destroy',$role->id) }}">@csrf 
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                  </form>
                                </li></a>
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