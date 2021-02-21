@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Product Options</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li> 
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item"><a href="{{route('options.index')}}">Options</a></li>
              <li class="breadcrumb-item active">Edit</li>
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
          <a href="{{route('options.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                  <h3 class="card-title">Edit Product Option</h3>

              </div>
              <div class="card-body">
     
                  {{ Form::model($option,['method' => 'PATCH', 'class'=>'optionForm' , 'route' =>['options.update',$option->id]]) }}
                  <div class="form-group">
                    <div class="col-sm-12">
                      <label for="option_name">Option Name *</label>
                      <span class="text-danger name" style="display:none;">Option Name is required</span>
                      {{ Form::text('option_name',null,['class'=>'form-control','id'=>'option_name']) }}
                      @if($errors->has('option_name'))
                        <span class="text-danger">{{ $errors->first('option_name') }}</span>
                      @endif
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="col-sm-12">
                      <label for="option_name">Option Value *</label>
                      <div class="table-responsive"> 
                        <table class="table table-bordered"> 
                          <thead> 
                            <tr> 
                              <th class="text-center">#</th>
                              <th class="text-center">Value</th> 
                              <th class="text-center">
                                Code &nbsp;
                                <span title="This Code is used for Product Variant SKU." class="ico-help">
                                  <i class="fa fa-question-circle"></i>
                                </span>
                              </th> 
                              <th class="text-center">
                                <button class="btn btn-md btn-primary" id="addBtn" type="button">
                                  <i class="fas fa-plus"></i>
                                </button>
                              </th> 
                            </tr> 
                          </thead> 
                          <tbody id="tbody"> 
                            @foreach($option_values as $values)
                              <tr id="R1" class="parent_tr"> 
                                <input type="hidden" id="valueId" name="option_values[id][]" value="{{ $values->id }}">
                                <td class="row-index text-center"> 
                                  <p id="count">{{ $values->display_order }}</p>
                                  <input type="hidden" id="hiddenCount" name="option_values[count][]" value="{{ $values->display_order }}">
                                </td>
                                <td>
                                  <input type="text" class="form-control" id="value" name="option_values[value][]" autocomplete="off" value="{{ $values->option_value }}">
                                </td>
                                <td>
                                  <input type="text" class="form-control" id="code" name="option_values[code][]" value="{{ $values->option_value_code }}">
                                </td>
                                <td class="text-center"> 
                                  <button class="btn btn-danger delete" type="button"><i class="fas fa-trash"></i></i>
                                  </button> 
                                </td> 
                              </tr>
                            @endforeach
                          </tbody> 
                        </table> 
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-6">
                      <label for="display_order">Display Order *</label>
                      {{ Form::text('display_order',null,['class'=>'form-control','id'=>'display_order','onkeyup'=>'validateNum(event,this);']) }}
                      <span class="text-danger display" style="display:none;">Display Order of Option is required</span>
                      @if($errors->has('display_order'))
                        <span class="text-danger">{{ $errors->first('display_order') }}</span>
                      @endif
                    </div>
                    @if (isset($option->published) && $option->published==1)
                      @php $checked='checked'; @endphp
                    @else  
                      @php $checked=''; @endphp
                    @endif
                    <div class="col-sm-6" style="margin-top: 35px">
                      <div class="icheck-info d-inline">
                        <input type="checkbox" name="status" id="Published" {{ $checked }}> 
                        <label for="Published">Published</label>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <a href="{{route('options.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" id="submit-btn" class="btn save-btn">Save</button>
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
    .form-group{display: flex;}
    #addBtn, .remove, .delete {font-size: 12px;padding: 5px 10px;}
    .ico-help{cursor:pointer;}
  </style>

  @push('custom-scripts')
    <script type="text/javascript">
      $(document).ready(function () { 
        var rowIdx = <?php echo $values_count; ?>;
        $('#addBtn').on('click', function () { 
          $('#tbody:last').append(
            `<tr id="R${++rowIdx}" class="parent_tr">
              <input type="hidden" id="valueId" name="option_values[id][]" value="">
              <td class="row-index text-center"> 
                <p>${rowIdx}</p>
                <input type="hidden" id="hiddenCount" name="option_values[count][]" value="${rowIdx}">
              </td>
              <td>
                <input type="text" class="form-control" id="value" name="option_values[value][]" value="">
              </td>
              <td>
                <input type="text" class="form-control" id="code" name="option_values[code][]" value="">
              </td>
              <td class="text-center"> 
                <button class="btn btn-danger remove" type="button"><i class="fas fa-minus"></i></button> 
              </td> 
            </tr>`
          );
        }); 

        $('#tbody').on('click', '.remove', function () { 
          var child = $(this).closest('tr').nextAll(); 
          child.each(function () { 
            var id = $(this).attr('id'); 
            var idx = $(this).children('.row-index').children('p'); 
            var hideencount = $(this).children('.row-index').children('#hiddenCount'); 
            var dig = parseInt(id.substring(1)); 
            idx.html(`${dig - 1}`); 
            hideencount.val(`${dig - 1}`);
            $(this).attr('id', `R${dig - 1}`); 
          }); 

          var rowCount = $('#tbody tr').length;
          if(rowCount<3){
            $('.remove').css('pointer-events','none');
            $('.remove').css('opacity','0.3');
          }
          $(this).closest('tr').remove(); 
          rowIdx--;
        }); 
      }); 

      $('#tbody').on('click', '.delete', function (event) { 
        if (confirm('Are you sure you want to delete?')) {
          $(this).closest('tr').remove(); 
          var valueId = $(this).closest('.parent_tr').find('#valueId').val();
          $.ajax({
            url: "{{route('delete.option.value')}}",
            type: "POST",
            data: {"_token": "{{ csrf_token() }}",id:valueId},
            dataType: "html",
            success: function(response){       
              alert("Value deleted successfully.!");
            }
          });
        } else {
          return false;
        }

      });


      $(document).on('keyup','#value',function(event) {
        var value = $(this).val();
        var valueCode = value.substr(0, 5);
        $(this).closest('.parent_tr').find('#code').val(valueCode.toUpperCase());
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


      $('.optionForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });


      $(document).on('click', '.save-btn', function(event) {
        if(validate()!=false){
          $('.optionForm').submit();
        }else{
          scroll_to();
          return false;
        }
      });

      function validate(){
        var valid=true;
        if ($("#option_name").val()=="") {
          $("#option_name").closest('.form-group').find('span.text-danger.name').show();
          valid = false;
        }else{
          $("#option_name").closest('.form-group').find('span.text-danger.name').hide();
        }

        if ($("#display_order").val()=="") {
          $("#display_order").closest('.form-group').find('span.text-danger.display').show();
          valid = false;
        }else{
          $("#display_order").closest('.form-group').find('span.text-danger.display').hide();
        }

        var empty_field = $('#tbody .form-control').filter(function(){
          return !$(this).val();
        }).length;

        if (empty_field!=0) {
          alert('Please fill the value before save.!');
          return false;
        }
        return valid;
      }

      function scroll_to(form){
        $('html, body').animate({
          scrollTop: $(".optionForm").offset().top
        },1000);
      }
    </script>
  @endpush
@endsection

