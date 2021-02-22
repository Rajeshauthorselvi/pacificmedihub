<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prefix;
use App\Models\Orders;
use App\Models\Prurchase;
use App\Models\RFQ;
use App\Models\Product;
use App\User;
use App\Models\Vendor;
use App\Models\Employee;
use Session;
use Redirect;
use Arr;

class PrefixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $data['status'] = [1=>'Yes',2=>'No'];
        $data['reset']  = [1=>'Yearly',2=>'No'];
        $data['prefix'] = Prefix::where('key','prefix')->pluck('content','code')->toArray();
        $data['orders_count']   = Orders::count();
        $data['purchase_count'] = Orders::count();
        $data['rfq_count']      = RFQ::count();
        $data['product_count']  = Product::count();
        $data['user_count']     = User::where('role_id',7)->count();
        $data['vendor_count']   = Vendor::count();
        $data['employee_count'] = Employee::count();

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
    
        Prefix::updateOrCreate(
            ['key'=>'prefix','code'=>$request->from],
            $data
        );


        return Redirect::back()->with('success','Prefix added successfully...!');
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

        Prefix::insert($data);
        return Redirect::back()->with('success','Prefix added successfully...!');
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
