@extends('admin.layouts.master')
@section('main_container')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item active">Add Product</li>
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
          @if (Request::get('from')=="vendor" && Request::has('vendor_id'))
           <a href="{{route('vendor-products.index',['vendor_id'=>Request::get('vendor_id')])}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
          @else
          <a href="{{route('products.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
          @endif
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Product</h3>
              </div>
              <div class="card-body">
                <form action="{{route('products.store')}}" method="post" enctype="multipart/form-data" id="productForm">
                  @csrf
                  <div class="product-col-dividers">
                    <div class="col-one col-sm-6">
                      <div class="form-group">
                        <label for="productName">Product Name *</label>
                        <input type="text" class="form-control" name="product_name" id="productName" value="{{old('product_name')}}">
                        <span class="text-danger product_required hidden">Product name is reqiured</span>
                        @if($errors->has('product_name'))
                          <span class="text-danger">{{ $errors->first('product_name') }}</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="productCode">Product Code</label>
                        {!! Form::text('product_code', $product_id,['class'=>'form-control','id'=>'productCode','readonly']) !!}
                      </div>

                      <div class="form-group">
                        <label for="productCategory">Category *</label>
                        <select class="form-control select2bs4" name="category">
                          <option selected="selected" value="">Select Category</option>
                          @foreach($categories as $category)
                            <?php
                              $category_name = $category->name;
                              if($category->parent_category_id!=NULL){
                                $category_name = $category->parent->name.'  >>  '.$category->name;
                              }
                            ?>
                            <option value="{{$category->id}}" {{ (collect(old('category'))->contains($category->id)) ? 'selected':'' }}>{{$category_name}}</option>
                          @endforeach
                        </select>
                        <span class="text-danger category_required hidden">Category is reqiured</span>
                        @if($errors->has('category'))
                          <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#CategoryModal">
                          <i class="fa fa-plus"></i>&nbsp;Add New Category
                        </button>
                      </div>
                      <div class="form-group">
                        <label for="alertQty">Alert Quantity</label>
                        <input type="text" class="form-control" name="alert_qty" id="alertQty" onkeyup="validateNum(event,this);" value="{{old('alert_qty')}}">
                      </div>
                      <div class="form-group">
                        <label for="shortDescription">Product Short Details</label>
                        <textarea class="form-control" rows="3" name="short_description" id="shortDescription">{{old('short_description')}}</textarea>
                      </div>
                      <div class="form-group">
                        <label for="mainImage">Product Image</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="main_image" id="mainImage" accept="image/*" value="{{old('main_image')}}">
                          <label class="custom-file-label" for="mainImage">Choose file</label>
                        </div>
                      </div>
                    </div>

                    <div class="col-two col-sm-6">
                      <div class="form-group">
                        <label for="productBrand">Brand</label>
                        <select class="form-control select2bs4" name="brand">
                          <option selected="selected" value="">Select Brand</option>
                          @foreach($brands as $brand)
                            <option value="{{$brand->id}}" {{ (collect(old('brand'))->contains($brand->id)) ? 'selected':'' }}>{{$brand->name}}</option>
                          @endforeach
                        </select>
                        
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#BrandModal">
                          <i class="fa fa-plus"></i>&nbsp;Add New Brand
                        </button>
                      </div>
                      
                      
                      <div class="clearfix"></div>
                      <div class="form-group" style="display:flex;">
                        <div class="col-sm-6" style="padding-left:0">
                          <label for="commissionType">Commission Type *</label>
                          <select class="form-control commission select2bs4" id="commissionType" name="commision_type">
                            <option selected="selected" value="">Select Commission</option>
                            @foreach($commissions as $commission)
                              <?php 
                                if($commission->commission_type=='f') $type = 'Fixed (amount)';
                                else $type = 'Percentage (%)';
                              ?>
                              <option value="{{$commission->id}}" {{ (collect(old('commision_type'))->contains($commission->id)) ? 'selected':'' }}>{{$type}}</option>
                            @endforeach
                          </select>
                        <span class="text-danger commission_required hidden">Product Commission is reqiured</span>
                        </div>
                        <div class="col-sm-6" style="padding:0">
                          <label for="commissionValue">Value *</label>
                          <input type="text" class="form-control" name="commision_value" id="commissionValue" onkeyup="validateNum(event,this);" value="{{old('commision_value')}}" autocomplete="off">
                        </div>
                      </div>
                      
                      <div class="form-group clearfix">
                        <div class="icheck-info d-inline">
                          <input type="checkbox" name="published" id="Published" checked>
                          <label for="Published">Published</label>
                        </div>
                      </div>
                      <div class="form-group clearfix">
                        <div class="icheck-info d-inline">
                          <input type="checkbox" name="homepage" id="homePage" @if(old('homepage')=='on') checked @endif>
                          <label for="homePage">Show on Home Page</label>
                        </div>
                      </div>

                      <div class="product-variant-selectbox">
                        <div class="form-group">
                          <label for="productVariant">Product Variant Options</label>
                          <select class="form-control select2bs4" id="productVariant" multiple="multiple" data-placeholder="Select Variant Option" name="variant_option">
                            @foreach($product_options as $options)
                              <option value="{{$options->id}}" {{ (collect(old('variant_option'))->contains($options->id)) ? 'selected':'' }}>{{$options->option_name}}</option>
                            @endforeach
                          </select>
                        </div>

                        <div class="form-group">
                          <label for="VendorSupplier">Vendor/Supplier</label>
                          <select class="form-control select2bs4" id="VendorSupplier" multiple="multiple" data-placeholder="Select Vendor/Supplier" name="vendor">
                            @foreach($vendors as $vendor)
                              <option value="{{$vendor->id}}" {{ (collect(old('vendor'))->contains($vendor->id)) ? 'selected':'' }}>{{$vendor->name}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="submit-sec">
                          <a id="clear-option" class="btn reset-btn" style="display:none">Clear</a>
                          <button type="button" class="btn save-btn" id="add-options" style="display:none">Apply</button>
                          &nbsp;
                        </div>
                      </div>

                    </div>
                  </div>

                    <div class="form-group">
                      <article>
                        <label for="files">Product Gallery Images</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" name="images[]" id="files" multiple onChange="validateImg(this.value)">
                          <label class="custom-file-label" for="files">Choose file</label>
                        </div>
                        <output id="result" style="display:none;"></output>
                        <button type="button" id="clear" style="display:none;">Clear</button>
                      </article>
                    </div>
                    <div id="product-variant-block"></div>

                    <div class="form-group">
                      <label>Product Details</label>
                      <textarea class="summernote" name="product_details">{{old('product_details')}}</textarea>
                    </div>

                   <!--  <div class="form-group">
                      <label>Treatment Information</label>
                      <textarea class="summernote" name="treatment_information">{{old('treatment_information')}}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Dosage Instructions</label>
                      <textarea class="summernote" name="dosage_instructions">{{old('dosage_instructions')}}</textarea>
                    </div> -->

                    <div class="form-group">
                      <label for="searchEngine">Search Engine Friendly Page Name *</label>
                      <span title="Set a search engine friendly page name e.g. 'the-best-product' to make your page URL 'http://www.abcdxyz.com/the-best-product'." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <input type="text" class="form-control" name="search_engine" id="searchEngine" value="{{old('search_engine')}}">
                      @if($errors->has('search_engine'))
                        <span class="text-danger">{{ $errors->first('search_engine') }}</span>
                      @endif
                    </div>
                    <div class="form-group">
                      <label for="metaTile">Meta Title</label>
                      <span title="Override the page title. The default is the name of the product." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <input type="text" class="form-control" name="meta_title" id="metaTile" value="{{old('meta_title')}}">
                    </div>
                    <div class="form-group">
                      <label for="metaKeyword">Meta Keywords</label>
                      <span title="Meta description to be added to product page header." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <textarea class="form-control" rows="3" name="meta_keyword" id="metaKeyword">{{old('meta_keyword')}}</textarea>
                    </div>
                    <div class="form-group">
                      <label for="metaDescription">Meta Description</label>
                      <span title="Meta description to be added to product page header." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <textarea class="form-control" rows="3" name="meta_description" id="metaDescription">{{old('meta_description')}}</textarea>
                    </div>
                  
                  <div class="form-group">
                    <a href="{{route('products.index')}}" class="btn reset-btn">Cancel</a>
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
 <!-- Modal -->
<div class="modal fade" id="BrandModal" tabindex="-1" role="dialog" aria-labelledby="BrandModal" aria-hidden="true">
<form action="{{url('admin/brand-ajax')}}" method="post" enctype="multipart/form-data" id="brand-post" >
                  @csrf
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add New Brand</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger alert-block alert-brand">
          <button type="button" class="close" data-dismiss="alert">×</button> 
          <ul class="error_append"> </ul>
        </div>
                  <div class="form-group">
                    <label for="brandName">Brand Name *</label>
                    <input type="text" class="form-control" name="brand_name" id="brandName" value="{{old('brand_name')}}">

                  </div>
                  <div class="form-group" style="display:flex;">
                    <div class="col-sm-6" style="padding-left:0">
                      <label for="manfName">Manufacturing Name *</label>
                      <input type="text" class="form-control" name="manf_name" id="manfName" value="{{old('manf_name')}}">
                      @if($errors->has('manf_name'))
                        <span class="text-danger">{{ $errors->first('manf_name') }}</span>
                      @endif
                    </div>
                    <div class="col-sm-6" style="padding-left:0">
                      <label for="manfName">Manufacturing Country</label>
                      {!! Form::select('country_id',$countries,null,['class'=>'form-control select2bs4 required']) !!}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="brandImage">Image</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="brand_image" id="brandImage" accept="image/*" value="{{old('brand_image')}}">
                      <label class="custom-file-label" for="brandImage">Choose file</label>
                    </div>
                  </div>
                  <div class="form-group clearfix">
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="brand_published" id="Published" checked>
                      <label for="Published">Published</label>
                    </div>
                  </div>
                
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary add-brand">Add New Brand</button>
      </div>
    </div>
  </div>
</form>
</div> 

 <!-- Modal -->
<div class="modal fade" id="CategoryModal" tabindex="-1" role="dialog" aria-labelledby="CategoryModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Add New Category</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form action="{{route('categories.store')}}" method="post" enctype="multipart/form-data" id="category-post">
        @csrf 
        <!-- Modal body -->
        <div class="modal-body">
          <div class="col-sm-12">
      <div class="modal-body">
        <div class="alert alert-danger alert-block alert-category">
          <button type="button" class="close" data-dismiss="alert">×</button> 
          <ul class="error_append_category"> </ul>
        </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="categoryName">Category Name *</label>
                      <input type="text" class="form-control" name="category_name" id="categoryName" value="{{old('category_name')}}" autocomplete="off">
                      @if($errors->has('category_name'))
                        <span class="text-danger">{{ $errors->first('category_name') }}</span>
                      @endif
                    </div>
                    <div class="col-sm-6">
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
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="categoryImage">Web Banner Image</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="category_image" id="categoryImage" accept="image/*" value="{{old('category_image')}}">
                        <label class="custom-file-label" for="categoryImage">Choose file</label>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <label for="categoryIcon">Mobile Icon Image</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="category_icon" id="categoryIcon" accept="image/*" value="{{old('category_icon')}}">
                        <label class="custom-file-label" for="categoryIcon">Choose file</label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-12">
                      <label for="description">Description</label>
                      <textarea class="form-control" rows="3" name="category_description" id="description">{{old('category_description')}}</textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-6">
                      <label for="searchEngineCat">Search Engine Friendly Page Name *</label>
                      <span title="Set a search engine friendly page name e.g. 'the-best-category' to make your page URL 'http://www.abcdxyz.com/the-best-category'." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <input type="text" class="form-control" name="search_engine" id="searchEngineCat" value="{{old('search_engine')}}">
                      @if($errors->has('search_engine'))
                        <span class="text-danger">{{ $errors->first('search_engine') }}</span>
                      @endif
                    </div>
                    <div class="col-md-6">
                      <label for="metaTile">Meta Title</label>
                      <span title="Override the page title. The default is the name of the category." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <input type="text" class="form-control" name="meta_title" id="metaTile" value="{{old('meta_title')}}">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <label for="metaKeyword">Meta Keywords</label>
                      <span title="Meta description to be added to category page header." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <textarea class="form-control" rows="3" name="meta_keyword" id="metaKeyword">{{old('meta_keyword')}}</textarea>
                    </div>
                    <div class="col-md-6">
                      <label for="metaDescription">Meta Description</label>
                      <span title="Meta description to be added to category page header." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      <textarea class="form-control" rows="3" name="meta_description" id="metaDescription">{{old('meta_description')}}</textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-6">
                      <label for="displayOrder">Display Order</label>
                      <input type="number" class="form-control" name="display_order" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" id="displayOrder" value="{{old('display_order',$display_order+1)}}">
                    </div>
                    <div class="col-md-4" style="margin-top:1rem">
                      <div class="icheck-info">
                        <input type="checkbox" name="category_published" id="Published" checked>
                        <label for="Published">Published</label>
                      </div>
                      <div class="icheck-info">
                        <input type="checkbox" name="category_homepage" id="homePage" @if(old('category_homepage')=='on') checked @endif>
                        <label for="homePage">Show on Home Page</label>
                      </div>
                    </div>
                  </div>
          </div>
              </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn save-btn add-category">Add New Category</button>
                  </div>
      </form>
        
      </div>
    </div>
  </div>  
<style type="text/css">

   #CategoryModal .form-group{display:flex}
  #clear-option,#add-options{float: left;}
  #add-options{margin-right: 10px;}
  .hidden{display: none;}
</style>
  @push('custom-scripts')
    <script type="text/javascript">

      $(document).on('keyup','#categoryName',function(){
        var product_name = $(this).val();
        var rmvSplChr = product_name.replace(/[^\w\s]/gi, '');
        var slug = rmvSplChr.replace(/\s+/g, '-');
        $('#searchEngineCat').val(slug.toLowerCase());
      });

      $(document).on('keypress','#searchEngineCat',function(event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });
    


      $('.alert-brand').hide();
      $('.alert-category').hide();
  $("body").on("click",".add-brand",function(e){
  $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
    var formData = new FormData($('#brand-post')[0]);
    $.ajax({
      url: '{{ url("admin/brand-ajax") }}',
      type: 'POST',
      processData: false,
      contentType: false,
      data: formData
    })
    .done(function(response) {
      $('.error_append').html('');
      $('#BrandModal').modal('hide');
      $('[name="brand"]').append('<option value="'+response.brand_id+'">'+response.brand_name+'</option>');
      $('[name="brand"]').val(response.brand_id).change();
        $('#brand-post')[0].reset();   
    })
    .fail(function(errors) {
      $('.alert-brand').show();
      $.each(errors.responseJSON.errors, function(index, val) {
         $('.error_append').append('<li>'+val+'</li>');
      });
    });
    
  });

  $("body").on("click",".add-category",function(e){
  $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
   });
    var formData = new FormData($('#category-post')[0]);
    $.ajax({
      url: '{{ url("admin/category-ajax") }}',
      type: 'POST',
      processData: false,
      contentType: false,
      data: formData
    })
    .done(function(response) {
      $('.error_append_category').html('');
      $('#CategoryModal').modal('hide');
      $('[name="category"]').append('<option value="'+response.category_id+'">'+response.category_name+'</option>');
      // $('.select2bs4').selectpicker('refresh');
      $('[name="category"]').val(response.category_id).change();
        $('#category-post')[0].reset();   
    })
    .fail(function(errors) {
      $('.alert-category').show();
      $.each(errors.responseJSON.errors, function(index, val) {
         $('.error_append_category').append('<li>'+val+'</li>');
      });
    });
    
  });

      //Image Upload
      window.onload = function(){   
        //Check File API support
        if(window.File && window.FileList && window.FileReader)
        {
          $('#files').on('change', function(event) {
            var files = event.target.files; //FileList object
            var output = document.getElementById("result");
            for(var i = 0; i< files.length; i++)
            {
              var file = files[i];
              //Only pics
              // if(!file.type.match('image'))
              if(file.type.match('image.*')){
                if(this.files[0].size < 2097152){    
                  // continue;
                  var picReader = new FileReader();
                  picReader.addEventListener("load",function(event){
                    var picFile = event.target;
                    var div = document.createElement("div");
                    div.className = "gallery"
                    div.innerHTML = "<img class='thumbnail' src='" + picFile.result + "'" +"title='preview image'/>";
                    output.insertBefore(div,null);
                  });
                  //Read the image
                  $('#clear, #result').show();
                  picReader.readAsDataURL(file);
                }else{
                  alert("Image Size is too big. Minimum size is 2MB.");
                  $(this).val("");
                }
              }else{
                alert("You can only upload image file.");
                $(this).val("");
              }
            }                                  
          });
        }
        else
        {
          console.log("Your browser does not support File API");
        }
      }
        
      $('#files').on("click", function() {
        $('.thumbnail').parent().remove();
        $('result').hide();
        $(this).val("");
      });

      $('#clear').on("click", function() {
        $('.thumbnail').parent().remove();
        $('#result').hide();
        $('#files').val("");
        $(this).hide();
      });

      function validateImg(file) {
        var ext = file.split(".");
        ext = ext[ext.length-1].toLowerCase();      
        var arrayExtensions = ["jpg" , "jpeg", "png"];
        if (arrayExtensions.lastIndexOf(ext) == -1) {
          alert("You can only upload jpg,jpeg,png image file only.");
          $("#files").val("");
        }
      }

      $(function ($) {
        $('body').find('.commission.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });

      $('.commission').on('change',function(){
        var commissionTypeId = $('.commission').val();
        $.ajax({
          url:"{{ url('admin/get-commission-value') }}",
          type:"GET",
          data:{"_token": "{{ csrf_token() }}",id:commissionTypeId},
          success: function (data) { 
            $('#commissionValue').val(data);
          }
        });
      });

      $(document).on('click', '#submit-btn', function(event) {
        event.preventDefault();
        var product_name=$('[name=product_name]').val();
        var product_code=$('[name=product_code]').val();
        var category=$('[name=category]').val();
        var commissionType = $('#commissionType').val();
        var commissionValue = $('#commissionValue').val();

        if (product_name=="") {
          $('.product_required').removeClass('hidden');
        }
        else{
          $('.product_required').addClass('hidden');
        }
        if (category=="") {
          $('.category_required').removeClass('hidden');
        }
        else{
          $('.category_required').addClass('hidden');
        }

        if(commissionType==""){
          $('.commission_required').removeClass('hidden');
        }else{
          $('.commission_required').addClass('hidden'); 
        }

        if(commissionValue==""){
          $('.commission_required').removeClass('hidden');
        }else{
          $('.commission_required').addClass('hidden'); 
        }

        if (product_name=="" || product_code=="" || category==""||commissionType==""||commissionValue=="") {
          scroll_up();
          return false;
        } 

        if ($('#variantList').length==0) {
          alert('Please add variants');
          scroll_up();
          return false
        }
        var empty_field = $('#variantList .form-control').filter(function(){
                            return !$(this).val();
                          }).length;

        if (empty_field!=0) {
          alert('Please fill all the variants.');
          return false;
        }
        else{
          $('#productForm').submit();
        }

      });

      //Validate Number
      function validateNum(e , field) {
        var val = field.value;
        var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
        var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
        if (re.test(val)) {

          } else {
              val = re1.exec(val);
              if (val) {
                  field.value = val[0];
              } else {
                  field.value = "";
              }
          }
      }
      $(function() {
        $('.validateTxt').keydown(function (e) {
          if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault();
          } else {
            var key = e.keyCode;
            if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
              e.preventDefault();
            }
          }
        });
      });

      $(document).on('keyup','#productName',function(){
        var product_name = $(this).val();
        var rmvSplChr = product_name.replace(/[^\w\s]/gi, '');
        var slug = rmvSplChr.replace(/\s+/g, '-');
        $('#searchEngine').val(slug.toLowerCase());
      });

      $(document).on('keypress','#searchEngine',function(event) {
        var regex = new RegExp("^[a-zA-Z0-9]+$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
          event.preventDefault();
          return false;
        }
      });

      //Add Variant
      $('#productVariant').on('change',function(){
        if($('#productVariant option:selected').val()==null) {
          $('#add-options').css('display','none');
        }
        $('#add-options').css('display','block');
      });

      $('#add-options').on('click',function(){
        var options = $('#productVariant option:selected');
        
        if(options.length > 0  && options.length <=5 ){
                
          var selectedOption = JSON.stringify($('#productVariant').val());
          var selectedVendor = JSON.stringify($('#VendorSupplier').val());
          var productCode    = $('#productCode').val();
          if($('#VendorSupplier').val().length==0 && $('#productVariant').val()){
            alert('Please select Vendor/Supplier.!');
            return false;
          }
          else{
            $('.product-variant-selectbox').find('.select2').css({'pointer-events':'none','opacity':'0.5'});
            $('#add-options').css({'pointer-events':'none','opacity':'0.5'});
            $('#clear-option').css('display','block');
          }

          var csrf_token = "{{ csrf_token() }}";
          $.ajax({
            url:"{{ url('admin/product_variant') }}",
            type:"POST",
            data:{"_token": "{{ csrf_token() }}",options:selectedOption,vendors:selectedVendor,product_code:productCode},
            success: function (data) { 
              //console.log(data);
              $('#product-variant-block').html(data);
              scroll_to();
            }
          });
        }else{
          alert('Please select maximum of 5 options only.!');
        }
      });

      function scroll_to(div){
        $('html, body').animate({
          scrollTop: $("#product-variant-block").offset().top
        },1000);
      }

      function scroll_up(){
        $('html, body').animate({
          scrollTop: $("#productForm").offset().top
        },1000);
      }

      //Clear Options
      $('#clear-option').on('click',function () {
        $('#clear-option').css('display','none');
        $('#add-options').css('display','none');
        $('#productVariant').val('').change();
        $('#VendorSupplier').val('').change();
        $('#add-options').css({'pointer-events':'auto','opacity':'1'});
        $('.product-variant-selectbox').find('.select2').css({'pointer-events':'auto','opacity':'1'});
        $('#product-variant-block').find('table').remove();
      });

    </script>
  @endpush
@endsection