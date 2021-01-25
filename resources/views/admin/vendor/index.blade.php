@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">List Vendors</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">List Vendors</li>
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
          <div class="col-md-12 action-controllers">
            <a class="btn add-new" href="{{route('vendor.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
            </a>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Vendors</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Vendor Name</th><th>Email</th><th>Mobile No</th><th>Total Purchase</th><th>Total Purchase Amount</th><th>Due Amount</th><th>Status</th><th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($vendors as $vendor)
                        <tr>
                          <td>{{$vendor->name}}</td>
                          <td>{{$vendor->email}}</td>
                          <td>{{$vendor->contact_number}}</td>
                         
                          <td>{{ \App\Models\Vendor::TotalVendorOrder($vendor->id) }}</td> 
                          <td>{{ \App\Models\Vendor::TotalVendorSales($vendor->id) }}</td> 
                          <td>{{ \App\Models\Vendor::DueAmount($vendor->id) }}</td> 
                          <?php 
                            if($vendor->status==0) $status = "Not Approved";
                            elseif($vendor->status==1) $status = "Approved";
                          ?>
                          <td>{{$status}}</td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu" style="">
                                <a href="#"><li class="dropdown-item"><i class="far fa-eye"></i>&nbsp;&nbsp;View</li></a>
                                <a href="{{route('vendor.edit',$vendor->id)}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                <a href="{{ route('vendor-products.index',['vendor_id'=>$vendor->id]) }}"><li class="dropdown-item"><i class="far fa-file-alt"></i>&nbsp;&nbsp;List Products</li></a>
                                <a href="#"><li class="dropdown-item">
                                  <form method="POST" action="{{ route('vendor.destroy',$vendor->id) }}">@csrf 
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
@endsection