@extends('admin.layouts.master')
@section('main_container')
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
                      {!! Form::text('role_name',null,['class'=>'form-control','id'=>'roleName','placeholder'=>'Role Name']) !!}
                    </div>
                  </div>
                  
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="product-read-write-all" id="productReadWriteAll">
                                <label for="productReadWriteAll"></label>
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
                          <input type="hidden" name="product[menu][]" value="List Products">
                          <input type="hidden" name="product[route][]" value="products.index">
                          <td class="name">List Products</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[product_list][read][]" class="product-read" id="productListRead">
                                <label for="productListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[product_list][read_write][]" class="product-read-write" id="productListReadWrite">
                                <label for="productListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[product_list][delete][]" class="product-delete" id="productListDelete">
                                <label for="productListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="product[menu][]" value="Add Product">
                          <input type="hidden" name="product[route][]" value="products.create">
                          <td class="name">Add Product</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[add_product][read][]" class="product-read" id="productAddRead">
                                <label for="productAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[add_product][read_write][]" class="product-read-write" id="productAddReadWrite">
                                <label for="productAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[add_product][delete][]" class="product-delete" id="productAddDelete">
                                <label for="productAddDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <input type="hidden" name="product[menu][]" value="Import Products">
                          <input type="hidden" name="product[route][]" value="product_import.index">
                          <td class="name">Import Products</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read][product_import.index][]" class="product-read" id="productImportRead">
                                <label for="productImportRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read_write][product_import.index][]" class="product-read-write" id="productImportReadWrite">
                                <label for="productImportReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[delete][product_import.index][]" class="product-delete" id="productImportDelete">
                                <label for="productImportDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <input type="hidden" name="product[menu][]" value="Categories">
                          <input type="hidden" name="product[route][]" value="categories.index">
                          <td class="name">Categories</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read][categories.index][]" class="product-read" id="productCategoryRead">
                                <label for="productCategoryRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read_write][categories.index][]" class="product-read-write" id="productCategoryReadWrite">
                                <label for="productCategoryReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[delete][categories.index][]" class="product-delete" id="productCategoryDelete">
                                <label for="productCategoryDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <input type="hidden" name="product[menu][]" value="Options">
                          <input type="hidden" name="product[route][]" value="options.index">
                          <td class="name">Options</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read][options.index][]" class="product-read" id="productOptionRead">
                                <label for="productOptionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read_write][options.index][]" class="product-read-write" id="productOptionReadWrite">
                                <label for="productOptionReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[delete][options.index][]" class="product-delete" id="productOptionDelete">
                                <label for="productOptionDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>6</td>
                          <input type="hidden" name="product[menu][]" value="Option Value">
                          <input type="hidden" name="product[route][]" value="option_values.index">
                          <td class="name">Option Value</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read][option_values.index][]" class="product-read" id="productOptionValueRead">
                                <label for="productOptionValueRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read_write][option_values.index][]" class="product-read-write" id="productOptionValueReadWrite">
                                <label for="productOptionValueReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[delete][option_values.index][]" class="product-delete" id="productOptionValueDelete">
                                <label for="productOptionValueDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>7</td>
                          <input type="hidden" name="product[menu][]" value="Brands">
                          <input type="hidden" name="product[route][]" value="brands.index">
                          <td class="name">Brands</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read][brands.index][]" class="product-read" id="productBrandRead">
                                <label for="productBrandRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[read_write][brands.index][]" class="product-read-write" id="productBrandReadWrite">
                                <label for="productBrandReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="product[delete][brands.index][]" class="product-delete" id="productBrandDelete">
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="purchase-read-write-all" id="purchaseReadWriteAll">
                                <label for="purchaseReadWriteAll"></label>
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
                          <input type="hidden" name="purchase[menu][]" value="List Purchase">
                          <input type="hidden" name="purchase[route][]" value="purchase.index">
                          <td class="name">List Purchase</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase_list_read" class="purchase-read" id="purchaseListRead" @if(old('purchase_list_read')=='on') checked @endif>
                                <label for="purchaseListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase_list_read_write" class="purchase-read-write" id="purchaseListReadWrite" @if(old('purchase_list_read_write')=='on') checked @endif>
                                <label for="purchaseListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase_list_delete" class="purchase-delete" id="purchaseListDelete" @if(old('purchase_list_delete')=='on') checked @endif>
                                <label for="purchaseListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="purchase[menu][]" value="Add Purchase">
                          <input type="hidden" name="purchase[route][]" value="purchase.create">
                          <td class="name">Add Purchase</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase_add_read" class="purchase-read" id="purchaseAddRead" @if(old('purchase_add_read')=='on') checked @endif>
                                <label for="purchaseAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase_add_read_write" class="purchase-read-write" id="purchaseAddReadWrite" @if(old('purchase_add_read_write')=='on') checked @endif>
                                <label for="purchaseAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="purchase_add_delete" class="purchase-delete" id="purchaseAddDelete" @if(old('purchase_add_delete')=='on') checked @endif>
                                <label for="purchaseAddDelete"></label>
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="stock-read-write-all" id="stockReadWriteAll">
                                <label for="stockReadWriteAll"></label>
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
                          <input type="hidden" name="stock[menu][]" value="Stock-In-Transit">
                          <input type="hidden" name="stock[route][]" value="stock-in-transit.index">
                          <td class="name">Stock-In-Transit</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock_read" class="stock-read" id="stockRead" @if(old('stock_read')=='on') checked @endif>
                                <label for="stockRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock_read_write" class="stock-read-write" id="stockReadWrite" @if(old('stock_read_write')=='on') checked @endif>
                                <label for="stockReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="stock_delete" class="stock-delete" id="stockDelete" @if(old('stock_delete')=='on') checked @endif>
                                <label for="stockDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="stock[menu][]" value="List Return">
                          <input type="hidden" name="stock[route][]" value="return.index">
                          <td class="name">List Return</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="return_list_read" class="stock-read" id="returnListRead" @if(old('return_list_read')=='on') checked @endif>
                                <label for="returnListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="return_list_read_write" class="stock-read-write" id="returnListReadWrite" @if(old('return_list_read_write')=='on') checked @endif>
                                <label for="returnListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="return_list_delete" class="stock-delete" id="returnListDelete" @if(old('return_list_delete')=='on') checked @endif>
                                <label for="returnListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <input type="hidden" name="stock[menu][]" value="Add Return">
                          <input type="hidden" name="stock[route][]" value="return.create">
                          <td class="name">Add Return</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="return_add_read" class="stock-read" id="returnAddRead" @if(old('return_add_read')=='on') checked @endif>
                                <label for="returnAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="return_add_read_write" class="stock-read-write" id="returnAddReadWrite" @if(old('return_add_read_write')=='on') checked @endif>
                                <label for="returnAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="return_add_delete" class="stock-delete" id="purchaseAddDelete" @if(old('return_add_delete')=='on') checked @endif>
                                <label for="purchaseAddDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <input type="hidden" name="stock[menu][]" value="Wastage">
                          <input type="hidden" name="stock[route][]" value="wastage.index">
                          <td class="name">Wastage</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="wastage_read" class="stock-read" id="wastageRead" @if(old('stock_add_read')=='on') checked @endif>
                                <label for="wastageRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="wastage_read_write" class="stock-read-write" id="wastageReadWrite" @if(old('wastage_read_write')=='on') checked @endif>
                                <label for="wastageReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="wastage_delete" class="stock-delete" id="wastageDelete" @if(old('wastage_delete')=='on') checked @endif>
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="rfq-read-write-all" id="rfqReadWriteAll">
                                <label for="rfqReadWriteAll"></label>
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
                          <input type="hidden" name="rfq[menu][]" value="List RFQ">
                          <input type="hidden" name="rfq[route][]" value="rfq.index">
                          <td class="name">List RFQ</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq_list_read" class="rfq-read" id="rfqListRead" @if(old('rfq_list_read')=='on') checked @endif>
                                <label for="rfqListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq_list_read_write" class="rfq-read-write" id="rfqListReadWrite" @if(old('rfq_list_read_write')=='on') checked @endif>
                                <label for="rfqListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq_list_delete" class="rfq-delete" id="rfqListDelete" @if(old('rfq_list_delete')=='on') checked @endif>
                                <label for="rfqListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="rfq[menu][]" value="Add RFQ">
                          <input type="hidden" name="rfq[route][]" value="rfq.create">
                          <td class="name">Add RFQ</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq_add_read" class="rfq-read" id="rfqAddRead" @if(old('rfq_add_read')=='on') checked @endif>
                                <label for="rfqAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq_add_read_write" class="rfq-read-write" id="rfqAddReadWrite" @if(old('purchase_add_read_write')=='on') checked @endif>
                                <label for="rfqAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="rfq_add_delete" class="rfq-delete" id="rfqAddDelete" @if(old('purchase_add_delete')=='on') checked @endif>
                                <label for="rfqAddDelete"></label>
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="orders-read-write-all" id="ordersReadWriteAll">
                                <label for="ordersReadWriteAll"></label>
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
                          <input type="hidden" name="order[menu][]" value="List Order">
                          <input type="hidden" name="order[route][]" value="orders.index">
                          <td class="name">List Orders</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="orders_list_read" class="orders-read" id="ordersListRead" @if(old('orders_list_read')=='on') checked @endif>
                                <label for="ordersListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="orders_list_read_write" class="orders-read-write" id="ordersListReadWrite" @if(old('orders_list_read_write')=='on') checked @endif>
                                <label for="ordersListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="orders_list_delete" class="orders-delete" id="ordersListDelete" @if(old('orders_list_delete')=='on') checked @endif>
                                <label for="ordersListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="order[menu][]" value="Add Order">
                          <input type="hidden" name="order[route][]" value="orders.create">
                          <td class="name">Add Orders</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="orders_add_read" class="orders-read" id="ordersAddRead" @if(old('orders_add_read')=='on') checked @endif>
                                <label for="ordersAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="orders_add_read_write" class="orders-read-write" id="ordersAddReadWrite" @if(old('orders_add_read_write')=='on') checked @endif>
                                <label for="ordersAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="orders_add_delete" class="orders-delete" id="ordersAddDelete" @if(old('orders_add_delete')=='on') checked @endif>
                                <label for="ordersAddDelete"></label>
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="customer-read-write-all" id="customerReadWriteAll">
                                <label for="customerReadWriteAll"></label>
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
                          <input type="hidden" name="customer[menu][]" value="List Customer">
                          <input type="hidden" name="customer[route][]" value="customers.index">
                          <td class="name">List Cutomers</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer_list_read" class="customer-read" id="customerListRead" @if(old('customer_list_read')=='on') checked @endif>
                                <label for="customerListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer_list_read_write" class="customer-read-write" id="customerListReadWrite" @if(old('customer_list_read_write')=='on') checked @endif>
                                <label for="customerListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer_list_delete" class="customer-delete" id="customerListDelete" @if(old('customer_list_delete')=='on') checked @endif>
                                <label for="customerListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="customer[menu][]" value="Add Customer">
                          <input type="hidden" name="customer[route][]" value="customers.create">
                          <td class="name">Add Customer</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer_add_read" class="customer-read" id="customerAddRead" @if(old('customer_add_read')=='on') checked @endif>
                                <label for="customerAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer_add_read_write" class="customer-read-write" id="customerAddReadWrite" @if(old('customer_add_read_write')=='on') checked @endif>
                                <label for="customerAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="customer_add_delete" class="customer-delete" id="customerAddDelete" @if(old('customer_add_delete')=='on') checked @endif>
                                <label for="customerAddDelete"></label>
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="vendor-read-write-all" id="vendorReadWriteAll">
                                <label for="vendorReadWriteAll"></label>
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
                          <input type="hidden" name="vendor[menu][]" value="List Vendor">
                          <input type="hidden" name="vendor[route][]" value="vendor.index">
                          <td class="name">List Vendors</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor_list_read" class="vendor-read" id="vendorListRead" @if(old('vendor_list_read')=='on') checked @endif>
                                <label for="vendorListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor_list_read_write" class="vendor-read-write" id="vendorListReadWrite" @if(old('vendor_list_read_write')=='on') checked @endif>
                                <label for="vendorListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor_list_delete" class="vendor-delete" id="vendorListDelete" @if(old('vendor_list_delete')=='on') checked @endif>
                                <label for="vendorListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="vendor[menu][]" value="List Vendor">
                          <input type="hidden" name="vendor[route][]" value="vendor.create">
                          <td class="name">Add Vendor</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor_add_read" class="vendor-read" id="vendorAddRead" @if(old('vendor_add_read')=='on') checked @endif>
                                <label for="vendorAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor_add_read_write" class="vendor-read-write" id="vendorAddReadWrite" @if(old('vendor_add_read_write')=='on') checked @endif>
                                <label for="vendorAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="vendor_add_delete" class="vendor-delete" id="vendorAddDelete" @if(old('vendor_add_delete')=='on') checked @endif>
                                <label for="vendorAddDelete"></label>
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="employee-read-write-all" id="employeeReadWriteAll">
                                <label for="employeeReadWriteAll"></label>
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
                          <input type="hidden" name="employee[menu][]" value="List Employee">
                          <input type="hidden" name="employee[route][]" value="employees.index">
                          <td class="name">List Employee</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee_list_read" class="employee-read" id="employeeListRead" @if(old('employee_list_read')=='on') checked @endif>
                                <label for="employeeListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee_list_read_write" class="employee-read-write" id="employeeListReadWrite" @if(old('employee_list_read_write')=='on') checked @endif>
                                <label for="purchaseListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee_list_delete" class="employee-delete" id="employeeListDelete" @if(old('employee_list_delete')=='on') checked @endif>
                                <label for="employeeListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="employee[menu][]" value="Add Employee">
                          <input type="hidden" name="employee[route][]" value="employees.create">
                          <td class="name">Add Employee</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee_add_read" class="employee-read" id="employeeAddRead" @if(old('employee_add_read')=='on') checked @endif>
                                <label for="employeeAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee_add_read_write" class="employee-read-write" id="employeeAddReadWrite" @if(old('employee_add_read_write')=='on') checked @endif>
                                <label for="employeeAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="employee_add_delete" class="employee-delete" id="employeeAddDelete" @if(old('employee_add_delete')=='on') checked @endif>
                                <label for="employeeAddDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <input type="hidden" name="employee[menu][]" value="Salary">
                          <input type="hidden" name="employee[route][]" value="salary.list">
                          <td class="name">Salary</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_salary_read" class="employee-read" id="empSalaryRead" @if(old('emp_salary_read')=='on') checked @endif>
                                <label for="empSalaryRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_salary_read_write" class="employee-read-write" id="empSalaryReadWrite" @if(old('emp_salary_read_write')=='on') checked @endif>
                                <label for="empSalaryReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_salary_delete" class="employee-delete" id="empSalaryDelete" @if(old('emp_salary_delete')=='on') checked @endif>
                                <label for="empSalaryDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <input type="hidden" name="employee[menu][]" value="Commission">
                          <input type="hidden" name="employee[route][]" value="commission.list">
                          <td class="name">Commission</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_commission_read" class="employee-read" id="empCommissionRead" @if(old('emp_commission_read')=='on') checked @endif>
                                <label for="empCommissionRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_commission_read_write" class="employee-read-write" id="empCommissionReadWrite" @if(old('emp_commission_read_write')=='on') checked @endif>
                                <label for="empCommissionReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_commission_delete" class="employee-delete" id="empCommissionDelete" @if(old('emp_commission_delete')=='on') checked @endif>
                                <label for="empCommissionDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <input type="hidden" name="employee[menu][]" value="Department">
                          <input type="hidden" name="employee[route][]" value="departments.index">
                          <td class="name">Department</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_department_read" class="employee-read" id="empDepartmentRead" @if(old('emp_department_read')=='on') checked @endif>
                                <label for="empDepartmentRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_department_read_write" class="employee-read-write" id="empDepartmentReadWrite" @if(old('emp_department_read_write')=='on') checked @endif>
                                <label for="empDepartmentReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="emp_department_delete" class="employee-delete" id="empDepartmentDelete" @if(old('emp_department_delete')=='on') checked @endif>
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
                            Read/Write ( <div class="icheck-info d-inline">
                                <input type="checkbox" class="commission-read-write-all" id="commissionReadWriteAll">
                                <label for="commissionReadWriteAll"></label>
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
                          <input type="hidden" name="commission[menu][]" value="List Commission">
                          <input type="hidden" name="commission[route][]" value="comission_value.index">
                          <td class="name">List Commission</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission_list_read" class="commission-read" id="commissionListRead" @if(old('commission_list_read')=='on') checked @endif>
                                <label for="commissionListRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission_list_read_write" class="commission-read-write" id="commissionListReadWrite" @if(old('commission_list_read_write')=='on') checked @endif>
                                <label for="commissionListReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission_list_delete" class="commission-delete" id="commissionListDelete" @if(old('commission_list_delete')=='on') checked @endif>
                                <label for="commissionListDelete"></label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <input type="hidden" name="commission[menu][]" value="Add Commission">
                          <input type="hidden" name="commission[route][]" value="comission_value.create">
                          <td class="name">Add Commission</td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission_add_read" class="commission-read" id="commissionAddRead" @if(old('commission_add_read')=='on') checked @endif>
                                <label for="commissionAddRead"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission_add_read_write" class="commission-read-write" id="commissionAddReadWrite" @if(old('commission_add_read_write')=='on') checked @endif>
                                <label for="commissionAddReadWrite"></label>
                              </div>
                            </div>
                          </td>
                          <td>
                            <div class="form-group clearfix">
                              <div class="icheck-info d-inline">
                                <input type="checkbox" name="commission_add_delete" class="commission-delete" id="commissionAddDelete" @if(old('commission_add_delete')=='on') checked @endif>
                                <label for="commissionAddDelete"></label>
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

  @push('custom-scripts')
  <script type="text/javascript">
    $(document).ready(function() {
      //Products
      $('.product-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.product-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.product-read').prop('checked',false);
        }
      });
      $('.product-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.product-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.product-read-write').prop('checked',false);
        }
      });
      $('.product-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.product-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.product-delete').prop('checked',false);
        }
      });

      //Purchase
      $('.purchase-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.purchase-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.purchase-read').prop('checked',false);
        }
      });
      $('.purchase-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.purchase-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.purchase-read-write').prop('checked',false);
        }
      });
      $('.purchase-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.purchase-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.purchase-delete').prop('checked',false);
        }
      });

      //Stock
      $('.stock-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.stock-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.stock-read').prop('checked',false);
        }
      });
      $('.stock-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.stock-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.stock-read-write').prop('checked',false);
        }
      });
      $('.stock-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.stock-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.stock-delete').prop('checked',false);
        }
      });

      //RFQ
      $('.rfq-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.rfq-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.rfq-read').prop('checked',false);
        }
      });
      $('.rfq-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.rfq-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.rfq-read-write').prop('checked',false);
        }
      });
      $('.rfq-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.rfq-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.rfq-delete').prop('checked',false);
        }
      });

      //Orders
      $('.orders-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.orders-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.orders-read').prop('checked',false);
        }
      });
      $('.orders-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.orders-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.orders-read-write').prop('checked',false);
        }
      });
      $('.orders-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.orders-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.orders-delete').prop('checked',false);
        }
      });

      //Customer
      $('.customer-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.customer-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.customer-read').prop('checked',false);
        }
      });
      $('.customer-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.customer-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.customer-read-write').prop('checked',false);
        }
      });
      $('.customer-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.customer-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.customer-delete').prop('checked',false);
        }
      });

      //vendor
      $('.vendor-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.vendor-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.vendor-read').prop('checked',false);
        }
      });
      $('.vendor-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.vendor-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.vendor-read-write').prop('checked',false);
        }
      });
      $('.vendor-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.vendor-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.vendor-delete').prop('checked',false);
        }
      });

      //Employee
      $('.employee-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.employee-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.employee-read').prop('checked',false);
        }
      });
      $('.employee-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.employee-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.employee-read-write').prop('checked',false);
        }
      });
      $('.employee-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.employee-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.employee-delete').prop('checked',false);
        }
      });

      //Commission
      $('.commission-read-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.commission-read').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.commission-read').prop('checked',false);
        }
      });
      $('.commission-read-write-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.commission-read-write').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.commission-read-write').prop('checked',false);
        }
      });
      $('.commission-delete-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('.commission-delete').prop('checked',true);
        }
        if ($(this). prop("checked") == false) {
          $('.commission-delete').prop('checked',false);
        }
      });

    });
  </script>
  @endpush
  <style type="text/css">
    .role-table {width: 100%;}
    .role-table th, .role-table td {padding: 8px;border: 1px solid #eee;text-align:center;}
    .role-table td.name {text-align: left;}
  </style>
@endsection