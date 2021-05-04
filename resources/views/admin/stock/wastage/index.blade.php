@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Wastage</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Wastage</li>
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
              <a class="btn add-new" href="{{route('wastage.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <ul class="nav nav-tabs flex-nowrap">
                  <li class="nav-item">
                    <a href="{{ route('wastage.index') }}" class="nav-link active" title="Vendor Wastage List"><i class="fas fa-people-carry"></i> &nbsp;Wastage</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('customer-wastage.index') }}" class="nav-link" title="Customer Wastage List"><i class="fas fa-users"></i> &nbsp;Customer Wastage</a>
                  </li>
                </ul>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                      	<th>Date</th>
                        <th>Reference Number</th>
                        <th>Created By</th>
                        <th>Note</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($wastages as $wastage)
                          <tr>
                            <td><input type="checkbox" value="{{ $wastage['id'] }}" name="wastage-ids"></td>
                            <td>{{ $wastage['date'] }}</td>
                            <td>{{ $wastage['reference_number'] }}</td>
                            <td>{!! $wastage['created_by'] !!}</td>
                            <td>{!! $wastage['notes'] !!}</td>
                            <td>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu">
                                    <a href="{{route('wastage.show',$wastage['id'])}}"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                    <a href="#"><li class="dropdown-item">
                                      <form method="POST" action="{{ route('wastage.destroy',$wastage['id']) }}">@csrf 
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
  <style type="text/css">
    .pull-left{width:80%}
    .nav.nav-tabs.flex-nowrap {border: none;}
    .nav-tabs .nav-item{margin:0 3px;width:21%;text-align:center;}
    .nav-tabs .nav-item .nav-link{border: none;border-radius: 0;background: #ebeff5;}
    .nav-tabs .nav-item .nav-link.active {background: #02abbf;color: #fff;}
  </style>
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
        var checkedNum = $('input[name="wastage-ids"]:checked').length;
        if (checkedNum==0) {
          alert('Please select wastage');
        }
        else{
          if (confirm('Are you sure want to delete?')) {
            $('input[name="wastage-ids"]:checked').each(function () {
              var current_val=$(this).val();
              $.ajax({
                url: "{{ url('admin/wastage/') }}/"+current_val,
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