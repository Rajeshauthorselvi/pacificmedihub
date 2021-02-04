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
                @if ($type=="edit")
                  <h3 class="card-title">Edit Product Option</h3>
                  <h3 class="card-title">Add New Product Option</h3>
                @endif
              </div>
              <div class="card-body">
                @if ($type=="create")
                  {!! Form::open(['route'=>'options.store','method'=>'POST','id'=>'form-validate']) !!}
                @else
                  {{ Form::model($option,['method' => 'PATCH', 'route' =>['options.update',$option->id]]) }}
                @endif
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
                    <button type="submit" class="btn save-btn">Save</button>
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