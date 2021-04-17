<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\FeatureBlock;
use App\Models\Settings;
use App\Models\StaticPage;
use Redirect;
use Arr;

class StaticPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['static_pages'] = StaticPage::where('is_deleted',0)->get();
        return view('admin/static_page/pages/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = array();
        if($request->has('home')){
            $data['status']           = [1=>'Yes',2=>'No'];
            $data['selected_slider']  = Slider::where('is_deleted',0)->where('published',1)->value('id');
            $data['sliders']          = Slider::where('is_deleted',0)->orderBy('id','desc')->pluck('slider_name','id')
                                             ->toArray();
            $data['features']         = FeatureBlock::where('is_deleted',0)->orderBy('id','desc')->pluck('feature_name','id')
                                             ->toArray();
            $data['selected_feature'] = FeatureBlock::where('is_deleted',0)->where('published',1)->value('id');

            $setting = Settings::where('key','front-end')->pluck('content','code')->toArray();
            if(isset($setting['home'])){
                $statuses = unserialize($setting['home']);
            }
            
            $data['slider_status']         = isset($statuses['slider_status'])?$statuses['slider_status']:0;
            $data['features_status']       = isset($statuses['features_status'])?$statuses['features_status']:0;
            $data['new_arrival_status']    = isset($statuses['new_arrival_status'])?$statuses['new_arrival_status']:0;
            $data['category_block_status'] = isset($statuses['category_block_status'])?$statuses['category_block_status']:0;

            return view('admin/static_page/pages/home',$data);
        }elseif($request->has('header')){
            $setting = Settings::where('key','front-end')->pluck('content','code')->toArray();
            if(isset($setting['header'])){
                $get_datas = unserialize($setting['header']);
            }
            $data['datas'] = $get_datas;
            return view('admin/static_page/pages/header',$data);
        }
        else{
            return view('admin/static_page/pages/create',$data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=array();
        if($request->from=='home'){

            $update_slider = Slider::where('is_deleted',0)->update(['published'=>0]);
            if($update_slider){
                Slider::where('id',$request->slider)->update(['published'=>1]);
            }

            $update_features = FeatureBlock::where('is_deleted',0)->update(['published'=>0]);
            if($update_features){
                FeatureBlock::where('id',$request->features)->update(['published'=>1]);
            }

            $content=[
                'slider_status'         => $request->slider_status,
                'features_status'       => $request->features_status,
                'new_arrival_status'    => $request->new_arrival_status,
                'category_block_status' => $request->category_block_status
            ];
        }
        if($request->from=='header'){
            $image = $request->hasFile('main_image');
            if($image){
                $photo          = $request->file('main_image');            
                $filename       = $photo->getClientOriginalName();            
                $file_extension = $request->file('main_image')->getClientOriginalExtension();
                $image_name     = strtotime("now").".".$file_extension;
                $request->main_image->move(public_path('front/img/'), $image_name);
            }
            else{
                $image_name = 'logo_mtcu.png';
            }
            $content=[
                'image'     => $image_name,
                'email'     => $request->email,
                'helpline'  => $request->helpline
            ];  
        }

        $data['content']      = serialize($content);
        $data['key']          = 'front-end';
        $data['code']         = $request->from;
        $data['if_serialize'] = 1;
        $data['created_at']   = date('Y-m-d H:i:s');

        Settings::updateOrCreate(
            ['key'=>'front-end','code'=>$request->from],$data
        );

        return Redirect::route('static-page.index')->with('success','Page change updated successfully...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($from)
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
