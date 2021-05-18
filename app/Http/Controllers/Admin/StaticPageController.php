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
        //dd($request->all());
        $data=array();
        if($request->from=='page'){
            if($request->published){$published = 1;}else{$published = 0;}
            $add_page = new StaticPage;
            $add_page->page_title   = $request->page_title;
            $add_page->page_content = $request->page_content;
            $add_page->sort_by      = $request->sort_order;
            $add_page->slug         = $request->search_engine;
            $add_page->published    = $published;
            $add_page->created_at   = date('Y-m-d H:i:s');
            $add_page->save();
        }else{
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
        }

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
    public function edit($id)
    {
        $data = array();
        $data['page'] = StaticPage::find($id);
        return view('admin/static_page/pages/edit',$data);
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
        $update_page = StaticPage::find($id);
        if($update_page){
            if($request->published){$published = 1;}else{$published = 0;}
            $update_page->page_title   = $request->page_title;
            $update_page->page_content = $request->page_content;
            $update_page->sort_by      = $request->sort_order;
            $update_page->slug         = $request->search_engine;
            $update_page->published    = $published;
            $update_page->save();
            return redirect()->route('static-page.index')->with('info','Page updated successfully...!');
        }else{
            return redirect()->route('static-page.index')->with('info','Somthing wrong please check and try again...!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check_page = StaticPage::find($id);
        if($check_page){
            $check_page->published  = 0;
            $check_page->is_deleted = 1;
            $check_page->deleted_at = date('Y-m-d H:i:s');
            $check_page->save();
        }
        return redirect()->route('static-page.index')->with('error','Page deleted successfully...!');
    }
}
