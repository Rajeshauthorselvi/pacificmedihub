@extends('admin.layouts.master')
@section('main_container')
@push('custom-scripts') <script src="{{ asset('custom_data/js/access_control.js') }}"></script> @endpush
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Access Control</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Settings</a></li>
              <li class="breadcrumb-item active">Access Control</li>
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
          <a href="{{route('access-control.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Role</h3>
              </div>
              <div class="card-body col-md-10" style="margin:auto;">
                <form action="{{route('access-control.store')}}" method="post">
                  @csrf
                  <div class="form-group" style="display:flex;">
                    <div class="col-sm-2" style="padding-left:0">
                      <label for="roleName">Role Name *</label>
                    </div>
                    <div class="col-sm-9" style="padding:0">
                      {!! Form::text('role_name',$role->name,['class'=>'form-control','id'=>'roleName','placeholder'=>'Role Name']) !!}
                    </div>
                  </div>

                  <input type="hidden" name="role_id" value="{{ $role_id }}">
                  
                  <h4>Product</h4>
                  <div class="form-group"> 
                    <table id="productTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="product-read-all" id="productReadAll">
                                <label for="productReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="product-create-all" id="productCreateAll">
                                <label for="productCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="product-update-all" id="productUpdateAll">
                                <label for="productUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="product-delete-all" id="productDeleteAll">
                                <label for="productDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Products</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $read=(isset($permissions['product']) && $permissions['product']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[products][read]" class="product-read" id="productListRead" {{ $read }}>
                                <label for="productListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['product']) && $permissions['product']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[products][create]" class="product-create" id="productListReadWrite" {{ $create }}>
                                <label for="productListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $update=(isset($permissions['product']) && $permissions['product']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[products][update]" class="product-update" id="productListUpdate" {{ $update }}>
                                <label for="productListUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['product']) && $permissions['product']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[products][delete]" class="product-delete" id="productListDelete" {{ $delete }}>
                                <label for="productListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td class="name">Import Products</td>
                          <td>
        <?php $read=(isset($permissions['product']) && $permissions['import']['read']=='yes')?'checked':'' ?>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[import][read]" class="product-read" id="productImportRead" {{ $read }}>
                                <label for="productImportRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['product']) && $permissions['import']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[import][create]" class="product-create" id="productImportReadWrite" {{ $create }}>
                                <label for="productImportReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>-</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="name">Categories</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $read=(isset($permissions['product']) && $permissions['category']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[category][read]" class="product-read" id="productCategoryRead" {{ $read }}>
                                <label for="productCategoryRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['product']) && $permissions['category']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[category][create]" class="product-create" id="productCategoryCreate" {{ $create }}>
                                <label for="productCategoryCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $update=(isset($permissions['product']) && $permissions['category']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[category][update]" class="product-update" id="productCategoryUpdate" {{ $update }}>
                                <label for="productCategoryUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $delete=(isset($permissions['product']) && $permissions['category']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[category][delete]" class="product-delete" id="productCategoryDelete" {{ $delete }}>
                                <label for="productCategoryDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td class="name">Options</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $read=(isset($permissions['product']) && $permissions['option']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][read]" class="product-read" id="productOptionRead" {{ $read }}>
                                <label for="productOptionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['product']) && $permissions['option']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][create]" class="product-create" id="productOptionReadWrite" {{ $create }}>
                                <label for="productOptionReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $update=(isset($permissions['product']) && $permissions['option']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][update]" class="product-update" id="productOptionUpdate" {{ $update }}>
                                <label for="productOptionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $delete=(isset($permissions['product']) && $permissions['option']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][delete]" class="product-delete" id="productOptionDelete" {{ $delete }}>
                                <label for="productOptionDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td class="name">Option Value</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $read=(isset($permissions['product']) && $permissions['option_value']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option_value][read]" class="product-read" id="productOptionValueRead" {{ $read }}>
                                <label for="productOptionValueRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['product']) && $permissions['option_value']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option_value][create]" class="product-create" id="productOptionValueReadWrite" {{ $create }}>
                                <label for="productOptionValueReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $update=(isset($permissions['product']) && $permissions['option_value']['update']=='yes')?'checked':'' ?>                                
                                <input type="checkbox" name="product_sec[option_value][update]" class="product-update" id="productOptionValueUpdate" {{ $update }}>
                                <label for="productOptionValueUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $delete=(isset($permissions['product']) && $permissions['option_value']['delete']=='yes')?'checked':'' ?> 
                                <input type="checkbox" name="product_sec[option_value][delete]" class="product-delete" id="productOptionValueDelete" {{ $delete }}>
                                <label for="productOptionValueDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td class="name">Brands</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $read=(isset($permissions['product']) && $permissions['brands']['read']=='yes')?'checked':'' ?> 
                                <input type="checkbox" name="product_sec[brands][read]" class="product-read" id="productBrandRead" {{ $read }}>
                                <label for="productBrandRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $create=(isset($permissions['product']) && $permissions['brands']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[brands][create]" class="product-create" id="productBrandCreate" {{  $create }}>
                                <label for="productBrandCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $update=(isset($permissions['product']) && $permissions['brands']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[brands][update]" class="product-update" id="productBrandUpdate" {{ $update }}>
                                <label for="productBrandUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $delete=(isset($permissions['product']) && $permissions['brands']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[brands][delete]" class="product-delete" id="productBrandDelete" {{ $delete }}>
                                <label for="productBrandDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <h4>Purchase</h4>
                  <div class="form-group"> 
                    <table id="purchaseTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="purchase-read-all" id="purchaseReadAll">
                                <label for="purchaseReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="purchase-create-all" id="purchaseReadWriteAll">
                                <label for="purchaseReadWriteAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="purchase-update-all" id="purchase-update">
                                <label for="purchase-update"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="purchase-delete-all" id="purchaseDeleteAll">
                                <label for="purchaseDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Purchase</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['purchase']) && $permissions['purchase']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="purchase[purchase][read]" class="purchase-read" id="purchaseListRead" {{ $read }}>
                                <label for="purchaseListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['purchase']) && $permissions['purchase']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="purchase[purchase][create]" class="purchase-create" id="purchaseCreate" {{ $create }}>
                                <label for="purchaseCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['purchase']) && $permissions['purchase']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="purchase[purchase][update]" class="purchase-update" id="purchaseUpdate" {{ $update }}>
                                <label for="purchaseUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['purchase']) && $permissions['purchase']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="purchase[purchase][delete]" class="purchase-delete" id="purchaseDelete" {{ $delete }}>
                                <label for="purchaseDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>

                  <h4>Stock</h4>
                  <div class="form-group"> 
                    <table id="stockTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="stock-read-all" id="stockReadAll">
                                <label for="stockReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="stock-create-all" id="stockReadWriteAll">
                                <label for="stockReadWriteAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="stock-update-all" id="stockUpdate">
                                <label for="stockUpdate"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="stock-delete-all" id="stockDeleteAll">
                                <label for="stockDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Stock-In-Transit (Vendor)</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['stock_transist_vendor']) && $permissions['stock_transist_vendor']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[stock_transist_vendor][read]" class="stock-read" id="stockReadVendor" {{ $read }}>
                                <label for="stockReadVendor"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['stock_transist_vendor']) && $permissions['stock_transist_vendor']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[stock_transist_vendor][update]" class="stock-update" id="stockReadWrite" {{ $update }}>
                                <label for="stockReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td class="name">Stock-In-Transit (Customer)</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['stock_transist_customer']) && $permissions['stock_transist_customer']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[stock_transist_customer][read]" class="stock-read" id="stockReadCustomer" {{ $read }}>
                                <label for="stockReadCustomer"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['stock_transist_customer']) && $permissions['stock_transist_customer']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[stock_transist_customer][update]" class="stock-update" id="stockReadWrite" {{ $update }}>
                                <label for="stockReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="name">Return</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['return']) && $permissions['return']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[return][read]" class="stock-read" id="returnListRead" {{ $read }}>
                                <label for="returnListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['return']) && $permissions['return']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[return][create]" class="stock-create" id="returnCreate" {{ $create }}>
                                <label for="returnCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['return']) && $permissions['return']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[return][update]" class="stock-update" id="returnUpdate" {{ $update }}>
                                <label for="returnUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['return']) && $permissions['return']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[return][delete]" class="stock-delete" id="returnListDelete" {{ $delete }}>
                                <label for="returnListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td class="name">Wastage</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['wastage']) && $permissions['wastage']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[wastage][read]" class="stock-read" id="wastageRead" {{ $read }}>
                                <label for="wastageRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['wastage']) && $permissions['wastage']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[wastage][create]" class="stock-create" id="wastageCreate" {{ $create }}>
                                <label for="wastageCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['wastage']) && $permissions['wastage']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[wastage][update]" class="stock-update" id="wastageUpdate" {{ $update }}>
                                <label for="wastageUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['wastage']) && $permissions['wastage']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[wastage][delete]" class="stock-delete" id="wastageDelete" {{ $delete }}>
                                <label for="wastageDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>

                  <h4>RFQ</h4>
                  <div class="form-group"> 
                    <table id="rfqTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="rfq-read-all" id="rfqReadAll">
                                <label for="rfqReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="rfq-create-all" id="rfqCreateAll">
                                <label for="rfqCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="rfq-update-all" id="rfqUpdateAll">
                                <label for="rfqUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="rfq-delete-all" id="rfqDeleteAll">
                                <label for="rfqDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">RFQ</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['rfq']) && $permissions['rfq']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="rfq[rfq][read]" class="rfq-read" id="rfqListRead" {{ $read }}>
                                <label for="rfqListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['rfq']) && $permissions['rfq']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="rfq[rfq][create]" class="rfq-create" id="rfqCreate" {{ $create }}>
                                <label for="rfqCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['rfq']) && $permissions['rfq']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="rfq[rfq][update]" class="rfq-update" id="rfqUpdate" {{ $update }}>
                                <label for="rfqUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['rfq']) && $permissions['rfq']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="rfq[rfq][delete]" class="rfq-delete" id="rfqDelete" {{ $delete }}>
                                <label for="rfqDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <h4>Orders</h4>
                  <div class="form-group"> 
                    <table id="ordersTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="orders-read-all" id="ordersReadAll">
                                <label for="ordersReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="orders-create-all" id="ordersCreateAll">
                                <label for="ordersCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="orders-update-all" id="ordersUpdateAll">
                                <label for="ordersUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="orders-delete-all" id="ordersDeleteAll">
                                <label for="ordersDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Orders</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['order']) && $permissions['order']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order][read]" class="orders-read" id="ordersRead" {{ $read }}>
                                <label for="ordersRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['order']) && $permissions['order']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order][create]" class="orders-create" id="ordersCreate" {{ $create }}>
                                <label for="ordersCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['order']) && $permissions['order']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order][update]" class="orders-update" id="ordersUpdate" {{ $update }}>
                                <label for="ordersUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['order']) && $permissions['order']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order][delete]" class="orders-delete" id="ordersDelete" {{ $delete }}>
                                <label for="ordersDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <h4>Customers</h4>
                  <div class="form-group"> 
                    <table id="customerTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="customer-read-all" id="customerReadAll">
                                <label for="customerReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="customer-create-all" id="customerCreateAll">
                                <label for="customerCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="customer-update-all" id="customerUpdateAll">
                                <label for="customerUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="customer-delete-all" id="customerDeleteAll">
                                <label for="customerDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Cutomers</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['customer']) && $permissions['customer']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="customer[customer][read]" class="customer-read" id="customerRead" {{ $read }}>
                                <label for="customerRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['customer']) && $permissions['customer']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="customer[customer][create]" class="customer-create" id="customerCreate" {{ $create }}>
                                <label for="customerCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['customer']) && $permissions['customer']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="customer[customer][update]" class="customer-update" id="customerUpdate" {{ $update }}>
                                <label for="customerUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['customer']) && $permissions['customer']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="customer[customer][delete]" class="customer-delete" id="customerDelete" {{ $delete }}>
                                <label for="customerDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <h4>Vendors</h4>
                  <div class="form-group"> 
                    <table id="vendorTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="vendor-read-all" id="vendorReadAll">
                                <label for="vendorReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="vendor-create-all" id="vendorCreateAll">
                                <label for="vendorCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="vendor-update-all" id="vendorUpdateAll">
                                <label for="vendorUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="vendor-delete-all" id="vendorDeleteAll">
                                <label for="vendorDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Vendors</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['vendor']) && $permissions['vendor']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor][read]" class="vendor-read" id="vendorRead" {{ $read }}>
                                <label for="vendorRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['vendor']) && $permissions['vendor']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor][create]" class="vendor-create" id="vendorCreate" {{ $create }}>
                                <label for="vendorCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['vendor']) && $permissions['vendor']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor][update]" class="vendor-update" id="vendorUpdate" {{ $update }}>
                                <label for="vendorUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['vendor']) && $permissions['vendor']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor][delete]" class="vendor-delete" id="vendorDelete" {{ $delete }}>
                                <label for="vendorDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <h4>Employees</h4>
                  <div class="form-group"> 
                    <table id="employeeTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="employee-read-all" id="employeeReadAll">
                                <label for="employeeReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="employee-create-all" id="employeeCreateAll">
                                <label for="employeeCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="employee-update-all" id="employeeUpdateAll">
                                <label for="employeeUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="employee-delete-all" id="employeeDeleteAll">
                                <label for="employeeDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Employee</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['employee']) && $permissions['employee']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[employee][read]" class="employee-read" id="employeeRead" {{ $read }}>
                                <label for="employeeRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['employee']) && $permissions['employee']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[employee][create]" class="employee-create" id="employeeCreate" {{ $create }}>
                                <label for="employeeCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['employee']) && $permissions['employee']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[employee][update]" class="employee-update" id="employeeUpdate" {{ $update }}>
                                <label for="employeeUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['employee']) && $permissions['employee']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[employee][delete]" class="employee-delete" id="employeeDelete" {{ $delete }}>
                                <label for="employeeDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td class="name">Salary</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['salary']) && $permissions['salary']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[salary][read]" class="employee-read" id="empSalaryRead" {{ $read }}>
                                <label for="empSalaryRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['salary']) && $permissions['salary']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[salary][create]" class="employee-create" id="empSalaryCreate" {{ $create }}>
                                <label for="empSalaryCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['salary']) && $permissions['salary']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[salary][update]" class="employee-update" id="empSalaryUpdate" {{ $update }}>
                                <label for="empSalaryUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['salary']) && $permissions['salary']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[salary][delete]" class="employee-delete" id="empSalaryDelete" {{ $delete }}>
                                <label for="empSalaryDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="name">Commission</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['emp_commission']) && $permissions['emp_commission']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[emp_commission][read]" class="employee-read" id="empCommissionRead" {{ $read }}>
                                <label for="empCommissionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['emp_commission']) && $permissions['emp_commission']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[emp_commission][create]" class="employee-create" id="empCommissionCreate" {{ $create }}>
                                <label for="empCommissionCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['emp_commission']) && $permissions['emp_commission']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[emp_commission][update]" class="employee-update" id="empCommissionUpdate" {{ $update }}>
                                <label for="empCommissionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['emp_commission']) && $permissions['emp_commission']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[emp_commission][delete]" class="employee-delete" id="empCommissionDelete" {{ $delete }}>
                                <label for="empCommissionDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td class="name">Department</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['department']) && $permissions['department']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[department][read]" class="employee-read" id="empDepartmentRead" {{ $read }}>
                                <label for="empDepartmentRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['department']) && $permissions['department']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[department][create]" class="employee-create" id="empDepartmentCreate" {{ $create }}>
                                <label for="empDepartmentCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['department']) && $permissions['department']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[department][update]" class="employee-update" id="empDepartmentUpdate" {{ $update }}>
                                <label for="empDepartmentUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['department']) && $permissions['department']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="employee[department][delete]" class="employee-delete" id="empDepartmentDelete" {{ $delete }}>
                                <label for="empDepartmentDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        
                      </tbody>
                    </table>
                  </div>

                  <h4>Commission</h4>
                  <div class="form-group"> 
                    <table id="commissionTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="commission-read-all" id="commissionReadAll">
                                <label for="commissionReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="commission-create-all" id="commissionCreateAll">
                                <label for="commissionCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="commission-update-all" id="commissionUpdateAll">
                                <label for="commissionUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="commission-delete-all" id="commissionDeleteAll">
                                <label for="commissionDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Commission</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['commission']) && $permissions['commission']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="commission[commission][read]" class="commission-read" id="commissionRead" {{ $read }}>
                                <label for="commissionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['commission']) && $permissions['commission']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="commission[commission][create]" class="commission-create" id="commissionCreate" {{ $create }}>
                                <label for="commissionCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['commission']) && $permissions['commission']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="commission[commission][update]" class="commission-update" id="commissionUpdate" {{ $update }}>
                                <label for="commissionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['commission']) && $permissions['commission']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="commission[commission][delete]" class="commission-delete" id="commissionDelete" {{ $delete }}>
                                <label for="commissionDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <h4>Delivery Zone</h4>
                  <div class="form-group"> 
                    <table id="commissionTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="zone-read-all" id="zoneReadAll">
                                <label for="zoneReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="zone-create-all" id="zoneCreateAll">
                                <label for="zoneCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="zone-update-all" id="zoneUpdateAll">
                                <label for="zoneUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="zone-delete-all" id="zoneDeleteAll">
                                <label for="zoneDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Delivery Zone</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['delivery_zone']) && $permissions['delivery_zone']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="zone[delivery_zone][read]" class="zone-read" id="zoneRead" {{ $read }}>
                                <label for="zoneRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['delivery_zone']) && $permissions['delivery_zone']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="zone[delivery_zone][create]" class="zone-create" id="zoneCreate" {{ $create }}>
                                <label for="zoneCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['delivery_zone']) && $permissions['delivery_zone']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="zone[delivery_zone][update]" class="zone-update" id="zoneUpdate" {{ $update }}>
                                <label for="zoneUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['delivery_zone']) && $permissions['delivery_zone']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="zone[delivery_zone][delete]" class="zone-delete" id="zoneDelete" {{ $delete }}>
                                <label for="zoneDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <div class="form-group">
                    <a href="{{ route('access-control.index') }}" class="btn reset-btn">Cancel</a>
                    <button class="btn save-btn" type="submit">Save</button>
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
    .role-table {width: 100%;}
    .role-table th, .role-table td {padding: 8px;border: 1px solid #eee;text-align:center;}
    .role-table td.name {text-align: left;}
  </style>
@endsection