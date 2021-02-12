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
              <li class="breadcrumb-item"><a href="{{route('purchase.index')}}">Wastage</a></li>
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
                  {{ Form::model($wastages,['method' => 'PATCH', 'route' =>['wastage.update',$wastages->id]]) }}
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
                          {!! Form::text('reference_number',null,['class'=>'form-control']) !!}
                        </div>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="order-item-sec">
                    <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Product (Code-Name)</th>
                            @foreach ($options as $option)
                              <th>{{ $option }}</th>
                            @endforeach
                            <th>Quantity</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($wastage_products as $wastage)
                            <tr>
                              <td>{{ $wastage['product_name'] }}</td>
                              @if (isset($wastage['option_value_id1']))
                                <td>{{ $wastage['option_value_id1'] }}</td>
                              @endif
                              @if (isset($wastage['option_value_id2']))
                                <td>{{ $wastage['option_value_id2'] }}</td>
                              @endif
                              @if (isset($wastage['option_value_id3']))
                                <td>{{ $wastage['option_value_id3'] }}</td>
                              @endif
                              @if (isset($wastage['option_value_id4']))
                                <td>{{ $wastage['option_value_id4'] }}</td>
                              @endif
                              @if (isset($wastage['option_value_id5']))
                                <td>{{ $wastage['option_value_id5'] }}</td>
                              @endif
                              <td><input type="text" name="quantity[{{$wastage['id']}}]" value="{{ $wastage['quantity'] }}" class="form-control"></td>
                            </tr>
                          @endforeach
                        </tbody>
                    </table>
                  </div>
                  <div class="clearfix"></div>
                    <div class="col-sm-12">
                        <div class="form-group">
                          <label for="purchase_date">Note</label>
                          {!! Form::textarea('note', $wastages->notes,['class'=>'form-control summernote','rows'=>5]) !!}
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

@push('custom-scripts')
<script type="text/javascript">

  $(document).on('click', '.remove-item', function(event) {
    $(this).parents('tr').remove();
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
            $('.vatiant_table tbody').append(response);
          }
        });

    }
function createTable(options){
  var html='';
      html += '<div class="table-responsive">';
      html += '  <table  id="variantList" class="table table-striped table-bordered table-hover vatiant_table">';
      html += '    <thead>';
      html += '      <tr>';
      for(option of options){
        html += '        <td class="text-left">' + option + '</td>';
      }
      html += '        <td class="text-left">Base Price</td>';
      html += '        <td class="text-left">Retail Price</td>';
      html += '        <td class="text-left">Minimum Selling Price</td>';
      html += '        <td class="text-left">Quantity</td>';
      html += '        <td class="text-left">Sub Total</td>';
      html += '        <td class="text-left"></td>';
      //html += '        <td></td>';
      html += '      </tr>';
      html += '    </thead>';
      html += '    <tbody>';
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