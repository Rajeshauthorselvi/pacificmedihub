@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Brands</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Products</a></li>
              <li class="breadcrumb-item active">Brands</li>
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
            <a class="btn add-new" href="{{route('brands.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
            </a>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Brands</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th><th>Image</th><th>Published</th><th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($brands as $brand)
                        <tr>
                          <td>{{$brand->name}}</td>
                          <?php 
                            if(!empty($brand->image)){$img_url="theme/images/brands/".$brand->image;} 
                            else{$img_url="theme/images/no_image.jpg";}
                            if($brand->published==1){$published = "fa-check";}
                            else{$published = "fa-ban";}
                          ?>
                          <td><img class="brand-img" style="width:25%;height:auto;" src="{{ asset($img_url)}}"></td>
                          <td><i class="fas {{$published}}"></i></td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu" style="">
                                <a href="#"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                <a href="{{route('brands.edit',$brand->id)}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                <a href="#"><li class="dropdown-item"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;
                                  <form method="POST" action="{{ route('brands.destroy',$brand->id) }}">@csrf 
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
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