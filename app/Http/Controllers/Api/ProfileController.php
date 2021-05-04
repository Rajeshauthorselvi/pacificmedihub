<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use App\User;
use App\Models\UserBankAcccount;
use App\Models\UserAddress;
use App\Models\UserPoc;
use App\Models\Countries;
use App\Models\UserCompanyDetails;
use App\Models\Prefix;
use App\Models\Employee;
use App\Models\Product;
use Validator;
use Redirect;
use Arr;
use Session;
use File;
use Auth;

class ProfileController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return response()->json(['success'=>false]);
        }
        $id =  Auth::id();
        $data = $profile_data = array();
        $profile = User::where('users.role_id',7)->where('id',$id)->first();

        $profile_data['user_id'] 		= $profile->id;
        $profile_data['name'] 			= $profile->name;
        $profile_data['email'] 			= $profile->email;
        $profile_data['contact_number'] = $profile->contact_number;
        $profile_data['logo'] 			= $profile->logo;
        $profile_data['customer_no'] 	= $profile->customer_no;
        $profile_data['company_uen'] 	= $profile->company_uen;
        $profile_data['address_1'] 		= $profile->address_1;
        $profile_data['address_2'] 		= $profile->address_2;
        $profile_data['post_code'] 		= $profile->post_code;
        $profile_data['country'] 	    = $profile->getCountry->name;
        $profile_data['country_id'] 	= $profile->country_id;
        $profile_data['state'] 			= $profile->getState->name;
        $profile_data['state_id'] 		= $profile->state_id;
        $profile_data['city'] 			= $profile->getCity->name;
        $profile_data['city_id'] 		= $profile->city_id;
        $profile_data['sales_rep'] 		= $profile->getSalesRep->emp_name;
        $profile_data['logo_url'] 		= url('theme/images/customer/company/');
        $profile_data['dummy_image']	= url('theme/images/products/placeholder.jpg');

        $data['profile_data']  = $profile_data;
        $data['bank_data']	   = UserBankAcccount::where('customer_id',$id)->first();
        $data['poc_data'] 	   = UserPoc::where('customer_id',$id)->get();
        return response()->json(['success'=> true,'data'=>$data]);
    }

    public function updateProfile(Request $request)
    {
        $credentials = $request->only('user_id','name','company_uen','contact_number','address_1','country_id','post_code');
        $rules = [
            'user_id'        => 'required',
            'name'           => 'required',
            'company_uen'    => 'required',
            'contact_number' => 'required',
            'address_1'      => 'required',
            'country_id'     => 'required',
            'post_code'      => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
        }
        else
        {
            $user_data = User::find($request->user_id);
            $user_data->name            =  $request->name;
            $user_data->company_uen     =  $request->company_uen;
            $user_data->contact_number  =  $request->contact_number;
            $user_data->address_1       =  $request->address_1;
            $user_data->address_2       =  $request->address_2;
            $user_data->country_id      =  $request->country_id;
            $user_data->state_id        =  $request->state_id;
            $user_data->city_id         =  $request->city_id;
            $user_data->post_code       =  $request->post_code;
            $user_data->save();

            $photo = $request->logo;
            if (base64_decode($photo, true) == true  && ($photo!=""))
            {
                $check_image = User::where('id',$request->user_id)->value('logo');
                File::delete(public_path('theme/images/customer/company/'.$request->user_id.'/'.$check_image));
                $filename        = $photo->getClientOriginalName();            
                $file_extension  = $photo->getClientOriginalExtension();
                $logo_image_name = strtotime("now").".".$file_extension;
                $photo->move(public_path('theme/images/customer/company/'.$request->user_id.'/'), $logo_image_name);
                User::where('id',$request->user_id)->update(['logo'=>$logo_image_name]);
            }
            return response()->json(['success'=> true,'message'=> 'Profile updated successfully.!','data'=>$user_data]);
        }
    }

    public function updateBankData(Request $request)
    {
        $credentials = $request->only('user_id');
        $rules = ['user_id' => 'required'];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
        }
        else
        {
            $bank_data = UserBankAcccount::where('customer_id',$request->user_id)->first();
            $bank_data->account_name   = $request->account_name;
            $bank_data->account_number = $request->account_number;
            $bank_data->bank_name      = $request->bank_name;
            $bank_data->bank_branch    = $request->bank_branch;
            $bank_data->paynow_contact = $request->paynow_contact;
            $bank_data->place          = $request->place;
            $bank_data->save();

            return response()->json(['success'=> true,'message'=> 'Bank details updated successfully.!','data'=>$bank_data]);
        }
    }

    public function updatePOCData(Request $request)
    {

    }

    public function wishlistGet()
    {
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
            $items = Cart::instance('Wishlist')->content();
            $wishlist_data = array();
            $key=0;
            foreach($items as $uniqueId => $item)
            {
                $wishlist_data[$key]['uniqueId']      = $item->getUniqueId();
                $wishlist_data[$key]['product_id']    = $item->id;
                $wishlist_data[$key]['product_name']  = $item->name;
                $wishlist_data[$key]['product_image'] = $item->options['product_img'];
                $wishlist_data[$key]['url']           = url('/api/products/'.$item->id);
                
                $key++;
            }

            $data['user_id'] = $user_id;
            $data['wishlist_count'] = Cart::count();
            $data['wishlist_data']  = $wishlist_data;
            $data['product_image_url']   = url('theme/images/products/main/');
            $data['dummy_image']         = url('theme/images/products/placeholder.jpg');
            
            return response()->json(['success'=>true, 'data'=>$data]);
        }else{
            return response()->json(['success'=>false]);
        }
    }

    public function addWishlist(Request $request)
    {
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
        
            $product_id  = $request->product_id;
            $wish_action = $request->row_id;
            $product = Product::find($product_id);
            
            if(Cart::has($wish_action)){
                Cart::instance('wishlist')->remove($request->row_id);
                Cart::instance('wishlist')->store('userID_'.$user_id);
                $message="removed";
            }else{
                Cart::instance('wishlist')->add($product->id,$product->name,0,1,['product_img'=>$product->main_image]);
                Cart::instance('wishlist')->store('userID_'.$user_id);
                $message="added";
            }
            return response()->json(['success'=>true, 'data'=>$message]);
        }else{
            return response()->json(false);
        }
    }
}
