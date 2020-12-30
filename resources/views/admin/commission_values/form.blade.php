@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Commissions</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Commissions</li>
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
          <a href="{{route('comission_value.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                @if ($type=="edit")
                  <h3 class="card-title">Edit Commissions Value</h3>
                @else     
                  <h3 class="card-title">Add New Commissions Value</h3>
                @endif
              </div>
              <div class="card-body">
                @if ($type=="create")
                  {!! Form::open(['route'=>'comission_value.store','method'=>'POST','id'=>'form-validate']) !!}
                @else
                  {{ Form::model($commission_value,['method' => 'PATCH', 'route' =>['comission_value.update',$commission_value->id]]) }}
                @endif
                  <div class="form-group">
                    <label for="commission_id">Commission</label>
                    {{ Form::select('commission_id',$commissions,null,['class'=>'form-control select2bs4','id'=>'commission_id']) }}
                    @if($errors->has('commission_id'))
                      <span class="text-danger">{{ $errors->first('commission_id') }}</span>
                    @endif
                  </div>

                  <div class="form-group">
                    <label for="commission_type">Commission Type</label>
                    {{ Form::select('commission_type',$commission_type,null,['class'=>'form-control select2bs4','id'=>'commission_type']) }}
                    @if($errors->has('commission_type'))
                      <span class="text-danger">{{ $errors->first('commission_type') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    <label for="commission_value">Commission Value</label>
                    {{ Form::text('commission_value',null,['class'=>'form-control','id'=>'commission_value']) }}
                    @if($errors->has('commission_value'))
                      <span class="text-danger">{{ $errors->first('commission_value') }}</span>
                    @endif
                  </div>
                  <div class="form-group">
                    @if (isset($commission_value->published) && $commission_value->published==1)
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
                    <a href="{{route('comission_value.index')}}" class="btn reset-btn">Cancel</a>
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
       $(function ($) {
          $('.select2bs4').select2({
            minimumResultsForSearch: -1
          });
        });
    </script>
  @endpush
@endsection