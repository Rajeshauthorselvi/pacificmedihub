<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\MenuList;
use App\Models\Permission;
use App\Models\RoleAccessPermission;
use Redirect;
use Auth;
class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('access_control','read')) {
                abort(404);
            }
        }
        $data = array();
        $data['roles'] = Role::whereNotIn('id',[1,7])->where('is_delete',0)->orderBy('id','desc')->get();
        return view('admin.settings.access.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('access_control','create')) {
                abort(404);
            }
        }
        $data=array();
        return view('admin.settings.access.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      /*  $this->validate(request(), [
            'role_name' => 'required|unique:roles'
        ]);
*/
/*        $add_role = new Role;
        $add_role->name = $request->role_name;
        $add_role->created_at = date('Y-m-d H:i:s');*/

        // dd($request->all());
        if ($request->has('role_id')) {
            $role_id=$request->role_id;    
        }
        else{
            $add_role = new Role;
            $add_role->name = $request->role_name;
            $add_role->created_at = date('Y-m-d H:i:s');
            $add_role->save();
            $role_id=$add_role->id;
        }

        $total_opration=['read','create','update','delete'];
        if ($request->has('product_sec')) {
            $product_data=$request->get('product_sec');
            $this->EntireProductSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::whereIn('object',['product','import','category','option','option_value','brands'])->update(['allow_access'=>'no']);
        }
        /*Purchase*/
        if ($request->has('purchase')) {
            $product_data=$request->get('purchase');
            $this->EntirePurchaseSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','purchase')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','purchase_payment')->update(['allow_access'=>'no']);
        }
        /*Purchase*/
        /*Stock*/
        if ($request->has('stock')) {
            $product_data=$request->get('stock');
            $this->EntireStockSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','like','%stock_transist%')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','return')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','wastage')->update(['allow_access'=>'no']);
        }
        /*Stock*/
        /*RFQ*/
        if ($request->has('rfq')) {
            $product_data=$request->get('rfq');
            $this->EntireRFQSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','rfq')->update(['allow_access'=>'no']);
        }
        /*RFQ*/
        /*Order*/
        if ($request->has('order')) {
            $product_data=$request->get('order');
            $this->EntireOrderSec($role_id,$total_opration,$product_data);
        }
        else{
            
           RoleAccessPermission::where('object','new_order')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','assign_order')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','delivery_order')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','completed_orders')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','cancelled_order')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','order_payment')->update(['allow_access'=>'no']);
        }
        /*Order*/
        /*Customer*/
        if ($request->has('customer')) {
            $product_data=$request->get('customer');
            $this->EntireCustomerSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','customer')->update(['allow_access'=>'no']);
        }
        /*Customer*/
        /*Vendor*/
        if ($request->has('vendor')) {
            $product_data=$request->get('vendor');
            $this->EntireVendorSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','vendor')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','vendor_products')->update(['allow_access'=>'no']);
        }
        /*Vendor*/
        /*Commission*/
        if ($request->has('commission')) {
            $product_data=$request->get('commission');
            $this->EntireCommissionSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','commission')->update(['allow_access'=>'no']);
        }
        /*Commission*/
        /*Employee*/
        if ($request->has('employee')) {
            $product_data=$request->get('employee');
            $this->EntireEmployeSec($role_id,$total_opration,$product_data);
        }
        else{
            RoleAccessPermission::where('object','employee')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','salary')->update(['allow_access'=>'no']);
        }
        /*Employee*/
        /*Zone*/
        if ($request->has('zone')) {
            $product_data=$request->get('zone');
            $this->EntireDeliveryZoneSec($role_id,$total_opration,$product_data);
        }
        else{
            RoleAccessPermission::where('object','delivery_zone')->update(['allow_access'=>'no']);
        }
        /*Zone*/

        /*Settings*/
        if ($request->has('settings')) {
            $product_data=$request->get('settings');
            $this->EntireSettingSec($role_id,$total_opration,$product_data);
        }
        else{
            RoleAccessPermission::where('object','general_settings')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','access_control')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','department_setting')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','commission_setting')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','prefix_setting')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','order_setting')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','customer_setting')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','tax_setting')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','currency_setting')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','payment_setting')->update(['allow_access'=>'no']);
        }
        /*Settings*/

        /*Static*/
        if ($request->has('static_page')) {
            $product_data=$request->get('static_page');
            $this->EntireStaticSec($role_id,$total_opration,$product_data);
        }
        else{
            RoleAccessPermission::where('object','pages')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','static')->update(['allow_access'=>'no']);
            RoleAccessPermission::where('object','features')->update(['allow_access'=>'no']);
        }
        /*Static*/


        return Redirect::route('access-control.index')->with('success','Role permissions added successfully...!');

    }

    public function DataLoop($loop_count,$opration,$slug,$content,$role_id)
    {
        for ($i=0; $i <$loop_count ; $i++) { 
            $produt_sec=[
                'role_id'   => $role_id,
                'object'   => $slug,
                'operation'   => $opration[$i],
                'allow_access'   => isset($content[$opration[$i]])?'yes':'no',
                'created_at'   => date('Y-m-d H:i:s'),
            ];
        RoleAccessPermission::updateOrCreate(['object'=>$slug,'operation'=>$opration[$i]],$produt_sec);
        }

    }
    public function EntireStaticSec($role_id,$total_opration,$product_data)
    {
            /*Pages*/
            if (isset($product_data['pages'])) {
                $this->DataLoop(4,$total_opration,'pages',$product_data['pages'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','pages')->update(['allow_access'=>'no']);
            }
            /*Pages*/

            /*Slider*/
            if (isset($product_data['static'])) {
                $this->DataLoop(4,$total_opration,'static',$product_data['static'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','static')->update(['allow_access'=>'no']);
            }
            /*Slider*/

            /*Feature*/
            if (isset($product_data['features'])) {
                $this->DataLoop(4,$total_opration,'features',$product_data['features'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','features')->update(['allow_access'=>'no']);
            }
            /*Feature*/
    }
    public function EntireSettingSec($role_id,$total_opration,$product_data)
    {
            /*General Settings*/
            if (isset($product_data['general_settings'])) {
                $this->DataLoop(4,$total_opration,'general_settings',$product_data['general_settings'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','general_settings')->update(['allow_access'=>'no']);
            }
            /*General Settings*/

            /*Access Control*/
            if (isset($product_data['access_control'])) {
                $this->DataLoop(4,$total_opration,'access_control',$product_data['access_control'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','access_control')->update(['allow_access'=>'no']);
            }
            /*Access Control*/

            /*Department Settings*/
            if (isset($product_data['department_setting'])) {
                $this->DataLoop(4,$total_opration,'department_setting',$product_data['department_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','department_setting')->update(['allow_access'=>'no']);
            }
            /*Department Settings*/

            /*Commission Settings*/
            if (isset($product_data['commission_setting'])) {
                $this->DataLoop(4,$total_opration,'commission_setting',$product_data['commission_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','commission_setting')->update(['allow_access'=>'no']);
            }
            /*Commission Settings*/

            /*Prefix Settings*/
            if (isset($product_data['prefix_setting'])) {
                $this->DataLoop(4,$total_opration,'prefix_setting',$product_data['prefix_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','prefix_setting')->update(['allow_access'=>'no']);
            }
            /*Prefix Settings*/

            /*Order Settings*/
            if (isset($product_data['order_setting'])) {
                $this->DataLoop(4,$total_opration,'order_setting',$product_data['order_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','order_setting')->update(['allow_access'=>'no']);
            }
            /*Order Settings*/

            /*Customer Settings*/
            if (isset($product_data['customer_setting'])) {
                $this->DataLoop(4,$total_opration,'customer_setting',$product_data['customer_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','customer_setting')->update(['allow_access'=>'no']);
            }
            /*Customer Settings*/

            /*Tax Settings*/
            if (isset($product_data['tax_setting'])) {
                $this->DataLoop(4,$total_opration,'tax_setting',$product_data['tax_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','tax_setting')->update(['allow_access'=>'no']);
            }
            /*Tax Settings*/

            /*Currency Settings*/
            if (isset($product_data['currency_setting'])) {
                $this->DataLoop(4,$total_opration,'currency_setting',$product_data['currency_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','currency_setting')->update(['allow_access'=>'no']);
            }
            /*Currency Settings*/

            /*Payment Settings*/
            if (isset($product_data['payment_setting'])) {
                $this->DataLoop(4,$total_opration,'payment_setting',$product_data['payment_setting'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','payment_setting')->update(['allow_access'=>'no']);
            }
            /*Payment Settings*/
    }
    public function EntireDeliveryZoneSec($role_id,$total_opration,$product_data)
    {
            /*Employee*/
            if (isset($product_data['delivery_zone'])) {
                $this->DataLoop(4,$total_opration,'delivery_zone',$product_data['delivery_zone'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','delivery_zone')->update(['allow_access'=>'no']);
            }
            /*Employee*/ 
    }
    public function EntireEmployeSec($role_id,$total_opration,$product_data)
    {
            /*Employee*/
            if (isset($product_data['employee'])) {
                $this->DataLoop(4,$total_opration,'employee',$product_data['employee'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','employee')->update(['allow_access'=>'no']);
            }
            /*Employee*/ 

            /*Salary*/
            if (isset($product_data['salary'])) {
                $this->DataLoop(4,$total_opration,'salary',$product_data['salary'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','salary')->update(['allow_access'=>'no']);
            }
            /*Salary*/  

            /*Department*/
            if (isset($product_data['department'])) {
                $this->DataLoop(4,$total_opration,'department',$product_data['department'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','department')->update(['allow_access'=>'no']);
            }
            /*Department*/  
            /*Department*/
            if (isset($product_data['emp_commission'])) {
                $this->DataLoop(4,$total_opration,'emp_commission',$product_data['emp_commission'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','emp_commission')->update(['allow_access'=>'no']);
            }
            /*Department*/  
    }
    public function EntireCustomerSec($role_id,$total_opration,$product_data)
    {
            /*Order*/
            if (isset($product_data['customer'])) {
                $this->DataLoop(4,$total_opration,'customer',$product_data['customer'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','customer')->update(['allow_access'=>'no']);
            }
            /*Order*/
    }

    public function EntireVendorSec($role_id,$total_opration,$product_data)
    {
            /*Vendor*/
            if (isset($product_data['vendor'])) {
                $this->DataLoop(4,$total_opration,'vendor',$product_data['vendor'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','vendor')->update(['allow_access'=>'no']);
            }
            /*Vendor*/
            /*Vendor Products*/
            if (isset($product_data['vendor'])) {
                $this->DataLoop(4,$total_opration,'vendor_products',$product_data['vendor'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','vendor_products')->update(['allow_access'=>'no']);
            }
            /*Vendor Products*/
            
    }

    public function EntireCommissionSec($role_id,$total_opration,$product_data)
    {
            /*Order*/
            if (isset($product_data['commission'])) {
                $this->DataLoop(4,$total_opration,'commission',$product_data['commission'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','commission')->update(['allow_access'=>'no']);
            }
            /*Order*/
    }

    public function EntireOrderSec($role_id,$total_opration,$product_data)
    {
            /*New Order*/
            if (isset($product_data['new_order'])) {
                $this->DataLoop(4,$total_opration,'new_order',$product_data['new_order'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','new_order')->update(['allow_access'=>'no']);
            }
            /*New Order*/

            /*Assign for Delivery*/
            if (isset($product_data['assign_order'])) {
                $this->DataLoop(4,$total_opration,'assign_order',$product_data['assign_order'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','assign_order')->update(['allow_access'=>'no']);
            }
            /*Assign for Delivery*/

            /*Delivery In Progress*/
            if (isset($product_data['delivery_order'])) {
                $this->DataLoop(4,$total_opration,'delivery_order',$product_data['delivery_order'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','delivery_order')->update(['allow_access'=>'no']);
            }
            /*Delivery In Progress*/

            /*Completed Orders*/
            if (isset($product_data['completed_orders'])) {
                $this->DataLoop(4,$total_opration,'completed_orders',$product_data['completed_orders'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','completed_orders')->update(['allow_access'=>'no']);
            }
            /*Completed Orders*/

            /*Cancelled/Missed Orders*/
            if (isset($product_data['cancelled_order'])) {
                $this->DataLoop(4,$total_opration,'cancelled_order',$product_data['cancelled_order'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','cancelled_order')->update(['allow_access'=>'no']);
            }
            /*Cancelled/Missed Orders*/

            /*Order Payment*/
            if (isset($product_data['order_payment'])) {
                $this->DataLoop(4,$total_opration,'order_payment',$product_data['order_payment'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','order_payment')->update(['allow_access'=>'no']);
            }
            /*Order Payment*/

    }

    public function EntireRFQSec($role_id,$total_opration,$product_data)
    {
            /*Stock In Transist*/
            if (isset($product_data['rfq'])) {
                $this->DataLoop(4,$total_opration,'rfq',$product_data['rfq'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','rfq')->update(['allow_access'=>'no']);
            }
            /*Stock In Transist*/
    }

    public function EntireStockSec($role_id,$total_opration,$product_data)
    {
            /*Stock In Transist Vendor*/
            if (isset($product_data['stock_transist_vendor'])) {
                $this->DataLoop(4,$total_opration,'stock_transist_vendor',$product_data['stock_transist_vendor'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','stock_transist_vendor')->update(['allow_access'=>'no']);
            }
            /*Stock In Transist Vendor*/
            
            /*Stock In Transist Customer*/
            if (isset($product_data['stock_transist_customer'])) {
                $this->DataLoop(4,$total_opration,'stock_transist_customer',$product_data['stock_transist_customer'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','stock_transist_customer')->update(['allow_access'=>'no']);
            }
            /*Stock In Transist Customer*/

            /*Return*/
            if (isset($product_data['return'])) {
                $this->DataLoop(4,$total_opration,'return',$product_data['return'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','return')->update(['allow_access'=>'no']);
            }
            /*Return*/

            /*Wastage*/
            if (isset($product_data['wastage'])) {
                $this->DataLoop(4,$total_opration,'wastage',$product_data['wastage'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','wastage')->update(['allow_access'=>'no']);
            }
            /*Wastage*/

            /*Low Stock*/
            if (isset($product_data['low_stock'])) {
                $this->DataLoop(4,$total_opration,'low_stock',$product_data['low_stock'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','low_stock')->update(['allow_access'=>'no']);
            }
            /*Low Stock*/
            /*Low Stock*/
            if (isset($product_data['stock_list'])) {
                $this->DataLoop(1,$total_opration,'stock_list',$product_data['stock_list'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','stock_list')->update(['allow_access'=>'no']);
            }
            /*Low Stock*/

    }

    public function EntirePurchaseSec($role_id,$total_opration,$product_data)
    {

            /*Purchase*/
            if (isset($product_data['purchase'])) {
                $this->DataLoop(4,$total_opration,'purchase',$product_data['purchase'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','purchase')->update(['allow_access'=>'no']);
            }
            /*Purchase*/ 
            /*Purchase Payment*/
            if (isset($product_data['purchase_payment'])) {
                $this->DataLoop(2,$total_opration,'purchase_payment',$product_data['purchase_payment'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','purchase_payment')->update(['allow_access'=>'no']);
            }
            /*Purchase*/       
    }


    public function EntireProductSec($role_id,$total_opration,$product_data)
    {

            /*Products*/
            if (isset($product_data['products'])) {
                $this->DataLoop(4,$total_opration,'product',$product_data['products'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','product')->update(['allow_access'=>'no']);
            }
            /*Products*/

            /*Import Products*/
            if (isset($product_data['import'])) {
                $this->DataLoop(2,$total_opration,'import',$product_data['import'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','import')->update(['allow_access'=>'no']);
            }
            /*Import Products*/

            /*Categories*/
            if (isset($product_data['category'])) {
                $this->DataLoop(4,$total_opration,'category',$product_data['category'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','category')->update(['allow_access'=>'no']);
            }
            /*Categories*/

            /*Options*/
            if (isset($product_data['option'])) {
                $this->DataLoop(4,$total_opration,'option',$product_data['option'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','option')->update(['allow_access'=>'no']);
            }
            /*Options*/

            /*Option Value*/
            if (isset($product_data['option_value'])) {
                $this->DataLoop(4,$total_opration,'option_value',$product_data['option_value'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','option_value')->update(['allow_access'=>'no']);
            }
            /*Option Value*/

            /*Option Value*/
            if (isset($product_data['brands'])) {
                $this->DataLoop(4,$total_opration,'brands',$product_data['brands'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','brands')->update(['allow_access'=>'no']);
            }
            /*Option Value*/
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($role_id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('access_control','update')) {
                abort(404);
            }
        }        
        $data=array();
        $data['role_id']=$role_id;
        $role_permissions=RoleAccessPermission::where('role_id',$role_id)->get();
        $data['role']=Role::find($role_id);
        $permissions=array();
        foreach ($role_permissions as $key => $permission) {
            $permissions[$permission->object][$permission->operation]=$permission->allow_access;
        }
        $data['permissions']=$permissions;
        return view('admin.settings.access.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
