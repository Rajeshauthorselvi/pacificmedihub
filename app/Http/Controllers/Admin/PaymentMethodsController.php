<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Session;
use Auth;
use Redirect;
class PaymentMethodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('payment_setting','read')) {
                abort(404);
            }
        }
        $data=array();
        $data['all_payments']=PaymentMethod::where('status','<>',3)->orderBy('id','DESC')->get();
        return view('admin.payment_methods.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('payment_setting','create')) {
                abort(404);
            }
        }
        $data=array();
        $data['type']='create';
        return view('admin.payment_methods.form',$data);
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
            'payment_method'    => 'required'
        ]);
        PaymentMethod::insert([
            'payment_method'=>$request->payment_method,
            'status'=>1
        ]);

        return Redirect::route('payment_method.index')->with('success','Payment method added successfully...!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $paymentMethod)
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('payment_setting','update')) {
                abort(404);
            }
        }
        $data=array();
        $data['payment_method']=PaymentMethod::find($paymentMethod->id);
        $data['type']='edit';

        return view('admin.payment_methods.form',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {

            $payment_method=PaymentMethod::find($paymentMethod->id);
            $payment_method->update([
                'payment_method'=>$request->payment_method,
            ]);

           return Redirect::route('payment_method.index')->with('success','Payment method updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, PaymentMethod $paymentMethod)
    {
       if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('payment_setting','delete')) {
                abort(404);
            }
        }
       $payment_method=PaymentMethod::find($paymentMethod->id);
       $payment_method->status=3;
       $payment_method->save();

        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('payment_method.index')->with('success','Payment method deleted successfully...!');
    }
}
