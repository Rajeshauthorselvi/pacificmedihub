<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
//use Input;

class CkfinderController extends Controller
{
  public function uploadAction(Request $request)
  {
        if($request->hasFile('upload')) {
             $originName = $request->file('upload')->getClientOriginalName();
             $fileName = pathinfo($originName, PATHINFO_FILENAME);
             $extension = $request->file('upload')->getClientOriginalExtension();
             $fileName = $fileName.'_'.time().'.'.$extension;
            
             $request->file('upload')->move(public_path('images'), $fileName);

             $CKEditorFuncNum = $request->input('CKEditorFuncNum');
/*             $url = asset('images/'.$fileName); 
             $msg = 'Image uploaded successfully'; 
             $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";*/
             $CKEditorFuncNum = $request->input('CKEditorFuncNum');
             $url = asset('images/'.$fileName); 
             $msg = 'Image uploaded successfully'; 
             $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
                   
             @header('Content-type: text/html; charset=utf-8'); 
             echo $response;
            
         }
         else{
            return ['status'=>'response'];
         }

  }
}
