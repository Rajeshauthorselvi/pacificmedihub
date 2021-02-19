<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\MenuList;
use App\Models\Permission;
use App\Models\RoleAccessPermission;

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
        $data['roles'] = Role::whereNotIn('id',[1,7])->where('is_delete',0)->orderBy('created_at','desc')->get();
        return view('admin.settings.access.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.settings.access.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        $this->validate(request(), [
            'role_name' => 'required|unique:roles'
        ]);

        $add_role = new Role;
        $add_role->name = $request->role_name;
        $add_role->created_at = date('Y-m-d H:i:s');
        //$add_role->save();

        //if($add_role){}

        if($request->product){

            $product_data = [];
            $i = 0;
            foreach ($request->product['menu'] as $menu) {
                $product_data[$i]['menu'] = $menu;
                $i++;
            }

            $i = 0;
            foreach ($request->product['route'] as $route) {
                $product_data[$i]['route'] = $route;
                $i++;
            }

            foreach ($product_data as $prd_data) {
                $add_menu = new MenuList();
                $add_menu->menu_name = $prd_data['menu'];
                $add_menu->route_name = $prd_data['route'];
                $add_menu->save();
            }

            
                $add_role_permission = new RoleAccessPermission;
                $add_role_permission->role_id = $add_role->id;
                /*$add_role_permission->menu_id = ;
                $add_role_permission->permission_id = ;*/
            

            
        }

        

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
    public function edit($id)
    {
        //
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
