@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">View Return</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item"><a href="{{route('return.index')}}">Return</a></li>
              <li class="breadcrumb-item active">View Return</li>
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
          <a href="{{route('return.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Return Details</h3>
              </div>
              <div class="card-body">
                {{Form::model($purchase_detail,['method'=>'PATCH','route' =>['return.update',$purchase_detail->id]]) }}
                  <div class="col-sm-12 product-sec">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Date</label>
                        <input class="form-control" readonly value="{{date('d-m-Y',strtotime($purchase->created_at))}}">
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Vendor Name</label>
                        <input class="form-control" value="{{ $vendor_name }}" readonly>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Return Status</label>
                        {!! Form::select('return_status',$status,$purchase_detail->return_status,['class'=>'form-control read-only select2bs4','readonly']) !!}
                      </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-sm-12">
                    <table class="table table-bordered">
                      <tbody>
                        @foreach ($purchase_products as $product)
                          <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                            <td class="expand-button"></td>
                              <?php
                                $total_based_products=\App\Models\PurchaseProducts::TotalDatas($purchase->id,$product['product_id']);
                              ?>
                            <td>{{ $product['product_name'] }}</td>
                          </tr>
                          <tr class="hide-table-padding">
                            <td></td>
                            <td colspan="5">
                              <div id="collapse{{ $product['product_id'] }}" class="collapse in p-3">
                                <table class="table table-bordered" style="width: 100%">
                                  <thead>
                                    <th>No</th>
                                      @foreach ($product['options'] as $option)
                                        <th>{{ $option }}</th>
                                      @endforeach
                                      <th>Damage Quantity</th>
                                      <th>Return Quantity</th>
                                    </thead>
                                    <tbody>
                                      <?php $total_amount=$total_quantity=$final_price=0 ?>
                                      <?php $s_no=1; ?>
                                      @foreach($product['product_variant'] as $key=>$variant)
                                        <?php 
                                          $option_count=$product['option_count'];
                                          $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase_id);
                                          $product_price=\App\Models\PurchaseProducts::ProductPrice($product['product_id'],$variant['variant_id']);
                                        ?>
                                        <tr class="parent_tr">
                                          <td>{{ $key+1 }}</td>
                                          <td>{{$variant['option_value1']}}</td>
                                          @if($option_count==2||$option_count==3||$option_count==4||$option_count==5)
                                            <td>{{$variant['option_value2']}}</td>
                                          @endif
                                          @if($option_count==3||$option_count==4||$option_count==5)
                                            <td>{{$variant['option_value3']}}</td>
                                          @endif
                                          @if($option_count==4||$option_count==5)
                                            <td>{{$variant['option_value4']}}</td>
                                          @endif
                                          @if($option_count==5)
                                            <td> {{$variant['option_value5']}} </td>
                                          @endif
                                          <td>
                                            <?php 
                                            $damage_data=isset($damage_quantity[$variant['variant_id']])?$damage_quantity[$variant['variant_id']]:0;
                                            $return_data=isset($return_quantity[$variant['variant_id']])?$return_quantity[$variant['variant_id']]:0;
                                            ?>
                                            <input type="text" name="damage_quantity[{{ $variation_details['id'] }}]" class="form-control damaged_quantity" value="{{ $damage_data }}" readonly>
                                            <input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                                            <input type="hidden" name="product_id[{{ $variation_details['id'] }}]" value="{{ $variant['product_id'] }}">
                                          </td>
                                          <td>
                                            <input type="text" name="return_quantity[{{ $variation_details['id'] }}]" class="form-control return_quantity" value="{{ $return_data }}" readonly>
                                          </td>
                                        </tr>
                                      @endforeach
                                    </table>
                                  </div>
                                </td>
                              </tr>
                              <?php $s_no++; ?>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                      <div class="col-sm-12 notes-sec">
                        @if(isset ($purchase_detail->return_notes))
                          <div class="notes">
                            <label>Return Note</label>
                            {!! $purchase_detail->return_notes !!}
                          </div>
                        @endif
                        @if(isset ($purchase_detail->staff_notes))
                          <div class="notes" style="margin:0">
                            <label>Staff Note</label>
                            {!! $purchase_detail->staff_notes !!}
                          </div>
                        @endif
                      </div>
                      <div class="clearfix"></div><br><br>
                    {!! Form::close() !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
  <style type="text/css">
    .product-sec .col-sm-4{float: left;}
    .notes{background-color: #f6f6f6;padding: 15px;border: 1px solid #ddd;margin-right: 10px;width:49%;display:inline-block;}

  </style>

  @push('custom-scripts')
    <script type="text/javascript">
      $(function ($) {
        $('.read-only.select2bs4').select2({
          disabled: true
        });
      });
    </script>
  @endpush
@endsection