@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a title="My Profile Page">My Profile</a></li>
		</ul>
	</div>
</div>
@include('flash-message')
<div class="main">
	<div class="container">
		<div class="row customer-page">
		  <div id="column-left" class="col-sm-3 hidden-xs column-left">
		   	<div class="column-block">
		     	<ul class="box-menu treeview-list treeview collapsable" >
		     		<li>
		     			<a class="link" href="javascript:void(0);">
           			<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
              </a>
            </li>
		        <li>
          		<a class="link" href="javascript:void(0);">
              	<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
              </a>
            </li>
            <li>
            	<a class="link" href="javascript:void(0);">
             		<i class="fas fa-dolly-flatbed"></i>&nbsp;&nbsp;My Orders
             	</a>
            </li>
            <li>
              <a class="link" href="{{ route('wishlist.index') }}">
            		<i class="far fa-heart"></i>&nbsp;&nbsp;My Wishlist
            	</a>
            </li>
            <li>
            	<a class="link active" href="javascript:void(0);">
            		<i class="fas fa-street-view"></i>&nbsp;&nbsp;My Address
            	</a>
            </li>
            <li>
            	<a class="link" href="{{route('customer.logout')}}">
            		<i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Logout
            	</a>
            </li>
          </ul>
        </div>
      </div>

		
		  <div class="col-sm-9">
        <h2>Manage Addresses</h2>
        <div class="add-new"><a href="{{ route('my-address.create') }}"> <i class="fas fa-plus"></i> ADD NEW ADDRESS </a></div>
        <div class="address-container">
          <div class="address-block">
            <h4>{{ $primary->name }}</h4>
            <div class="address-box">
              <div class="show-address">{{ $primary->address_line1 }} <br> {{ $primary->address_line2 }}</div>
            </div>
            <div class="contact">
              <span>Contact Number </span>: {{ $primary->mobile }}
            </div>
            <div class="action-block">
              <a href="{{ route('my-address.edit',$primary->id) }}"><i class="fas fa-edit"></i> Edit</a>
              <span class="space-break"></span>
              <a><i class="fas fa-check-circle"></i> Primary</a>
            </div>
          </div>

          @foreach($all_address as $address)
            <div class="address-block">
              <h4>{{ $address->name }}</h4>
              <div class="address-box">
                <div class="show-address">{{ $address->address_line1 }} <br> {{ $address->address_line2 }}</div>
              </div>
              <div class="contact">
                <span>Contact Number </span>: {{ $address->mobile }}
              </div>
              <div class="action-block">
                <a href="{{ route('my-address.edit',$address->id) }}"><i class="fas fa-edit"></i> Edit</a>
                <span class="space-break"></span>
                <a href="javascript:void(0);">
                  <form method="POST" action="{{ route('my-address.destroy',$address->id) }}">@csrf 
                    <input name="_method" type="hidden" value="DELETE"><i class="fas fa-trash"></i>&nbsp;&nbsp;
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this?');">Remove</button>
                  </form>
                </a><span class="space-break"></span>
                <a href="{{ route('setPrimary.address',$address->id) }}"><i class="far fa-check-circle"></i> Set as Default</a>
              </div>
            </div>
          @endforeach

         

        </div>
        
      </div>

    </div>
  </div>
</div>
  
  <style type="text/css">
    .customer-page .add-new {
      margin: 15px 0;
    }
    .customer-page .add-new a {
      padding: 15px 25px;
      width: 100%;
      display: block;
      border: 1px solid #ededed;
      border-radius: 3px;
      color: #3e72b1;
      font-weight: 600;
    }
    .customer-page .add-new a:hover {
    background: #3e72b1;
    color: #fff;
    cursor: pointer;
}
    .customer-page .address-container {
    margin: 30px 0;
}
.customer-page .address-block {
    padding: 1.5rem 2rem;
    border: 1px solid #ededed;
    margin-bottom: 1.5rem;
    border-radius: 3px;
    font-size: 14px
}
.customer-page .address-block h5 {
    margin-bottom: 10px;
}

.customer-page .address-block .contact span{
  font-size: 14px;
    display: inline-block;
    margin: 5px 0;
    font-weight: 600;
}
.customer-page .address-block .action-block {
    margin: 10px 0 0;
}
.customer-page .address-block .action-block .space-break {
    border: 1px solid #000;
    margin: 0 15px;
}
.customer-page .address-block .action-block a{
  font-weight: 600;
  color: #313131;
}
.customer-page .address-block .action-block button {
    border: none;
    background: transparent;
    font-weight: 600;
    color: #313131;
}
.customer-page .address-block .action-block a:hover, .customer-page .address-block .action-block button:hover{
    color: #3e72b1;
}

  </style>

@push('custom-scripts')
  <script type="text/javascript">

  </script>
@endpush
@endsection