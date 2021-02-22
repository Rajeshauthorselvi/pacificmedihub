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
    <span class="hr"></span>
    @include('flash-message')
    <!-- Main content -->

    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('wastage.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
	    <div class="container-fluid">
	        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Wastage</h3>
              </div>
              <div class="card-body">
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Date *</label>
                          <input type="text" class="form-control" name="purchase_date" value="{{ date('d-m-Y',strtotime($wastages->created_at)) }}" readonly="true" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="reference_number">Reference Number</label>
                          {!! Form::text('reference_number',$wastages->reference_number,['class'=>'form-control','readonly']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="order-item-sec">
			        <div class="table-responsive">
				        <table  id="variantList" class="table table-striped table-bordered vatiant_table">
				          <thead class="heading-top">
				            <tr>
				              <th class="text-left">#</th>
				              <th class="text-left" colspan="4">Products</th>
				            </tr>
				          </thead>
				          <tbody class="parent_tbody">
							@foreach ($wastage_products as $product)
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
                                          <th>Quantity</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php $total_amount=$total_quantity=$final_price=0 ?>
                                        @foreach($product['product_variant'] as $key=>$variant)
                                          <?php 
                                            $option_count = $product['option_count'];
                                            $variation_details = \App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$wastages->id);
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
                                              <div class="form-group">
                                                  <input type="text" class="form-control stock_qty" name="variant[stock_qty][]" value="{{ $wastage_quantity[$variant['variant_id']] }}" autocomplete="off" readonly>
                                              </div>
                                            </td>
                                            
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
                  <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', $wastages->notes,['class'=>'form-control summernote','rows'=>5,'readonly']) !!}
                        </div>
                    </div>
              </div>
            </div>
          </div>
        </div>
	    </div>
	</section>
</div>

  @push('custom-scripts')
    <script type="text/javascript">
      $('.summernote').summernote('disable');
    </script>
  @endpush
<style type="text/css">
	    .date-sec .col-sm-4{
      float: left;
    }
</style>
@endsection