@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Prefix</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Prefixx</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
     <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">
                  @if ($type=="create")
                      Add New Payment Methods
                  @else
                      Edit Payment Method
                  @endif
                </h3>
              </div>
              <div class="card">
                @if ($type=='create')
                  {!! Form::open(['route'=>'payment_method.index']) !!}
                @else
                  {{ Form::model($payment_method, ['route' => ['payment_method.update', $payment_method->id], 'method' => 'patch']) }}
                @endif
                 <div class="card-body order-sec">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Payment Method Name *</label>
                        {!! Form::text('payment_method',null,['class'=>'form-control']) !!}
                        @if($errors->has('payment_method'))
                          <span class="text-danger">{{ $errors->first('payment_method') }}</span>
                        @endif
                      </div>
                      <div class="form-group">
                        <a href="{{ route('payment_method.index') }}" class="btn reset-btn">
                          Cancel
                        </a>
                        <button type="submit" class="btn save-btn">
                          Save
                        </button>
                      </div>
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

@endsection