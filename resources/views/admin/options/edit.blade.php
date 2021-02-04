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
              <li class="breadcrumb-item active">Options</li>
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
                    <label for="option_name">Option Name</label>
                    {{ Form::text('option_name',null,['class'=>'form-control','id'=>'option_name']) }}
                    @if($errors->has('option_name'))
                      <span class="text-danger">{{ $errors->first('option_name') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="display_order">Display Order</label>
                    {{ Form::text('display_order',null,['class'=>'form-control','id'=>'display_order','onkeyup'=>'validateNum(event,this);']) }}
                    @if($errors->has('display_order'))
                      <span class="text-danger">{{ $errors->first('display_order') }}</span>
                    @endif
                  </div>
                  <div class="form-group">


                    <label for="option_values">Option Values</label>
                    <div class="">
                    {{ Form::text('options', null, array('class' => 'raters form-control','id'=>'option','placeholder'=>'Type Options')) }}
                       <div id="chat-screen" class="well">
                        <div style="padding: 5px">Add Option values</div>
                        <ul class="chat-screen list-unstyled">

                          @foreach($option_values as $option_id=>$option_value)
                            <li class="multipleInput-value"> 
                              {{$option_value}}
                              <span>
                                <input class="rater_value" value="{{$option_value}}" name="option_values[{{ $option_id }}]" type="hidden">
                              </span>
                                <a href="javascript:void(0)" class="multipleInput-close" title="Remove"><i class="fa fa-times-circle"></i> </a>
                            </li>
                          @endforeach
            
                        </ul>
                       </div>
                    </div>
                     @if($errors->has('option_values'))
                      <span class="text-danger">{{ $errors->first('option_values') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    @if (isset($option->published) && $option->published==1)
                      @php $checked='checked'; @endphp
                    @else  
                      @php $checked=''; @endphp
                    @endif
                    <div class="icheck-info d-inline">
                      <input type="checkbox" name="status" id="Published" {{ $checked }}> 
                      <label for="Published">Published</label>
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

.chat-screen {
   
    margin: 10px;
    min-height: 80px;
    max-height: 80px;
    overflow: auto;
    width: auto;
}
li.multipleInput-value {
    float: left;
    margin-right: 2px;
    margin-bottom: 1px;
    border: 1px #eee solid;
    padding: 2px;
    background: #eee;
}
.multipleInput-close {
    width: 16px;
    height: 16px;
    display: block;
    float: right;
    margin: 0 3px;
}
#chat-screen{
   padding: 0;
   overflow: auto;
   border: 1px solid #ccc;
}
.required{
  border-color: #a94442 !important;
}
</style>
  @push('custom-scripts')
<script type="text/javascript">


$(".multipleInput-close").click(function(){
    $(this).parent().remove();
    return false;
});

$('#option').keydown(function(event) {

    if (event.keyCode == 13 || event.keyCode == 9) {
          var append_val = true;
          var keypress_val=$(this).val();
             $(".chat-screen .multipleInput-value").each(function(){
                 if(keypress_val.trim() == $(this).text().trim())
                 { 
                    append_val = false;
                 }
             });

      if ($(this).val()!=""  && append_val==true) {
         $('.chat-screen').append($('<li class="multipleInput-value" > ' + $("#option").val() + '<span><input type="hidden" value="' + $("#option").val() + '" name="option_values[]"></span></li>')
                      .append($('<a href="javascript:void(0)" class="multipleInput-close" title="Remove"><i class="fa fa-times-circle"></i></a>')
                                   .click(function(e) {
                                        $(this).parent().remove();
                                        e.preventDefault();
                                   })
                              )
                   );
      }
    else{
      
      if ($(this).val()!="" ) {
       alert('This Rater already exists');
      }
    }
    $('.remove-message').remove();
    $('#chat-screen').removeClass('required');
    $("#activate-step-2"). removeAttr("disabled");
    $(this).val('');
    event.preventDefault();
    return false;
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
/*Rater Field Keypress*/

$('#rater').keydown(function(event) {

    if (event.keyCode == 13 || event.keyCode == 9) {
          var append_val = true;
          var keypress_val=$(this).val();
             $(".chat-screen .multipleInput-value").each(function(){
                 if(keypress_val.trim() == $(this).text().trim())
                 { 
                    append_val = false;
                 }
             });

      if ($(this).val()!=""  && append_val==true) {
         $('.chat-screen').append($('<li class="multipleInput-value" > ' + $("#rater").val() + '<span><input type="hidden" value="' + $("#rater").val() + '" name="rater_value[]"></span></li>')
                      .append($('<a href="javascript:void(0)" class="multipleInput-close" title="Remove"><i class="glyphicon glyphicon-remove-sign"></i></a>')
                                   .click(function(e) {
                                        $(this).parent().remove();
                                        e.preventDefault();
                                   })
                              )
                   );
      }
    else{
      
      if ($(this).val()!="" ) {
       alert('This Rater already exists');
      }
    }
    $('.remove-message').remove();
    $('#chat-screen').removeClass('required');
    $("#activate-step-2"). removeAttr("disabled");
    $(this).val('');
    event.preventDefault();
    return false;
    }
});
/*End Rater Field Keypress*/
    </script>
  @endpush

@endsection

