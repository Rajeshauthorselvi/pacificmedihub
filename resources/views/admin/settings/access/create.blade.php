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
                                <input type="checkbox" class="product-create-all" id="productReadCreateAll">
                                <label for="productReadCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="product-update-all" id="productUpdateWriteAll">
                                <label for="productUpdateWriteAll"></label>
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

                                <input type="checkbox" name="product_sec[products][read]" class="product-read" id="productListRead"
                                >
                                <label for="productListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[products][create]" class="product-create" id="productListReadWrite">
                                <label for="productListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[products][update]" class="product-update" id="productListUpdate">
                                <label for="productListUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[products][delete]" class="product-delete" id="productListDelete">
                                <label for="productListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td class="name">Import Products</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[import][read]" class="product-read" id="productImportRead">
                                <label for="productImportRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[import][create]" class="product-create" id="productImportReadWrite">
                                <label for="productImportReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>-</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td class="name">Categories</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[category][read]" class="product-read" id="productCategoryRead">
                                <label for="productCategoryRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[category][create]" class="product-create" id="productCategoryCreate">
                                <label for="productCategoryCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[category][update]" class="product-update" id="productCategoryUpdate">
                                <label for="productCategoryUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[category][delete]" class="product-delete" id="productCategoryDelete">
                                <label for="productCategoryDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td class="name">Options</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[option][read]" class="product-read" id="productOptionRead">
                                <label for="productOptionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[option][create]" class="product-create" id="productOptionReadWrite">
                                <label for="productOptionReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[option][update]" class="product-update" id="productOptionUpdate">
                                <label for="productOptionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[option][delete]" class="product-delete" id="productOptionDelete">
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
                                <input type="checkbox" name="product_sec[option_value][read]" class="product-read" id="productOptionValueRead">
                                <label for="productOptionValueRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[option_value][create]" class="product-create" id="productOptionValueReadWrite">
                                <label for="productOptionValueReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[option_value][update]" class="product-update" id="productOptionValueUpdate">
                                <label for="productOptionValueUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[option_value][delete]" class="product-delete" id="productOptionValueDelete">
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
                                <input type="checkbox" name="product_sec[brands][read]" class="product-read" id="productBrandRead">
                                <label for="productBrandRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[brands][create]" class="product-create" id="productBrandCreate">
                                <label for="productBrandCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[brands][update]" class="product-update" id="productBrandUpdate">
                                <label for="productBrandUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product_sec[brands][delete][]" class="product-delete" id="productBrandDelete">
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
                                <input type="checkbox" name="purchase[purchase][read]" class="purchase-read" id="purchaseListRead">
                                <label for="purchaseListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase[purchase][create]" class="purchase-create" id="purchaseCreate">
                                <label for="purchaseCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase[purchase][update]" class="purchase-update" id="purchaseUpdate">
                                <label for="purchaseUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase[purchase][delete]" class="purchase-delete" id="purchaseDelete">
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
                                <input type="checkbox" class="stock-create-all" id="stockCreateAll">
                                <label for="stockCreateAll"></label>
                              </div>all)
                          </th>
                          <th>
                            Update ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="stock-update-all" id="stockUpdateAll">
                                <label for="stockUpdateAll"></label>
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
                                <input type="checkbox" name="stock[stock_transist_vendor][read]" class="stock-read" id="stockRead">
                                <label for="stockRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[stock_transist_vendor][update]" class="stock-update" id="stockReadWrite" >
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
                                <input type="checkbox" name="stock[stock_transist_customer][read]" class="stock-read" id="stockRead">
                                <label for="stockRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>-</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[stock_transist_customer][update]" class="stock-update" id="stockReadWrite" >
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
                                <input type="checkbox" name="stock[return][read]" class="stock-read" id="returnListRead">
                                <label for="returnListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[return][create]" class="stock-create" id="returnCreate">
                                <label for="returnCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[return][update]" class="stock-update" id="returnUpdate">
                                <label for="returnUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[return][delete]" class="stock-delete" id="returnListDelete">
                                <label for="returnListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr></tr>
                        <tr>
                          <td>4</td>
                          <td class="name">Wastage</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[wastage][read]" class="stock-read" id="wastageRead">
                                <label for="wastageRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[wastage][create]" class="stock-create" id="wastageCreate">
                                <label for="wastageCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[wastage][update]" class="stock-update" id="wastageUpdate">
                                <label for="wastageUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock[wastage][delete]" class="stock-delete" id="wastageDelete">
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
                            Read/Write ( <div class="icheck-info d-inline">
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
                                <input type="checkbox" name="rfq[rfq][read]" class="rfq-read" id="rfqListRead">
                                <label for="rfqListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq[rfq][create]" class="rfq-create" id="rfqCreate">
                                <label for="rfqCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq[rfq][update]" class="rfq-update" id="rfqUpdate">
                                <label for="rfqUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq[rfq][delete]" class="rfq-delete" id="rfqDelete">
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
                                <input type="checkbox" name="order[order][read]" class="orders-read" id="ordersRead">
                                <label for="ordersRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="order[order][create]" class="orders-create" id="ordersCreate">
                                <label for="ordersCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="order[order][update]" class="orders-update" id="ordersUpdate">
                                <label for="ordersUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="order[order][delete]" class="orders-delete" id="ordersDelete">
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
                                <input type="checkbox" name="customer[customer][read]" class="customer-read" id="customerRead">
                                <label for="customerRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer[customer][create]" class="customer-create" id="customerCreate">
                                <label for="customerCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer[customer][update]" class="customer-update" id="customerUpdate">
                                <label for="customerUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer[customer][delete]" class="customer-delete" id="customerDelete">
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
                                <input type="checkbox" name="vendor[vendor][read]" class="vendor-read" id="vendorRead">
                                <label for="vendorRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor[vendor][create]" class="vendor-create" id="vendorCreate">
                                <label for="vendorCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor[vendor][update]" class="vendor-update" id="vendorUpdate">
                                <label for="vendorUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor[vendor][delete]" class="vendor-delete" id="vendorDelete">
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
                                <input type="checkbox" name="employee[employee][read]" class="employee-read" id="employeeRead">
                                <label for="employeeRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[employee][create]" class="employee-create" id="employeeCreate">
                                <label for="employeeCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[employee][update]" class="employee-update" id="employeeUpdate">
                                <label for="employeeUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[employee][delete]" class="employee-delete" id="employeeDelete">
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
                                <input type="checkbox" name="employee[salary][read]" class="employee-read" id="empSalaryRead">
                                <label for="empSalaryRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[salary][create]" class="employee-create" id="empSalaryCreate">
                                <label for="empSalaryCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[salary][update]" class="employee-update" id="empSalaryUpdate">
                                <label for="empSalaryUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[salary][delete]" class="employee-delete" id="empSalaryDelete">
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
                                <input type="checkbox" name="employee[emp_commission][read]" class="employee-read" id="empCommissionRead">
                                <label for="empCommissionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[emp_commission][create]" class="employee-create" id="empCommissionCreate">
                                <label for="empCommissionCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[emp_commission][update]" class="employee-update" id="empCommissionUpdate">
                                <label for="empCommissionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[emp_commission][delete]" class="employee-delete" id="empCommissionDelete">
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
                                <input type="checkbox" name="employee[department][read]" class="employee-read" id="empDepartmentRead">
                                <label for="empDepartmentRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[department][create]" class="employee-create" id="empDepartmentCreate">
                                <label for="empDepartmentCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[department][update]" class="employee-update" id="empDepartmentUpdate">
                                <label for="empDepartmentUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee[department][delete]" class="employee-delete" id="empDepartmentDelete">
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
                                <input type="checkbox" name="commission[commission][read]" class="commission-read" id="commissionRead">
                                <label for="commissionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission[commission][create]" class="commission-create" id="commissionCreate">
                                <label for="commissionCreate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission[commission][update]" class="commission-update" id="commissionUpdate">
                                <label for="commissionUpdate"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission[commission][delete]" class="commission-delete" id="commissionDelete">
                                <label for="commissionDelete"></label>
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