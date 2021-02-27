<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\OptionValue;
use Redirect;
use Arr;
use DB;
use Auth;
class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('option','read')) {
                abort(404);
            }
        }
        $data=array();
        $data['product_options']=Option::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/options/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('option','create')) {
                abort(404);
            }
        }
        $data['type']='create';
        return view('admin/options/form',$data);
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
            'option_name' => 'required', 'option_values' => 'required', 'display_order' => 'required'
        ]);

        $input = $request->all();
        Arr::forget($input,['_token','status','options','option_values']);
        $input['published']  = ($request->status=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        $option_id           = Option::insertGetId($input);

        if($option_id){
            if($request->option_values){
                $values_data = [];
                $i = 0;
                foreach ($request->option_values['count'] as $count) {
                    $values_data[$i]['count'] = $count;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->option_values['value'] as $value) {
                    $values_data[$i]['value'] = $value;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->option_values['code'] as $code) {
                    $values_data[$i]['code'] = $code;
                    $i = $i+1;
                }
            }

            foreach ($values_data as $values) {
                $option_value = new OptionValue;
                $option_value->option_id         = $option_id;
                $option_value->option_value      = $values['value'];
                $option_value->display_order     = $values['count'];
                $option_value->option_value_code = $values['code'];
                $option_value->save();
            }
        }
        return Redirect::route('options.index')->with('success','New Option added successfully.!');
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
            if (!Auth::guard('employee')->user()->isAuthorized('option','update')) {
                abort(404);
            }
        }
        $data['option']        = Option::find($id);
        $data['type']          = "edit";
        $option_values         = OptionValue::where('option_id',$id)->where('is_deleted',0)
                                    ->orderBy('display_order','asc')->get();
        $data['option_values'] = $option_values;
        $data['values_count']  = count($option_values);
        return view('admin/options/edit',$data);
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
        // dd($request->all());

        $this->validate(request(), [
            'option_name' => 'required','option_values' => 'required','display_order' => 'required'
        ]);
        $status = ($request->status=='on')?1:0;
        $option = Option::find($id);
        $option->option_name   = $request->option_name;
        $option->display_order = $request->display_order;
        $option->published     = $status;
        $option->update();


        if($request->option_values){
            $values_data = [];

            if(isset($request->option_values['id'])){
                $i = 0;
                foreach ($request->option_values['id'] as $id) {
                    $values_data[$i]['id'] = $id;
                    $i = $i + 1;
                }
            }else{
                $i = 0;
                foreach ($request->option_values['base_price'] as $base_price) {
                    $values_data[$i]['id'] = null;
                    $i = $i + 1;
                }
            }

            $i = 0;
            foreach ($request->option_values['count'] as $count) {
                $values_data[$i]['count'] = $count;
                $i = $i+1;
            }

            $i = 0;
            foreach ($request->option_values['value'] as $value) {
                $values_data[$i]['value'] = $value;
                $i = $i+1;
            }

            $i = 0;
            foreach ($request->option_values['code'] as $code) {
                $values_data[$i]['code'] = $code;
                $i = $i+1;
            }
        }


        foreach ($values_data as $values) {
            $option_value = OptionValue::find($values['id']);
            if($option_value!=null)
            {
                $option_value->option_id         = $option->id;
                $option_value->option_value      = $values['value'];
                $option_value->display_order     = $values['count'];
                $option_value->option_value_code = $values['code'];
                $option_value->update();
            }
            elseif($option_value==null)
            {
                $add_option_value = new OptionValue;
                $add_option_value->option_id         = $option->id;
                $add_option_value->option_value      = $values['value'];
                $add_option_value->display_order     = $values['count'];
                $add_option_value->option_value_code = $values['code'];
                $add_option_value->save();
            }
        }
        return Redirect::route('options.index')->with('info','Option updated successfully.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $option=Option::find($id);
        $option->is_deleted=1;
        $option->deleted_at = date('Y-m-d H:i:s');
        $option->save();

        $option_value = OptionValue::where('option_id',$id)->get();
        foreach ($option_value as $key => $value) {
            $value = OptionValue::find($value->id);
            $value->is_deleted=1;
            $value->deleted_at = date('Y-m-d H:i:s');
            $value->save();    
        }
        
        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('options.index')->with('error','Option deleted successfully...!');
    }

    public function deleteOptionValue(Request $request)
    {
        $option_value = OptionValue::find($request->id);
        $option_value->is_deleted = 1;
        $option_value->deleted_at = date('Y-m-d H:i:s');
        $option_value->update();
        return ['status'=>true];
    }
}
