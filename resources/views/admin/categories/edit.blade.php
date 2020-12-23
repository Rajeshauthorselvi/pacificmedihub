@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Products</a></li>
              <li class="breadcrumb-item active">Categories</li>
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
          <a href="{{route('categories.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Category</h3>
              </div>
              <div class="card-body">
                <form action="{{route('categories.update',$category->id)}}" method="post" enctype="multipart/form-data">
                  @csrf 
                  <input name="_method" type="hidden" value="PATCH">
                  <div class="form-group">
                    <label for="categoryName">Category Name *</label>
                    <input type="text" class="form-control" name="category_name" id="categoryName" value="{{old('category_name',$category->name)}}">
                    @if($errors->has('category_name'))
                      <span class="text-danger">{{ $errors->first('category_name') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="3" name="category_description" id="description">{{old('category_description',$category->description)}}</textarea>
                    @if($errors->has('category_description'))
                      <span class="text-danger">{{ $errors->first('category_description') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label>Parent Category</label>
                    <select class="form-control select2bs4" name="parent_category">
                      <option selected="selected" value="">[None]</option>
                      @foreach($category_list as $cat)
                        <option @if($category->parent_category_id==$cat->id) selected="selected" @endif  value="{{$cat->id}}" {{ (collect(old('parent_category'))->contains($cat->id)) ? 'selected':'' }}>{{$cat->name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <?php 
                    if(!empty($category->image)){$image = "theme/images/categories/".$category->image;}
                    else {$image = "theme/images/no_image.jpg";}
                  ?>
                  <div class="form-group">
                    <label for="categoryImage">Image</label>
                    <input type="file" name="category_image" id="categoryImage" accept="image/*" onchange="preview_image(event)" style="display:none;" value="{{$category->image}}">
                    <img title="Click to Change" class="img-category" id="output_image" onclick="$('#categoryImage').trigger('click'); return true;" style="width:100px;height:100px;cursor:pointer;" src="{{asset($image)}}">
                    @if($errors->has('category_image'))
                      <span class="text-danger">{{ $errors->first('category_image') }}</span>
                    @endif
                  </div>
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="category_published" id="Published" @if((old('category_published')=='on')||($category->published==1)) checked @endif>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="category_homepage" id="homePage" @if((old('category_homepage')=='on')||($category->show_home==1)) checked @endif>
                      <label for="homePage">Show on Home Page</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="displayOrder">Display Order</label>
                    <input type="number" class="form-control" name="display_order" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" id="displayOrder" value="{{old('display_order',$category->display_order)}}">
                  </div>
                  <div class="form-group">
                    <label for="searchEngine">Search Engine Friendly Page Name</label>
                    <input type="text" class="form-control" name="search_engine" id="searchEngine" value="{{old('search_engine',$category->search_engine_name)}}">
                  </div>
                  <div class="form-group">
                    <label for="metaTile">Meta Title</label>
                    <input type="text" class="form-control" name="meta_title" id="metaTile" value="{{old('meta_title',$category->meta_title)}}">
                  </div>
                  <div class="form-group">
                    <label for="metaKeyword">Meta Keywords</label>
                    <textarea class="form-control" rows="3" name="meta_keyword" id="metaKeyword">{{old('meta_keyword',$category->meta_keyword)}}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="metaDescription">Meta Description</label>
                    <textarea class="form-control" rows="3" name="meta_description" id="metaDescription">{{old('meta_description',$category->meta_description)}}</textarea>
                  </div>
                  <div class="form-group">
                    <a href="{{route('categories.index')}}" class="btn reset-btn">Cancel</a>
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