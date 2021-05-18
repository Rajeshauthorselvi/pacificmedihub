@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List Pages</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a  href="javascript:void(0);">Static Pages</a></li>
              <li class="breadcrumb-item active">List Pages</li>
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
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('rfq','create'))
              <div class="col-sm-6 text-right pull-right">
                <a class="btn add-new" href="{{ route('static-page.create') }}">
                  <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
                </a>
              </div>
            @endif
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Pages</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Page Name</th>
                        <th>Show Home</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Header Block</td><td><i class="fas fa-check"></i></td>
                        <td><a href="{{ route('static-page.create','header') }}" class="btn btn-primary">
                          <i class="far fa-edit"></i>&nbsp;Edit</a>
                        </td>
                      </tr>
                      <tr>
                        <td>Home Page</td><td><i class="fas fa-check"></i></td>
                        <td><a href="{{ route('static-page.create','home') }}" class="btn btn-primary">
                          <i class="far fa-edit"></i>&nbsp;Edit</a>
                        </td>
                      </tr>
                      @foreach($static_pages as $page)
                        <tr>
                          <td>{{ $page->page_title }}</td>
                          <?php 
                            if($page->published==1){$published = "fa-check";}
                            else{$published = "fa-ban";}
                          ?>
                          <td><i class="fas {{$published}}"></i></td>
                          <td>
                            <a href="{{ route('static-page.edit',$page->id) }}" class="btn btn-primary">
                            <i class="far fa-edit"></i>&nbsp;Edit</a>&nbsp;&nbsp;
                            
                            <a href="#">
                              <form method="POST" action="{{ route('static-page.destroy',$page->id) }}">@csrf 
                                <input name="_method" type="hidden" value="DELETE">
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to delete this page?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                              </form>
                            </a>
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

  <style type="text/css">
    #example2 td a {
      display: inline-block;
    }
  </style>
@endsection