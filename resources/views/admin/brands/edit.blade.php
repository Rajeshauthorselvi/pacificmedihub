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
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item"><a href="{{route('brands.index')}}">Brands</a></li>
              <li class="breadcrumb-item active">Edit</li>
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
          <a href="{{route('brands.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Brand</h3>
              </div>
              <div class="card-body">
                <form action="{{route('brands.update',$brand->id)}}" method="post" enctype="multipart/form-data">
                  @csrf 
                  <input name="_method" type="hidden" value="PATCH">
                  <div class="form-group">
                    <label for="brandName">Brand Name</label>
                    <input type="text" class="form-control" name="brand_name" id="brandName" value="{{old('brand_name',$brand->name)}}">
                    @if($errors->has('brand_name'))
                      <span class="text-danger">{{ $errors->first('brand_name') }}</span>
                    @endif
                  </div>
                  <div class="form-group" style="display:flex;">
                    <div class="col-sm-6" style="padding-left:0">
                      <label for="manfName">Manufacturing Name *</label>
                      <input type="text" class="form-control" name="manf_name" id="manfName" value="{{old('manf_name',$brand->manf_name)}}">
                      @if($errors->has('manf_name'))
                        <span class="text-danger">{{ $errors->first('manf_name') }}</span>
                      @endif
                    </div>
                    <div class="col-sm-6" style="padding-left:0">
                      <label for="manfName">Manufacturing Country</label>
                      {!! Form::select('country_id',$countries,$brand->manf_country_id,['class'=>'form-control select2bs4 required']) !!}
                    </div>
                  </div>
                 <?php 
                  if(!empty($brand->image)){$image = "theme/images/brands/".$brand->image;}
                  else {$image = "theme/images/no_image.jpg";}
                  ?>
                  <div class="form-group">
                    <label for="brandImage">Image</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="brand_image" id="brandImage" accept="image/*" value="{{old('brand_image')}}">
                      <label class="custom-file-label" for="brandImage">Choose file</label>
                    </div><br><br>
                    <img class="img-brand" style="width:100px;height:100px;" src="{{asset($image)}}">
                  </div>
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="brand_published" id="Published" @if((old('brand_published')=='on')||($brand->published==1)) checked @endif>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="{{route('brands.index')}}" class="btn reset-btn">Cancel</a>
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