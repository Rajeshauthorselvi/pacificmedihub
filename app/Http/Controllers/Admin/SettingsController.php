<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Employee;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Arr;
class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('prefix_setting','create')) {
                abort(404);
            }
        } 
        $data=array();
        $data['status']=[''=>'Please Select',1=>'Yes',2=>'No'];
        $data['reset']=[''=>'Please Select',1=>'Yearly',2=>'No'];
        $data['prefix']=Settings::where('key','prefix')->pluck('content','code')->toArray();

        $data['employee_count']=Employee::count();
        $data['product_count']=Product::count();
        $data['vendor_count']=Vendor::count();
        return view('admin.settings.prefix.form',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        Session::forget('error_place');
        Session::flash('error_place',$request->from);
        $this->validate(request(),[
            'status'    => 'required',
            'value'    => 'required',
            'reset'    => 'required',
            'from'    => 'required',
        ]);

        $data=array();
        $content=[
            'status'    => $request->status,
            'value'     => $request->value,
            'reset'     => $request->reset,
        ];
        $data['content']=serialize($content);
        $data['code']=$request->from;
        $data['key']='prefix';
        $data['if_serialize']=1;
        $data['created_at']=date('Y-m-d H:i:s');

        // Settings::insert($data);

        Settings::updateOrCreate(
            ['key'=>'prefix','code'=>$request->from],
            $data
        );


        return Redirect::back()->with('success','Prefix added successfully...!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(Settings $settings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(Settings $settings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Settings $settings)
    {
   
        Session::forget('error_place');
        Session::flash('error_place',$request->from);
        $this->validate(request(),[
            'status'    => 'required',
            'value'    => 'required',
            'reset'    => 'required',
            'from'    => 'required',
        ]);

        $data=array();
        $content=[
            'status'    => $request->status,
            'value'     => $request->value,
            'reset'     => $request->reset,
        ];
        $data['content']=serialize($content);
        $data['code']=$request->from;
        $data['key']='prefix';
        $data['if_serialize']=1;
        $data['created_at']=date('Y-m-d H:i:s');

        Settings::insert($data);
        return Redirect::back()->with('success','Prefix added successfully...!');

    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(Settings $settings)
    {
        //
    }
}
