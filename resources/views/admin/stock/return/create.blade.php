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
                    <div class="col-sm-6 pull-left">
                      <label for="option_name">Po Number</label>
                      {{ Form::text('po_number',null,['class'=>'form-control','id'=>'po_number']) }}
                    </div>
                    <div class="col-sm-6 pull-left search-btn-sec">
                    <a href="javascript:void(0)" class="btn btn-primary search-btn">
                      Search
                    </a>
                    </div>
                  <div class="clearfix"></div>
                  <br>
                  <br>
                  <div class="product_sec">
                    
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

      $('.notes-sec').hide();
      $('.submit-sec').hide();

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
        minLength: 3,
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