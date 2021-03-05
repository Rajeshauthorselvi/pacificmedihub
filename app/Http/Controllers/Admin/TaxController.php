<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use Arr;
use Redirect;
use Auth;
class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('tax_setting','read')) {
                abort(404);
            }
        }
        $data = array();
        $data['taxes'] = Tax::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/settings/tax/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('tax_setting','create')) {
                abort(404);
            }
        }
        $data['type']='create';
        return view('admin/settings/tax/form',$data);
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
            'name'     => 'required',
            'rate'     => 'required'
        ]); 

        $input = $request->all();
        Arr::forget($input,['_token','published']);
        $input['published']=($request->published=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        Tax::insert($input);
        return Redirect::route('tax.index')->with('success','Tax added successfully...!');
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
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('tax_setting','update')) {
                abort(404);
            }
        }
        $data = array();
        $data['type']='edit';
        $data['tax']=tax::where('id',$id)->first();
        return view('admin/settings/tax/form',$data);
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
        $this->validate(request(), [
            'name'     => 'required',
            'rate'     => 'required'
        ]); 

        $published=($request->published=='on')?1:0;

        $update_tax = Tax::find($id);
        $update_tax->name = $request->name;
        $update_tax->code = $request->code;
        $update_tax->rate = $request->rate;
        $update_tax->published = $published;
        $update_tax->update();

        return Redirect::route('tax.index')->with('info','Tax updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('tax_setting','delete')) {
                abort(404);
            }
        }
        $check_tax = Tax::find($id);
        $check_tax->is_deleted = 1;
        $check_tax->deleted_at=date('Y-m-d H:i:s');
        $check_tax->update();

        if ($request->ajax())  return ['status'=>true];
        else
           return Redirect::route('tax.index')->with('error','Tax deleted successfully...!');
    }
}
