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
            <h1 class="m-0">Add Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('orders.index')}}">Order List</a></li>
              <li class="breadcrumb-item active">Add Order</li>
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
          <a href="{{route('orders.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Order</h3>
              </div>
              <div class="card">
                <div class="card-body">
              {!! Form::open(['route'=>'orders.store','method'=>'POST','id'=>'form-validate']) !!}
                  <div class="clearfix"></div>
                  <div class="date-sec">
                    <div class="col-sm-4">
                     
                        <div class="form-group">
                          <label for="date">Date *</label>
                          <input type="text" class="form-control" name="created_at" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="order_no">Order No *</label>
                          {!! Form::text('order_no',$order_code,['class'=>'form-control','readonly']) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="status">Status *</label>
                          {!! Form::select('order_status',$order_status, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
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
                          {!! Form::select('customer_id',$customers,  null,['class'=>'form-control','id'=>'customer',]) !!}
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="sales_rep_id">Sales Rep *</label>
                          {!! Form::select('sales_rep_id',$sales_rep, null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="product-append-sec"></div>
                  <div class="clearfix"></div>
                  <div class="panel panel-default payment-note-sec">
                    <div class="panel-body">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Reference Number</label>
                          {!! Form::text('payment_ref_no', null,['class'=>'form-control']) !!}
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
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Payment Status *</label>
                          <?php $payment_status=[''=>'Please Select',1=>'Paid',2=>'Partly Paid',3=>'Not Paid']; ?>
                          {!! Form::select('payment_status',$payment_status, null,['class'=>'form-control']) !!}
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
                  <div class="clearfix"></div>
                  <br>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('orders.index') }}" class="btn  reset-btn">
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
        </div>

    </section>
  </div>
  <style type="text/css">

    .footer-sec .col-sm-6{
      float: left;
    }
  .notes-sec,.created-sec {
    background-color: #f6f6f6;
    padding: 15px;
  }
  .date-sec .col-sm-4, .payment-note-sec .col-sm-4, .product-sec .col-sm-4 {
    float: left;
  }

  .order-no-sec {
    line-height: 38px;
    font-size: 18px;
  }
  .action_sec li {
    float: left;
    width: 16.6%;
    text-align: center;
  }
  .action_sec li a {
    float: left;
    width: 100%;
    color: #fff;
    padding: 8px;
  }
  .place-order,.pdf{
    background-color:#3471a8;
    border-right: 1px solid #5cbfdd;
  }
  .email{
    background-color:#5cbfdd;
  }
  .comment{
    background-color:#48bb77;
  }
  .edit{
    background-color:#efab4f;
  }
  .delete{
    background-color:#d85450;
  }
  .address-sec {
    padding: 10px
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
    $(this).parents('.parent_tr').remove();
  });

    $('#prodct-add-sec').autocomplete({
      source: function( request, response) {
        $.ajax({
          url: "{{ url('admin/orders-product') }}",
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
          url: "{{ url('admin/orders-product') }}",
          data: {
            product_search_type: 'product_options',
            product_id:ui.item.value
          },
        })
        .done(function(response) {
          if (response.status==false) {
            return false;
          }

          if ($('.vatiant_table').length==0) {
            createTable();
          } 

          $('.parent_tbody').append(response);


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
         var base_price=base.find('.max_price').val();
         var total_price=base_price*$(this).val();

          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);
         
          $('.all_quantity').text(SumTotal('.stock_qty'));
          $('.all_amount').text(SumTotal('.subtotal_hidden'));

          var attr_id=$(this).parents('tbody').find('.collapse.show').attr('id');
          var total_quantity=SumTotal('#'+attr_id+' .stock_qty');
          var total_amount=SumTotal('#'+attr_id+' .subtotal_hidden');
          
         $('.'+attr_id).find('.total-quantity').text(total_quantity);
         $('.'+attr_id).find('.total').text(total_amount);

         $('#'+attr_id).find('.total_quantity').text(total_quantity);
         $('#'+attr_id).find('.total_amount').text(total_amount);

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
      data +='<th>Total Amount:<span class="all_amount"></span></th>';
      data +='<th></th>';
      data +='</tr>';
      data +='</thead>';
      data +='<tbody class="parent_tbody">';
      data +='</tbody>';
      data +='</table>';
      data +='</div>';
      data +='</div>';
      $('.product-append-sec').html(data);
}
  </script>
@endpush
@endsection