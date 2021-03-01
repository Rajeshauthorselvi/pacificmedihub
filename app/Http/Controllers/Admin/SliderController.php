<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Redirect;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['sliders'] = Slider::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/settings/slider/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/settings/slider/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $published=($request->published=='on')?1:0;

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
                $sliders = new Slider;
                $sliders->images        = $values['image'];
                $sliders->title         = $values['title'];
                $sliders->description   = $values['description'];
                $sliders->button        = $values['button'];
                $sliders->link          = $values['link'];
                $sliders->display_order = $values['display_order'];
                $sliders->published     = $published;
                $sliders->created_at    = date('Y-m-d H:i:s');
                $sliders->save();
            }
            return Redirect::route('static-page-slider.index')->with('success','New Slider added successfully...!');
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
        $data = array();
        $data['slider'] = Slider::find($id);
        return view('admin/settings/slider/edit',$data);
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
    public function destroy(Request $request, $id)
    {
        $slider=Slider::find($id);
        $slider->is_deleted=1;
        $slider->deleted_at = date('Y-m-d H:i:s');
        $slider->save();
        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('static-page-slider.index')->with('error','Slider deleted successfully...!');
    }

    public function deleteSliderImage(Request $request)
    {
        $option_value = OptionValue::find($request->id);
        $option_value->is_deleted = 1;
        $option_value->deleted_at = date('Y-m-d H:i:s');
        $option_value->update();
        return ['status'=>true];
    }
}
