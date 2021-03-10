<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FeatureBlock;
use App\Models\FeatureBlockData;
use DB;
use Auth;
use Redirect;

class FeatureBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('features','read')) {
                abort(404);
            }
        }

        $data = array();
        $data['features'] = FeatureBlock::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/static_page/features/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('features','create')) {
                abort(404);
            }
        }
        return view('admin/static_page/features/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $published = ($request->published=='on')?1:0;

        if ($request->has('published')) {
            $old_data = FeatureBlock::exists();
            if($old_data){
                DB::table('feature_block')->update(['published'=>0]);
            }
        }

        $feature = new FeatureBlock;
        $feature->feature_name = $request->features_name;
        $feature->published    = $published;
        $feature->created_at   = date('Y-m-d H:i:s');
        $feature->save();

        if($feature->id){
            if($request->features){
                $values_data = [];
                $i = 0;
                foreach ($request->features['title'] as $title) {
                    $values_data[$i]['title'] = $title;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->features['message'] as $message) {
                    $values_data[$i]['message'] = $message;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->features['image'] as $image) {
                    $photo           = $image;
                    $file_extension  = $image->getClientOriginalExtension();
                    $filename        = $photo->getClientOriginalName();
                    $image->move(public_path('theme/images/features'), $filename);
                    $values_data[$i]['image'] = $filename;
                    $i = $i+1;
                }
                foreach ($values_data as $values) {
                    $feature_block = new FeatureBlockData;
                    $feature_block->feature_id = $feature->id;
                    $feature_block->title      = $values['title'];
                    $feature_block->message    = $values['message'];
                    $feature_block->images     = $values['image'];
                    $feature_block->timestamps = false;
                    $feature_block->save();
                }
            }
            return Redirect::route('static-page-features.index')->with('success','New Features added successfully...!');
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!');
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
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('features','update')) {
                abort(404);
            }
        }
        $data = array();
        $data['features'] = FeatureBlock::find($id);
        $data['features_data'] = FeatureBlockData::where('feature_id',$id)->get();
        return view('admin/static_page/features/edit',$data);
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
        $published = ($request->published=='on')?1:0;

        if ($request->has('published')) {
            $old_data = FeatureBlock::exists();
            if($old_data){
                DB::table('feature_block')->update(['published'=>0]);
            }
        }
        $feature = FeatureBlock::find($id);
        $feature->feature_name = $request->features_name;
        $feature->published    = $published;
        $feature->update();

        if($feature->id){

            if($request->features){
               $values_data = [];

               $i = 0;
                foreach ($request->features['id'] as $id) {
                    $values_data[$i]['id'] = $id;
                    $i = $i + 1;
                }

                $i = 0;
                foreach ($request->features['title'] as $title) {
                    $values_data[$i]['title'] = $title;
                    $i = $i+1;
                }

               $i = 0;
                foreach ($request->features['message'] as $message) {
                    $values_data[$i]['message'] = $message;
                    $i = $i+1;
                }

                foreach ($values_data as $values) {
                    $feature_block = FeatureBlockData::find($values['id']);
                    $feature_block->feature_id = $feature->id;
                    $feature_block->title      = $values['title'];
                    $feature_block->message    = $values['message'];
                    $feature_block->timestamps = false;
                    $feature_block->update();
                }
            }
            return Redirect::route('static-page-features.index')->with('success','Features updated successfully...!');
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!');
        }
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
            if (!Auth::guard('employee')->user()->isAuthorized('features','delete')) {
                abort(404);
            }
        }

        $features=FeatureBlock::find($id);
        $features->is_deleted=1;
        $features->deleted_at = date('Y-m-d H:i:s');
        $features->save();

        FeatureBlockData::where('feature_id',$id)->delete();

        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('static-page-features.index')->with('error','Features deleted successfully...!');
    }
}
