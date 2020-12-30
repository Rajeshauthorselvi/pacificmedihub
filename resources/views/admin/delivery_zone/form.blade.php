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
              <li class="breadcrumb-item active">Delivery Zone</li>
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
                @if ($type=="edit")
                  <h3 class="card-title">Edit Delivery Zone</h3>
                @else     
                  <h3 class="card-title">Add New Delivery Zone</h3>
                @endif
              </div>
              <div class="card-body">
                @if ($type=="create")
                  {!! Form::open(['route'=>'delivery_zone.store','method'=>'POST','id'=>'form-validate']) !!}
                @else
                  {{ Form::model($zone,['method' => 'PATCH', 'route' =>['delivery_zone.update',$zone->id]]) }}
                @endif
                  <div class="form-group">
                    <label for="post_code">Post Code</label>
                    {{ Form::text('post_code',null,['class'=>'form-control','id'=>'post_code']) }}
                    @if($errors->has('post_code'))
                      <span class="text-danger">{{ $errors->first('post_code') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="delivery_fee">Delivery Fee</label>
                    {{ Form::text('delivery_fee',null,['class'=>'form-control','id'=>'delivery_fee']) }}
                    @if($errors->has('delivery_fee'))
                      <span class="text-danger">{{ $errors->first('delivery_fee') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    @if (isset($zone->status) && $zone->status==1)
                        
                        @php $checked='checked'; @endphp
                    @else  
                        @php $checked=''; @endphp
                    @endif
                    
                    <label><input type="checkbox" name="status" {{ $checked }}> Published</label>
                    
                  </div>
                  <div class="form-group">
                    <a href="{{route('delivery_zone.index')}}" class="btn reset-btn">Cancel</a>
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

@endsection