<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Role;
use Session;
use Redirect;
use Arr;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $data['departments'] = Department::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin.department.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['type']='create';
        $data['roles'] = Role::where('is_delete',0)->whereNotIn('name',['Super Admin','Admin','Customer'])->get();
        //dd($data);
        return view('admin.department.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $this->validate(request(), [
            'dept_id' => 'required',
            'dept_name' => 'required|unique:departments'
        ],[
            'dept_id.required' => 'Department ID is required',
            'dept_name.required' => 'Department Name is required',
            'dept_name.unique' => 'Department Name has already been taken'
        ]); 

        $input=$request->all();
        Arr::forget($input,['_token','status']);
        $input['status']=($request->status=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        Department::insert($input);
        return Redirect::route('departments.index')->with('success','Department added successfully.!');
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
    public function edit(Department $department)
    {
        $data['dept']=Department::find($department->id);
        $data['roles'] = Role::where('is_delete',0)->whereNotIn('name',['Super Admin','Admin','Customer'])->get();
        $data['type']="edit";
        return view('admin.department.form',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $this->validate(request(), [
            'dept_id' => 'required',
            'dept_name' => 'required'
        ],[
            'dept_id.required' => 'Department ID is required',
            'dept_name.required' => 'Department Name is required',
            'dept_name.unique' => 'Department Name has already been taken'
        ]); 

       $status=($request->status=='on')?1:0;
       $dept=Department::find($department->id);
       $dept->dept_id=$request->dept_id;
       $dept->dept_name=$request->dept_name;
       $dept->role_id=$request->role_id;
       $dept->status=$status;
       $dept->save();

       return Redirect::route('departments.index')->with('success','Department details updated successfully.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Department $department)
    {
        $dept=Department::find($department->id);
        $dept->is_deleted=1;
        $dept->deleted_at = date('Y-m-d H:i:s');
        $dept->save();

        if ($request->ajax())  return ['status'=>true];
        else
           return Redirect::route('departments.index')->with('success','Department deleted successfully.!');
    }
}
