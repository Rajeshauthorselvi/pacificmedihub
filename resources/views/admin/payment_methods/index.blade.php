@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Payment Methods</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Payment Methods</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
     <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 action-controllers ">
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('payment_setting','delete'))
            <div class="col-sm-6 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
            </div>
            @endif
            @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('payment_setting','create'))
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('payment_method.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
            @endif
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Payment Methods</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>S.No</th>
                        <th>Payment Methods</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $s_no=1; ?>
                        @foreach ($all_payments as $payments)
                          <tr>
                            <td><input type="checkbox" value="{{ $payments->id }}" name="payments-ids"></td>
                            <td>{{ $s_no }}</td>
                            <td>{{ $payments->payment_method }}</td>
                            <td class="text-center">
                              @if ((Auth::check() || Auth::guard('employee')->user()->isAuthorized('payment_setting','update')) || (Auth::check() || Auth::guard('employee')->user()->isAuthorized('payment_setting','delete')))
                                <div class="input-group-prepend">
                                  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu">
                                    @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('payment_setting','update'))
                                    <a href="{{route('payment_method.edit',$payments->id)}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                    @endif
                                    @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('payment_setting','delete'))
                                    <a href="javascript:void(0)"><li class="dropdown-item">
                                      <form method="POST" action="{{ route('payment_method.destroy',$payments->id) }}">@csrf 
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete this item?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                      </form>
                                    </li></a>
                                    @endif
                                  </ul>
                                </div>
                              @else
                                -
                              @endif
                          </td>
                          </tr>
                          <?php $s_no++ ?>
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
    	.order-sec .col-sm-3 {
			float: left;
		}
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
        var checkedNum = $('input[name="payments-ids"]:checked').length;
        if (checkedNum==0) {
          alert('Please select payment');
        }
        else{
          if (confirm('Are you sure want to delete?')) {
            $('input[name="payments-ids"]:checked').each(function () {
              var current_val=$(this).val();
              $.ajax({
                url: "{{ url('admin/payment_method/') }}/"+current_val,
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