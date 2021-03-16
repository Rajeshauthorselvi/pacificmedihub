@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Stock Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">View Stock</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    <!-- Main content -->
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('verify-stock.show',[$order_id])}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">View Stock</h3>
              </div>
              <div class="card-body">
                <form action="{{route('purchase.store')}}" method="post" class="purchase-form">
                  @csrf
                  <div class="date-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Date *</label>
                        {!! Form::text('purchase_date', date('d-m-Y'),['class'=>'form-control','readonly'=>true]) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Purchase Code *</label>
                        {!! Form::text('purchase_order_number',$purchase_code,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Status *</label>
                        {!! Form::select('purchase_status',$order_status, 1,['class'=>'form-control','disabled']) !!}
                        {!! Form::hidden('purchase_status',1,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="order-item-sec">
                    <div class="container my-4">
                      <div class="table-responsive">
                        <table class="table">
                          <thead class="heading-top">
                            
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Product Name</th>
                              <th scope="col"></th>
                            </tr>
                          </thead>
                          <tbody class="parent_tbody">
                            @foreach ($purchase_products as $product)
                              <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                <td class="expand-button"></td>
                                  <td>{{ $product['product_name'] }}</td>
                              </tr>
                              <tr class="hide-table-padding">
                                <td></td>
                                <td colspan="5">
                                  <div id="collapse{{ $product['product_id'] }}" class="collapse in p-3">
                                    <table class="table table-bordered" style="width: 100%">
                                      <thead>
                                        <tr>
                                          @foreach ($product['options'] as $option)
                                            <th>{{ $option }}</th>
                                          @endforeach
                                          <th>Available Quantity</th>
                                          <th>Ordered Quantity</th>
                                          <th>Remaining Needed</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $total_amount=$total_quantity=$final_price=0 ?>
                                        {{-- {{ dd($product['product_variant'],$product) }} --}}
                                        @foreach($product['product_variant'] as $key=>$variant)
                                        <?php $option_count=$product['option_count']; ?>
                                          
                                          <input type="hidden" name="variant[row_id][]" value="{{$variant['variant_id']}}">
                                          <input type="hidden" name="variant[product_id][]" value="{{ $product['product_id'] }}" class="product_id">
                                          <tr class="parent_tr">
                
                <input type="hidden" name="variant[variant_id][]" value="{{$variant['variant_id']}}">
                <td>
                  <div class="form-group">
                    <input type="hidden" name="variant[option_id1][]" value="{{$variant['option_id1']}}">
                    <input type="hidden" name="variant[option_value_id1][]" value="{{$variant['option_value_id1']}}">
                    {{$variant['option_value1']}}
                  </div>
                </td>
                @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                  <td>
                    <div class="form-group">
                      <input type="hidden" name="variant[option_id2][]" value="{{$variant['option_id2']}}">
                      <input type="hidden" name="variant[option_value_id2][]" value="{{$variant['option_value_id2']}}">
                      {{$variant['option_value2']}}
                    </div>
                  </td>
                @endif
                @if($option_count==3||$option_count==4||$option_count==5)
                  <td>
                    <div class="form-group">
                      <input type="hidden" name="variant[option_id3][]" value="{{$variant['option_id3']}}">
                      <input type="hidden" name="variant[option_value_id3][]" value="{{$variant['option_value_id3']}}">
                      {{$variant['option_value3']}}
                    </div>
                  </td>
                @endif
                @if($option_count==4||$option_count==5)
                  <td>
                    <div class="form-group">
                      <input type="hidden" name="variant[option_id4][]" value="{{$variant['option_id4']}}">
                      <input type="hidden" name="variant[option_value_id4][]" value="{{$variant['option_value_id4']}}">
                      {{$variant['option_value4']}}
                    </div>
                  </td>
                @endif
                @if($option_count==5)
                  <td>
                    <div class="form-group">
                      <input type="hidden" name="variant[option_id5][]" value="{{$variant['option_id5']}}">
                      <input type="hidden" name="variant[option_value_id5][]" value="{{$variant['option_value_id5']}}">
                      {{$variant['option_value5']}}
                    </div>
                  </td>
                @endif
              <?php 
                    $order_quantity=\App\Models\OrderProducts::where('order_id',$order_id)
                                          ->where('product_id',$product['product_id'])
                                          ->where('product_variation_id',$variant['variant_id'])
                                          ->value('quantity');

                  

              ?>
                <td>{{ $variant['stock_quantity'] }}</td>
                <td>{{ $order_quantity }}</td>
                <td>{{ $order_quantity-$variant['stock_quantity'] }}</td>
              </tr>
                                        @endforeach
                                    
                                      </tbody>
                                    </table>
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
        </div>
      </div>
    </section>
  </div>

  <style type="text/css">
    .form-group{display:flex;}
  </style>
  @push('custom-scripts')

  @endpush
@endsection