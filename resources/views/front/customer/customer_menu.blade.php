<span style="display: none;">{{$current_route=request()->route()->getName()}}</span>
<div class="column-block">
 	<ul class="box-menu treeview-list treeview collapsable" >
 		<li>
 			<a class="link @if($current_route=='my-profile.index'||$current_route=='my-profile.edit') active @endif" href="@if($current_route=='my-profile.index') javascript:void(0); @else {{ route('my-profile.index') }} @endif">
   			<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
      </a>
    </li>
    <li>
  		<a class="link @if($current_route=='my-rfq.index'||$current_route=='my-rfq.show'||$current_route=='my-rfq.edit') active @endif" href="@if($current_route=='my-rfq.index') javascript:void(0); @else {{ route('my-rfq.index') }} @endif">
      	<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
      </a>
    </li>
    <li>
    	<a class="link @if($current_route=='my-orders.index'||$current_route=='my-orders.show'||$current_route=='my-orders.edit') active @endif" href="@if($current_route=='my-orders.index') javascript:void(0); @else {{ route('my-orders.index') }} @endif">
     		<i class="fas fa-dolly-flatbed"></i>&nbsp;&nbsp;My Orders
     	</a>
    </li>
    <li>
      <a class="link @if($current_route=='wishlist.index') active @endif" href="@if($current_route=='wishlist.index') javascript:void(0); @else {{ route('wishlist.index') }} @endif">
    		<i class="far fa-heart"></i>&nbsp;&nbsp;My Wishlist
    	</a>
    </li>
    <li>
    	<a class="link @if($current_route=='my-address.index'||$current_route=='my-address.create'||$current_route=='my-address.edit') active @endif" href="@if($current_route=='my-address.index') javascript:void(0); @else {{ route('my-address.index') }} @endif">
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