@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Prefix</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Settings</li>
              <li class="breadcrumb-item">Prefix</li>
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
          <div class="col-md-12">
          	<?php $check_error_place=Session::get('error_place'); ?>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Order Code</h3>
              </div>
              <div class="card">
              	@include('admin.settings.prefix.order_no')
              </div>
            </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Purchase Code</h3>
              </div>
              <div class="card">
                @include('admin.settings.prefix.purchase_products')
              </div>
            </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">RFQ Code</h3>
              </div>
              <div class="card">
              	@include('admin.settings.prefix.rfq')
              </div>
            </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Invoice No</h3>
              </div>
              <div class="card">
              	@include('admin.settings.prefix.invoice')
              </div>
            </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Product Code</h3>
              </div>
              <div class="card">
                @include('admin.settings.prefix.product_code')
              </div>
            </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Customer Code</h3>
              </div>
              <div class="card">
              	@include('admin.settings.prefix.customer')
              </div>
            </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Vendor Code</h3>
              </div>
              <div class="card">
              	@include('admin.settings.prefix.vendor')
              </div>
            </div>
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Employee Code</h3>
              </div>
              <div class="card">
              	@include('admin.settings.prefix.employee')
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    </div>

    <style type="text/css">
    	.form-group{display:flex}
      .btn.save-btn.prefix {margin-top: 30px;}
    </style>

  @push('custom-scripts')
    <script type="text/javascript">
      $(function ($) {
        $('.read-only.select2bs4').select2({
          disabled: true
        });
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });
    </script>
  @endpush
@endsection