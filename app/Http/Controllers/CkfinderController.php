<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
//use Input;

class CkfinderController extends Controller
{
  public function uploadAction(Request $request)
  {
    dd($request->all());

     $CKEditor = $request->get('CKEditor');
        $funcNum  = $request->get('CKEditorFuncNum');
        $message  = $url = '';

        if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        if ($file->isValid()) {
        $filename =rand(1000,9999).$file->getClientOriginalName();
        $file->move(public_path().'/wysiwyg/', $filename);
        $url = url('wysiwyg/' . $filename);
        } else {
        $message = 'An error occurred while uploading the file.';
        }
        } else {
        $message = 'No file uploaded.';
        }
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
        }
}
