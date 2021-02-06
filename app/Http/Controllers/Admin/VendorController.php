<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorPoc;
use App\Models\Settings;
use App\Models\Employee;
use Redirect;
use File;
use App\Models\Countries;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['vendors'] = Vendor::where('is_deleted',0)->orderBy('created_at','desc')->get();
        return view('admin/vendor/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();

        $key_val=Settings::where('key','prefix')
                 ->where('code','employee')
                 ->value('content');
        $data['employee_id']='';
        if (isset($key_val)) {
            $value=unserialize($key_val);

            $char_val=$value['value'];
            $total_datas=Vendor::count();
            $data_original='VEN-[dd]-[mm]-[yyyy]-[Start No]';

            $search=['[dd]', '[mm]', '[yyyy]', '[Start No]'];
            $replace=[date('d'), date('m'), date('Y'), $total_datas+1 ];

            $data['employee_id']=str_replace($search,$replace, $data_original);
        }

        return view('admin/vendor/create',$data);
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
            'vendor_name'    => 'required',
            'vendor_uen'     => 'required',
            'email'          => 'required|email|unique:vendors',
            'vendor_contact' => 'required',
            'address1'       => 'required',
            'postcode'       => 'required',
            'country'        => 'required',
            'state'          => 'required',
            'city'           => 'required'
        ]);

        $gst_image= $request->hasFile('vendorGst_image');
        if($gst_image){
            $photo          = $request->file('vendorGst_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('vendorGst_image')->getClientOriginalExtension();
            $gst_image_name = strtotime("now").".".$file_extension;
            $request->vendorGst_image->move(public_path('theme/images/vendor/gst/'), $gst_image_name);
        }
        else{
            $gst_image_name = NULL;
        }

        /*$pan_image= $request->hasFile('vendorPan_image');
        if($pan_image){
            $photo          = $request->file('vendorPan_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('vendorPan_image')->getClientOriginalExtension();
            $pan_image_name = strtotime("now").".".$file_extension;
            $request->vendorPan_image->move(public_path('theme/images/vendor/pan/'), $pan_image_name);
        }
        else{
            $pan_image_name = NULL;
        }*/

        $logo_image= $request->hasFile('vendor_logo');
        if($logo_image){
            $photo          = $request->file('vendor_logo');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('vendor_logo')->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->vendor_logo->move(public_path('theme/images/vendor/'), $logo_image_name);
        }
        else{
            $logo_image_name = NULL;
        }

        $add_vendor = new Vendor;
        $add_vendor->code = $request->vendor_code;
        $add_vendor->uen = $request->vendor_uen;
        $add_vendor->name = $request->vendor_name;
        $add_vendor->email = $request->email;
        $add_vendor->contact_number = $request->vendor_contact;
        $add_vendor->logo_image = $logo_image_name;
        $add_vendor->gst_no = $request->vendor_gst;
        $add_vendor->gst_image = $gst_image_name;
        /*$add_vendor->pan_no = $request->vendor_pan;
        $add_vendor->pan_image = $pan_image_name;*/
        $add_vendor->address_line1 = $request->address1;
        $add_vendor->address_line2 = $request->address2;
        $add_vendor->post_code = $request->postcode;
        $add_vendor->country = $request->country;
        $add_vendor->state = $request->state;
        $add_vendor->city = $request->city;
        $add_vendor->account_name = $request->account_name;
        $add_vendor->account_number = $request->account_number;
        $add_vendor->bank_name = $request->bank_name;
        $add_vendor->bank_branch = $request->bank_branch;
        $add_vendor->paynow_contact_number = $request->paynow_no;
        $add_vendor->bank_place = $request->place;
        $add_vendor->others = $request->others;
        $add_vendor->status = 1;
        $add_vendor->created_at = date('Y-m-d H:i:s');
        $add_vendor->save();

        if($add_vendor->id){
            if($request->poc){
                $poc_data = [];
                $i = 0;
                foreach ($request->poc['name'] as $name) {
                    $poc_data[$i]['name'] = $name;
                    $i = $i + 1;
                }
                $i = 0;
                foreach ($request->poc['email'] as $email) {
                    $poc_data[$i]['email'] = $email;
                    $i = $i + 1;
                }
                $i = 0;
                foreach ($request->poc['contact'] as $contact) {
                    $poc_data[$i]['contact'] = $contact;
                    $i = $i + 1;
                }
                foreach ($poc_data as $key => $value) {
                    if($value['name']!=null){
                        $add_poc = new VendorPoc;
                        $add_poc->vendor_id = $add_vendor->id;
                        $add_poc->name = $value['name'];
                        $add_poc->email = $value['email'];
                        $add_poc->contact_no = $value['contact'];
                        $add_poc->timestamps = false;
                        $add_poc->save();
                    }
                }
            }
            return redirect()->route('vendor.index')->with('success','New Vendor Added successfully...!');
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
        $data['vendor'] = Vendor::find($id);
        $data['vendor_poc'] = VendorPoc::where('vendor_id',$id)->get();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
        $data['count'] = 1;
        return view('admin/vendor/edit',$data);
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
            'vendor_name'    => 'required',
            'vendor_uen'     => 'required',
            'vendor_email'   => 'required|email',
            'vendor_contact' => 'required',
            'address1'       => 'required',
            'postcode'       => 'required',
            'country'        => 'required',
            'state'          => 'required',
            'city'           => 'required'
        ]);


        $add_vendor = Vendor::find($id);
        $add_vendor->name = $request->vendor_name;
        $add_vendor->uen = $request->vendor_uen;
        $add_vendor->email = $request->vendor_email;
        $add_vendor->contact_number = $request->vendor_contact;
        $add_vendor->gst_no = $request->vendor_gst;
        /*$add_vendor->pan_no = $request->vendor_pan;*/
        $add_vendor->address_line1 = $request->address1;
        $add_vendor->address_line2 = $request->address2;
        $add_vendor->post_code = $request->postcode;
        $add_vendor->country = $request->country;
        $add_vendor->state = $request->state;
        $add_vendor->city = $request->city;
        $add_vendor->account_name = $request->account_name;
        $add_vendor->account_number = $request->account_number;
        $add_vendor->bank_name = $request->bank_name;
        $add_vendor->bank_branch = $request->bank_branch;
        $add_vendor->paynow_contact_number = $request->paynow_no;
        $add_vendor->bank_place = $request->place;
        $add_vendor->others = $request->others;
        $add_vendor->created_at = date('Y-m-d H:i:s');


       
        if($request->hasFile('vendorGst_image')){
            if (File::exists(public_path('theme/images/vendor/gst/'.$add_vendor->gst_image))) {
                File::delete(public_path('theme/images/vendor/gst/'.$add_vendor->gst_image));
            }
            $photo          = $request->file('vendorGst_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('vendorGst_image')->getClientOriginalExtension();
            $gst_image_name = strtotime("now").".".$file_extension;
            $request->vendorGst_image->move(public_path('theme/images/vendor/gst/'), $gst_image_name);
            $add_vendor->gst_image = $gst_image_name;
        }
 
        /*if($request->hasFile('vendorPan_image')){
            if (File::exists(public_path('theme/images/vendor/pan/'.$add_vendor->pan_image))) {
                File::delete(public_path('theme/images/vendor/pan/'.$add_vendor->pan_image));
            }
            $photo          = $request->file('vendorPan_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('vendorPan_image')->getClientOriginalExtension();
            $pan_image_name = strtotime("now").".".$file_extension;
            $request->vendorPan_image->move(public_path('theme/images/vendor/pan/'), $pan_image_name);
            $add_vendor->pan_image = $pan_image_name;
        }*/

        
        if($request->hasFile('vendor_logo')){
            if (File::exists(public_path('theme/images/vendor/pan/'.$add_vendor->logo_image))) {
                File::delete(public_path('theme/images/vendor/pan/'.$add_vendor->logo_image));
            }
            $photo          = $request->file('vendor_logo');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('vendor_logo')->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->vendor_logo->move(public_path('theme/images/vendor/'), $logo_image_name);
            $add_vendor->logo_image = $logo_image_name;
        }
         $add_vendor->save();

         
        if($request->poc){

            VendorPoc::where('vendor_id',$id)->delete();
            $poc_data = [];
            $i = 0;
            foreach ($request->poc['name'] as $name) {
                $poc_data[$i]['name'] = $name;
                $i = $i + 1;
            }
            $i = 0;
            foreach ($request->poc['email'] as $email) {
                $poc_data[$i]['email'] = $email;
                $i = $i + 1;
            }
            $i = 0;
            foreach ($request->poc['contact'] as $contact) {
                $poc_data[$i]['contact'] = $contact;
                $i = $i + 1;
            }
            foreach ($poc_data as $key => $value) {
                if($value['name']!=null){
                    $add_poc = new VendorPoc;
                    $add_poc->vendor_id = $add_vendor->id;
                    $add_poc->name = $value['name'];
                    $add_poc->email = $value['email'];
                    $add_poc->contact_no = $value['contact'];
                    $add_poc->timestamps = false;
                    $add_poc->save();
                }
            }
        }

        return Redirect::route('vendor.index')->with('info','Vendor details modified successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $check_vendor=Vendor::find($id);
        if($check_vendor){
            $check_vendor->is_deleted = 1;
            $check_vendor->deleted_at = date('Y-m-d H:i:s');
            $check_vendor->update();
            $vendor_poc = VendorPoc::where('vendor_id',$id)->delete();
        }
        if ($request->ajax())  return ['status'=>true];
        else return redirect()->route('vendor.index')->with('error','Vendor deleted successfully...!');
    }
}
