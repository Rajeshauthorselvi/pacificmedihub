@section('auth_guard')
<?php 
global $stock_vendor_allow;
$stock_vendor_allow="";
if(Auth::guard('employee')->user()->isAuthorized('stock_transist_vendor','read')){
	 $stock_vendor_allow="yes";
}
?>
@endsection