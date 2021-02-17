@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Return</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item"><a href="{{route('return.index')}}">Products</a></li>
              <li class="breadcrumb-item active">Add Return</li>
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
                  <h3 class="card-title">Add Return</h3>
              </div>
              <div class="card-body">
                  {!! Form::open(['route'=>'return.store','method'=>'POST','id'=>'form-validate']) !!}
           
                  <div class="form-group col-sm-12">
<div class="col-sm-12 product-sec">
  <div class="col-sm-4">
    <div class="form-group">
      <label>Date</label>
      <input class="form-control" readonly value="{{ date('d-m-Y',strtotime($purchase->purchase_date)) }}">
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
      {!! Form::select('return_status',$status,null,['class'=>'form-control']) !!}
    </div>
  </div>
</div>
<div class="clearfix"></div>
<div class="col-sm-12">

                  <div class="container my-4">
                    <div class="table-responsive">
                      <table class="table">
                        <tbody>
                           @foreach ($purchase_products as $product)
                           <tr class="accordion-toggle collapsed" id="accordion{{ $product['product_id'] }}" data-toggle="collapse" data-parent="#accordion{{ $product['product_id'] }}" href="#collapse{{ $product['product_id'] }}">
                            <td class="expand-button"></td>
                             <?php
                                  $total_based_products=\App\Models\PurchaseProducts::TotalDatas($purchase->id,$product['product_id']);
                               ?>
                              <td>{{ $product['product_name'] }}</td>
                             {{--  <td>
                                Quantity:&nbsp;
                                <span class="total-quantity">{{ $total_based_products->quantity }}</span>
                              </td>
                              <td>
                                Total:&nbsp;
                                <span class="total">{{ $total_based_products->sub_total }}</span>
                              </td> --}}
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
              <th>Purchase Price</th>
              <th>Total Purchase Quantity</th>
              <th>Damage Quantity</th>
              <th>Missed Quantity</th>
              <th>Total Return Quantity</th>
              <th>Total Return Amount</th>
            </thead>
                        <tbody>
                        <?php $missed_quantity=$damage_quantity=$total_amount=$total_quantity=$final_price=0 ?>
                        <?php $s_no=1; ?>
                       @foreach($product['product_variant'] as $key=>$variant)

                       <?php 
                         $option_count=$product['option_count'];
                         $variation_details=\App\Models\PurchaseProducts::VariationPrice($product['product_id'],$variant['variant_id'],$purchase->id);
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
                            {{-- <td> {{$variant['base_price']}} </td> --}}
                            {{-- <td> {{$variant['retail_price']}}</td> --}}
                            {{-- <td><span class="test"> {{$variant['minimum_selling_price']}} </span></td> --}}
                            <td>
                              <input type="hidden" class="purchase_price" value="{{ $product_price }}">
                              {{ $product_price }}
                            </td>
                            <td>
                              {{ $variation_details['quantity'] }}
                              <input type="hidden" class="total_quantity" value="{{ $variation_details['quantity'] }}">
                            </td>
                            
                            <td>
                              <?php $damage_quantity=$variation_details['damage_quantity']; ?>
                                <input type="text" name="damage_quantity[{{ $variation_details['id'] }}]" class="form-control damaged_quantity" value="{{ $variation_details['damage_quantity'] }}" max-count="{{ $variation_details['quantity'] }}" readonly>
                                <input type="hidden" name="purchase_id" value="{{ $purchase_id }}">
                                <input type="hidden" name="product_id[{{ $variation_details['id'] }}]" value="{{ $variant['product_id'] }}">
                              </td>
                              <td>
                                <input type="text" name="missed_quantity[{{ $variation_details['id'] }}]" class="form-control missed_quantity" value="{{ $variation_details['missed_quantity'] }}" readonly>
                              </td>
                              <th>
                                <?php $total_return=$variation_details['damage_quantity']+$variation_details['missed_quantity']; ?>
                                <input type="text" name="return_quantity[{{ $variation_details['id'] }}]" class="form-control missed_quantity" value="{{ $total_return }}" readonly>
                              </th>
                              <td>
                                <span class="sub_total_text">
                                  <?php $total_return_amount=($variation_details['missed_quantity']+$variation_details['damage_quantity'])*$product_price ?>
                                  {{ $total_return_amount }}
                                  <input type="hidden" name="sub_total[{{ $variation_details['id'] }}]" value="{{ $total_return_amount }}" class="sub_total">
                                </span>
                              </td>
                          </tr>
                          <?php $total_amount +=$total_return_amount; ?>
                          <?php $damage_quantity +=$variation_details['damage_quantity']; ?>
                          <?php $missed_quantity +=$variation_details['missed_quantity']; ?>
                        @endforeach
                        {{-- <tr>
                          <td colspan="{{ count($product['options'])+4 }}" class="text-right">Total:</td>
                          <td><span class="damage-quantity">{{ $damage_quantity }}</span></td>
                          <td><span class="missed-quantity">{{ $missed_quantity }}</span></td>
                          <td><span class="total-amount">{{ $total_amount }}</span></td>
                        </tr> --}}
                      </tbody>
                      </table>

                              </div>
                            </td>
                          </tr>
                          <?php $s_no++; ?>
                           @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>

                  </div>
<div class="clearfix"></div>
<br>
<br>
<style type="text/css">
  .product-sec .col-sm-4{
    float: left;
  }
</style>


                  </div>
                  <div class="col-sm-12 notes-sec">
                    <div class="col-sm-6 pull-left">
                      <label>Return Note</label>
                      <textarea class="form-control summernote" name="return_notes"></textarea>
                    </div>
                    <div class="col-sm-6 pull-left">
                      <label>Staff Note</label>
                      <textarea class="form-control summernote" name="staff_notes"></textarea>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <br>
                  <br> 
                  <div class="form-group col-sm-12 submit-sec">
                    <a href="{{route('return.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" class="btn save-btn">Save</button>
                  </div>
                  </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<style type="text/css">
  .search-btn-sec{
  margin-top: 30px;
  }
</style>
  @push('custom-scripts')
    <script type="text/javascript">


        $(document).on('keyup', '.missed_quantity', function(event) {
          event.preventDefault();

          if (/\D/g.test(this.value)){
              this.value = this.value.replace(/\D/g, '');
              return false
            }

          var total_quantity=$(this).parents('.parent_tr').find('.total_quantity').val();
          var current_field_val=$(this).val();
          var damaged_quantity=$(this).parents('.parent_tr').find('.damaged_quantity').val();

          if (damaged_quantity!="" && damaged_quantity!=0) {
            var balance_amount=parseInt(total_quantity)-parseInt(damaged_quantity);
          }
          else{
              var balance_amount=total_quantity;
          }
          if ((current_field_val !== '') && (current_field_val.indexOf('.') === -1)) {
                var current_field_val=Math.max(Math.min(current_field_val, parseInt(balance_amount)), -90);
                $(this).val(current_field_val);
          }

          var purchase_price=$(this).parents('.parent_tr').find('.purchase_price').val();
          var total=(parseInt(current_field_val)+parseInt(damaged_quantity))*parseInt(purchase_price);
           $(this).parents('.parent_tr').find('.sub_total_text').text(total);
           $(this).parents('.parent_tr').find('.sub_total').val(total);


        });
      $(document).on('keyup', '.damaged_quantity', function(event) {

          event.preventDefault();
          if (/\D/g.test(this.value)){
              this.value = this.value.replace(/\D/g, '');
              return false
            }

          var total_quantity=$(this).parents('.parent_tr').find('.total_quantity').val();
          var current_field_val=$(this).val();
          var missed_val=$(this).parents('.parent_tr').find('.missed_quantity').val();
          if (missed_val!="" && missed_val!=0) {
            var balance_amount=parseInt(total_quantity)-parseInt(missed_val);
          }
          else{
              var balance_amount=total_quantity;
          }
            if ((current_field_val !== '') && (current_field_val.indexOf('.') === -1)) {
                var current_field_val=Math.max(Math.min(current_field_val, parseInt(balance_amount)), -90);
                $(this).val(current_field_val);
            }

          var purchase_price=$(this).parents('.parent_tr').find('.purchase_price').val();
          var total=(parseInt(current_field_val)+parseInt(missed_val))*parseInt(purchase_price);

           $(this).parents('.parent_tr').find('.sub_total_text').text(total);
           $(this).parents('.parent_tr').find('.sub_total').val(total);


      });

      $(document).on('click', '.search-btn', function(event) {
          if ($('#po_number').val()=="") {
            alert('Please enter PO Number');
            return false;
          }
          var po_number=$('#po_number').val();
          $.ajax({
            url: '{{ url("admin/return/") }}'+'/'+po_number
          })
          .done(function(response) {
              if (response.status==false) {
                alert('Po Number is invalid');
                $('.product_sec').html('');
                $('.notes-sec').hide();
                $('.submit-sec').hide();
              }
              else{
                $('.notes-sec').show();
                $('.submit-sec').show();
                $('.product_sec').html(response.view);
              }
          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            console.log("complete");
          });
        
      });


      $('#po_number').autocomplete({
        source: function( request, response) {
          $.ajax({
            url: "{{ url('admin/search-purchase-no') }}",
            dataType: 'JSON',
            data: {
              name: request.term
            },
            success: function( data ) {
              response(data);
            }
          });
        },
        minLength: 1,
        open: function() {
          $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
          $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
      });

      


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

@endsection