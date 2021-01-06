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
              <li class="breadcrumb-item"><a href="{{route('product.index')}}">Products</a></li>
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
          <a href="{{route('product.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
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
                <form action="{{route('product.store')}}" method="post" enctype="multipart/form-data" id="productForm">
                  @csrf
                  <div class="product-col-dividers">
                    <div class="col-one col-sm-6">
                      <div class="form-group">
                        <label for="productName">Product Name *</label>
                        <input type="text" class="form-control" name="product_name" id="productName" value="{{old('product_name')}}">
                        @if($errors->has('product_name'))
                          <span class="text-danger">{{ $errors->first('product_name') }}</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="productCode">Product Code *</label>
                        <input type="text" class="form-control" name="product_code" id="productCode" value="{{old('product_code')}}">
                        @if($errors->has('product_code'))
                          <span class="text-danger">{{ $errors->first('product_code') }}</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="productSku">SKU</label>
                        <input type="text" class="form-control" name="product_sku" id="productSku" value="{{old('product_sku')}}">
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
                        @if($errors->has('category'))
                          <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
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

                    <div class="col-two col-sm-5">
                      <div class="form-group">
                        <label for="productBrand">Brand</label>
                        <select class="form-control select2bs4" name="brand">
                          <option selected="selected" value="">Select Brand</option>
                          @foreach($brands as $brand)
                            <option value="{{$brand->id}}" {{ (collect(old('brand'))->contains($brand->id)) ? 'selected':'' }}>{{$brand->name}}</option>
                          @endforeach
                        </select>
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

                        <button type="button" class="btn save-btn" id="add-options" style="display:none">Save</button>

                      </div>
                      <a id="clear-option" class="btn save-btn" style="display:none">Clear</a>

                      <div class="form-group" style="display:flex;">
                        <div class="col-sm-6" style="padding-left:0">
                          <label for="productBrand">Commission Type</label>
                          <select class="form-control commission select2bs4" name="commision_type">
                            <option selected="selected" value="0">Percentage (%)</option>
                            <option value="1">Fixed (amount)</option>
                          </select>
                        </div>
                        <div class="col-sm-6" style="padding:0">
                          <label for="commissionValue">Value</label>
                          <input type="text" class="form-control" name="commision_value" id="commissionValue" onkeyup="validateNum(event,this);" value="{{old('commision_value')}}">
                        </div>
                      </div>
                      
                      <div class="form-group clearfix" style="display:flex;">
                        <div class="col-sm-6" style="padding-left:0">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="published" id="Published" @if(old('published')=='on') checked @endif>
                            <label for="Published">Published</label>
                          </div>
                        </div>
                        <div class="col-sm-6" style="padding:0">
                          <div class="icheck-info d-inline">
                            <input type="checkbox" name="homepage" id="homePage" @if(old('homepage')=='on') checked @endif>
                            <label for="homePage">Show on Home Page</label>
                          </div>
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

                  <div class="form-group">
                    <label>Product Details</label>
                    <textarea class="summernote" name="product_details">{{old('product_details')}}</textarea>
                  </div>

                  <div class="product-variant-block"></div>

                  <div class="form-group">
                    <label>Treatment Information</label>
                    <textarea class="summernote" name="treatment_information">{{old('treatment_information')}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Dosage Instructions</label>
                    <textarea class="summernote" name="dosage_instructions">{{old('dosage_instructions')}}</textarea>
                  </div>


                  <!-- <div class="product-variant-block">
                    <label>Product Variants</label>
                    <table class="list" id="variantList">
                      <thead>
                        <tr>
                          <th>Dosage</th><th>Package</th><th>Base Price</th><th>Retail Price</th><th>Minimum Selling Price</th><th>Stock Qty</th><th>Select Vendor</th><th>&nbsp;Default&nbsp;</th><th>Order By</th><th>Display</th><th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control dosage" name="variant[dosage][]">
                              <span class="text-danger" style="display:none">Dosage is required</span>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control package" name="variant[package][]">
                              <span class="text-danger" style="display:none">Package is required</span>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control base_price" onkeyup="validateNum(event,this);" name="variant[base_price][]">
                              <span class="text-danger" style="display:none">Base Price is required</span>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control retail_price" onkeyup="validateNum(event,this);" name="variant[retail_price][]">
                              <span class="text-danger" style="display:none">Retail Price is required</span>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[minimum_price][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control stock_qty" onkeyup="validateNum(event,this);" name="variant[stock_qty][]">
                              <span class="text-danger" style="display:none">Stock Qty is required</span>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <select class="form-control select2" multiple="multiple" data-placeholder="Select Vendor/Supplier" name="variant[vendor][0][]">


                                @foreach($vendors as $vendor)
                                  <option value="{{$vendor->id}}">{{$vendor->name}}</option>
                                @endforeach
                              </select>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="radio" class="default-variant" name="default" id="defaultVariant" value="1">
                                <input type="hidden" name="variant[default][]" class="hidden-default-variant" value="1">
                                <label for="defaultVariant"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[display_order][]">
                            </div>
                          </td>
                          <td>
                            <div class="form-group">
                              <select class="form-control commission select2bs4" name="variant[display][]">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                              </select>
                            </div>
                            <input type="hidden" class="length" value="1">
                          </td>
                          <td>
                            <a href="#" id="add"><i class="fas fa-plus-circle"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div> -->

                  <div class="form-group">
                    <label for="searchEngine">Search Engine Friendly Page Name</label>
                    <input type="text" class="form-control" name="search_engine" id="searchEngine" value="{{old('search_engine')}}">
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
                    <a href="{{route('product.index')}}" class="btn reset-btn">Cancel</a>
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

  @push('custom-scripts')
    <script type="text/javascript">
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

      /*function createRow() {
        var inputs = $('.length').val();
        var id = parseInt(inputs)+parseInt(1);
        $('.length').val(id);
        var newrow = [
          '<div class="form-group"><input type="text" class="form-control" name="variant[dosage][]"></div>',
          '<div class="form-group"><input type="text" class="form-control" name="variant[package][]"></div>',
          '<div class="form-group"><input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[base_price][]"></div>',
          '<div class="form-group"><input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[retail_price][]"></div>',
          '<div class="form-group"><input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[minimum_price][]"></div>',
          '<div class="form-group"><input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[stock_qty][]"></div>',
          '<div class="form-group"><select class="form-control select2" multiple="multiple" data-placeholder="Select Vendor/Supplier" name="variant[vendor]['+id+'][]">@foreach($vendors as $vendor)<option value="{{$vendor->id}}">{{$vendor->name}}</option>@endforeach</select></div>',
          '<div class="form-group clearfix"><div class="icheck-info d-inline"><input type="radio" class="default-variant" name="default" id="defaultVariant'+id+'" value="0"><input type="hidden" name="variant[default][]" class="hidden-default-variant" value="0"><label for="defaultVariant'+id+'"></label></div></div>',
          '<div class="form-group"><input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[display_order][]"></div>',
          '<div class="form-group"><select class="form-control commission select2bs4" name="variant[display][]"><option value="0" selected>No</option><option value="1">Yes</option></select></div>',
          '<a href="#" class="deleteButton"><i class="fas fa-minus-circle"></i></a>'
        ];
        return '<tr><td>'+newrow.join('</td><td>')+'</td></tr>';
      }

      $('a#add').click(function() {
        $('table#variantList tbody').append(createRow());
        return false;
      });

      $('table#variantList').on('click','.addButton',function() {
        $(this).closest('tr').after(createRow());
      }).on('click','.deleteButton',function() {
        $(this).closest('tr').remove();
        return false;
      });

      $(document).ready(function() {
        $("body").on("click",".default-variant",function(){
          var val = $(this).val();
          if(val>0){
            $("input[type='radio']:checked").next("input[type='hidden']").val('1');
            $("input[type='radio']:checked").val('1');
            $("input[type='radio']:not(:checked)").next("input[type='hidden']").val('0');
            $("input[type='radio']:not(:checked)").val('0');
          }else{
            $("input[type='radio']:checked").next("input[type='hidden']").val('1');
            $("input[type='radio']:checked").val('1');
            $("input[type='radio']:not(:checked)").next("input[type='hidden']").val('0');
            $("input[type='radio']:not(:checked)").val('0');
          }
        });
      });

      function validate(){
        var valid=true;
        if($(".dosage").val()=="") {
          $(".dosage").closest('.form-group').find('span.text-danger').show();
          valid=false;
        }
        if($(".package").val()=="") {
          $(".package").closest('.form-group').find('span.text-danger').show();
          valid=false;
        }
        if($(".base_price").val()=="") {
          $(".base_price").closest('.form-group').find('span.text-danger').show();
          valid=false;
        }
        if($(".retail_price").val()=="") {
          $(".retail_price").closest('.form-group').find('span.text-danger').show();
          valid=false;
        }
        if($(".stock_qty").val()=="") {
          $(".stock_qty").closest('.form-group').find('span.text-danger').show();
          valid=false;
        }
        return valid;
      }

      $('#productForm').submit(validate);*/

      $('.contact').keyup(function(e){
        if(/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
          alert('Enter numbers only');
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

      //Add Variant
      $('#productVariant').on('change',function(){
        if($('#productVariant option:selected').val()==null) {
          $('#add-options').css('display','none');
        }
        $('#add-options').css('display','block');
      });

      $('#add-options').on('click',function(){
        var options = $('#productVariant option:selected');
        
        if(options.length > 0  && options.length <=3 ){
          $('.product-variant-selectbox').css({'pointer-events':'none','opacity':'0.5'});
          $('#clear-option').css('display','block');      
          var selectedOption = JSON.stringify($('#productVariant').val());
          var selectedVendor = JSON.stringify($('#VendorSupplier').val());
          if($('#VendorSupplier').val().length==0){
            alert('Please select Vendor/Supplier.!');
            return false;
          }
          console.log(selectedOption);
          console.log(selectedVendor);
          var csrf_token = "{{ csrf_token() }}";
          $.ajax({
            url:"{{ url('admin/product_variant') }}",
            type:"POST",
            dataType:"HTML",
            data:{"_token": "{{ csrf_token() }}",options:selectedOption,vendors:selectedVendor},
            success: function (data) { 
              $('.product-variant-block').append(data);
            }
          });
        }else{
          alert('Please select maximum of 3 options only.!');
        }
      });

      
      //Clear Options
      $('#clear-option').on('click',function () {
        $('#clear-option').css('display','none');
        $('#add-options').css('display','none');
        $('#productVariant').val('').change();
        $('#VendorSupplier').val('').change();
        $('.product-variant-selectbox').css({'pointer-events':'auto','opacity':'1'});
        $('.product-variant-block').find('table').remove();
      });

      

    </script>
  @endpush
@endsection