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
                 <?php 
                  if(!empty($brand->image)){$image = "theme/images/brands/".$brand->image;}
                  else {$image = "theme/images/no_image.jpg";}
                  ?>
                  <div class="form-group">
                    <label for="brandImage">Image</label>
                    <input type="file" name="brand_image" id="brandImage" accept="image/*" onchange="preview_image(event)" style="display:none;" value="{{$brand->image}}">
                    <img title="Click to Change" class="img-brand" id="output_image"  onclick="$('#brandImage').trigger('click'); return true;" style="width:100px;height:100px;cursor:pointer;" src="{{asset($image)}}">
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
  <script type='text/javascript'>
    function preview_image(event) 
    {
      var reader = new FileReader();
      reader.onload = function()
      {
        var output = document.getElementById('output_image');
        output.src = reader.result;
      }
      reader.readAsDataURL(event.target.files[0]);
    }
  </script>
@endsection