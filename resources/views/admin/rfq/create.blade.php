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
            <h1 class="m-0">Add RFQ</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">RFQ</a></li>
              <li class="breadcrumb-item active">Add RFQ</li>
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
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('rfq.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add RFQ</h3>
              </div>
              <div class="card-body">
                {!! Form::open(['route'=>'rfq.store','method'=>'POST','class'=>'rfq-form' ,'id'=>'form-validate']) !!}
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="date">Date *</label>
                          <input type="text" class="form-control" name="created_at" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="order_no">RFQ No *</label>
                          {!! Form::text('order_no',$rfq_id,['class'=>'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_order_number">Status *</label>
                          {!! Form::select('status',$order_status, null,['class'=>'form-control select2bs4']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="product">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="customer_id">Customer *</label>
                          {!! Form::select('customer_id',$customers, null,['class'=>'form-control select2bs4','id'=>'customer']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="sales_rep_id">Sales Rep *</label>
                          {!! Form::select('sales_rep_id',$sales_rep, null,['class'=>'form-control select2bs4']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="product-append-sec"></div>
                  <div class="clearfix"></div>
                  <div class="col-sm-12">
                     <div class="form-group">
                          <label for="sales_rep_id">Notes</label>
                        {!! Form::textarea('notes',null,['class'=>'form-control summernote']) !!}
                    </div>
                  </div>
                  <div class="clearfix"></div> 
                  <br>
                  <div class="form-group">
                    <a href="{{ route('rfq.index') }}" class="btn reset-btn">Cancel</a>
                    <button class="btn save-btn" type="submit">Save</button>
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
    .date-sec .col-sm-4, .product-sec .col-sm-4 {
      float: left;
    }


</style>
@push('custom-scripts')
  <script type="text/javascript">

$(document).on('click', '.save-btn', function(event) {
    
    var check_variants_exists=$('.vatiant_table').length;
    if (check_variants_exists==0) {
      alert('Please select products');
       event.preventDefault();
    }

});
  

  $(document).on('click', '.remove-item', function(event) {
    $(this).parents('.parent_tr').remove();
  });

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
      minLength: 1,
      select: function( event, ui ) {
        $.ajax({
          url: "{{ url('admin/rfq-product') }}",
          data: {
            product_search_type: 'product_options',
            product_id:ui.item.value
          },
        })
        .done(function(response) {
          if ($('.vatiant_table').length==0) {
            createTable();
          }
          $('.parent_tbody').append(response);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_rfq_price').text(SumTotal('.rfq_price'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));

          var sum_inside_total=$('#collapse'+ui.item.value+' .stock_qty');
          $('.quantity_'+ui.item.value).text(SumTotal('#collapse'+ui.item.value+' .stock_qty'));
          $('.total_'+ui.item.value).text(SumTotal('#collapse'+ui.item.value+' .subtotal_hidden'));
          $('.rfq_'+ui.item.value).text(SumTotal('#collapse'+ui.item.value+' .rfq_price'));

        });
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
    $(document).on('keyup', '.stock_qty', function(event) {
      if (/\D/g.test(this.value))
      {
        this.value = this.value.replace(/\D/g, '');
      }
      else{
         var base=$(this).parents('.parent_tr');
         var base_price=base.find('.rfq_price').val();
         var total_price=base_price*$(this).val();
         
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);
            
          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var attr=$(this).parents('tbody').find('.collapse.show');
          var total_quantity=SumTotal('.collapse.show .stock_qty');
          console.log(total_quantity);

          $('.collapse.show').find('.total_quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total_quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));
      }
    });

    $(document).on('keyup', '.rfq_price', function(event) {
      if (/\D/g.test(this.value))
      {
        this.value = this.value.replace(/\D/g, '');
      }
      else{
         var base=$(this).parents('.parent_tr');
         var base_price=base.find('.stock_qty').val();
         var total_price=base_price*$(this).val();
         
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);
            
          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var attr=$(this).parents('tbody').find('.collapse.show');
          var total_quantity=SumTotal('.collapse.show .stock_qty');
          console.log(total_quantity);

          $('.collapse.show').find('.total_quantity').text(total_quantity);
          $('[href="#'+attr_id+'"]').find('.total_quantity').text(total_quantity);
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          $('.collapse.show').find('.total').text(total_amount);
          $('[href="#'+attr_id+'"]').find('.total').text(total_amount);

          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));
      }
    });

$(document).on('keyup', '.rfq_price', function(event) {
  $('.all_rfq_price').text(SumTotal('.rfq_price'));
});


function SumTotal(class_name) {
  var sum = 0;
  $(class_name).each(function(){
      sum += parseFloat(this.value);
  });

  return sum;
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
      // data +='<th>Total Price:&nbsp;<span class="all_rfq_price"></span></th>';
      data +='<th>Total Amount:<span class="all_amount"></span></th>';
      data +='</tr>';
      data +='</thead>';
      data +='<tbody class="parent_tbody">';
      data +='</tbody>';
      data +='</table>';
      data +='</div>';
      data +='</div>';
      $('.product-append-sec').html(data);
}
  
  $('.rfq-form').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
      e.preventDefault();
      return false;
    }
  });

  </script>
@endpush
@endsection