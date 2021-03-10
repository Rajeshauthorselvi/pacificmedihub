@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Features</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0);">Static Pages</a></li>
              <li class="breadcrumb-item"><a href="{{route('static-page-features.index')}}">Features</a></li>
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
          <a href="{{route('static-page-features.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Features</h3>
              </div>
              <div class="card-body">
                <form action="{{route('static-page-features.update',$features->id)}}" method="post" enctype="multipart/form-data" id="staticForm">
                  @csrf
                  <input name="_method" type="hidden" value="PATCH">
                  <div class="form-group">
                    <label for="sliderName">Features Name *</label>
                    <input type="text" name="features_name" class="form-control" id="featureName" value="{{ old('features_name',$features->feature_name) }}">
                    <span class="text-danger name" style="display:none;">Name is Required</span>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <label for="addBanner">Add Features *</label>
                      <div class="table-responsive"> 
                        <table class="table table-bordered banner">
                          <thead>
                            <tr><th style="width:350px">Title</th><th>Message</th><th style="width:100px">Icon</th></tr>
                          </thead>
                          <tbody>
                            <?php $count = 1 ?>
                            @foreach($features_data as $feature)
                              <tr>
                                <td>
                                  <input type="hidden" id="valueId" name="features[id][]" value="{{$feature->id}}">
                                  <input type="text" class="form-control" name="features[title][]" value="{{ $feature->title }}">
                                </td>
                                <td><textarea class="form-control" rows="1" name="features[message][]">{{ $feature->message }}</textarea></td>
                                <td>
                                  <img class="features-img" id="output_image1" src="{{asset('theme/images/features/'.$feature->images)}}" style="cursor:default;border:none">
                                </td>
                              </tr>
                              <?php $count++ ?>
                            @endforeach
                          </tbody>
                        </table> 
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="published" id="Published" @if($features->published==1) checked @endif>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                
                  <div class="form-group">
                    <a href="{{route('static-page-features.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" id="submit-btn" class="btn save-btn">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

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

       $('#submit-btn').on('click',function(){
          var name = $('#featureName').val();
          
          if(name==''){
            $('.text-danger.name').show();
            return false;
          }else{
            $('.text-danger.name').hide();
          }

          var empty_field = $('.banner .form-control').filter(function(){
            return !$(this).val();
          }).length;
          if (empty_field!=0) {
            alert('Please fill all the row and upload Image.');
            return false;
          }
          else{
            $('#staticForm').submit();
          }
      });
    </script>
  @endpush

  <style type="text/css">
    .btn.save-btn.prefix {margin-top: 30px;}
    .features-img {width: 60px;cursor:pointer;}
    .table-bordered.banner th{text-align:center;}
    .table-bordered.banner th, .table-bordered.banner td{vertical-align:middle;}
  </style>
 
@endsection