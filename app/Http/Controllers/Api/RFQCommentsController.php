<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\RFQComments;
use App\Models\Notification;
use App\Models\RFQ;
use Auth;
class RFQCommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->rfq_id;
        $data=array();
        $rfq=RFQ::find($id);
        $data['check_parent']=$check_parent = User::where('id',Auth::id())->first();
        $data['rfq_details']  = RFQ::with('customer','salesrep','statusName')->where('rfq.id',$id)->first();
        $data['rfq_id']       = $id;
        $comments     = RFQComments::where('rfq_id',$id)->get();
        $comments_data=array();
        foreach ($comments as $key => $comment) {
            if ($comment->commented_by==Auth::id()) {
                $comment->commented_in=true;
            }
            else{
                $comment->commented_in=false;
            }

            $comments_data[]=$comment;
        }
        $data['comments']=$comments_data;
        $data['show_edit'] =($rfq->status==25)?true:false;
        $data['check_parent'] = ($check_parent->parent_company==0)?true:false;

        return response()->json(['success'=> true,'data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=[
            'rfq_id'                  => $request->rfq_id,
            'comment'                 => $request->comment,
            'commented_by'            => $auth_id,
            'commented_by_user_type'  => $created_user_type
        ];

        $rfq_commene_id = RFQComments::insertGetId($data);

        $rfq_details=RFQ::with('customer','salesrep','statusName')->where('rfq.id',$request->rfq_id)->first();
        Notification::insert([
            'type'                => 'orders',
            'ref_id'              => $request->rfq_id,
            'customer_id'         => Auth::id(),
            'content'             => $creater_name.' added new comment to '.$rfq_details->order_no,
            'url'                 => url('admin/rfq-comments/'.$request->rfq_id),
            'created_at'          => date('Y-m-d H:i:s'),
            'created_by'          => $auth_id,
            'created_user_type'   => 3,
        ]);

        return response()->json(['success'=> true,'data'=>[]]);
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
