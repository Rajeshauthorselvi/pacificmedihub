<span style="display: none;">{{$current_route=request()->route()->getName()}}</span>
<div class="column-block">
 	<ul class="box-menu treeview-list treeview collapsable" >
 		<li>
 			<a class="link @if($current_route=='my-profile.index'||$current_route=='my-profile.edit') active @endif" href="@if($current_route=='my-profile.index') javascript:void(0); @else {{ route('my-profile.index') }} @endif">
   			<i class="far fa-user-circle"></i>&nbsp;&nbsp;My Profile
      </a>
    </li>
    <?php 
      $check_parent = App\Models\UserCompanyDetails::where('customer_id',Auth::id())->first();
    ?>
    @if($check_parent->parent_company==0)
      <li class="drop-down-menu">
        <a class="drop-down link"><i class="far fa-comments"></i>&nbsp;&nbsp;RFQ</a>
        <ul class="drop-down-list">
          <li>
            <a class="link @if($current_route=='my-rfq.index'||$current_route=='my-rfq.show'||$current_route=='my-rfq.edit'||$current_route=='my.rfq.comments') active @endif" href="@if($current_route=='my-rfq.index') javascript:void(0); @else {{ route('my-rfq.index') }} @endif">
              <i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;My RFQ</a>
          </li>
          <li>
             <a class="link">
              <i class="fas fa-angle-double-right"></i>&nbsp;&nbsp;Child RFQ</a>
          </li>
        </ul>
      </li>
    @else  
      <li>
    		<a class="link @if($current_route=='my-rfq.index'||$current_route=='my-rfq.show'||$current_route=='my-rfq.edit'||$current_route=='my.rfq.comments') active @endif" href="@if($current_route=='my-rfq.index') javascript:void(0); @else {{ route('my-rfq.index') }} @endif">
        	<i class="far fa-comments"></i>&nbsp;&nbsp;My RFQ
        </a>
      </li>
    @endif

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

<style type="text/css">
  .customer-page .column-block ul.box-menu li.drop-down-menu .drop-down-list{
    display: none;
    position: absolute;
  }
  .customer-page .column-block ul.box-menu li.drop-down-menu:hover .drop-down-list{
    position: relative;
    display: block;
    top: -10px;
  }
  .customer-page .column-block ul.box-menu li.drop-down-menu .drop-down-list li{
    border: none;
  }
  .customer-page .column-block ul.box-menu li.drop-down-menu .drop-down-list li a {
    color: #313131;
    padding: 8px 5px 8px 25px;
    font-size: 15px;
  }
  .customer-page .column-block ul.box-menu li.drop-down-menu .drop-down-list li a:hover{
    color: #3e72b1;
  }
  .customer-page .column-block ul.box-menu li.drop-down-menu .drop-down-list li a i{
    font-size: 12px;
  }
  .customer-page .column-block ul.box-menu li.drop-down-menu::before {
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    content: "\f054";
    position: absolute;
    top:4.5rem;
    right: 1.4rem;
    color: #888;
}
.customer-page .column-block ul.box-menu li.drop-down-menu:hover::before{content:"\f078"}
</style>