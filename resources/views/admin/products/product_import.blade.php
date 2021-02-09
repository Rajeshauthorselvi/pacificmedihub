@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Import Products</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Import Products</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
 @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{!!  $error  !!}</li>
            @endforeach
        </ul>
    </div>
@endif
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Products By Excel</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  {!! Form::open(['route'=>'post.product.import','files'=>true,'method'=>'POST']) !!}
                   <div class="alert alert-info"> Note: Please start product id from <b>{{ $last_product_id+1 }}</b></div>
                    <div class="form-group">
                      <label>Upload File</label>
                      {!! Form::file('product_sheet',['class'=>'form-control']) !!}
                      <span class="text-danger">{{ $errors->first('product_sheet') }}</span>
                    </div>
                  <div class="form-group">
                    <a href="{{route('products.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" id="submit-btn" class="btn save-btn">Save</button>
                  </div>
                    {!! Form::close() !!}
                </div>
              </div>
            </div>
            </div>
        </div>
      </div>
    </section>
  </div>

@endsection