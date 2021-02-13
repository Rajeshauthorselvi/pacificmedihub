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
              <li class="breadcrumb-item active">Tax</li>
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
          	<div class="col-sm-6 text-left pull-left">
          		<a href="javascript:void(0)" class="btn btn-danger delete-all">
          			<i class="fa fa-trash"></i> Delete (selected)
          		</a>
          	</div>
          	<div class="col-sm-6 text-right pull-right">
	            <a class="btn add-new" href="{{route('tax.create')}}">
	              <i class="fas fa-plus-square"></i>&nbsp;&nbsp;Add New
	            </a>
          	</div>
          </div>
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">All Tax</h3>
              </div>
              <div class="card">
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                      	<th><input type="checkbox" class="select-all"></th>
                      	<th>Name</th>
                      	<th>Code</th>
                      	<th>Tax Rate</th>
                        <th>Type</th>
                        <th>Published</th>
                      	<th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($taxes as $tax)
                        <tr>
                          <td><input type="checkbox" name="tax_ids" value="{{ $tax->id }}"></td>
                          <td>
                            {{$tax->name}} @if($tax->tax_type=='p') @  {{round($tax->rate,2)}} % @endif
                          </td>
                          <td>{{$tax->code}}</td>
                          <td>{{number_format($tax->rate,2,'.',',')}}</td>
                          <td>
                            <?php 
                              if($tax->tax_type=='f') $type='Fixed (amount)';
                              else if($tax->tax_type=='p') $type='Percentage (%)';
                             ?>
                            {{$type}}
                          </td>
                          <td>
                             <?php
                              if($tax->published==1){$published = "fa-check";}
                              else{$published = "fa-ban";}
                            ?>
                            <i class="fas {{$published}}"></i>
                          </td>
                          <td>
                            <div class="input-group-prepend">
                              <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu">
                                <a href="{{route('tax.edit',$tax->id)}}"><li class="dropdown-item"><i class="far fa-edit"></i>&nbsp;&nbsp;Edit</li></a>
                                <a href="#"><li class="dropdown-item">
                                  <form method="POST" action="{{ route('tax.destroy',$tax->id) }}">@csrf 
                                    <input name="_method" type="hidden" value="DELETE">
                                    <button class="btn" type="submit" onclick="return confirm('Are you sure you want to delete?');"><i class="far fa-trash-alt"></i>&nbsp;&nbsp;Delete</button>
                                  </form>
                                </li></a>
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
  			var checkedNum = $('input[name="tax_ids"]:checked').length;
  			if (checkedNum==0) {
  				alert('Please select comission value');
  			}
  			else{
  				if (confirm('Are you sure want to delete?')) {
	  				$('input[name="tax_ids"]:checked').each(function () {
	  					var current_val=$(this).val();
		  				$.ajax({
		  					url: "{{ url('admin/tax/') }}/"+current_val,
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