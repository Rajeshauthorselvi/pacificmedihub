@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Stock-In-Transit</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item active">Stock-In-Transit</li>
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
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('stock-in-transit.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                  <h3 class="card-title">Edit</h3>
              </div>
              <div class="card-body">
                  {{ Form::model($purchase,['method' => 'PATCH', 'route' =>['stock-in-transit.update',$purchase->id]]) }}
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Date</label>
                          {!! Form::text('purchase_date', null,['class'=>'form-control','readonly'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Purchase Order Number</label>
                          {!! Form::text('purchase_order_number',null,['class'=>'form-control','readonly'=>true]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('purchase_status',$order_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Vendor *</label>
                          {!! Form::select('vendor_id',$vendors, null,['class'=>'form-control','style'=>'pointer-events:none','readonly'=>true]) !!}
                        </div>
                    </div>
                  <div class="product-sec col-sm-12">
                    <label>Order Items</label>
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Product (Code-Name)</th>
                            <th>Options</th>
                            <th>Qty Ordered</th>
                            <th>Qty Recevied</th>
                            <th>Issue Qty</th>
                            <th>Reason</th>
                            {{-- <th></th> --}}
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($purchase_products as $products)
                            <tr>
                              <td>{{ $products->product->name }}</td>
                              <td>{{ $products->optionvalue->option_value }}</td>
                              <td>{{ $products->quantity }}</td>
                              <td>
                                  <input type='text' name='quantity_received[{{ $products->product->id }}][{{ $products->optionvalue->option_id }}][{{ $products->optionvalue->id }}]' value='{{ $products->qty_received }}' class='form-control quantity'>
                              </td>
                              <td>
                                  <input type='text' name='issued_quantity[{{ $products->product->id }}][{{ $products->optionvalue->option_id }}][{{ $products->optionvalue->id }}]' value='{{ $products->issue_quantity }}' class='form-control'>
                              </td>
                              <td>
                                  <input type='text' name='reason[{{ $products->product->id }}][{{ $products->optionvalue->option_id }}][{{ $products->optionvalue->id }}]' value='{{ $products->reason }}' class='form-control'>
                              </td>
                    {{--           <td>
                                <a href="javascript:void(0)" class="btn btn-danger" title="remove">
                                  <i class="fa fa-trash"></i>
                                </a>
                              </td> --}}
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                  </div>
                  <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('stock_notes', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('stock-in-transit.index') }}" class="btn  reset-btn">
                        Cancel
                      </a>
                      <button class="btn save-btn" type="submit">
                        Submit
                      </button>

                    </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @push('custom-scripts')
    <script type="text/javascript">
      //Validate Number
      function validateNum(e , field) {
        var val = field.value;
        var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
        var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
        if (re.test(val)) {

        } else {
          val = re1.exec(val);
          if (val) {
            field.value = val[0];
          } else {
            field.value = "";
          }
        }
      }
      $(function() {
        $('.validateTxt').keydown(function (e) {
          if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault();
          } else {
            var key = e.keyCode;
            if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
              e.preventDefault();
            }
          }
        });
      });
    </script>
  @endpush
  <style type="text/css">
    .date-sec .col-sm-4, .tax-sec .col-sm-4 , .payment-note-sec .col-sm-4 {
      float: left;
    }
    .product-sec .col-sm-8,.product-sec .col-sm-4{
      float: left;
    }
  </style>  
@endsection