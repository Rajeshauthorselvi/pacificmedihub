<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Arr;
use DB;
use Auth;
class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('currency_setting','read')) {
                abort(404);
            }
        }
        $data=array();
        $data['currency']=Currency::where('is_deleted',0)->get();
        return view('admin.currency.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('currency_setting','create')) {
                abort(404);
            }
        }
        $data=array();

        return view('admin.currency.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
                'currency_code' => 'required',
                'currency_name' => 'required',
                'exchange_rate' => 'required'
        ]);
        $input=$request->all();
        Arr::forget($input,['_token','is_primary','published']);
        if ($request->has('is_primary')) {
           DB::table('currency')->update(['is_primary'=>2]);
        }
        $input['is_primary']=($request->has('is_primary'))?1:2;
        $input['published']=($request->published=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        Currency::insert($input);
        return Redirect::route('currency.index')->with('success','Currency added successfully...!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('currency_setting','update')) {
                abort(404);
            }
        }
       $data['currency']=Currency::find($currency->id);

       return view('admin.currency.edit',$data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $this->validate(request(),[
                'currency_code' => 'required',
                'currency_name' => 'required',
                'exchange_rate' => 'required'
        ]);
        $input=$request->all();

        Arr::forget($input,['_token','is_primary','_method','published']);

        if ($request->has('is_primary')) {
           DB::table('currency')->update(['is_primary'=>2]);
        }

        $input['is_primary']=($request->has('is_primary'))?1:2;
        $input['published']=($request->published=='on')?1:0;
        $currency=Currency::where('id',$currency->id)->update($input);
        
        return Redirect::route('currency.index')->with('success','Currency updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Currency $currency)
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('currency_setting','delete')) {
                abort(404);
            }
        }
        Currency::where('id',$currency->id)->update(['status'=>3]);

        if ($request->ajax())  return ['status'=>true];
        else
        return Redirect::route('currency.index')->with('success','Currency deleted successfully...!');
    }
}
