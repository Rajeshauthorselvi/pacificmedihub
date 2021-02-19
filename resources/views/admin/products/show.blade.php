@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item active">Product Details</li>
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
          <a href="{{route('products.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Product Details</h3>
              </div>
              <a href="{{route('products.edit',$product->id)}}" class="btn emp-edit"><i class="far fa-edit"></i>&nbsp;Edit</a>
              <div class="card-body">
                <div class="product-col-dividers">
                  <div class="col-one col-sm-6">
                    <div class="form-group">
                      <label for="productName">Product Name *</label>
                      <input type="text" class="form-control" id="productName" readonly value="{{$product->name}}">
                    </div>
                    <div class="form-group">
                      <label for="productCode">Product Code *</label>
                      <input type="text" class="form-control" id="productCode" readonly value="{{$product->code}}" readonly>
                    </div>
                    {{-- <div class="form-group">
                      <label for="productSku">SKU</label>
                      <input type="text" class="form-control" id="productSku" readonly value="{{$product->sku}}">
                    </div> --}}
                    <div class="form-group">
                      <label for="productCategory">Category *</label>
                      <select class="form-control select2bs4" name="category" disabled>
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
                    </div>
                    <div class="form-group">
                      <label for="shortDescription">Product Short Details</label>
                      <textarea class="form-control" rows="4" id="shortDescription" readonly>{{$product->short_description}}</textarea>
                    </div>

                    <?php 
                      if(!empty($product->main_image)){$image = "theme/images/products/main/".$product->main_image;}
                      else {$image = "theme/images/no_image.jpg";}
                    ?>
                    <div class="form-group">
                      <label for="mainImage">Product Image</label><br>
                      <img title="Click to Change" class="img-product" id="output_image" style="width:100px;height:100px;" src="{{asset($image)}}">
                    </div>
                   
                  </div>

                  <div class="col-two col-sm-5">
                    <div class="form-group">
                      <label for="productBrand">Brand</label>
                      <select class="form-control select2bs4" name="brand" disabled>
                        <option selected="selected" value="">Select Brand</option>
                        @foreach($brands as $brand)
                          <option @if($product->brand_id==$brand->id) selected="selected" @endif value="{{$brand->id}}" {{ (collect(old('brand'))->contains($brand->id)) ? 'selected':'' }}>{{$brand->name}}</option>
                        @endforeach
                      </select>
                    </div>
                    
                    <div class="form-group" style="display:flex;">
                      <div class="col-sm-6" style="padding-left:0">
                        <label for="productBrand">Commission Type</label>
                        <select class="form-control commission select2bs4" name="commision_type" disabled>
                          <option selected="selected" value="">Select Brand</option>
                          @foreach($commissions as $commission)
                            <?php 
                              if($commission->commission_type=='f') $type = 'Fixed (amount)';
                              else $type = 'Percentage (%)';
                            ?>
                            <option @if($product->commission_type==$commission->id) selected="selected" @endif value="{{$commission->id}}" {{ (collect(old('commision_type'))->contains($commission->id)) ? 'selected':'' }}>{{$type}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-sm-6" style="padding:0">
                        <label for="commissionValue">Value</label>
                        <input type="text" class="form-control" id="commissionValue" readonly value="{{$product->commission_value}}">
                      </div>
                    </div>
                    
                    <div class="form-group clearfix">
                      <div class="icheck-info d-inline">
                        <input type="checkbox" id="Published" disabled @if($product->published==1) checked @endif>
                        <label for="Published">Published</label>
                      </div>
                    </div>
                    <div class="form-group clearfix">
                      <div class="icheck-info d-inline">
                        <input type="checkbox" id="homePage" disabled @if($product->show_home==1) checked @endif>
                        <label for="homePage">Show on Home Page</label>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="alertQty">Alert Quantity</label>
                      <input type="text" class="form-control" readonly id="alertQty" value="{{$product->alert_quantity}}">
                    </div>

                    <div class="product-variant-selectbox">
                      <div class="form-group">
                        <label for="productVariant">Product Variant Options</label>
                        {!! Form::select('variant_option',$product_options,$options_id,['class'=>'form-control select2bs4', 'id'=>'productVariant', 'multiple'=>'multiple','disabled']) !!}
                      </div>
                      <div class="form-group">
                        <label for="VendorSupplier">Vendor/Supplier</label>
                        {!! Form::select('vendor',$vendors,$vendors_id,['class'=>'form-control select2bs4', 'id'=>'VendorSupplier', 'multiple'=>'multiple','disabled']) !!}
                      </div>
                    </div>

                  </div>
                </div>

                <div class="form-group">
                  <label for="files">Product Gallery Images</label><br>
                  @foreach($product_images as $images)
                    <img class="img-category" style="width:120px;height: 100px;" src="{{ asset('theme/images/products/'.$images->name)}}">
                  @endforeach
                </div>

                <div class="product-variant-block">
                  <label>Product Variants</label>
                  <?php inputFields($get_options,$product_variant,$option_count); ?>
                </div>

                <div class="form-group">
                  <label>Product Details</label>
                  <textarea class="summernote">{{$product->long_description}}</textarea>
                </div>

               <!--  <div class="form-group">
                  <label>Treatment Information</label>
                  <textarea class="summernote">{{$product->treatment_information}}</textarea>
                </div>
                <div class="form-group">
                  <label>Dosage Instructions</label>
                  <textarea class="summernote">{{$product->dosage_instructions}}</textarea>
                </div> -->

                <div class="form-group">
                  <label for="searchEngine">Search Engine Friendly Page Name</label>
                  <input type="text" class="form-control" readonly id="searchEngine" value="{{$product->search_engine_name}}">
                </div>
                <div class="form-group">
                  <label for="metaTile">Meta Title</label>
                  <input type="text" class="form-control" readonly id="metaTile" value="{{$product->meta_title}}">
                </div>
                <div class="form-group">
                  <label for="metaKeyword">Meta Keywords</label>
                  <textarea class="form-control" rows="3" readonly>{{$product->meta_keyword}}</textarea>
                </div>
                <div class="form-group">
                  <label for="metaDescription">Meta Description</label>
                  <textarea class="form-control" rows="3" readonly>{{$product->meta_description}}</textarea>
                </div>
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
            foreach($get_options as $option){
             echo '<th class="option-head">'.$option.'</th>';
            }
          @endphp
          <th class="variant-sku">SKU</th>
          <th class="input-box">Base Price</th>
          <th class="input-box">Retail Price</th>
          <th class="input-box">Minimum Selling Price</th>
          <th class="input-box">Stock Qty</th>
          <th class="input-box">Order By</th>
          <th class="input-box">Display</th>
          @php 
            if($type!="old"){ @endphp
          @php } @endphp
        </tr>
      </thead>
        <tbody>
          @php foreach($product_variant as $variant){ @endphp
            <tr>
              <td>{{$variant['vendor_name']}}</td>
              <td>{{$variant['option_value1']}}</td>
              @if($option_count==2)
                <td>{{$variant['option_value2']}}</td>
              @endif
              @if($option_count==3||$option_count==4||$option_count==5)
                <td>{{$variant['option_value3']}}</td>
              @endif
              @if($option_count==4||$option_count==5)
                <td>{{$variant['option_value4']}}</td>
              @endif
              @if($option_count==5)
                <td>{{$variant['option_value5']}}</td>
              @endif
              <td>{{$variant['sku']}}</td>
              <td>
                <div class="form-group">
                  <input type="text" class="form-control base_price" readonly value="{{$variant['base_price']}}">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input type="text" class="form-control retail_price" readonly value="{{$variant['retail_price']}}">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input type="text" class="form-control" readonly value="{{$variant['minimum_selling_price']}}">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input type="text" class="form-control stock_qty" readonly value="{{$variant['stock_quantity']}}">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <input type="text" class="form-control" readonly value="{{$variant['display_order']}}">
                </div>
              </td>
              <td>
                <div class="form-group">
                  <select class="form-control display_variant select2bs4" name="variant[display][]" disabled>
                    <option @if($variant['display_variant']==0) selected="selected" @endif value="0" selected>No</option>
                    <option @if($variant['display_variant']==1) selected="selected" @endif value="1">Yes</option>
                  </select>
                </div>
              </td>
            </tr>
          @php } @endphp
        </tbody>
      </table>
    @php } @endphp


  <style type="text/css">
    
    .list td{text-align: center;}
    #variantList{width: 100%}
    #variantList .input-box{width: 100px}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">
      $('.summernote').summernote('disable');
    </script>
  @endpush

@endsection