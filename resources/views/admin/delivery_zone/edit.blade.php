@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Delivery Zone</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('delivery_zone.index')}}">Delivery Zone</a></li>
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
          <a href="{{route('delivery_zone.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Delivery Zone</h3>
              </div>
              <div class="card-body">
                {!! Form::open(['route'=>'delivery_zone.store','method'=>'POST','id'=>'zoneForm']) !!}
                  <div class="form-group clearfix">
                    <label for="region_name">Region Name *</label><br>
                    {{ Form::text('region_name',$region->name,['class'=>'form-control','id'=>'region_name']) }}
                    <span class="text-danger name" style="display:none;">Region Name is required</span>
                    @if($errors->has('region_name'))
                      <span class="text-danger">{{ $errors->first('region_name') }}</span>
                    @endif
                  </div>

                  <div class="form-group">
                    <div class="col-sm-12">
                      <label for="option_name">Zone's *</label>
                      <div class="table-responsive"> 
                        <table class="table table-bordered"> 
                          <thead> 
                            <tr> 
                              <th class="text-center">#</th>
                              <th class="text-center">Post Code</th>
                              <th class="text-center">Display</th>
                              <th class="text-center">
                                <button class="btn btn-md btn-primary" id="addBtn" type="button">
                                  <i class="fas fa-plus"></i>
                                </button>
                              </th> 
                            </tr> 
                          </thead> 
                          <tbody id="tbody">
                            <?php $count=1; ?>
                            @foreach($post_codes as $values)
                              <tr id="R1" class="parent_tr"> 
                                <input type="hidden" id="valueId" name="option_values[id][]" value="{{ $values->id }}">
                                <td class="row-index text-center"> 
                                  <p id="count">{{ $count }}</p>
                                </td>
                                <td>
                                  <input type="text" class="form-control" name="post_code[name][]" autocomplete="off" onkeyup="validateNum(event,this);" maxlength="6" value="{{ $values->post_code }}">
                                </td>
                                <td>
                                  <div class="form-group">
                                    <select class="form-control display select2bs4" name="post_code[published][]" style="width:100%">
                                      <option value="1" @if($values->published==1) selected @endif>Yes</option>
                                      <option value="0" @if($values->published==0) selected @endif>No</option>
                                    </select>
                                  </div>
                                </td>
                                <td class="text-center"> 
                                  <button class="btn btn-danger delete" type="button"><i class="fas fa-trash"></i></i>
                                  </button> 
                                </td> 
                              </tr>
                              <?php $count++; ?>
                            @endforeach                        
                          </tbody> 
                        </table> 
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="status" id="Published" @if($region->published==1) checked @endif> 
                      <label for="Published">Published</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="{{route('delivery_zone.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="button" class="btn save-btn">Save</button>
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
      $(function ($) {
        $('body').find('.display.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });
      $(document).ready(function () { 
        var rowIdx = <?php echo $values_count; ?>; 
        $('#addBtn').on('click', function () { 
          $('#tbody:last').append(
            `<tr id="R${++rowIdx}" class="parent_tr">
              <td class="row-index text-center"> 
                <p>${rowIdx}</p>
              </td>
              <td>
                <input type="text" class="form-control" id="value" name="post_code[name][]" onkeyup="validateNum(event,this);" maxlength="6">
              </td>
              <td>
                <div class="form-group">
                  <select class="form-control display select2bs4" name="post_code[published][]">
                    <option value="1" selected>Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
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

      $(document).on('keyup','#value',function(event) {
        var value = $(this).val();
        value = value.replace(/[_\W]+/g, "");
        var valueCode = value.substr(0, 6);
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

      $('#zoneForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });


      $(document).on('click', '.save-btn', function(event) {
        if(validate()!=false){
          $('#zoneForm').submit();
        }else{
          scroll_to();
          return false;
        }
      });

      function validate(){
        var valid=true;
        if ($("#region_name").val()=="") {
          $("#region_name").closest('.form-group').find('span.text-danger.name').show();
          valid = false;
        }else{
          $("#region_name").closest('.form-group').find('span.text-danger.name').hide();
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
          scrollTop: $("#zoneForm").offset().top
        },1000);
      }

      $('#tbody').on('click', '.delete', function (event) { 
        if (confirm('Are you sure you want to delete?')) {
          $(this).closest('tr').remove(); 
          var valueId = $(this).closest('.parent_tr').find('#valueId').val();
          $.ajax({
            url: "{{route('delete.post.code')}}",
            type: "POST",
            data: {"_token": "{{ csrf_token() }}",id:valueId},
            dataType: "html",
            success: function(response){       
              alert("Post Code deleted successfully.!");
            }
          });
        } else {
          return false;
        }

      });

    </script>
  @endpush

@endsection