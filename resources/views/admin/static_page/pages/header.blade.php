@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Header Block</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item">Static Pages</li>
              <li class="breadcrumb-item">Header</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <span class="hr"></span>
    @include('flash-message')
    <section class="content">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">
          <a href="{{route('static-page.index')}}"><i class="fas fa-angle-left"></i>&nbsp;Back</a>
        </li>
      </ol>
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <form action="{{route('static-page.store')}}" method="post" enctype="multipart/form-data" id="staticForm">
            @csrf
            {!! Form::hidden('from','header') !!}
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Header Block</h3>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <div class="col-sm-5">
                    <label for="mainImage">Site Logo *</label>
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="main_image" id="mainImage" accept="image/*" value="{{old('main_image',$datas['image'])}}" onchange="preview_image(event)">
                      <label class="custom-file-label" for="mainImage">{{ $datas['image'] }}</label>
                    </div>
                    <img title="Site Logo" id="output_image" src="{{ asset('theme/images/'.$datas['image'])}}"><br>
                      <div class="w-n-h">Width: <span id='width'></span> &nbsp; Height: <span id='height'></span></div>
                      <h4 id='response'></h4>
                  </div>
                  <div class="col-sm-4">
                    <label for="mainImage">Email us *</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $datas['email'] }}">
                    <span class="text-danger email" style="display:none;">Email is required.</span>
                    <span class="text-danger email-validate" style="display:none;">Please enter valid Email.</span>
                  </div>
                  <div class="col-sm-3">
                    <label for="mainImage">Free Helpline *</label>
                    <input type="text" name="helpline" class="form-control" id="helpline" value="{{$datas['helpline']}}">
                    <span class="text-danger helpline" style="display:none;">Helpline number is required.</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <a href="{{route('static-page.index')}}" class="btn reset-btn">Cancel</a>
              <button type="button" id="submit-btn" class="btn save-btn">Save</button>
            </div>
          </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <style type="text/css">
    .form-group{display:flex}
    .btn.save-btn.prefix {margin-top: 30px;}
    .w-n-h{display:none}
    #output_image {width: 150px;margin-top: 5px;}
  </style>

  @push('custom-scripts')
    <script type='text/javascript'>
      var imgwidth = 0;
      var imgheight = 0;
      var maxwidth = 165;
      var maxheight = 60;
      function preview_image(event) 
      {
        var reader = new FileReader();
        reader.onload = function(theFile)
        {
          var image = new Image();
          image.src = theFile.target.result;
          image.onload = function() {
              imgwidth = this.width; 
              imgheight = this.height;
              $(".w-n-h").css('display','block');
              $("#width").text(imgwidth);
              $("#height").text(imgheight);
              console.log(imgwidth,imgheight);
            if(imgwidth <= maxwidth && imgheight <= maxheight){
              var output = document.getElementById('output_image');
              $("#submit-btn").css('pointer-events','auto');
              $("#submit-btn").css('opacity','1');
              $("#response").css('display','none');
            }else{
              var output = document.getElementById('output_image');
              $("#response").text("Image size must be "+maxwidth+"X"+maxheight);
              $("#submit-btn").css('pointer-events','none');
              $("#submit-btn").css('opacity','0.3');
            }
          }
            var output = document.getElementById('output_image');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
      }

      $(document).on('click', '#submit-btn', function(event) {
        if(validate()!=false){
          $('#staticForm').submit();
        }else{
          return false;
        }
      });

      function validate(){
        var valid=true;
        if ($("#email").val()=="") {
          $("#email").closest('.form-group').find('span.text-danger.email').show();
          valid = false;
        }else{
          $("#email").closest('.form-group').find('span.text-danger.email').hide();
        }
        if ($("#helpline").val()=="") {
          $("#helpline").closest('.form-group').find('span.text-danger.helpline').show();
          valid = false;
        }else{
          $("#helpline").closest('.form-group').find('span.text-danger.helpline').hide();
        }
        if(!validateEmail($('#email').val())){
          $("#email").closest('.form-group').find('span.text-danger.email-validate').show();
          valid=false;
        }else{
          $("#email").closest('.form-group').find('span.text-danger.email-validate').hide();
        }
        return valid;
      }

      function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test( $email );
      }
    </script>
  @endpush
@endsection