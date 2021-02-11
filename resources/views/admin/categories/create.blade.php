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
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item"><a href="{{route('categories.index')}}">Categories</a></li>
              <li class="breadcrumb-item active">Create</li>
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
                <h3 class="card-title">Add New Category</h3>
              </div>
              <div class="card-body">
                <form action="{{route('categories.store')}}" method="post" enctype="multipart/form-data">
                  @csrf 
                  <div class="form-group">
                    <label for="categoryName">Category Name *</label>
                    <input type="text" class="form-control" name="category_name" id="categoryName" value="{{old('category_name')}}">
                    @if($errors->has('category_name'))
                      <span class="text-danger">{{ $errors->first('category_name') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="3" name="category_description" id="description">{{old('category_description')}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Parent Category</label>
                    <select class="form-control select2bs4" name="parent_category">
                      <option selected="selected" value="">[None]</option>
                      @foreach($categories as $category)
                        <?php
                          $category_name = $category->name;
                          if($category->parent_category_id!=NULL){
                            $category_name = $category->parent->name.'  >>  '.$category->name;
                          }
                        ?>
                        <option value="{{$category->id}}" {{ (collect(old('parent_category'))->contains($category->id)) ? 'selected':'' }}>{{$category_name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="categoryImage">Image</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="category_image" id="categoryImage" accept="image/*" value="{{old('category_image')}}">
                      <label class="custom-file-label" for="categoryImage">Choose file</label>
                    </div>
                  </div>
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="category_published" id="Published" checked>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="category_homepage" id="homePage" @if(old('category_homepage')=='on') checked @endif>
                      <label for="homePage">Show on Home Page</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="displayOrder">Display Order</label>
                    <input type="number" class="form-control" name="display_order" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" id="displayOrder" value="{{old('display_order')}}">
                  </div>
                  <div class="form-group">
                    <label for="searchEngine">Search Engine Friendly Page Name *</label>
                    <input type="text" class="form-control" name="search_engine" id="searchEngine" value="{{old('search_engine')}}">
                    @if($errors->has('search_engine'))
                      <span class="text-danger">{{ $errors->first('search_engine') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="metaTile">Meta Title</label>
                    <input type="text" class="form-control" name="meta_title" id="metaTile" value="{{old('meta_title')}}">
                  </div>
                  <div class="form-group">
                    <label for="metaKeyword">Meta Keywords</label>
                    <textarea class="form-control" rows="3" name="meta_keyword" id="metaKeyword">{{old('meta_keyword')}}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="metaDescription">Meta Description</label>
                    <textarea class="form-control" rows="3" name="meta_description" id="metaDescription">{{old('meta_description')}}</textarea>
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

@endsection