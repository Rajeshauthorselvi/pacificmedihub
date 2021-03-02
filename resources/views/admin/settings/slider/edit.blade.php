@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Slider</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Stating Page</a></li>
              <li class="breadcrumb-item"><a href="{{route('static-page-slider.index')}}">Slider</a></li>
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
          <a href="{{route('static-page-slider.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Slider</h3>
              </div>
              <div class="card-body">
                <form action="{{route('static-page-slider.update',$slider->id)}}" method="post" enctype="multipart/form-data" id="bannerForm">
                  @csrf
                  <input name="_method" type="hidden" value="PATCH">
                  <div class="form-group">
                    <label for="sliderName">Slider Name *</label>
                    <input type="text" name="slider_name" class="form-control" value="{{ old('slider_name',$slider->slider_name) }}">
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <label for="option_name">Banner Images *</label>
                      <div class="table-responsive"> 
                        <table class="table table-bordered banner" id="bannerList"> 
                          <thead> 
                            <tr> 
                              <th class="text-center title">Title</th>
                              <th class="text-center short">Short Description</th>
                              <th class="text-center btn_text">Button text</th>
                              <th class="text-center btn_link">Button Link</th>
                              <th class="text-center image">
                                Image 
                                <span title="Please upload jpg,jpeg,png image file only and it should be less then 2MB." class="ico-help">
                                  <i class="fa fa-question-circle"></i>
                                </span>
                              </th>
                              <th class="text-center sort">Sort By</th>
                              {{-- <th class="text-center add_rmv">
                                <button class="btn btn-md btn-primary" id="addBtn" type="button">
                                  <i class="fas fa-plus"></i>
                                </button>
                              </th>  --}}
                              <th>Remove</th>
                            </tr> 
                          </thead> 
                          <tbody id="tbody">
                            <?php $count = 1; ?>
                            @foreach($slider_banners as $banner)
                              <tr id="R1" class="parent_tr">
                                <td class="row-index text-center"> 
                                  <input type="hidden" id="valueId" name="slider_data[id][]" value="{{$banner->id}}">
                                  <input type="text" class="form-control" name="slider_data[title][]" value="{{$banner->title}}" autocomplete="off">
                                </td>
                                <td class="row-index text-center"> 
                                  <textarea class="form-control" name="slider_data[description][]">{{$banner->description}}</textarea>
                                </td>
                                <td>
                                  <input type="text" class="form-control" name="slider_data[button][]" autocomplete="off" value="{{$banner->button}}">
                                </td>
                                <td>
                                  <input type="text" class="form-control" name="slider_data[link][]" autocomplete="off" value="{{$banner->link}}">
                                </td>
                                <td>
                                  <img class="banner-img" id="output_image1" src="{{asset('theme/images/sliders/'.$banner->images)}}" style="cursor:default;border:none">
                                </td>
                                <td>
                                  <input type="text" class="form-control" name="slider_data[display_order][]" onkeyup='validateNum(event,this);' value="{{$banner->display_order}}">
                                </td>
                                <td class="text-center"> 
                                  <button class="btn btn-danger delete" type="button"><i class="fas fa-trash"></i></i>
                                  </button> 
                                </td>
                              </tr>
                              <?php $count++; ?>
                            @endforeach
                          </tbody> 
                        </table> 
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="published" id="Published" checked>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                
                  <div class="form-group">
                    <a href="{{route('static-page-slider.index')}}" class="btn reset-btn">Cancel</a>
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
  <style type="text/css">
    .delete {font-size: 12px;padding: 5px 10px;}
    .hidden{opacity:0;position:absolute;width:10%}
  </style>
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
          
            if(fileSize < 2097152){  
              var reader = new FileReader();
              reader.onload = function()
              {
                var output = document.getElementById('output_image'+count);
                output.src = reader.result;
              }
              reader.readAsDataURL(event.target.files[0]);
            }
            else{
              alert("Image Size is too big. Minimum size is 2MB.");
              $(this).val("");
            }
        }
      }
    </script>
 

    <script type="text/javascript">

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
          
            if(fileSize < 2097152){  
              var reader = new FileReader();
              reader.onload = function()
              {
                var output = document.getElementById('output_image'+count);
                output.src = reader.result;
              }
              reader.readAsDataURL(event.target.files[0]);
            }
            else{
              alert("Image Size is too big. Minimum size is 2MB.");
              $(this).val("");
            }
        }
      }
    </script>
 

    <script type="text/javascript">

      $(function ($) {
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });

      $(document).on('click','#customText',function() {
        var customeInput = $('#customText').is(':checked');
        if(customeInput==true){
          $('.custome-input').css('display','block');
        }else if(customeInput==false){
          $('.custome-input').css('display','none');
        }
      });
    </script>

    <script type="text/javascript">
      $(document).ready(function () { 
        var rowIdx = 1; 
        var rowCount = $('#tbody tr').length;
        $('#addBtn').on('click', function () {
        var count = ++rowCount;
        var imagecount = rowCount - 1;
          $('#tbody:last').append(
            `<tr id="R${count}" class="parent_tr">
              <td class="row-index text-center"> 
                <input type="text" class="form-control" name="slider_data[title][]" value="" autocomplete="off">
              </td>
              <td class="row-index text-center"> 
                <textarea class="form-control" name="slider_data[description][]"></textarea>
              </td>
              <td>
                <input type="text" class="form-control" name="slider_data[button][]" autocomplete="off" value="">
              </td>
              <td>
                <input type="text" class="form-control" name="slider_data[link][]" autocomplete="off" value="">
              </td>
              <td>
                <input type="file" class="form-control slider-image" name="slider_data[image][${imagecount}]" id="sliderImage${count}" accept="image/*" onchange="preview_image(event,${count},this.value)" style="display:none;" value="">
                <img title="Click to Add" class="banner-img" id="output_image${count}"  onclick="$('#sliderImage${count}').trigger('click'); return true;" src="{{ asset('theme/images/image_add.png')}}">
              </td>
              <td>
                <input type="text" class="form-control" name="slider_data[display_order][]" onkeyup='validateNum(event,this);' value="${count}">
              </td>
              <td class="text-center"> 
                <button class="btn btn-danger remove" type="button"><i class="fas fa-minus"></i></button> 
              </td> 
            </tr>`
          );
          if(rowCount==5){
            $('#addBtn').css('pointer-events','none');
            $('#addBtn').css('opacity','0.3');
          }
        }); 


        if(rowCount==5){
          $('#addBtn').css('pointer-events','none');
          $('#addBtn').css('opacity','0.3');
        }

        $('#tbody').on('click', '.remove', function () { 
          var child = $(this).closest('tr').nextAll(); 
          child.each(function () { 
            var id = $(this).attr('id'); 
            var idx = $(this).children('.row-index').children('p'); 
            var hideencount = $(this).children('.row-index').children('#hiddenCount'); 
            var dig = parseInt(id.substring(1)); 
            idx.html(`${dig - 1}`); 
            hideencount.val(`${dig - 1}`);
            $(this).attr('id', `R${dig - 1}`); 
          }); 

          
          if(rowCount<3){
            $('.remove').css('pointer-events','none');
            $('.remove').css('opacity','0.3');
          }
          $(this).closest('tr').remove(); 
          rowIdx = rowCount; 

          if(rowIdx<5){
            $('#addBtn').css('pointer-events','visible');
            $('#addBtn').css('opacity','1');
          }else if(rowIdx==5){
            $('#addBtn').css('pointer-events','none');
            $('#addBtn').css('opacity','0.3'); 
          }
        }); 

        $('#tbody').on('click', '.delete', function (event) { 
          if (confirm('Are you sure you want to delete?')) {
            $(this).closest('tr').remove(); 
            var valueId = $(this).closest('.parent_tr').find('#valueId').val();
            $.ajax({
              url: "{{route('delete.slider.banner')}}",
              type: "POST",
              data: {"_token": "{{ csrf_token() }}",id:valueId},
              dataType: "html",
              success: function(response){       
                alert("Banner deleted successfully.!");
              }
            });
          } else {
            return false;
          }
          if(rowIdx<5){
            $('#addBtn').css('pointer-events','visible');
            $('#addBtn').css('opacity','1');
          }else if(rowIdx==5){
            $('#addBtn').css('pointer-events','none');
            $('#addBtn').css('opacity','0.3'); 
          }
        });

      }); 
    </script>

    <script type="text/javascript">
      $('#submit-btn').on('click',function(){
       if ($('#bannerList').length==0) {
          alert('Please add Banners');
          return false;
        }
        var empty_field = $('#bannerList .slider-image').filter(function(){
          return !$(this).val();
        }).length;
        if (empty_field!=0) {
          alert('Please fill all the row and upload Image or remove the empty rows.');
          return false;
        }
        else{
          $('#bannerForm').submit();
        }
      });
    </script>

  @endpush
@endsection