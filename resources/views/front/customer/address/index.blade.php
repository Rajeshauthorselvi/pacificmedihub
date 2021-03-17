@extends('front.layouts.default')
@section('front_end_container')
<div class="breadcrumbs">
	<div class="container">
		<ul class="items">
			<li><a href="{{ url('/') }}" title="Go to Home Page">Home</a></li>
			<li><a href="{{ route('my-profile.index') }}" title="My Profile Page">My Profile</a></li>
      <li><a title="My Address">My Address</a></li>
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
		     			<a class="link" href="{{ route('my-profile.index') }}">
           			<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
              </a>
            </li>
		        <li>
          		<a class="link" href="{{ route('myrfq.index') }}">
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
          <div class="address-block primary">
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
                <a href="{{ route('setPrimary.address',$address->id) }}" onclick="return confirm('Are you sure?');">
                  <i class="far fa-check-circle"></i> Set as Primary
                </a>
              </div>
            </div>
          @endforeach

        </div>
        
      </div>

    </div>
  </div>
</div>

@endsection