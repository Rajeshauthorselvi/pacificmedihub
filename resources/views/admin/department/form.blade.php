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
                @else     
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
                    {{ Form::text('dept_name',null,['class'=>'form-control','id'=>'dept_name']) }}
                    @if($errors->has('dept_name'))
                      <span class="text-danger">{{ $errors->first('dept_name') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    @if (isset($dept->status) && $dept->status==1)
                      @php $checked='checked'; @endphp
                    @else  
                      @php $checked=''; @endphp
                    @endif
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="status" id="dept_status" {{$checked}}> 
                      <label for="dept_status">Status</label>
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

@endsection