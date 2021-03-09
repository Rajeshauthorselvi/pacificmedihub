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
                <h3 class="card-title">Add New Features</h3>
              </div>
              <div class="card-body">
                <form action="{{route('static-page-features.store')}}" method="post" enctype="multipart/form-data" id="staticForm">
                  @csrf
                  <div class="form-group">
                    <label for="sliderName">Features Name *</label>
                    <input type="text" name="features_name" class="form-control" id="featureName" value="{{ old('features_name') }}">
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
                            <tr>
                              <td><input type="text" class="form-control" name="features[title][]" value=""></td>
                              <td><textarea class="form-control" rows="1" name="features[message][]"></textarea></td>
                              <td>
                                <input type="file" class="form-control image" name="features[image][]" id="featuresImage1" accept="image/*" onchange="preview_image(event,1,this.value);" style="display:none;" value="">
                                <img title="Click to Add" class="features-img" id="output_image1"  onclick="$('#featuresImage1').trigger('click'); return true;" src="{{ asset('theme/images/image_add.png')}}">
                              </td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="features[title][]" value=""></td>
                              <td><textarea class="form-control" rows="1" name="features[message][]"></textarea></td>
                              <td>
                                <input type="file" class="form-control image" name="features[image][]" id="featuresImage2" accept="image/*" onchange="preview_image(event,2,this.value);" style="display:none;" value="">
                                <img title="Click to Add" class="features-img" id="output_image2"  onclick="$('#featuresImage2').trigger('click'); return true;" src="{{ asset('theme/images/image_add.png')}}">
                              </td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="features[title][]" value=""></td>
                              <td><textarea class="form-control" rows="1" name="features[message][]"></textarea></td>
                              <td>
                                <input type="file" class="form-control image" name="features[image][]" id="featuresImage3" accept="image/*" onchange="preview_image(event,3,this.value);" style="display:none;" value="">
                                <img title="Click to Add" class="features-img" id="output_image3"  onclick="$('#featuresImage3').trigger('click'); return true;" src="{{ asset('theme/images/image_add.png')}}">
                              </td>
                            </tr>
                            <tr>
                              <td><input type="text" class="form-control" name="features[title][]" value=""></td>
                              <td><textarea class="form-control" rows="1" name="features[message][]"></textarea></td>
                              <td>
                                <input type="file" class="form-control image" name="features[image][]" id="featuresImage4" accept="image/*" onchange="preview_image(event,4,this.value);" style="display:none;" value="">
                                <img title="Click to Add" class="features-img" id="output_image4"  onclick="$('#featuresImage4').trigger('click'); return true;" src="{{ asset('theme/images/image_add.png')}}">
                              </td>
                            </tr>
                          </tbody>
                        </table> 
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="published" id="Published">
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
      function preview_image(event,count,file) 
      {
        var ext = file.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["jpg" , "jpeg", "png"];
        if (arrayExtensions.lastIndexOf(ext) == -1) {
          alert("You can only upload jpg,jpeg,png image file only.");
          $(this).val("");
        }else{ 
          var fileSize = event.target.files[0].size;
          if(fileSize < 153600){  
            var reader = new FileReader();
            reader.onload = function()
            {
              var output = document.getElementById('output_image'+count);
              output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
          }
          else{
            alert("Image Size is too big. Minimum size 150-KB.");
            $(this).val("");
          }
        }
      }

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