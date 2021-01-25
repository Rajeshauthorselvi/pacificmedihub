@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Wastage</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Wastage</li>
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
          <div class="col-md-12 action-controllers">
            <a class="btn add-new" href="{{route('wastage.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
            </a>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Wastage</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th>Date</th>
                        <th>Reference Number</th>
                        <th>Created By</th>
                        <th>Note</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($wastages as $wastage)
                          <tr>
                            <td>{{ $wastage['date'] }}</td>
                            <td>{{ $wastage['reference_number'] }}</td>
                            <td>{!! $wastage['created_by'] !!}</td>
                            <td>{!! $wastage['notes'] !!}</td>
                            <td>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu">
                                    {{-- <a href="{{route('wastage.edit',$wastage['id'])}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a> --}}
                                    <a href="#"><li class="dropdown-item">
                                      <form method="POST" action="{{ route('wastage.destroy',$wastage['id']) }}">@csrf 
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
@endsection