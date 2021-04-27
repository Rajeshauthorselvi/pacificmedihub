@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Rejected Customer</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('customers.index')}}">Customers</a></li>
              <li class="breadcrumb-item active">Rejected Customer</li>
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
      <div class="container-fluid customer">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <ul class="nav nav-tabs flex-nowrap">
                  <li class="nav-item">
                    <a href="{{ route('customers.index') }}" class="nav-link" title="Active Customer List"><i class="fas fa-users"></i> &nbsp;Active Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('new.customer') }}" class="nav-link" title="New Customer Requests List"><i class="fas fa-user-plus"></i> &nbsp;New Requests</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('reject.customer') }}" class="nav-link active" title="Rejected Customer List"><i class="fas fa-user-times"></i> &nbsp;Rejected Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('blocked.customer') }}" class="nav-link" title="Blocked Customer List"><i class="fas fa-user-lock"></i> &nbsp;Blocked Customers</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('interested.customers') }}" class="nav-link" title="Interested Customer List"><i class="fas fa-user-tie"></i> &nbsp;Interested Customers</a>
                  </li>
                </ul>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>Customer Code</th>
                        <th>Company Name</th>
                        <th>Parent Company</th>
                        <th>Email</th>
                        <th>Mobile No</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($rejected_customers as $r_customer)
                        <tr>
                          <th><a href="{{route('customers.show',$r_customer->id)}}">{{$r_customer->customer_no}}</a></th>
                          <td>{{$r_customer->name}}</td>
                          <td>
                            <?php
                              if($r_customer->parent_company==0){
                                $parent = '-';
                              }else{
                                $parent = $r_customer->ParentCompany($r_customer->parent_company);
                              }
                             ?>
                            {{ isset($parent)?$parent:'-' }}
                          </td>
                          <td>{{ $r_customer->email }}</td>
                          <td>{{ $r_customer->contact_number }}</td>
                          <td>
                            @if(Auth::check() || Auth::guard('employee')->user()->isAuthorized('customer','delete'))
                              <a href="#">
                                <form method="POST" action="{{ route('customers.destroy',$r_customer->id) }}">@csrf 
                                  <input name="_method" type="hidden" value="DELETE">
                                  <input name="from" type="hidden" value="reject_list">
                                  <button class="btn btn-danger" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                </form>
                              </a>
                            @endif
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
    .customer .pull-left{width:80%}
    .customer .nav.nav-tabs.flex-nowrap {border: none;}
    .customer .nav-tabs .nav-item{margin:0 3px;width:21%;text-align:center;}
    .customer .nav-tabs .nav-item .nav-link{border: none;border-radius: 0;background: #ebeff5;}
    .customer .nav-tabs .nav-item .nav-link.active {background: #02abbf;color: #fff;}
  </style>
 @endsection