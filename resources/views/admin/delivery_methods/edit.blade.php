@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Delivery Methods</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('delivery-methods.index')}}">Delivery Methods</a></li>
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
          <a href="{{route('delivery-methods.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Delivery Method</h3>
              </div>
              <div class="card-body">
                  {{ Form::model($delivery_method,['method' => 'PATCH', 'route' =>['delivery-methods.update',$delivery_method->id]]) }}
                  <div class="form-group">
                    <label for="brandName">Delivery Method Name *</label>
                    <input type="text" class="form-control" name="delivery_method" id="brandName" value="{{ $delivery_method->delivery_method }}">
                    @if($errors->has('delivery_method'))
                      <span class="text-danger">{{ $errors->first('delivery_method') }}</span>
                    @endif
                  </div>

                  <div class="form-group">
                    <label for="amount">Amount *</label>
                    <input type="text" class="form-control" name="amount" id="amount" value="{{ $delivery_method->amount }}">
                    @if($errors->has('amount'))
                      <span class="text-danger">{{ $errors->first('amount') }}</span>
                    @endif
                  </div>
                  
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                       <input type="checkbox" name="published" id="Published" @if((old('brand_published')=='on')||($delivery_method->status==1)) checked @endif>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="{{route('delivery-methods.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" class="btn save-btn">Save</button>
                  </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

@endsection