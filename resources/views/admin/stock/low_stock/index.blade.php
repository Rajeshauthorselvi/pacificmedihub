@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Low Stock Quantity</h1>
          </div><!-- /.col -->
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Low Stock Quantity</li>
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
            <div class="col-sm-4 pull-right">
              <label>Filter By: </label>
              {{ Form::select('vendor_ids', $all_vendors,null,['class'=>'form-control vendors']) }}
            </div>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Low Stock Quantity</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all select-check" disabled></th>
                      	<th>Product name</th>
                        <th>SKU</th>
                        <th>Stock Quantity</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($stock_details as $product_name=>$low_stocks)
                          @foreach ($low_stocks as $low_stock)
                          <?php $vendor_name=\App\Models\Vendor::find($low_stock->vendor_id); ?>
                            <tr>
                              <td>
                                <input type="checkbox" class="select-check" value="{{ $low_stock->id }}" disabled>
                                <span class='hidden' style="display: none;" >{{ $vendor_name->name }}</span>
                              </td>
                              <td>{{ $product_name }}</td>
                              <td>{{ $low_stock->sku }}</td>
                              <td>{{ $low_stock->stock_quantity }}</td>
                              <td>
                                <a href="{{ route('low-stock-purchase.create',['product_id'=>$low_stock->product_id,'product_variant_id'=>$low_stock->product_variant_id,'vendor_id'=>$low_stock->vendor_id]) }}" class="btn btn-primary">
                                  <i class="fa fa-plus"></i> Create Purchase Order
                                </a>
                              </td>
                            </tr>
                          @endforeach
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

</style>
  @push('custom-scripts')
    <script type="text/javascript">
      var oTable=$("#data-table").DataTable({
             dom: 'lrtip'
          });

      $(document).on('change', '.vendors', function(event) {

        if ($(this).val()!="") {
          
            $('.select-check').removeAttr('disabled');
          var selectedValue = $(".vendors option:selected").text();
          oTable.column(0).search(selectedValue).draw();   
        }
        else{
          $('.select-check').attr('disabled',true);
        }
      });
    </script>
  @endpush
@endsection