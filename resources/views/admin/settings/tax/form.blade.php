@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tax</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="javascript:void(0)">Settings</a></li>
              <li class="breadcrumb-item"><a href="{{route('tax.index')}}">Tax</a></li>
              @if ($type=="edit")
                <li class="breadcrumb-item active">Edit</li>
              @else
                <li class="breadcrumb-item active">Create</li>
              @endif
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
          <a href="{{route('tax.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                @if ($type=="edit")
                  <h3 class="card-title">Edit Tax</h3>
                @else     
                  <h3 class="card-title">Add New Tax</h3>
                @endif
              </div>
              <div class="card-body">
                @if ($type=="create")
                  {!! Form::open(['route'=>'tax.store','method'=>'POST','id'=>'form-validate']) !!}
                @else
                  {{ Form::model($tax,['method' => 'PATCH', 'route' =>['tax.update',$tax->id]]) }}
                @endif
                  <div class="form-group">
                    <div class="col-sm-5">
                      <label for="name">Name *</label>
                      {{ Form::text('name',null,['class'=>'form-control','id'=>'name']) }}
                      @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                      @endif
                    </div>
                    <div class="col-sm-5">
                      <label for="code">Code</label>
                      {{ Form::text('code',null,['class'=>'form-control','id'=>'code']) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-5">
                      <label for="tax_type">Type *</label>
                      {{ Form::select('tax_type',$tax_type,null,['class'=>'form-control select2bs4','id'=>'tax_type']) }}
                      @if($errors->has('tax_type'))
                        <span class="text-danger">{{ $errors->first('tax_type') }}</span>
                      @endif
                    </div>
                    <div class="col-sm-5">
                      <label for="rate">Rate *</label>
                      {{ Form::text('rate',null,['class'=>'form-control','id'=>'rate','onkeyup'=>"validateNum(event,this);"]) }}
                      @if($errors->has('rate'))
                        <span class="text-danger">{{ $errors->first('rate') }}</span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6">
                      @if ($type=="edit")
                        @if (isset($tax->published) && $tax->published==1)
                          @php $checked='checked'; @endphp
                        @else  
                          @php $checked=''; @endphp
                        @endif
                      @else
                        @php $checked=''; @endphp
                      @endif
                      <div class="icheck-info d-inline">
                        <input type="checkbox" name="published" id="Published" {{ $checked }}> 
                        <label for="Published">Published</label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="{{route('tax.index')}}" class="btn reset-btn">Cancel</a>
                    <button type="submit" class="btn save-btn">Save</button>
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
    .form-group{display:flex;}
  </style>
  @push('custom-scripts')
    <script type="text/javascript">
       $(function ($) {
          $('.select2bs4').select2({
            minimumResultsForSearch: -1
          });
        });
    </script>
  @endpush
@endsection