@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Product Units</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Products</a></li>
              <li class="breadcrumb-item active">Product Units</li>
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
          <a href="{{route('product-units.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Unite</h3>
              </div>
              <div class="card-body">
                <form action="{{route('product-units.update',$unit->id)}}" method="post">
                  @csrf 
                  <input name="_method" type="hidden" value="PATCH">
                  <div class="form-group">
                    <label for="unitName">Unit Name</label>
                    <input type="text" class="form-control" name="unit_name" id="unitName" value="{{old('unit_name',$unit->unit_name)}}">
                    @if($errors->has('unit_name'))
                      <span class="text-danger">{{ $errors->first('unit_name') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="unitCode">Unit Code</label>
                    <input type="text" class="form-control" name="unit_code" id="unitCode" value="{{old('unit_code',$unit->unit_code)}}">
                    @if($errors->has('unit_code'))
                      <span class="text-danger">{{ $errors->first('unit_code') }}</span>
                    @endif
                  </div>
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="unit_published" id="Published" @if((old('unit_published')=='on')||($unit->published==1)) checked @endif>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="{{route('product-units.index')}}" class="btn reset-btn">Cancel</a>
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