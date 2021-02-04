@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Product Option Values</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item"><a href="{{route('options.index')}}">Options</a></li>
              <li class="breadcrumb-item active">Option Values</li>
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
          <a href="{{route('option_values.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                @if ($type=="edit")
                  <h3 class="card-title">Edit Product Option</h3>
                @else     
                  <h3 class="card-title">Add New Product Option</h3>
                @endif
              </div>
              <div class="card-body">
                @if ($type=="create")
                  {!! Form::open(['route'=>'option_values.store','method'=>'POST','id'=>'form-validate']) !!}
                @else
                  {{ Form::model($option_values,['method' => 'PATCH', 'route' =>['option_values.update',$option_values->id]]) }}
                @endif
                  <div class="form-group">
                    <label for="option_id">Option</label>
                    {{ Form::select('option_id',$product_options,null,['class'=>'form-control select2bs4','id'=>'option_id']) }}
                    @if($errors->has('option_id'))
                      <span class="text-danger">{{ $errors->first('option_id') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="option_value">Option Value</label>
                    {{ Form::text('option_value',null,['class'=>'form-control','id'=>'option_value']) }}
                    @if($errors->has('option_value'))
                      <span class="text-danger">{{ $errors->first('option_value') }}</span>
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
                    <a href="{{route('option_values.index')}}" class="btn reset-btn">Cancel</a>
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