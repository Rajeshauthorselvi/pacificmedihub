@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Verify Stock</h1>
          </div><!-- /.col -->
          
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Verify Stock</li>
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
                <h3 class="card-title">All Verify Stock</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="data-table" class="table table-bordered">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all select-check" disabled></th>
                      	<th>Product name</th>
                        <th>Current Stock</th>
                        <th>Ordered Stock</th>
                        <th>Remaining Needed</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($product_data as $datas)
                          <tr>
                            <td><input type="checkbox"></td>
                            <td width="30%">{{ $datas['product_name'] }}</td>
                            <td>{{ $datas['total_avail_quantity'] }}</td>
                            <td>{{ $datas['order_quantity'] }}</td>
                            <td>{{ $datas['order_quantity']-$datas['total_avail_quantity'] }}</td>
                            <td>
                              @if (Auth::guard('employee')->check())
                                <?php $label_text="View Stock"; ?>
                              @else
                                <?php $label_text="Verify Stock"; ?>
                              @endif
                              <a href="{{ route('verify-stock.edit',[$datas['order_id'],'product_id'=>$datas['product_id']]) }}" class="btn btn-primary">
                                 <i class="fa fa-eye"></i> {{ $label_text }}
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