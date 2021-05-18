@extends('admin.layouts.master')
@section('main_container')

<script src="{{ asset('theme/plugins/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('theme/plugins/ckeditor/samples/js/sample.js') }}"></script>
<link rel="stylesheet" href="{{ asset('theme/plugins/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css') }}">


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Add Page</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('rfq.index')}}">Static Pages</a></li>
              <li class="breadcrumb-item active">Add Page</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif    
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('static-page.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid product">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Page</h3>
              </div>
              <div class="card-body">
                {!! Form::open(['route'=>'static-page.store','method'=>'POST','class'=>'page-form']) !!}
                  <div class="form-group">
                    {!! Form::hidden('from','page') !!}
                    <div class="col-sm-12">
                      <label for="pageTitle">Page Title *</label>
                      {!!Form::text('page_title',null, ['class'=>'form-control','id'=>'pageTitle'])!!}
                      <span class="text-danger page_title" style="display:none;">Page Title is required.</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <label>Page Contents *</label>
                      <div class="adjoined-bottom">
                        <div class="grid-container">
                          <div class="grid-width-100">
                            <textarea id="editor" name="page_content"></textarea>
                          </div>
                        </div>
                      </div>
                      <span class="text-danger page_contents" style="display:none;">Page Contents is required.</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-8">
                      <label for="searchEngine">Search Engine Friendly Page Name</label>
                      <span title="Set a search engine friendly page name e.g. 'the-best-page' to make your page URL 'http://www.abcdxyz.com/the-best-page'." class="ico-help">
                        <i class="fa fa-question-circle"></i>
                      </span>
                      {!!Form::text('search_engine',null, ['class'=>'form-control','id'=>'searchEngine'])!!}
                    </div>
                    <div class="col-sm-2">
                      <label for="sortOrder">Sort by Order</label>
                      {!!Form::text('sort_order',null, ['class'=>'form-control','id'=>'sortOrder'])!!}
                    </div>
                    <div class="col-sm-2" style="margin-top:2.5rem">
                      <div class="icheck-info d-inline">
                        <input type="checkbox" name="published" id="published" checked>
                        <label for="published">Published</label>
                      </div>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <a href="{{ route('static-page.index') }}" class="btn reset-btn">Cancel</a>
                      <button class="btn save-btn" type="button">Save</button>
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



  <style type="text/css">
    .form-group{
      display: flex;
    }
  </style>
  <script>
    initSample();
  </script>

  @push('custom-scripts')
    <script type="text/javascript">

      $('.page-form').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();
          return false;
        }
      });

      $(document).on('click', '.save-btn', function(event) {
        if(validate()!=false){
          $('.page-form').submit();
        }else{
          scroll_to();
          return false;
        }
      });
      
      function validate(){
        var valid=true;
        if ($("#pageTitle").val()=="") {
          $("#pageTitle").closest('.form-group').find('span.text-danger.page_title').show();
          valid = false;
        }
      }

      function scroll_to(form){
        $('html, body').animate({
          scrollTop: $(".page-form").offset().top
        },1000);
      }

    </script>
  @endpush
@endsection