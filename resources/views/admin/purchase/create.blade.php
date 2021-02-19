@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Purchase</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">Purchase</a></li>
              <li class="breadcrumb-item active">Add Purchase</li>
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
          <a href="{{route('purchase.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Purchase</h3>
              </div>
              <div class="card-body">
                <form action="{{route('purchase.store')}}" method="post" class="purchase-form">
                  @csrf
                  <div class="date-sec">
                    <div class="form-group">
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Date *</label>
                        <input type="text" class="form-control" name="purchase_date" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Purchase Code *</label>
                        {!! Form::text('purchase_order_number',$purchase_code,['class'=>'form-control','readonly']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="purchase_order_number">Status *</label>
                        {!!Form::select('purchase_status',$order_status,1,['class'=>'form-control read-only select2bs4 '])!!}
                        {!! Form::hidden('purchase_status',1,['class'=>'form-control','readonly']) !!}
                      </div>
                    </div>
                  </div>

                  <div class="product-sec">
                    <div class="form-group">
                      <div class="col-sm-8">
                        <label for="prodct-add-sec">Products *</label>
                        {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec']) !!}
                      </div>
                      <div class="col-sm-4">
                        <label for="vendorId">Vendor *</label>
                        {!! Form::select('vendor_id',$vendors, null,['class'=>'form-control vendors select2bs4','id'=>'vendorId']) !!}
                        <span class="text-danger vendor" style="display:none;">Status is required. Please Select</span>
                      </div>
                    </div>
                  </div>

                  <div class="order-item-sec"></div>
          
                  <div class="tax-sec">
                    <div class="form-group">
                      <div class="col-sm-3">
                        <label for="purchase_date">Order Tax</label>
                        <select class="form-control no-search select2bs4" name="order_tax" id="order_tax">
                          @foreach($taxes as $tax)
                            <option tax-rate="{{$tax->rate}}" value="{{$tax->id}}" @if($tax->name=='No Tax')  selected="selected" @endif {{ (collect(old('order_tax'))->contains($tax->id)) ? 'selected':'' }}>
                              {{$tax->name}} 
                              @if($tax->name=='No Tax') 
                              @else @  {{round($tax->rate,2)}}% 
                              @endif
                            </option>
                          @endforeach
                        </select>

                      </div>
                      <div class="col-sm-3">
                        <label for="purchase_date">Order Discount</label>
                        {!! Form::text('order_discount', 0,['class'=>'form-control','id'=>'order-discount','autocomplete'=>'off','onkeyup'=>'validateNum(event,this);']) !!}
                      </div>
                      <div class="col-sm-3">
                        <label for="purchase_date">Payment Term</label>
                        {!! Form::select('payment_term',$payment_terms,null,['class'=>'form-control no-search select2bs4']) !!}
                      </div>
                      <div class="col-sm-3">
                        <label for="purchase_date">Payment Status *</label>
                        <?php $payment_status=[1=>'Paid',3=>'Partly Paid',2=>'Not Paid']; ?>
                        {!! Form::select('payment_status',$payment_status, 2,['class'=>'form-control no-search select2bs4','id'=>'payment_status']) !!}
                      </div>
                    </div>
                  </div>

                  <div>
                    <input type="hidden" name="total_amount" id="total_amount_hidden">
                    <input type="hidden" name="order_tax_amount" id="order_tax_amount_hidden">
                    <input type="hidden" name="sgd_total_amount" id="sgd_total_amount_hidden">
                  </div>

                  <div class="panel panel-default payment-note-sec">
                    <div class="panel-body" style="display:none">
                      <div class="form-group">
                        <div class="col-sm-4">
                          <label for="purchase_date">Payment Reference Number</label>
                          {!! Form::text('payment_reference_no', null,['class'=>'form-control']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="purchase_date">Amount</label>
                          {!! Form::text('amount', null,['class'=>'form-control']) !!}
                        </div>
                        <div class="col-sm-4">
                          <label for="purchase_date">Paying By</label>
                          {!! Form::select('paying_by', $payment_method, null,['class'=>'form-control no-search select2bs4']) !!}
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          <label for="purchase_date">Payment Note</label>
                          {!! Form::textarea('payment_note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <label for="purchase_date">Note</label>
                      {!! Form::textarea('note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                    </div>
                  </div>

                  <div class="col-sm-12 submit-sec">
                    <a href="{{ route('purchase.index') }}" class="btn  reset-btn">Cancel</a>
                    <button class="btn save-btn" type="submit">Submit</button>
                  </div>
                </form>
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
    <script type="text/javascript">
      $(function ($) {
        $('.read-only.select2bs4').select2({
          disabled: true
        });
        $('.no-search.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });

      var path ="{{ url('admin/product-search') }}";
      $('#prodct-add-sec').autocomplete({
        source: function( request, response) {
          

          $.ajax({
            url: path,
            data: {
              name: request.term,
              product_search_type:'product',
              vendor_id:$('.vendors').val()
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        minLength: 1,
        select: function( event, ui ) {

          var check_length=$('.product_id[value='+ui.item.value+']').length;
          if (check_length>0) {
              alert('This product already exists');
              $(this).val('');
              return false;
          }
          /*Load Related Vendors*/
          
            $.ajax({
              url: '{{ url('admin/search-vendor') }}',
              type: 'post',
              data:{
                '_token':"{{ csrf_token() }}",
                product_id:ui.item.value
              }
            })

            .done(function(response) {
              $('.vendors').empty();
              $.each(response.products, function(key, value) {
                   var $option = $("<option/>", {
                      value: key,
                      text: value
                    });
                    $('.vendors').append($option);
              });
            })
            .fail(function() {
              alert('Ajax Error:--');
            });

          /*Load Related Vendors*/
          
           ajaxFunction('options',ui);
           $('.no-match').hide();
           $(this).val('');
           return false;

        },
        open: function() {
          $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
          $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
      });

      function ajaxFunction(type,ui) {
        $.ajax({
          url: "{{ url('admin/product-search') }}",
          data: {
            product_search_type: type,
            product_id:ui.item.value
          },
        })
        .done(function(response) {
          if ($('.vatiant_table').length==0) {
            createTable();
          }
          $('.parent_tbody').append(response);
        });
      }

      /*Remove Product Row*/
      $(document).on('click', '.remove-product-row', function(event) {
        event.preventDefault();
        $(this).closest('tr').next('tr').remove();
        $(this).closest('tr').remove();
      });
      /*Remove Product Row*/
      
      $(document).on('click', '.remove-item', function(event) {
        $(this).closest('tr').remove();
        var subtotal=$(this).parents('.vatiant_table').find('.subtotal_hidden');
        var sum = 0;
        $(subtotal).each(function(){
          sum += parseFloat(this.value);
        });
        return false;

        $(this).parents('.vatiant_table').find('.total_amount').text(sum);
        var stock_qty=$(this).parents('.vatiant_table').find('.stock_qty');
        var quantity=0;
        $(stock_qty).each(function(){
          quantity += parseFloat(this.value);
        });
        $(this).parents('.vatiant_table').find('.total_quantity').text(quantity);

        SumAllTotal();
      });

      $(document).on('keyup', '.stock_qty', function(event) {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
        }
        else{
          var base=$(this).parents('.parent_tr');
          var base_price=base.find('.base_price').val();
          var total_price=base_price*$(this).val();
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);

          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');

          var total_quantity=SumTotal('#'+attr_id+' .stock_qty');
          $('.'+attr_id).find('.total-quantity').text(total_quantity);

          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.'+attr_id).find('.total').text(total_amount);

          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));

          var all_amount = $('#allAmount').text();
          var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
          overallCalculation(all_amount,tax_rate);
        }
      });

      function SumTotal(class_name) {
        var sum = 0;
        $(class_name).each(function(){
          sum += parseFloat(this.value);
        });
        return sum;
      }

      function SumAllTotal() {
        $('.order-total-sec').show();
        var sum = 0;
        $('.total_amount').each(function(){
          sum += parseFloat($(this).text());
        });

        $('.all_total').html('<b>Grant Total: '+sum+'</b>');

        var quantity=0;
        $('.total_quantity').each(function(){
            quantity += parseFloat($(this).text());
        });
        $('.all_quantity').html('<b>Total Quantity: '+quantity+'</b>');
      }

      $(document).on('keyup', '#order-discount', function() {
        var discount = $(this).val();
        $('.order-discount').text(discount);
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', '#order_tax').attr("tax-rate");
        overallCalculation(all_amount,tax_rate);
      });

      $(document).on('change', '#order_tax', function() {
        var all_amount = $('#allAmount').text();
        var tax_rate = $('option:selected', this).attr("tax-rate");
        overallCalculation(all_amount,tax_rate);
      });

      function overallCalculation(all_amount,tax_rate){
        var allAmount    = all_amount;
        var taxRate      = tax_rate;
        var tax          = taxRate/100;
        var calculatTax  = tax*allAmount;
        var taxAmount    = calculatTax.toFixed(2);
        var discount     = $('#order-discount').val();
        var calculateSGD = parseFloat(allAmount)+parseFloat(taxAmount);
        var totalSGD     = parseFloat(calculateSGD)-parseFloat(discount);

        $('#orderTax').text(taxAmount);
        $('#total_amount_sgd').text(totalSGD.toFixed(2));
        $('#total_amount_hidden').val(allAmount);
        $('#order_tax_amount_hidden').val(taxAmount);
        $('#sgd_total_amount_hidden').val(totalSGD);
      }

      $(document).on('change', '#payment_status', function() {
        var paymentStatus = $('option:selected', this).val();
        if((paymentStatus==1) || (paymentStatus==2)){
          $('.panel-body').show();
        }else{
          $('.panel-body').hide();
        }
      });

      $('.purchase-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });

      function createTable(){
        var data='<div class="container my-4">';
        data +='<div class="table-responsive vatiant_table">';
        data +='<table class="table">';
        data +='<thead class="heading-top">';
        data +='<tr>';
        data +='<td>#</td>';
        data +='<th scope="col">Product Name</th>';
        data +='<th>Total Quantity:&nbsp;<span class="all_quantity"></span></th>';
        data +='<th>Total Amount:&nbsp;<span class="all_amount" id="allAmount"></span></th>';
        data +='<th></th>';
        data +='</tr>';
        data +='</thead>';
        data +='<tbody class="parent_tbody">';
        data +='</tbody>';
        data +='<tr class="total-calculation"><td colspan="3" class="title">Total</td>';
        data +='<td colspan="2"><span class="all_amount">0.00</span></td></tr>';
        data +='<tr class="total-calculation"><td colspan="3" class="title">Order Discount</td>';
        data +='<td colspan="2"><span class="order-discount">0.00</span></td></tr>';
        data +='<tr class="total-calculation"><td colspan="3" class="title">Order Tax</td>';
        data +='<td colspan="2" id="orderTax">0.00</td></tr>';
        data +='<tr class="total-calculation"><th colspan="3" class="title">Total Amount(SGD)</th>';
        data +='<th colspan="2" id="total_amount_sgd">0.00</th></tr>';
        data +='<tr><td colspan="5"></td></tr>';
        data +='</table>';
        data +='</div>';
        data +='</div>';
        $('.order-item-sec').html(data);
      }

      $(document).on('click', '.save-btn', function(event) {
        var check_variants_exists=$('.parent_tr').length;
        if (check_variants_exists==0) {
          alert('Product is required. Please Select');
          event.preventDefault();
        }

        if(validate()!=false){
          $('.purchase-form').submit();
        }else{
          scroll_to();
          return false;
        }

      });

      function validate(){
        var valid=true;
        if ($("#vendorId").val()=="") {
          $("#vendorId").closest('.form-group').find('span.text-danger.vendor').show();
          valid = false;
        }else{
          $("#vendorId").closest('.form-group').find('span.text-danger.vendor').hide();
        }
        if($('#total_amount_hidden').val()==""||$('#total_amount_hidden').val()==0)
        {
          alert('Please enter minimum Quantity');
          valid = false;
        }
        return valid;
      }

      function scroll_to(form){
        $('html, body').animate({
          scrollTop: $(".purchase-form").offset().top
        },1000);
      }

    </script>
  @endpush
@endsection