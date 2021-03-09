<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\SliderBanner;
use Redirect;
use DB;
use Auth;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('slider','read')) {
                abort(404);
            }
        }
        $data = array();
        $data['sliders'] = Slider::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/static_page/slider/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('slider','create')) {
                abort(404);
            }
        }
        return view('admin/static_page/slider/create');
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
            $old_data = Slider::exists();
            if($old_data){
                DB::table('slider')->update(['published'=>0]);
            }
        }

        $slider = new Slider;
        $slider->slider_name = $request->slider_name;
        $slider->published   = $published;
        $slider->created_at  = date('Y-m-d H:i:s');
        $slider->save();

        if($slider->id){

            if($request->slider_data){
                $values_data = [];
                $i = 0;
                foreach ($request->slider_data['title'] as $title) {
                    $values_data[$i]['title'] = $title;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['description'] as $description) {
                    $values_data[$i]['description'] = $description;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['button'] as $button) {
                    $values_data[$i]['button'] = $button;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['link'] as $link) {
                    $values_data[$i]['link'] = $link;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['display_order'] as $display_order) {
                    $values_data[$i]['display_order'] = $display_order;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['image'] as $image) {
                    $photo           = $image;
                    $file_extension  = $image->getClientOriginalExtension();
                    $filename        = $photo->getClientOriginalName();
                    $image->move(public_path('theme/images/sliders'), $filename);
                    $values_data[$i]['image'] = $filename;
                    $i = $i+1;
                }
                foreach ($values_data as $values) {
                    $slider_banners = new SliderBanner;
                    $slider_banners->slider_id     = $slider->id;
                    $slider_banners->images        = $values['image'];
                    $slider_banners->title         = $values['title'];
                    $slider_banners->description   = $values['description'];
                    $slider_banners->button        = $values['button'];
                    $slider_banners->link          = $values['link'];
                    $slider_banners->display_order = $values['display_order'];
                    $slider_banners->timestamps    = false;
                    $slider_banners->save();
                }
            }
            return Redirect::route('static-page-slider.index')->with('success','New Slider added successfully...!');
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
            if (!Auth::guard('employee')->user()->isAuthorized('slider','update')) {
                abort(404);
            }
        }
        $data = array();
        $data['slider'] = Slider::find($id);
        $data['slider_banners'] = SliderBanner::where('slider_id',$id)->get();
        return view('admin/static_page/slider/edit',$data);
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
            $old_data = Slider::exists();
            if($old_data){
                DB::table('slider')->update(['published'=>0]);
            }
        }

        $slider = Slider::find($id);
        $slider->slider_name = $request->slider_name;
        $slider->published   = $published;
        $slider->update();

        if($slider->id){

            if($request->slider_data){
                $values_data = [];

                $i = 0;
                foreach ($request->slider_data['id'] as $id) {
                    $values_data[$i]['id'] = $id;
                    $i = $i + 1;
                }
                
                $i = 0;
                foreach ($request->slider_data['title'] as $title) {
                    $values_data[$i]['title'] = $title;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['description'] as $description) {
                    $values_data[$i]['description'] = $description;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['button'] as $button) {
                    $values_data[$i]['button'] = $button;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['link'] as $link) {
                    $values_data[$i]['link'] = $link;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->slider_data['display_order'] as $display_order) {
                    $values_data[$i]['display_order'] = $display_order;
                    $i = $i+1;
                }

                foreach ($values_data as $values) {
                    $slider_banners = SliderBanner::find($values['id']);
                    $slider_banners->slider_id     = $slider->id;
                    $slider_banners->title         = $values['title'];
                    $slider_banners->description   = $values['description'];
                    $slider_banners->button        = $values['button'];
                    $slider_banners->link          = $values['link'];
                    $slider_banners->display_order = $values['display_order'];
                    $slider_banners->timestamps    = false;
                    $slider_banners->update();
                }
            }
            return Redirect::route('static-page-slider.index')->with('success','Slider updated successfully...!');
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
            if (!Auth::guard('employee')->user()->isAuthorized('slider','delete')) {
                abort(404);
            }
        }
        $slider=Slider::find($id);
        $slider->is_deleted=1;
        $slider->deleted_at = date('Y-m-d H:i:s');
        $slider->save();

        SliderBanner::where('slider_id',$id)->delete();

        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('static-page-slider.index')->with('error','Slider deleted successfully...!');
    }

    public function deleteSliderBanner(Request $request)
    {
        SliderBanner::where('id',$request->id)->delete();
        return ['status'=>true];
    }
}
