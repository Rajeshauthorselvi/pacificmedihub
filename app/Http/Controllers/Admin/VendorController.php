<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorPoc;
use App\Models\Prefix;
use App\Models\Employee;
use Redirect;
use File;
use App\Models\Countries;
use Auth;
use Excel;
use Response;
use App\Imports\vendor\VendorsImport;
class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('vendor','read')) {
                abort(404);
            }
        }
        $data = array();
        $data['vendors'] = Vendor::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/vendor/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('vendor','create')) {
                abort(404);
            }
        }
        $data = array();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();

        $data['vendor_id']      = '';
        $vendor_code = Prefix::where('key','prefix')->where('code','vendor')->value('content');
        if (isset($vendor_code)) {
            $value = unserialize($vendor_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = Vendor::count();
            $total_datas_count = $total_datas+1;

            if(strlen($total_datas_count)==1){
                $start_number = '0000'.$total_datas_count;
            }else if(strlen($total_datas_count)==2){
                $start_number = '000'.$total_datas_count;
            }else if(strlen($total_datas_count)==3){
                $start_number = '00'.$total_datas_count;
            }else if(strlen($total_datas_count)==4){
                $start_number = '0'.$total_datas_count;
            }else{
                $start_number = $total_datas_count;
            }
            $replace_year = str_replace('[yyyy]', $year, $char_val);
            $replace_number = str_replace('[Start No]', $start_number, $replace_year);
            $data['vendor_id']=$replace_number;
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
            // 'vendor_uen'     => 'required',
            // 'email'          => 'required|email|unique:vendors',
            'vendor_contact' => 'required',
            'address1'       => 'required',
            'country'        => 'required'
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

        if($request->vendor_status){$status = 1;}else{$status = 0;}

        $add_vendor = new Vendor;
        $add_vendor->code = $request->code;
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
        $add_vendor->website = $request->website;
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
        $add_vendor->status = $status;
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
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('vendor','read')) {
                abort(404);
            }
        }
        $data = array();
        $data['vendor'] = Vendor::find($id);
        $data['vendor_poc'] = VendorPoc::where('vendor_id',$id)->get();
        $data['count'] = 1;
        return view('admin/vendor/show',$data);
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
            if (!Auth::guard('employee')->user()->isAuthorized('vendor','update')) {
                abort(404);
            }
        }
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
        $this->validate(request(), [
            'vendor_name'    => 'required',
            // 'vendor_uen'     => 'required',
            // 'vendor_email'   => 'required|email',
            'vendor_contact' => 'required',
            'address1'       => 'required',
            'country'        => 'required'
        ]);

        if($request->vendor_status){$status = 1;}else{$status = 0;}

        $add_vendor = Vendor::find($id);
        $add_vendor->name = $request->vendor_name;
        $add_vendor->uen = $request->vendor_uen;
        $add_vendor->email = $request->vendor_email;
        $add_vendor->contact_number = $request->vendor_contact;
        $add_vendor->gst_no = $request->vendor_gst;
        /*$add_vendor->pan_no = $request->vendor_pan;*/
        $add_vendor->address_line1 = $request->address1;
        $add_vendor->website = $request->website;
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
        $add_vendor->status = $status;

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

        $add_vendor->update();

         
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

    public function VendorImport(Request $request)
    {
        $data=array();
        $data['last_vendor_id']=Vendor::orderBy('id','DESC')->latest()->value('id');
        return view('admin.vendor.vendor_import',$data);
    }
    public function VendorImportPost(Request $request)
    {
        try {
             Excel::import(new VendorsImport, $request->file('customer_import')); 
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors=[];
            foreach ($e->failures() as $failure) {
                $errors[] = "Error(s) in column " . $failure->attribute() . " at row " . $failure->row() . " with the message : <strong>" . implode($failure->errors()) ."</strong>";
            }
            return redirect()->back()->withErrors($errors);

        }

        return Redirect::back()->with('success','Vendor imported successfully');
    }
    public function DownloadSampleImportSheet()
    {
        $attachment="VendorImport.xls";
        $path=public_path('theme/sample_datas/').$attachment;
        return Response::download($path, $attachment);
    }
}
