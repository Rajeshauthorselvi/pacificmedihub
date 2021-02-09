@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List Products</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">List Products</li>
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 action-controllers ">
            <div class="col-sm-6 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
            </div>
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('products.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
            </a>
            <a href="{{ url('admin/product-import') }}" class="btn btn-success">
              <i class="fa fa-upload"></i> Import Products
            </a>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Products</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Image</th><th>Name</th><th>Code</th><th>SKU</th><th>Category</th><th>Stock</th>
                        <th>Base Price</th><th>Retail Price</th><th>Minimum Selling Price</th><th>Published</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($products as $product)
                        <tr>
                          <td><input type="checkbox" value="{{ $product['id'] }}" name="product-ids"></td>
                           <?php 
                            if(!empty($product['image'])){$img_url="theme/images/products/main/".$product['image'];} 
                            else{$img_url="theme/images/no_image.jpg";}
                          ?>
                          <td><img class="brand-img" style="width:50%;height:auto;" src="{{ asset($img_url)}}"></td>
                          <td>{{$product['name']}}</td>
                          <td>{{$product['code']}}</td>
                          <td>{{$product['sku']}}</td>
                          <td>{{$product['category']}}</td>
                          <td>{{$product['stock']}}</td>
                          <td>{{$product['base_price']}}</td>
                          <td>{{$product['retail_price']}}</td>
                          <td>{{$product['minimum_price']}}</td>
                          <?php
                            if($product['published']==1){$published = "fa-check";}
                            else{$published = "fa-ban";}
                          ?>
                          <td><i class="fas {{$published}}"></i></td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="#"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                <a href="{{route('products.edit',$product['id'])}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                <a href="#"><li class="dropdown-item">
                                  <form method="POST" action="{{ route('products.destroy',$product['id']) }}">@csrf 
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete this item?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                  </form>
                                </li></a>
                              </ul>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  @push('custom-scripts')
  <script type="text/javascript">
    $('.select-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('input:checkbox').prop('checked',true);
        }
        else{
          $('input:checkbox').prop('checked',false);
        }
    });

    $('.delete-all').click(function(event) {
      var checkedNum = $('input[name="product-ids"]:checked').length;
      if (checkedNum==0) {
        alert('Please select product');
      }
      else{
        if (confirm('Are you sure want to delete?')) {
          $('input[name="product-ids"]:checked').each(function () {
            var current_val=$(this).val();
            $.ajax({
              url: "{{ url('admin/products/') }}/"+current_val,
              type: 'DELETE',
              data:{
                "_token": $("meta[name='csrf-token']").attr("content")
              }
            })
            .done(function() {
              location.reload(); 
            })
            .fail(function() {
              console.log("Ajax Error :-");
            });
          });
        }
      }
    });
  </script>
  @endpush
@endsection