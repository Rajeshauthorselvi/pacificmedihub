@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock List</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">List Purchase</li>
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
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Stock List</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <td>Product Name</td>
                        <th>Product Code</th>
                        <th>Category</th>
                        <th>Total Stock</th>
                        <th>View Stock</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                          <td>{{ $product['name'] }}</td>
                          <td>{{ $product['code'] }}</td>
                          <td>{{ $product['category'] }}</td>
                          <td>{{ $product['stock'] }}</td>
                          <td class="text-center">
                            <a href="javascript:void(0)" class="btn btn-primary stock-list" product-id="{{ $product['id'] }}">
                              <i class="fa fa-eye"></i>
                            </a>
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
  {{-- Quantity List --}}
    <div class="modal fade" id="show_products" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Quantity List</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"></div>
      </div>
    </div>
  </div>
  {{-- Quantity List --}}

  <style type="text/css">
    .form-group{display:flex;}
    .disabled{pointer-events: none;opacity: 0.5;}
  </style>

  @push('custom-scripts')
    <script type="text/javascript">


      $(document).on('click', '.stock-list', function(event) {
        event.preventDefault();
        var product_id=$(this).attr('product-id');
        $.ajax({
          url: '{{ url('admin/stock-list') }}/'+product_id,
          type: 'GET',
          data: {product_id: product_id}
        })
        .done(function(response) {
          $('#show_products .modal-body').html(response);
          $('#show_products').modal('show');
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
         
      });

  </script>
  @endpush
@endsection