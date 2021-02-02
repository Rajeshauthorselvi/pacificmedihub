@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List RFQ</h1>
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
          <div class="col-md-12 action-controllers ">
            <div class="col-sm-6 text-left pull-left">
{{--               <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a> --}}
            </div>
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('rfq.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All RFQ</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Date</th>
                        <th>Order No</th>
                        <th>Customer</th>
                        <th>Sales Rep</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($rfqs as $rfq)
                        <tr>
                          <td><input type="checkbox" name=""></td>
                          <td>{{ date('m/d/Y',strtotime($rfq->created_at)) }}</td>
                          <td>{{ $rfq->order_no }}</td>
                          <td>{{ $rfq->customer->first_name }}</td>
                          <td>{{ $rfq->salesrep->emp_name }}</td>
                          <td>
                            <?php $total_quantity=\App\Models\RFQProducts::TotalDatas($rfq->id); ?>
                            {{ $total_quantity['quantity'] }}
                          </td>
                          <td>{{  $rfq->statusName->status_name  }}</td>
                          <td>
                                <div class="input-group-prepend">
                                  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu">
                                    <a href="{{route('rfq.edit',$rfq->id)}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                   {{--  <a href="#"><li class="dropdown-item">
                                      <form method="POST" action="{{ route('rfq.destroy',$rfq->id) }}">@csrf 
                                        <input name="_method" type="hidden" value="DELETE">
                                        <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                      </form>
                                    </li></a> --}}
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


@endsection