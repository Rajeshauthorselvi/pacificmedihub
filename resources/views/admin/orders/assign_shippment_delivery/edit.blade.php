@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route($back_route)}}">Order List</a></li>
              <li class="breadcrumb-item active">Edit Order</li>
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
          <a href="{{route($back_route)}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Order</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  {!! Form::model($order,['method' => 'PATCH', 'route' =>[$update_route,$order->id]]) !!}
                    <div class="date-sec">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="date">Date *</label>
                          <input type="text" class="form-control" name="created_at" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                        <div class="col-sm-4">
                          <label for="order_no">Order Code *</label>
                          {!! Form::text('order_no',null,['class'=>'form-control','readonly']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="status">Order Status *</label>
                          {!! Form::select('order_status',$order_status, $order->order_status,['class'=>'form-control no-search ','id'=>'order_status','readonly','style'=>'pointer-events:none']) !!}
                        </div>
                      </div>
                    </div>

                    <div class="product-sec">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="customer_id">Customer *</label>
                            {!! Form::select('customer_id',$customers,  null,['class'=>'form-control','id'=>'customer','disabled']) !!}
                            {!! Form::hidden('customer_id',$order->customer_id,['class'=>'form-control','readonly']) !!}
                          <span class="text-danger customer" style="display:none;">Customer is required. Please Select</span>
                        </div>
                        <div class="col-sm-4">
                          <label for="sales_rep_id">Sales Rep *</label>
                          {!! Form::select('sales_rep_id',$sales_rep,null,['class'=>'form-control','id'=>'sales_rep_id','disabled']) !!}
                          <span class="text-danger sales_rep" style="display:none;">Sales Rep is required. Please Select</span>
                        </div>
                            <div class="col-sm-4">
                              <?php
                              if (isset($check_quantity[0]) && $check_quantity[0]=="yes")
                                  $style="disabled";
                              else
                                  $style="";
                                 ?>
                              <label for="sales_rep_id">Delivery Status</label>
                              {!! Form::select('delivery_status',$delivery_status,null,['class'=>'form-control','id'=>'delivery_status',$style]) !!}
                              <span class="text-danger sales_rep" style="display:none;">Delivery Status is required. Please Select</span>
                            </div>

                      </div>
                    </div>
                    <div class="delivery-date">
                      <div class="form-group">
                      <?php $approximate_delivery_date=isset($order->approximate_delivery_date)?date('d-m-Y',strtotime($order->approximate_delivery_date)):date('d-m-Y'); ?>
                        <div class="col-sm-4">
                          <label for="sales_rep_id">Delivery Person</label>
                          {!! Form::select('delivery_person_id',$delivery_persons,null,['class'=>'form-control','id'=>'delivery_person_id']) !!}
                        </div>
                          <div class="col-sm-4">
                            {!! Form::label('doj', 'Delivery Date') !!}
                            <input type="text" name="delivery_date" class="form-control date-picker" value="{{ $approximate_delivery_date }}" />
                            </div>

                          </div>
                        </div>

                        </div>

                    <div class="product-sec">
                      <div class="container my-4">
                        <div class="table-responsive">
                          <table class="table">
                            <thead class="heading-top">
                              <?php $total_products=\App\Models\OrderProducts::TotalDatas($order->id); ?>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Name</th>
                                <th scope="col"></th>

                                <th scope="col"></th>
                                <th>
                                    Total Quantity:&nbsp;
                                    <span class="all_quantity">{{ $total_products->quantity }}</span>
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($product_datas as $product)

                                <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                                  <td class="expand-button"></td>
                                  <?php
                                    $total_based_products=\App\Models\OrderProducts::TotalDatas($order->id,$product['product_id']);
                                  ?>
                                  <td>{{ $product['product_name'] }}</td>
                                  <th></th>
                                  <th></th>
                                  <th class="total-head">
                                    Quantity: &nbsp;
                                    <span class="total_quantity">{{ $total_based_products->quantity }}</span>
                                  </th>
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
                                            <th>Quantity</th>
                                            <th>Batch Id</th>
                                            <th>Expiry Date</th>
                                            <th>Location Id</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <?php $total_amount=$total_quantity=$final_price=0 ?>
                                          @foreach($product['product_variant'] as $key=>$variant)
                                            <?php
                                                $batch_details=App\Models\Orders::PurchaseBatchInfo($product['product_id'],$variant['variant_id']);
                                              $option_count=$product['option_count'];
                                              $variation_details=\App\Models\OrderProducts::VariationPrice($product['product_id'],$variant['variant_id'],$order->id);

                                            ?>
                                            <tr class="parent_tr">
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
                                                <div class="form-group">{{ $variation_details['quantity'] }}</div>
                                              </td>
                                              <td width="20%">
                                                <?php
                                                $batch_details=App\Models\Orders::PurchaseBatchInfo($product['product_id'],$variant['variant_id']);


                                                $batch_val=explode(',',$variation_details->batch_ids);
                                                $exp_dates=App\Models\OrderProducts::BatchInfos($batch_val);
                                                ?>

                                                <select class="form-control select2 batch_data" name="batch_ids[{{ $product['product_id'] }}][{{ $variant['variant_id'] }}][]" multiple>
                                                  @foreach ($batch_details as $key=>$batch)
                                                  @if (in_array($batch->id,$batch_val))
                                                    <option value="{{ $batch->id }}" selected="selected">
                                                      {{ $batch->batch_id }}
                                                    </option>
                                                  @else
                                                    <option value="{{ $batch->id }}">
                                                      {{ $batch->batch_id }}
                                                    </option>
                                                  @endif
                                                  @endforeach
                                                </select>
                                                @foreach ($batch_details as $batch)
                                                  <input type="hidden"  class="batch_{{ $batch->id }}" value="{{ $batch->expiry_date }}">
                                                  <input type="hidden" name="exp_dates[{{ $product['product_id'] }}][{{ $variant['variant_id'] }}]" class="append_data_{{$batch->id}} ex-date">
                                                   <input type="hidden"  class="location_{{ $batch->id }}" value="{{ $batch->location_id }}">
                                                @endforeach

                                              </td>
                                              <td width="20%" class="expiry_date_text">

                                                  @if (isset($exp_dates['batch_exp']))
                                                    {{ $exp_dates['batch_exp'] }}
                                                  @endif
                                              </td>
                                              <td width="20%" class="location_id_text">
                                                @if (isset($exp_dates['location']))
                                                  {{ $exp_dates['location'] }}
                                                @endif
                                                 
                                              
                                              </td>
                                            </tr>
                                            <?php $total_quantity +=$variation_details['quantity']; ?>
                                          @endforeach
                                          <tr>
                                            <td colspan="{{count($product['options'])}}" class="text-right">Total:</td>
                                            <td class="total_quantity">{{ $total_quantity }}</td>
                                          </tr>
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
                      <div class="col-sm-12">
                    <div class="form-group">
                    <div class="col-sm-12">
                        <label for="purchase_date">Note</label>
                        {!! Form::textarea('notes', $order->logistic_instruction,['class'=>'form-control summernote']) !!}
                      </div>
                    </div>
                      </div>
                    </div>


                    <div class="form-group">
                      <div class="col-sm-12 submit-sec">
                        <a href="{{ route($back_route) }}" class="btn  reset-btn">Cancel</a>
                        <button class="btn save-btn" type="submit">Save</button>
                      </div>
                    </div>
                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

</div>
  <style type="text/css">
    .form-group{display:flex;}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">

      $(document).on('change', '.batch_data', function(event) {
        event.preventDefault();
        var parent_this=$(this);
        $(this).parents('.parent_tr').find('.expiry_date_text').text('');
        $(this).parents('.parent_tr').find('.location_id_text').text('');
        $(this).parents('.parent_tr').find('.ex-date').val('');

        var currenct_val=$(this).select2('data');
        $.each(currenct_val, function(index, val) {
            var att_id=val.element.attributes[0].value;
            var exp_date=$('.batch_'+att_id).val();
            var location=$('.location_'+att_id).val();
            var check_parent=parent_this.parents('.parent_tr').find('.expiry_date_text').text();

            if (check_parent=="") {

              parent_this.parents('.parent_tr').find('.expiry_date_text').append(exp_date);
            }
            else{
              parent_this.parents('.parent_tr').find('.expiry_date_text').append(', '+exp_date);
            }
              $('.append_data_'+att_id).val(exp_date);

            var check_parent=parent_this.parents('.parent_tr').find('.location_id_text').text();
            if (check_parent=="") {

              parent_this.parents('.parent_tr').find('.location_id_text').append(location);
            }
            else{
              parent_this.parents('.parent_tr').find('.location_id_text').append(', '+location);
            }
              // $('.append_data_'+att_id).val(exp_date);
        });
      });

      $(function ($) {
        $('.select2').select2({
          minimumResultsForSearch: -1
        });
      });
      $(document).ready(function() {
          var delivery_status="{{ $order->order_status }}";
          if (delivery_status==14 || delivery_status==15 || delivery_status==16) {
              $('.delivery-date').css('display','block');
          }
          else{
            $('.delivery-date').css('display','none');
          }
      });
      var $datepicker = $('.date-picker').datepicker({ minDate: 0,dateFormat: 'dd-mm-yy'});
      $(document).on('change', '#order_status, #delivery_status', function(event) {
       var currenct_val=$(this).val();
          if (currenct_val==14 || currenct_val==15 || currenct_val==16) {
              $('.delivery-date').css('display','block');
              $('.date-picker').val(<?php date('m-d-Y'); ?>);
          }
          else{
            $('.delivery-date').css('display','none');
          }
      });

    </script>
  @endpush
@endsection
