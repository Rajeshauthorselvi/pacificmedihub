@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Home Page</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Static Pages</li>
              <li class="breadcrumb-item">Home</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('static-page.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <form action="{{route('static-page.store')}}" method="post" enctype="multipart/form-data" id="staticForm">
            @csrf
            {!! Form::hidden('from','home') !!}
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Slider Block</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="col-sm-8">
                    <label>Slider</label>
                    {!!Form::select('slider',$sliders,$selected_slider,['class'=>'form-control no-search select2bs4'])!!}
                  </div>
                  <div class="col-sm-2">
                    <label>Enable</label>
                    {!! Form::select('slider_status',$status,null,['class'=>'form-control no-search select2bs4'])!!}
                  </div>
                  <div class="col-sm-2">
                    <a href="{{ route('static-page-slider.index') }}" class="btn btn-primary" style="margin-top:30px">Custom Sliders</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Features Block</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="col-sm-8">
                    <label>Features</label>
                    {!!Form::select('features',$features,$selected_feature,['class'=>'form-control no-search select2bs4'])!!}
                  </div>
                  <div class="col-sm-2">
                    <label>Enable</label>
                    {!! Form::select('features_status',$status,null,['class'=>'form-control no-search select2bs4'])!!}
                  </div>
                  <div class="col-sm-2">
                    <a href="{{ route('static-page-features.index') }}" class="btn btn-primary" style="margin-top:30px">Custom Features</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-6">
                <div class="card card-outline card-primary">
                  <div class="card-header">
                    <h3 class="card-title">New Arrival Block</h3>
                  </div>
                  <div class="card-body">
                    {!! Form::select('new_arrival_status',$status,null,['class'=>'form-control no-search select2bs4'])!!}
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="card card-outline card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Category Block</h3>
                  </div>
                  <div class="card-body">
                    {!!Form::select('category_block_status',$status,null,['class'=>'form-control no-search select2bs4'])!!}
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <a href="{{route('static-page.index')}}" class="btn reset-btn">Cancel</a>
              <button type="submit" id="submit-btn" class="btn save-btn">Save</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <style type="text/css">
    .form-group{display:flex}
    .btn.save-btn.prefix {margin-top: 30px;}
  </style>

  @push('custom-scripts')
    <script type='text/javascript'>
      $(function ($) {
        $('.read-only.select2bs4').select2({
          disabled: true
        });
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });
    </script>
  @endpush
@endsection