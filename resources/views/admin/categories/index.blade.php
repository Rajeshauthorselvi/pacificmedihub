@extends('admin.layouts.master')
@section('main_container')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Categories</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{route('products.index')}}">Products</a></li>
              <li class="breadcrumb-item active">Categories</li>
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
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12 action-controllers ">
             @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('category','delete')) 
            <div class="col-sm-6 text-left pull-left">
              <a href="javascript:void(0)" class="btn btn-danger delete-all">
                <i class="fa fa-trash"></i> Delete (selected)
              </a>
            </div>
            @endif
             @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('category','create')) 
            <div class="col-sm-6 text-right pull-right">
              <a class="btn add-new" href="{{route('categories.create')}}">
              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
              </a>
            </div>
            @endif
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Categories</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="select-all"></th>
                        <th>Category Name</th>
                        <th>Number of Products</th>
                        <th>Published</th>
                        <th>Show Home</th>
                        <th>Display Order</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($categories as $category)
                        <tr>
                          <td><input type="checkbox" value="{{ $category->id }}" name="category-ids"></td>
                          <?php
                            $category_name = $category->name;
                            if($category->parent_category_id!=NULL){
                              $category_name = $category->parent->name.'  >>  '.$category->name;
                            }
                          ?>
                          <td>{{$category_name}}</td>
                          <?php $products = App\Models\Product::where('category_id',$category->id)->count(); ?>
                          <td>{{$products}}</td>
                          <?php
                            if($category->published==1){$published = "fa-check";}
                            else{$published = "fa-ban";}
                          ?>
                          <td><i class="fas {{$published}}"></i></td>
                          <?php
                            if($category->show_home==1){$show_home = "fa-check";}
                            else{$show_home = "fa-ban";}
                          ?>
                          <td><i class="fas {{$show_home}}"></i></td>
                          <td>{{ isset($category->display_order)?$category->display_order:'-' }}</td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">

                                @if (Auth::check() || Auth::guard('employee')->check()) 
                                  @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('category','update')) 
                                    <a href="{{route('categories.edit',$category->id)}}">
                                      <li class="dropdown-item">
                                        <i class="far fa-edit"></i>&nbsp;&nbsp;Edit
                                      </li>
                                    </a>
                                  @endif
                                @endif
                                @if (Auth::check() || Auth::guard('employee')->check()) 
                                  @if (Auth::check() || Auth::guard('employee')->user()->isAuthorized('category','delete')) 
                                    <a href="#">
                                      <li class="dropdown-item">
                                        <form method="POST" action="{{ route('categories.destroy',$category->id) }}">
                                          @csrf 
                                          <input name="_method" type="hidden" value="DELETE">
                                          <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete this item?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                        </form>
                                      </li>
                                    </a>
                                  @endif
                                @endif
                              </ul>
                            </div>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
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
      
    $('.select-all').change(function() {
        if ($(this). prop("checked") == true) {
          $('input:checkbox').prop('checked',true);
        }
        else{
          $('input:checkbox').prop('checked',false);
        }
    });


      $('.delete-all').click(function(event) {
        var checkedNum = $('input[name="category-ids"]:checked').length;
        if (checkedNum==0) {
          alert('Please select category');
        }
        else{
          if (confirm('Are you sure want to delete?')) {
            $('input[name="category-ids"]:checked').each(function () {
              var current_val=$(this).val();
              $.ajax({
                url: "{{ url('admin/categories/') }}/"+current_val,
                type: 'DELETE',
                data:{
                 "_token": $("meta[name='csrf-token']").attr("content")
                }
              })
              .done(function() {
                 location.reload(); 
              })
              .fail(function() {
                console.log("Ajax Error :-");
              });
            });
          }

          
        }
        
      });

  </script>
  @endpush

@endsection