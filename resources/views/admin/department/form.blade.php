@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Departments</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('departments.index')}}">Departments</a></li>
              @if ($type=="create")
                <li class="breadcrumb-item active">Create</li>
              @else
                <li class="breadcrumb-item active">Edit</li>
              @endif
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
          <a href="{{route('departments.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                @if ($type=="edit")
                  <h3 class="card-title">Edit Department</h3>
                  <?php $role_id = $dept->role_id; ?>
                @else     
                  <?php $role_id = ""; ?>
                  <h3 class="card-title">Add New Department</h3>
                @endif
              </div>
              <div class="card-body">
                @if ($type=="create")
                  {!! Form::open(['route'=>'departments.store','method'=>'POST','id'=>'form-validate']) !!}
                @else
                  {{ Form::model($dept,['method' => 'PATCH', 'route' =>['departments.update',$dept->id]]) }}
                @endif
                  <div class="form-group">
                    <label for="dept_id">Department ID</label>
                    {{ Form::text('dept_id',null,['class'=>'form-control','id'=>'dept_id']) }}
                    @if($errors->has('dept_id'))
                      <span class="text-danger">{{ $errors->first('dept_id') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="dept_name">Department Name</label>
                    <select class="form-control select2bs4" name="dept_name" id="dept_name">
                      <option value="">--Select--</option>
                      @foreach($roles as $role)
                        @if ($type=="create")
                          <option value="{{$role->name}}" role-id="{{$role->id}}" {{ (collect(old('dept_name'))->contains($role->name)) ? 'selected' : '' }}>{{$role->name}}</option>
                        @else
                          <option value="{{$role->name}}" role-id="{{$role->id}}" @if($role->id==$dept->role_id) selected="selected" @endif {{ (collect(old('dept_name'))->contains($role->name)) ? 'selected' : '' }}>{{$role->name}}</option>
                        @endif
                      @endforeach
                      <input type="hidden" name="role_id" id="roleId" value="{{old('role_id',$role_id)}}">
                    </select>
                    @if($errors->has('dept_name'))
                      <span class="text-danger">{{ $errors->first('dept_name') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    @if ($type=="create")
                      @php $checked='checked'; @endphp
                    @elseif($type=="edit")
                      @if (isset($dept->status) && $dept->status==1)
                        @php $checked='checked'; @endphp
                      @else  
                        @php $checked=''; @endphp
                      @endif
                    @endif
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="status" id="dept_status" {{$checked}}> 
                      <label for="dept_status">Published</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="{{route('departments.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" class="btn save-btn">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @push('custom-scripts')
    <script type="text/javascript">
      $(document).ready(function () {
        $('#dept_name').change(function(){
          var roleId = $('option:selected', this).attr('role-id');
          $('#roleId').val(roleId);
        });
      });
    </script>
  @endpush
@endsection