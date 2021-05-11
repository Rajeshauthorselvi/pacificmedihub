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
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['import']) && $permissions['import']['create']=='yes')?'checked':'' ?>
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
        <?php $read=(isset($permissions['category']) && $permissions['category']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[category][read]" class="product-read" id="productCategoryRead" {{ $read }}>
                                <label for="productCategoryRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['category']) && $permissions['category']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[category][create]" class="product-create" id="productCategoryCreate" {{ $create }}>
                                <label for="productCategoryCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $update=(isset($permissions['category']) && $permissions['category']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[category][update]" class="product-update" id="productCategoryUpdate" {{ $update }}>
                                <label for="productCategoryUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $delete=(isset($permissions['category']) && $permissions['category']['delete']=='yes')?'checked':'' ?>
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
        <?php $read=(isset($permissions['option']) && $permissions['option']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][read]" class="product-read" id="productOptionRead" {{ $read }}>
                                <label for="productOptionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $create=(isset($permissions['option']) && $permissions['option']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][create]" class="product-create" id="productOptionReadWrite" {{ $create }}>
                                <label for="productOptionReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $update=(isset($permissions['option']) && $permissions['option']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][update]" class="product-update" id="productOptionUpdate" {{ $update }}>
                                <label for="productOptionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
        <?php $delete=(isset($permissions['option']) && $permissions['option']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[option][delete]" class="product-delete" id="productOptionDelete" {{ $delete }}>
                                <label for="productOptionDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td class="name">Brands</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $read=(isset($permissions['option']) && $permissions['brands']['read']=='yes')?'checked':'' ?> 
                                <input type="checkbox" name="product_sec[brands][read]" class="product-read" id="productBrandRead" {{ $read }}>
                                <label for="productBrandRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $create=(isset($permissions['option']) && $permissions['brands']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[brands][create]" class="product-create" id="productBrandCreate" {{  $create }}>
                                <label for="productBrandCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $update=(isset($permissions['option']) && $permissions['brands']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="product_sec[brands][update]" class="product-update" id="productBrandUpdate" {{ $update }}>
                                <label for="productBrandUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
          <?php $delete=(isset($permissions['option']) && $permissions['brands']['delete']=='yes')?'checked':'' ?>
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
                        <tr>
                          <td>2</td>
                          <td class="name">Purchase Payment</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['purchase_payment']) && $permissions['purchase_payment']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="purchase[purchase_payment][read]" class="purchase-read" id="purchasePaymentListRead" {{ $read }}>
                                <label for="purchasePaymentListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['purchase_payment']) && $permissions['purchase_payment']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="purchase[purchase_payment][create]" class="purchase-create" id="purchasePaymentCreate" {{ $create }}>
                                <label for="purchasePaymentCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>-</td>
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
                                <input type="checkbox" name="stock[stock_transist_customer][update]" class="stock-update" id="stockCustomerWrite" {{ $update }}>
                                <label for="stockCustomerWrite"></label>
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
                          <td>4</td>
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
                        
                        <tr>
                          <td>5</td>
                          <td class="name">Low Stock</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['low_stock']) && $permissions['low_stock']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[low_stock][read]" class="stock-read" id="LowStockRead" {{ $read }}>
                                <label for="LowStockRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['low_stock']) && $permissions['low_stock']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[low_stock][update]" class="stock-update" id="LowStockUpdate" {{ $update }}>
                                <label for="LowStockUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                        </tr>
                        
                        <tr>
                          <td>6</td>
                          <td class="name">Stock List</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['stock_list']) && $permissions['stock_list']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="stock[stock_list][read]" class="stock-read" id="StockListRead" {{ $read }}>
                                <label for="StockListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>-</td>
                          <td>-</td>
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
                          <td class="name">New Orders</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['new_order']) && $permissions['new_order']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[new_order][read]" class="orders-read" id="newOrdersRead" {{ $read }}>
                                <label for="newOrdersRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['new_order']) && $permissions['new_order']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[new_order][create]" class="orders-create" id="newOrdersCreate" {{ $create }}>
                                <label for="newOrdersCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['new_order']) && $permissions['new_order']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[new_order][update]" class="orders-update" id="newOrdersUpdate" {{ $update }}>
                                <label for="newOrdersUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['new_order']) && $permissions['new_order']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[new_order][delete]" class="orders-delete" id="newOrdersDelete" {{ $delete }}>
                                <label for="newOrdersDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td class="name">Assign for Delivery</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['assign_order']) && $permissions['assign_order']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[assign_order][read]" class="orders-read" id="assignOrdersRead" {{ $read }}>
                                <label for="assignOrdersRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['assign_order']) && $permissions['assign_order']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[assign_order][update]" class="orders-update" id="assignOrdersUpdate" {{ $update }}>
                                <label for="assignOrdersUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['assign_order']) && $permissions['assign_order']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[assign_order][delete]" class="orders-delete" id="assignOrdersDelete" {{ $delete }}>
                                <label for="assignOrdersDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="name">Delivery In Progress</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['delivery_order']) && $permissions['delivery_order']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[delivery_order][read]" class="orders-read" id="deliveryOrdersRead" {{ $read }}>
                                <label for="deliveryOrdersRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['delivery_order']) && $permissions['delivery_order']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[delivery_order][update]" class="orders-update" id="deliveryOrdersUpdate" {{ $update }}>
                                <label for="deliveryOrdersUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td class="name">Completed Orders</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['completed_orders']) && $permissions['completed_orders']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[completed_orders][read]" class="orders-read" id="completedOrdersRead" {{ $read }}>
                                <label for="completedOrdersRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['completed_orders']) && $permissions['completed_orders']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[completed_orders][delete]" class="orders-delete" id="completedOrdersDelete" {{ $delete }}>
                                <label for="completedOrdersDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td class="name">Cancelled/Missed Orders</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['cancelled_order']) && $permissions['cancelled_order']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[cancelled_order][read]" class="orders-read" id="cancelledordersRead" {{ $read }}>
                                <label for="cancelledordersRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['cancelled_order']) && $permissions['cancelled_order']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[cancelled_order][update]" class="orders-update" id="cancelledordersUpdate" {{ $update }}>
                                <label for="cancelledordersUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['cancelled_order']) && $permissions['cancelled_order']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[cancelled_order][delete]" class="orders-delete" id="cancelledordersDelete" {{ $delete }}>
                                <label for="cancelledordersDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        
                        <tr>
                          <td>2</td>
                          <td class="name">Order Payment</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['order_payment']) && $permissions['order_payment']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order_payment][read]" class="orders-read" id="ordersPaymentRead" {{ $read }}>
                                <label for="ordersPaymentRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['order_payment']) && $permissions['order_payment']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order_payment][create]" class="orders-create" id="ordersPaymentCreate" {{ $create }}> 
                                <label for="ordersPaymentCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['order_payment']) && $permissions['order_payment']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order_payment][update]" class="orders-update" id="ordersPaymentUpdate" {{ $update }}>
                                <label for="ordersPaymentUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['order_payment']) && $permissions['order_payment']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="order[order_payment][delete]" class="orders-delete" id="ordersPaymentDelete" {{ $delete }}>
                                <label for="ordersPaymentDelete"></label>
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
                        <tr>
                          <td>2</td>
                          <td class="name">Vendor Products</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['vendor_products']) && $permissions['vendor_products']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor_products][read]" class="vendor-read" id="vendorProductRead" {{ $read }}>
                                <label for="vendorProductRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['vendor_products']) && $permissions['vendor_products']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor_products][create]" class="vendor-create" id="vendorProductCreate" {{ $create }}>
                                <label for="vendorProductCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['vendor_products']) && $permissions['vendor_products']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor_products][update]" class="vendor-update" id="vendorProductUpdate">
                                <label for="vendorProductUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['vendor_products']) && $permissions['vendor_products']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="vendor[vendor_products][delete]" class="vendor-delete" id="vendorProductDelete" {{ $delete }}>
                                <label for="vendorProductDelete"></label>
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

                  <h4>Static Pages</h4>
                  <div class="form-group"> 
                    <table id="vendorTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="static-read-all" id="staticPageReadAll">
                                <label for="staticPageReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="static-create-all" id="staticPageCreateAll">
                                <label for="staticPageCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="static-update-all" id="staticPageUpdateAll">
                                <label for="staticPageUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="static-delete-all" id="staticPageDeleteAll">
                                <label for="staticPageDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">Pages</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['pages']) && $permissions['pages']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[pages][read]" class="static-read" id="PageRead" {{ $read }}>
                                <label for="PageRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['pages']) && $permissions['pages']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[pages][create]" class="static-create" id="PageCreate" {{ $create }}>
                                <label for="PageCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['pages']) && $permissions['pages']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[pages][update]" class="static-update" id="PageUpdate" {{ $update }}>
                                <label for="PageUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['pages']) && $permissions['pages']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[pages][delete]" class="static-delete" id="PageDelete" {{ $delete }}>
                                <label for="PageDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td class="name">Sliders</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['static']) && $permissions['static']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[static][read]" class="static-read" id="PageStaticRead" {{ $read }}>
                                <label for="PageStaticRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['static']) && $permissions['static']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[static][create]" class="static-create" id="PageStaticCreate" {{ $create }}>
                                <label for="PageStaticCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['static']) && $permissions['static']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[static][update]" class="static-update" id="PageStaticUpdate" {{ $update }}>
                                <label for="PageStaticUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['static']) && $permissions['static']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[static][delete]" class="static-delete" id="PageStaticDelete" {{ $delete }}>
                                <label for="PageStaticDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="name">Features</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['features']) && $permissions['features']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[features][read]" class="static-read" id="PageFeatureRead" {{ $read }}>
                                <label for="PageFeatureRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['features']) && $permissions['features']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[features][create]" class="static-create" id="PageFeatureCreate" {{ $create }}>
                                <label for="PageFeatureCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['features']) && $permissions['features']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[features][update]" class="static-update" id="PageFeatureUpdate" {{ $update }}>
                                <label for="PageFeatureUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['features']) && $permissions['features']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="static_page[features][delete]" class="static-delete" id="PageFeatureDelete" {{ $delete }}>
                                <label for="PageFeatureDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <h4>Settings</h4>
                  <div class="form-group"> 
                    <table id="commissionTable" class="role-table">
                      <thead>
                        <tr>
                          <th>SI No</th>
                          <th>Menu Name</th>
                          <th>
                            Read ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="setting-read-all" id="settingReadAll">
                                <label for="settingReadAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Create ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="setting-create-all" id="settingCreateAll">
                                <label for="settingCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="setting-update-all" id="settingUpdateAll">
                                <label for="settingUpdateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Delete ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="setting-delete-all" id="settingDeleteAll">
                                <label for="settingDeleteAll"></label>
                              </div>all)
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td class="name">General Settings</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['general_settings']) && $permissions['general_settings']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[general_settings][read]" class="setting-read" id="settingRead" {{ $read }}>
                                <label for="settingRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['general_settings']) && $permissions['general_settings']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[general_settings][create]" class="setting-create" id="settingCreate" {{ $create }}>
                                <label for="settingCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['general_settings']) && $permissions['general_settings']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[general_settings][update]" class="setting-update" id="settingUpdate" {{ $update }}>
                                <label for="settingUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['general_settings']) && $permissions['general_settings']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[general_settings][delete]" class="setting-delete" id="settingDelete" {{ $delete }}>
                                <label for="settingDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td class="name">Access Control</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['access_control']) && $permissions['access_control']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[access_control][read]" class="setting-read" id="accessRead" {{ $read }}>
                                <label for="accessRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['access_control']) && $permissions['access_control']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[access_control][create]" class="setting-create" id="accessCreate" {{ $create }}>
                                <label for="accessCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['access_control']) && $permissions['access_control']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[access_control][update]" class="setting-update" id="accessUpdate" {{ $update }}>
                                <label for="accessUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['access_control']) && $permissions['access_control']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[access_control][delete]" class="setting-delete" id="accessDelete" {{ $delete }}>
                                <label for="accessDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="name">Department</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['department_setting']) && $permissions['department_setting']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[department_setting][read]" class="setting-read" id="empDepartmentRead" {{ $read }}>
                                <label for="empDepartmentRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['department_setting']) && $permissions['department_setting']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[department_setting][create]" class="setting-create" id="empDepartmentCreate" {{ $create }}>
                                <label for="empDepartmentCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['department_setting']) && $permissions['department_setting']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[department_setting][update]" class="setting-update" id="empDepartmentUpdate" {{ $update }}>
                                <label for="empDepartmentUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['department_setting']) && $permissions['department_setting']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[department_setting][delete]" class="setting-delete" id="empDepartmentDelete" {{ $delete }}>
                                <label for="empDepartmentDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td class="name">Commission</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['commission_setting']) && $permissions['commission_setting']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[commission_setting][read]" class="setting-read" id="commissionRead" {{ $read }}>
                                <label for="commissionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['commission_setting']) && $permissions['commission_setting']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[commission_setting][create]" class="setting-create" id="commissionCreate" {{ $create }}>
                                <label for="commissionCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['commission_setting']) && $permissions['commission_setting']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[commission_setting][update]" class="setting-update" id="commissionUpdate" {{ $update }}>
                                <label for="commissionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['commission_setting']) && $permissions['commission_setting']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[commission_setting][delete]" class="setting-delete" id="commissionDelete" {{ $delete }}>
                                <label for="commissionDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td class="name">Prefix</td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['prefix_setting']) && $permissions['prefix_setting']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[prefix_setting][create]" class="setting-create" id="prefixCreate" {{ $create }}>
                                <label for="prefixCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>-</td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <td class="name">Order Settings</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['order_setting']) && $permissions['order_setting']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[order_setting][read]" class="setting-read" id="orderSettingRead" {{ $read }}>
                                <label for="orderSettingRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['order_setting']) && $permissions['order_setting']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[order_setting][create]" class="setting-create" id="orderSettingCreate" {{ $create }}>
                                <label for="orderSettingCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['order_setting']) && $permissions['order_setting']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[order_setting][update]" class="setting-update" id="orderSettingUpdate" {{ $update }}>
                                <label for="orderSettingUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['order_setting']) && $permissions['order_setting']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[order_setting][delete]" class="setting-delete" id="orderSettingDelete" {{ $delete }}>
                                <label for="orderSettingDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <td class="name">Tax</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['tax_setting']) && $permissions['tax_setting']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[tax_setting][read]" class="setting-read" id="taxSettingRead" {{ $read }}>
                                <label for="taxSettingRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['tax_setting']) && $permissions['tax_setting']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[tax_setting][create]" class="setting-create" id="taxSettingCreate" {{ $create }}>
                                <label for="taxSettingCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['tax_setting']) && $permissions['tax_setting']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[tax_setting][update]" class="setting-update" id="taxSettingUpdate" {{ $update }}>
                                <label for="taxSettingUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['tax_setting']) && $permissions['tax_setting']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[tax_setting][delete]" class="setting-delete" id="taxSettingDelete" {{ $delete }}>
                                <label for="taxSettingDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>8</td>
                          <td class="name">Currencies</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['currency_setting']) && $permissions['currency_setting']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[currency_setting][read]" class="setting-read" id="currencySettingRead" {{ $read }}>
                                <label for="currencySettingRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['currency_setting']) && $permissions['currency_setting']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[currency_setting][create]" class="setting-create" id="currencySettingCreate" {{ $create }}>
                                <label for="currencySettingCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['currency_setting']) && $permissions['currency_setting']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[currency_setting][update]" class="setting-update" id="currencySettingUpdate" {{ $update }}>
                                <label for="currencySettingUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['currency_setting']) && $permissions['currency_setting']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[currency_setting][delete]" class="setting-delete" id="currencySettingDelete" {{ $delete }}>
                                <label for="currencySettingDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>9</td>
                          <td class="name">Payment Methods</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $read=(isset($permissions['payment_setting']) && $permissions['payment_setting']['read']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[payment_setting][read]" class="setting-read" id="paymentSettingRead" {{ $read }}>
                                <label for="paymentSettingRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $create=(isset($permissions['payment_setting']) && $permissions['payment_setting']['create']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[payment_setting][create]" class="setting-create" id="paymentSettingCreate" {{ $create }}>
                                <label for="paymentSettingCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $update=(isset($permissions['payment_setting']) && $permissions['payment_setting']['update']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[payment_setting][update]" class="setting-update" id="paymentSettingUpdate" {{ $update }}>
                                <label for="paymentSettingUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
<?php $delete=(isset($permissions['payment_setting']) && $permissions['payment_setting']['delete']=='yes')?'checked':'' ?>
                                <input type="checkbox" name="settings[payment_setting][delete]" class="setting-delete" id="paymentSettingDelete" {{ $delete }}>
                                <label for="paymentSettingDelete"></label>
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