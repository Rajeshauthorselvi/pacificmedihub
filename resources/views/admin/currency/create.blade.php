@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Currency</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Currency</li>
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
          <a href="{{route('currency.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                  <h3 class="card-title">Add New Currency</h3>
              </div>
              <div class="card-body currency-block">

                <div class="col-md-6">
                  {!! Form::open(['route'=>'currency.store','method'=>'POST','id'=>'form-validate']) !!}
                    
                    <div class="form-group">
                      <label for="currency_code">Currency Code *</label>
                      {{ Form::text('currency_code',null,['class'=>'form-control','id'=>'currency_code']) }}
                      @if($errors->has('currency_code'))
                        <span class="text-danger">{{ $errors->first('currency_code') }}</span>
                      @endif
                    </div>

                    <div class="form-group">
                      <label for="currency_name">Currency Name *</label>
                      {{ Form::text('currency_name',null,['class'=>'form-control','id'=>'currency_name']) }}
                      @if($errors->has('currency_name'))
                        <span class="text-danger">{{ $errors->first('currency_name') }}</span>
                      @endif
                    </div>

                    <div class="form-group">
                      <label for="symbol">Symbol *</label>
                      {{ Form::text('symbol',null,['class'=>'form-control','id'=>'symbol']) }}
                      @if($errors->has('symbol'))
                        <span class="text-danger">{{ $errors->first('symbol') }}</span>
                      @endif
                    </div>

                    <div class="form-group">
                      <label for="exchange_rate">Exchange Rate *</label>
                      {{ Form::text('exchange_rate',null,['class'=>'form-control','id'=>'exchange_rate','onkeyup'=>"validateNum(event,this);"]) }}
                      @if($errors->has('exchange_rate'))
                        <span class="text-danger">{{ $errors->first('exchange_rate') }}</span>
                      @endif
                    </div>
                    <div class="form-group clearfix">
                      <div class="icheck-info d-inline">
                          <input type="checkbox" name="is_primary" id="primaryCurrency">
                          <label for="primaryCurrency">Primary Currency</label>
                      </div>
                    </div>
                    <div class="form-group">
                      <a href="{{route('currency.index')}}" class="btn reset-btn">Cancel</a>
                      <button type="submit" class="btn save-btn">Save</button>
                    </div>
                  {!! Form::close() !!}
                </div>

                <div class="col-md-6">
                  <!-- START CODE Attention! Do not modify this code; -->
                  <div class="currency-converter">
                    <script>var fm = "SGD";var to = "USD";var tz = "8";var sz = "1x1";var lg = "en";var st = "primary";var lr = "0";var rd = "0";</script><a href="https://currencyrate.today/converter-widget" title="Currency Converter"><script src="//currencyrate.today/converter"></script></a><div style="font-size:.8em;"><a href="https://currencyrate.today">Currency Converter</a></div>CODE -->
                    
                  </div>
                  <!-- Attention! Do not modify this code; END CODE -->
                </div>

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
        $('.select2bs4').select2({
          minimumResultsForSearch: -1
        });
      });
    </script>
  @endpush
@endsection