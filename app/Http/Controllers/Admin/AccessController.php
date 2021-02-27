<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\MenuList;
use App\Models\Permission;
use App\Models\RoleAccessPermission;
use Redirect;
class AccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $data=array();
        $data['role_id']=$request->role_id;
        $data['role']=Role::find($request->role_id);
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
        $role_id=$request->role_id;
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
        }
        /*Purchase*/
        if ($request->has('stock')) {
            $product_data=$request->get('stock');
            $this->EntireStockSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','like','%stock_transist%')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','return')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','wastage')->update(['allow_access'=>'no']);
        }

        if ($request->has('rfq')) {
            $product_data=$request->get('rfq');
            $this->EntireRFQSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','rfq')->update(['allow_access'=>'no']);
        }

        if ($request->has('order')) {
            $product_data=$request->get('order');
            $this->EntireOrderSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','order')->update(['allow_access'=>'no']);
        }

        if ($request->has('customer')) {
            $product_data=$request->get('customer');
            $this->EntireCustomerSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','customer')->update(['allow_access'=>'no']);
        }

        if ($request->has('vendor')) {
            $product_data=$request->get('vendor');
            $this->EntireVendorSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','vendor')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','employee')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','salary')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','emp_commission')->update(['allow_access'=>'no']);
           RoleAccessPermission::where('object','department')->update(['allow_access'=>'no']);
        }
        if ($request->has('commission')) {
            $product_data=$request->get('commission');
            $this->EntireCommissionSec($role_id,$total_opration,$product_data);
        }
        else{
           RoleAccessPermission::where('object','commission')->update(['allow_access'=>'no']);
        }
        if ($request->has('employee')) {
            $product_data=$request->get('employee');
            $this->EntireEmployeSec($role_id,$total_opration,$product_data);
        }
        else{
            RoleAccessPermission::where('object','employee')->update(['allow_access'=>'no']);
        }

        if ($request->has('zone')) {
            $product_data=$request->get('zone');
            $this->EntireDeliveryZoneSec($role_id,$total_opration,$product_data);
        }
        else{
            RoleAccessPermission::where('object','delivery_zone')->update(['allow_access'=>'no']);
        }


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
            /*Order*/
            if (isset($product_data['vendor'])) {
                $this->DataLoop(4,$total_opration,'vendor',$product_data['vendor'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','vendor')->update(['allow_access'=>'no']);
            }
            /*Order*/
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
            /*Order*/
            if (isset($product_data['order'])) {
                $this->DataLoop(4,$total_opration,'order',$product_data['order'],$role_id);
            }
            else{
                RoleAccessPermission::where('object','order')->update(['allow_access'=>'no']);
            }
            /*Order*/
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
