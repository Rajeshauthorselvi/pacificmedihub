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
            <h1 class="m-0">Add Wastage</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('wastage.index')}}">Wastage</a></li>
              <li class="breadcrumb-item active">Add Wastage</li>
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
          <a href="{{route('wastage.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Wastage</h3>
              </div>
              <div class="card-body">
                <form action="{{route('wastage.store')}}" method="post" enctype="multipart/form-data" id="productForm">
                  @csrf
                  <div class="date-sec">
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="purchase_date">Date *</label>
                          <input type="text" class="form-control" name="purchase_date" value="{{ date('d/m/Y H:i') }}" readonly="true" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                          <label for="reference_number">Reference Number</label>
                          {!! Form::text('reference_number',$ref_data,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="product-sec">
                    <div class="col-sm-8">
                        <div class="form-group">
                          <label for="product">Products *</label>
                          {!! Form::text('product',null, ['class'=>'form-control product-sec','id'=>'prodct-add-sec']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="order-item-sec"></div>
                  <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', null,['class'=>'form-control summernote','rows'=>5]) !!}
                        </div>
                    </div>
                    <div class="col-sm-12 submit-sec">
                      <a href="{{ route('wastage.index') }}" class="btn  reset-btn">
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

@push('custom-scripts')
<script type="text/javascript">

  $(document).on('click', '.save-btn', function(event) {
    event.preventDefault();
    var empty_field = $('.stock_qty').filter(function() {
                          return parseInt($(this).val(), 10) > 0;
                      });
    if (empty_field.length>0) {
        $('#productForm').submit();
    }
    else{
      alert('Please select products');
    }

  });
  

  $(document).on('keyup', '.stock_qty', function(event) {
    event.preventDefault();
          /*If empty to set 0 */
          if($(this).val()==""){ $(this).val(0); }
          /*If empty to set 0 */

          /*Allow only numbers*/
          if (/\D/g.test(this.value)){
            this.value = this.value.replace(/\D/g, '');
            return false
          }
          /*Allow only numbers*/

          var total_quantity=$(this).parents('.parent_tr').find('.total-avail-quantity').val();
          var current_field_val=$(this).val();
          if ((current_field_val !== '') && (current_field_val.indexOf('.') === -1)) {
            var current_field_val=Math.max(Math.min(current_field_val, parseInt(total_quantity)), -90);
            $(this).val(current_field_val);
          }
          var balance_quantity=parseInt(total_quantity)-parseInt(current_field_val);
          $(this).parents('.parent_tr').find('.total-quantity').val(balance_quantity);
  });


  $(document).on('click', '.remove-product-row', function(event) {
        $(this).closest('tr').next('tr').remove();
        $(this).closest('tr').remove();
  });

    $(document).on('keyup', '.stock_qty', function(event) {
      if (/\D/g.test(this.value))
      {
        this.value = this.value.replace(/\D/g, '');
      }
      else{
        var base=$(this).parents('tr');
          var base_price=base.find('.base_price').text();
          var total_price=base_price*$(this).val();
          base.find('.subtotal_hidden').val(total_price);
          base.find('.sub_total').text(total_price);
      }
    });

    $('#prodct-add-sec').autocomplete({
      source: function( request, response) {
        $.ajax({
          url: "{{ url('admin/wastage-product-search') }}",
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

        var check_length=$('.product_id[value='+ui.item.value+']').length;

        if (check_length>0) {
            alert('This product already exists');
            $(this).val('');
            return false;
        }
         $(this).val('');
         ajaxFunction('header',ui)
         ajaxFunction('options',ui);
         $('.no-match').hide();


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
          url: "{{ url('admin/wastage-product-search') }}",
          data: {
            product_search_type: type,
            product_id:ui.item.value
          },
        })
        .done(function(response) {
          if (type=="header") {
            if ($('.vatiant_table').length==0) {
              createTable(response['options'])
            }
          }
          else if(type=="options"){
            // $('.order-item-sec').append(response);
            $('.parent_tbody').append(response);
          }
        });

    }
function createTable(options){
  var html='';
      html += '<div class="table-responsive">';
      html += '  <table  id="variantList" class="table table-striped table-bordered vatiant_table">';
      html += '    <thead class="heading-top">';
      html += '      <tr>';
      html += '        <th class="text-left">#</th>';
      html += '        <th class="text-left" colspan="4">Products</th>';
      html += '      </tr>';
      html += '    </thead>';
      html += '    <tbody class="parent_tbody">';
      html += '    </tbody>';
      html += '  </table>';
      html += '</div>';
      $('.order-item-sec').html(html);
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