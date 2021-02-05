@extends('admin.layouts.master')
@section('main_container')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                <form action="{{route('purchase.store')}}" method="post" enctype="multipart/form-data" id="productForm">
                  @csrf
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Date *</label>
                          <input type="text" class="form-control" name="purchase_date" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Purchase Order Number *</label>
                          {!! Form::text('purchase_order_number',$purchase_code,['class'=>'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('purchase_status',$order_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="col-sm-8">
                        <div class="form-group">
                          <label for="purchase_order_number">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Vendor *</label>
                          {!! Form::select('vendor_id',$vendors, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="order-item-sec"></div>
                  <div class="clearfix"></div>

                  <div class="tax-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Order Tax</label>
                          {!! Form::text('order_tax', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Order Discount</label>
                          {!! Form::text('order_discount', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Term</label>
                          {!! Form::text('payment_term', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Status *</label>
                          <?php $payment_status=[''=>'Please Select',1=>'Paid',2=>'Partly Paid',3=>'Not Paid']; ?>
                          {!! Form::select('payment_status',$payment_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="panel panel-default payment-note-sec">
                    <div class="panel-body">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Reference Number</label>
                          {!! Form::text('payment_reference_no', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Amount</label>
                          {!! Form::text('amount', null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Paying By</label>
                          {!! Form::select('paying_by', $payment_method, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Payment Note</label>
                          {!! Form::textarea('payment_note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    </div>
                  </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('purchase.index') }}" class="btn  reset-btn">
                        Cancel
                      </a>
                      <button class="btn save-btn" type="submit">
                        Submit
                      </button>

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
  .total-sec{
    background-color: #fcf8e3;
  }
</style>
@push('custom-scripts')
<script type="text/javascript">
  
$(document).on('click', '.remove-product-row', function(event) {
  event.preventDefault();
  $(this).closest('tr').next('tr').remove();
  $(this).closest('tr').remove();
});
$(document).on('click', '.save-btn', function(event) {
    
    var check_variants_exists=$('.parent_tr').length;
    if (check_variants_exists==0) {
      alert('Please select products');
       event.preventDefault();
    }

});
  

  $(document).on('click', '.remove-item', function(event) {
   
            $(this).parents('tr').remove();
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

  var path ="{{ url('admin/product-search') }}";

    $('#prodct-add-sec').autocomplete({
      source: function( request, response) {
        $.ajax({
          url: "{{ url('admin/rfq-product') }}",
          data: {
            name: request.term,
            product_search_type:'product'
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {

        var check_length=$('.product_id[value='+ui.item.value+']').length;

        if (check_length>0) {
            alert('This product already exists');
            $(this).val('');
            return false;
        }
         // ajaxFunction('header',ui)
         ajaxFunction('options',ui);
         $('.no-match').hide();
         $(this).val('');
         collapseFunction();
         
         return false;

      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });

    function collapseFunction() {
        // Add minus icon for collapse element which is open by default
        $(".collapse.show").each(function(){
          $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
        });
        
        // Toggle plus minus icon on show hide of collapse element
        $(".collapse").on('show.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
        }).on('hide.bs.collapse', function(){
          $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
        });
  
    }
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


function createTable(){
  var data='<div class="container my-4">';
      data +='<div class="table-responsive vatiant_table">';
      data +='<table class="table">';
      data +='<thead>';
      data +='<tr>';
      data +='<td>#</td>';
      data +='<th scope="col">Product Name</th>';
      data +='<th>Total Quantity:&nbsp;<span class="all_quantity"></span></th>';
      data +='<th>Total Amount:<span class="all_amount"></span></th>';
      data +='<th></th>';
      data +='</tr>';
      data +='</thead>';
      data +='<tbody class="parent_tbody">';
      data +='</tbody>';
      data +='</table>';
      data +='</div>';
      data +='</div>';
      $('.order-item-sec').html(data);
}

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