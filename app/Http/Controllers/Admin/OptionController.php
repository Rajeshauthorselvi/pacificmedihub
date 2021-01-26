<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\OptionValue;
use Redirect;
use Arr;
use DB;
class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $data['product_options']=Option::where('is_deleted',0)->orderBy('created_at','desc')->get();
        return view('admin/options/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $this->validate(request(), ['option_name' => 'required','display_order' => 'required']); 

        $input=$request->all();
        Arr::forget($input,['_token','status','options','option_values']);
        $input['published']=($request->status=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        $option_id=Option::insertGetId($input);

        foreach ($request->option_values as $key => $values) {
            OptionValue::insert([
                'option_id'=>$option_id,
                'option_value'=> $values,
                'display_order'=>0,
                'created_at'=>date('Y-m-d H:i:s'),
                'is_deleted'=>0
            ]);
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
        $data['option']=Option::find($id);
        $data['type']="edit";
        $data['option_values'] = OptionValue::where('option_id',$id)
                                 ->where('is_deleted',0)
                                 ->orderBy('created_at','desc')
                                 ->pluck('option_value','id');

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


        $this->validate(request(), ['option_name' => 'required','display_order' => 'required']);
        $status=($request->status=='on')?1:0;
        $option=Option::find($id);
        $option->option_name=$request->option_name;
        $option->display_order=$request->display_order;
        $option->published=$status;
        $option->save();

        $option_cunt=OptionValue::where('option_id',$id)->count();

            foreach ($request->option_values as $key => $values) {
                $check_val=OptionValue::where('option_id',$id)
                           ->where('id',$key)
                           ->exists();
                if ($check_val) {
                    OptionValue::where('id',$key)->update([
                        'option_value'   => $values
                    ]);
                }
                else{
                    OptionValue::insert([
                        'option_id'=>$id,
                        'option_value'=>$values,
                        'display_order'=>0,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'is_deleted'=>0,
                    ]);
                }
            }
        if (count($request->option_values) < $option_cunt) {

            $data_check=OptionValue::whereNotIn('option_value',$request->option_values)
                        ->where('option_id',$id)
                        ->update([
                            'is_deleted'=>1,
                            'deleted_at'=>date('Y-m-d H:i:s')
                        ]);

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
}
