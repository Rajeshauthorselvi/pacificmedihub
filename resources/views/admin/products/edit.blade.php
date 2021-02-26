@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item active">Edit Product</li>
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
                <h3 class="card-title">Edit Product</h3>
              </div>
              <div class="card-body">
                <form action="{{route('products.update',$product->id)}}" method="post" enctype="multipart/form-data" id="productForm">
                  @csrf
                  <input name="_method" type="hidden" value="PATCH">
                  <div class="product-col-dividers">
                    <div class="col-one col-sm-6">
                      <div class="form-group">
                        <label for="productName">Product Name *</label>
                        <input type="text" class="form-control" name="product_name" id="productName" value="{{old('product_name',$product->name)}}">
                         <span class="text-danger product_required hidden">Product name is reqiured</span>
                        @if($errors->has('product_name'))
                          <span class="text-danger">{{ $errors->first('product_name') }}</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label for="productCode">Product Code *</label>
                        <input type="text" class="form-control" name="product_code" id="productCode" value="{{old('product_code',$product->code)}}" readonly>
                      <span class="text-danger product_code_required hidden">Product code is reqiured</span>
                        @if($errors->has('product_code'))
                          <span class="text-danger">{{ $errors->first('product_code') }}</span>
                        @endif
                      </div>
                      {{-- <div class="form-group">
                        <label for="productSku">SKU</label>
                        <input type="text" class="form-control" name="product_sku" id="productSku" value="{{old('product_sku',$product->sku)}}">
                      </div> --}}
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
                            <option @if($product->category_id==$category->id) selected="selected" @endif  value="{{$category->id}}" {{ (collect(old('category'))->contains($category->id)) ? 'selected':'' }}>{{$category_name}}</option>
                          @endforeach
                        </select>
                        <span class="text-danger category_required hidden">Product name is reqiured</span>
                        @if($errors->has('category'))
                          <span class="text-danger">{{ $errors->first('category') }}</span>
                        @endif
                      </div>

                      <div class="form-group">
                        <label for="shortDescription">Product Short Details</label>
                        <textarea class="form-control" rows="4" name="short_description" id="shortDescription">{{old('short_description',$product->short_description)}}</textarea>
                      </div>

                      <?php 
                        if(!empty($product->main_image)){$image = "theme/images/products/main/".$product->main_image;}
                        else {$image = "theme/images/no_image.jpg";}
                      ?>
                      <div class="form-group">
                        <label for="mainImage">Product Image</label><br>
                        <input type="file" name="main_image" id="mainImage" accept="image/*" onchange="preview_image(event)" style="display:none;" value="{{$product->main_image}}">
                        <img title="Click to Change" class="img-product" id="output_image"  onclick="$('#mainImage').trigger('click'); return true;" style="width:100px;height:100px;cursor:pointer;" src="{{asset($image)}}">
                      </div>
                     
                    </div>

                    <div class="col-two col-sm-5">
                      <div class="form-group">
                        <label for="productBrand">Brand</label>
                        <select class="form-control select2bs4" name="brand">
                          <option selected="selected" value="">Select Brand</option>
                          @foreach($brands as $brand)
                            <option @if($product->brand_id==$brand->id) selected="selected" @endif value="{{$brand->id}}" {{ (collect(old('brand'))->contains($brand->id)) ? 'selected':'' }}>{{$brand->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="form-group" style="display:flex;">
                        <div class="col-sm-6" style="padding-left:0">
                          <label for="commissionType">Commission Type</label>
                          <select class="form-control commission select2bs4" id="commissionType" name="commision_type">
                            <option selected="selected" value="">Select Commission</option>
                            @foreach($commissions as $commission)
                              <?php 
                                if($commission->commission_type=='f') $type = 'Fixed (amount)';
                                else $type = 'Percentage (%)';
                              ?>
                              <option @if($product->commission_type==$commission->id) selected="selected" @endif value="{{$commission->id}}" {{ (collect(old('commision_type'))->contains($commission->id)) ? 'selected':'' }}>{{$type}}</option>
                            @endforeach
                          </select>
                          <span class="text-danger commission_required hidden">Product Commission is reqiured</span>
                        </div>
                        <div class="col-sm-6" style="padding:0">
                          <label for="commissionValue">Value</label>
                          <input type="text" class="form-control" name="commision_value" id="commissionValue" onkeyup="validateNum(event,this);" value="{{old('commision_value',$product->commission_value)}}">
                        </div>
                      </div>

                      <div class="form-group clearfix">
                        <div class="icheck-info d-inline">
                          <input type="checkbox" name="published" id="Published" @if((old('published')=='on')||($product->published==1)) checked @endif>
                          <label for="Published">Published</label>
                        </div>
                      </div>
                      <div class="form-group clearfix">
                        <div class="icheck-info d-inline">
                          <input type="checkbox" name="homepage" id="homePage" @if((old('homepage')=='on')||($product->show_home==1)) checked @endif>
                          <label for="homePage">Show on Home Page</label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="alertQty">Alert Quantity</label>
                        <input type="text" class="form-control" name="alert_qty" id="alertQty" onkeyup="validateNum(event,this);" value="{{old('alert_qty',$product->alert_quantity)}}">
                      </div>

                      <div class="product-variant-selectbox">
                        <div class="form-group">
                          <label for="productVariant">Product Variant Options</label>
                          {!! Form::select('variant_option',$product_options,$options_id,['class'=>'form-control select2bs4', 'id'=>'productVariant', 'multiple'=>'multiple', 'data-placeholder'=>'Select Variant Option', '@if($product_options->id==$options_id){"disabled"=>"disabled"}']) !!}
                        </div>
                        <div class="form-group">
                          <label for="VendorSupplier">Vendor/Supplier</label>
                          {!! Form::select('vendor',$vendors,$vendors_id,['class'=>'form-control select2bs4', 'id'=>'VendorSupplier', 'multiple'=>'multiple', 'data-placeholder'=>'Select Vendor/Supplier' ]) !!}
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
                    <label for="files">Product Gallery Images</label><br>
                    <article>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="images[]" id="files" multiple onChange="validateImg(this.value)">
                        <label class="custom-file-label" for="files">Add New Images</label>
                      </div>
                      <output id="result" style="display:none;"></output>
                      <button type="button" id="clear" style="display:none;">Clear</button>
                    </article>
                    @if(count($product_images)>0)
                      <label>Existing Images</label><br>
                      @foreach($product_images as $images)
                        <img class="img-category" style="width:120px;height: 100px;" src="{{ asset('theme/images/products/'.$images->name)}}">
                        <a class="btn btn-danger del-image" product-id="{{$images->id}}" route-url="{{ route('remove.pimage') }}">-</a>
                      @endforeach
                    @endif
                  </div>
                  <div class="product-variant-block">
                    <label>Product Variants</label>
                    <?php inputFields($get_options,$product_variant,$option_count); ?>
                      
                  </div>
                  @if (isset($old_options) && count($old_product_variant)>0)
                  <div class="clearfix"></div>
                  <br>
                      <div class="bs-example">
                          <div class="accordion" id="accordionExample">
                              <div class="card">
                                  <div class="card-header" id="headingOne">
                                      <h2 class="mb-0">
                                          <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i> Old Variants</button>                  
                                      </h2>
                                  </div>
                                  <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                      <div class="card-body">
                                        <div class="product-variant-block-old">
                                            <label>Old Variants</label>
                                            <?php inputFields($old_options,$old_product_variant,$old_option_count,'old'); ?>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  <div class="clearfix"></div>
                  <br>
                  @endif

                  <label class="new-product-variant-lable" style="display:none">New Product Variants</label>
                  <div id="new-product-variant-block"></div>

                  <div class="form-group">
                    <label>Product Details</label>
                    <textarea class="summernote" name="product_details">{{old('product_details',$product->long_description)}}</textarea>
                  </div>

                 <!--  <div class="form-group">
                    <label>Treatment Information</label>
                    <textarea class="summernote" name="treatment_information">{{old('treatment_information',$product->treatment_information)}}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Dosage Instructions</label>
                    <textarea class="summernote" name="dosage_instructions">{{old('dosage_instructions',$product->dosage_instructions)}}</textarea>
                  </div> -->

                  <div class="form-group">
                    <label for="searchEngine">Search Engine Friendly Page Name *</label>
                    <span title="Set a search engine friendly page name e.g. 'the-best-product' to make your page URL 'http://www.abcdxyz.com/the-best-product'." class="ico-help">
                      <i class="fa fa-question-circle"></i>
                    </span>
                    <input type="text" class="form-control" name="search_engine" id="searchEngine" value="{{old('search_engine',$product->search_engine_name)}}">
                    @if($errors->has('search_engine'))
                      <span class="text-danger">{{ $errors->first('search_engine') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="metaTile">Meta Title</label>
                    <span title="Override the page title. The default is the name of the product." class="ico-help">
                      <i class="fa fa-question-circle"></i>
                    </span>
                    <input type="text" class="form-control" name="meta_title" id="metaTile" value="{{old('meta_title',$product->meta_title)}}">
                  </div>
                  <div class="form-group">
                    <label for="metaKeyword">Meta Keywords</label>
                    <span title="Meta description to be added to product page header." class="ico-help">
                      <i class="fa fa-question-circle"></i>
                    </span>
                    <textarea class="form-control" rows="3" name="meta_keyword" id="metaKeyword">{{old('meta_keyword',$product->meta_keyword)}}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="metaDescription">Meta Description</label>
                    <span title="Meta description to be added to product page header." class="ico-help">
                      <i class="fa fa-question-circle"></i>
                    </span>
                    <textarea class="form-control" rows="3" name="meta_description" id="metaDescription">{{old('meta_description',$product->meta_description)}}</textarea>
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
  @php function inputFields($get_options,$product_variant,$option_count,$type='') { @endphp

    @php if($type=="old") $class="old_variant"; else $class=""; @endphp
    <table class="list {{ $class }}" id="variantList">
      <thead>
        <tr>
          <th class="vendor-name">Vendor</th>
          @php 
            foreach($get_options as $option){ echo '<th class="option-head">'.$option.'</th>'; }
          @endphp
          <th class="variant-sku">SKU</th>
          <th class="input-box">Base Price</th>
          <th class="input-box">Retail Price</th>
          <th class="input-box">Minimum Selling Price</th>
          <th class="input-box">Stock Qty</th>
          <th class="input-box">Order By</th>
          <th class="input-box">Display</th>
          @php if($type!="old"){ @endphp
            <th class="input-box">Remove</th>
          @php } @endphp
        </tr>
      </thead>
      <tbody>
        <input type="hidden" id="product_variant_count" value="{{count($product_variant)}}">
        @php foreach($product_variant as $variant){ @endphp
          <tr>
            <input type="hidden" name="variant[id][]" value="{{$variant['variant_id']}}">
            <td>
              <div class="form-group">
                <input type="hidden" name="variant[vendor_id][]" value="{{$variant['vendor_id']}}">
                {{$variant['vendor_name']}}
              </div>
            </td>
            <td>
              <div class="form-group">
                <input type="hidden" name="variant[option_id1][]" value="{{$variant['option_id1']}}">
                <input type="hidden" name="variant[option_value_id1][]" value="{{$variant['option_value_id1']}}">
                {{$variant['option_value1']}}
              </div>
            </td>
            @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
              <td>
                <div class="form-group">
                  <input type="hidden" name="variant[option_id2][]" value="{{$variant['option_id2']}}">
                  <input type="hidden" name="variant[option_value_id2][]" value="{{$variant['option_value_id2']}}">
                  {{$variant['option_value2']}}
                </div>
              </td>
            @endif
            @if($option_count==3||$option_count==4||$option_count==5)
              <td>
                <div class="form-group">
                  <input type="hidden" name="variant[option_id3][]" value="{{$variant['option_id3']}}">
                  <input type="hidden" name="variant[option_value_id3][]" value="{{$variant['option_value_id3']}}">
                  {{$variant['option_value3']}}
                </div>
              </td>
            @endif
            @if($option_count==4||$option_count==5)
              <td>
                <div class="form-group">
                  <input type="hidden" name="variant[option_id4][]" value="{{$variant['option_id4']}}">
                  <input type="hidden" name="variant[option_value_id4][]" value="{{$variant['option_value_id4']}}">
                  {{$variant['option_value4']}}
                </div>
              </td>
            @endif
            @if($option_count==5)
              <td>
                <div class="form-group">
                  <input type="hidden" name="variant[option_id5][]" value="{{$variant['option_id5']}}">
                  <input type="hidden" name="variant[option_value_id5][]" value="{{$variant['option_value_id5']}}">
                  {{$variant['option_value5']}}
                </div>
              </td>
            @endif
            <td>
              <div class="form-group">
                <input type="hidden" name="variant[sku][]" value="{{$variant['sku']}}">
                {{$variant['sku']}}
              </div>
            </td>
            <td>
              <div class="form-group">
                <input type="text" class="form-control base_price" onkeyup="validateNum(event,this);" name="variant[base_price][]" value="{{$variant['base_price']}}">
                <span class="text-danger" style="display:none">Base Price is required</span>
              </div>
            </td>
            <td>
              <div class="form-group">
                <input type="text" class="form-control retail_price" onkeyup="validateNum(event,this);" name="variant[retail_price][]" value="{{$variant['retail_price']}}">
                <span class="text-danger" style="display:none">Retail Price is required</span>
              </div>
            </td>
            <td>
              <div class="form-group">
                <input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[minimum_price][]" value="{{$variant['minimum_selling_price']}}">
              </div>
            </td>
            <td>
              <div class="form-group">
                <input type="text" class="form-control stock_qty" onkeyup="validateNum(event,this);" name="variant[stock_qty][]" value="{{$variant['stock_quantity']}}">
                <span class="text-danger" style="display:none">Stock Qty is required</span>
              </div>
            </td>
            
            <td>
              <div class="form-group">
                <input type="text" class="form-control" onkeyup="validateNum(event,this);" name="variant[display_order][]" value="{{$variant['display_order']}}">
              </div>
            </td>
            <td>
              <div class="form-group">
                <select class="form-control display_variant select2bs4" name="variant[display][]">
                  <option @if($variant['display_variant']==0) selected="selected" @endif value="0" selected>No</option>
                  <option @if($variant['display_variant']==1) selected="selected" @endif value="1">Yes</option>
                </select>
              </div>
            </td>
            @php if($type!="old"){ @endphp
              <td>
                <a class="btn btn-danger remove-variant-individual" variant-id="{{$variant['variant_id']}}" route-url="{{route('delete.variant')}}">
                  <i class="far fa-trash-alt"></i>
                </a>
              </td>
            @php } @endphp 
          </tr>
        @php } @endphp
      </tbody>
    </table>
  @php } @endphp


  <style type="text/css">
    #clear-option,#add-options{float: left;}
    #add-options{margin-right: 10px;}
    .hidden{display: none;}
    .list td{text-align: center;}
    #variantList{width: 100%}
    #variantList .input-box{width: 100px}
</style>
  @push('custom-scripts')
    <script>
      $('.old_variant input').attr('disabled',true);
      $('.old_variant select').attr('disabled',true);
      $(document).ready(function(){
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
          $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
      });
    </script>
    <script type="text/javascript">
      //Add Variant
      $('#productVariant').on('change',function(){
        if($('#productVariant option:selected').val()==null) {
          $('#add-options').css('display','none');
        }
        $('#add-options').css('display','block');
      });

      $('#VendorSupplier').on('change',function(){
        if($('#VendorSupplier option:selected').val()==null) {
          $('#add-options').css('display','none');
        }
        $('#add-options').css('display','block');
      });

      $('#add-options').on('click',function(){
        var existing_options = <?php echo json_encode($options_id); ?>;
        var existing_vendors = <?php echo json_encode($vendors_id); ?>;
        var db_exist_options_id = JSON.stringify(existing_options);
        var exist_options_id = '['+$('#productVariant').val()+']';
        var db_exist_vendors_id = JSON.stringify(existing_vendors);
        var exist_vendors_id = '['+$('#VendorSupplier').val()+']';


        if((db_exist_options_id==exist_options_id) && (db_exist_vendors_id!=exist_vendors_id)){
          if (!confirm("Vendor's are changed it will affected variants. Do you want to make change?")) {
            return false;
          }else{
            createVendorBlock('no_scroll_to');
          }
        }

        if((db_exist_options_id==exist_options_id) && (db_exist_vendors_id==exist_vendors_id)){
          alert('This variants are already Existing');
          $('.product-variant-block').css('display','block');
        }else{
          if(db_exist_options_id==exist_options_id){
            createVendorBlock();
          }else{
            var order_exists='{{ $order_exists }}';
            if (order_exists) {
              if (!confirm('The product contain orders, adding new variant will result in disabling exiting variants.Do you want to make change?')) {
                return false;
              }else{
                $('.product-variant-block .display_variant').val(0).attr("selected", "selected");
                createVendorBlock();
              }
            }
            else{
              if(!confirm('Existing variants will be removed. Do you want to make change?')){
                return false;
              }else{
                $('.product-variant-block').css('display','none');
                createVendorBlock('delete');
              }
            }
          }
        }
      });

      function createVendorBlock(value)
      {
        var existing_options = <?php echo json_encode($options_id); ?>;
        var existingOption = JSON.stringify(existing_options);
        var existing_vendors = <?php echo json_encode($vendors_id); ?>;
        var existingVendor = JSON.stringify(existing_vendors);
        var options = $('#productVariant option:selected');
        
        var delete_request = 0;
        if(value=='delete'){
          delete_request = 1;
        }

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
            data:{
              "_token": "{{ csrf_token() }}",
              options:selectedOption,
              vendors:selectedVendor,
              dataFrom:'edit',
              deleteRequest:delete_request,
              existOption:existingOption,
              existVendor:existingVendor,
              product_code:productCode
            },
            success: function (data) { 
              console.log(data);
              $('.new-product-variant-lable').css('display','block');
              $('#new-product-variant-block').html(data);
                scroll_to();  
              
              
              /*createTable(data.options);
              addOptionValue(0,data)*/
            }
          });
        }else{
          alert('Please select maximum of 5 options only.!');
        }
      }

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

      function scroll_to(div){
        $('html, body').animate({
          scrollTop: $("#new-product-variant-block").offset().top
        },1000);
      }
      //Clear Options
      $('#clear-option').on('click',function () {
        $('#clear-option').css('display','none');
        //$('#add-options').css('display','none');
        // $('#productVariant').val('').change();
        // $('#VendorSupplier').val('').change();
        $('.new-product-variant-lable').css('display','none');
        $('#add-options').css({'pointer-events':'auto','opacity':'1'});
        $('.product-variant-selectbox').find('.select2').css({'pointer-events':'auto','opacity':'1'});
        $('#new-product-variant-block').find('table').remove();
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
      $(document).on('click', '#submit-btn', function(event) {
        event.preventDefault();
        var product_name=$('[name=product_name]').val();
        var product_code=$('[name=product_code]').val();
        var category=$('[name=category]').val();

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

        if (product_code=="") {
            $('.product_code_required').removeClass('hidden');
        }
        else{
          $('.product_code_required').addClass('hidden');
        }
        if (category=="") {
          $('.category_required').removeClass('hidden');
        }
        else{
          $('.category_required').addClass('hidden');
        }

        if(commissionType==""){
          $('.commission_required').removeClass('hidden');
        }

        if(commissionValue==""){
          $('.commission_required').removeClass('hidden');
        }
        if((commissionType!="") &&(commissionValue!="")){
          $('.commission_required').addClass('hidden');
        }

        if (product_name=="" || product_code=="" || category==""||commissionType==""||commissionValue=="") {
          scroll_up();
          return false;
        } 

        else{
          $('#productForm').submit();
        }
      });

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
        $('.commission.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });


      //Get Commission
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

      function scroll_up(){
        $('html, body').animate({
          scrollTop: $("#productForm").offset().top
        },1000);
      }

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

    //Remove Variant
    var prev_val;
    $(".remove-variant-individual").on('click', function(e, state) { 
        var productVariantCount = $('#product_variant_count').val();
        if(productVariantCount==1){
          confirm("Can't remove. Product contain atleast one variant");
          return false;
        }else{
          prev_val = $(this).val();
          var success = confirm('Are you sure you want to remove this Variant?');
          if(success) {
            var varId = $(this).attr('variant-id');
            var routeUrl = $(this).attr('route-url');
            deletevariant(varId,routeUrl);

          }else {
            $(this).val(prev_val);
            return false; 
          }
        }

      });
    function deletevariant(varId,routeUrl) {
      $.ajax({
        url: routeUrl,
        type: "POST",
        data: {"_token": "{{ csrf_token() }}",id:varId},
        dataType: "html",
        success: function(response){             
          location.reload();
        }
      });        
    }

    //Delete image
      $(".del-image").on('click', function (e1, state1) {
        prev_val = $(this).val();
        var success = confirm('Are you sure you want to remove this Image?');
        if(success) {
          var imgId = $(this).attr('product-id');
          var routeUrl = $(this).attr('route-url');
          removeImage(imgId,routeUrl);
        }else {
          $(this).val(prev_val);
          return false; 
        }
      });
      function removeImage(imgId,routeUrl) {
        $.ajax({
          url: routeUrl,
          type: "POST",
          data: {"_token": "{{ csrf_token() }}",id:imgId},
          dataType: "html",
          success: function(response){ 
            location.reload();
          }
        });
      }

    </script>
  @endpush
@endsection