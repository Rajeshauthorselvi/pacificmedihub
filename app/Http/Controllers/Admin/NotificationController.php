<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Auth;
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $access_via_role="";
         if (!Auth::check() && Auth::guard('employee')->check()) {
            $updated_by=Auth::guard('employee')->user()->id;
            $user_type=2;
            $role_user_id=Auth::guard('employee')->user()->id;
            if (Auth::guard('employee')->user()->role_id==6 || Auth::guard('employee')->user()->role_id==4) {
                $access_via_role="yes";
            }
         }
         else{
            $updated_by=Auth::id();
            $user_type=1;
         }
        $all_notifications=Notification::where('if_read','no')
                          ->where('created_user_type','<>',$user_type);
                          if ($access_via_role=="yes") {
                            $all_notifications->where('delivery_employee_id',$role_user_id);
                          }
                          $all_notifications=$all_notifications->where('created_by','<>',$updated_by)
                          ->orderBy('id','DESC')
                          ->get();

        $total_notification=array();
        foreach ($all_notifications as $key => $notification) {
            $total_notification[]=[
                'notification_id'   => $notification->id,
                'content'           => $notification->content,
                'url'               => $notification->url,
                'created_time'      => $this->humanTiming(strtotime($notification->created_at)),
            ];
        }

        return $total_notification;
    }

    public function humanTiming ($time)
    {
        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s ago':'ago');
        }
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
          $access_via_role="";
         if (!Auth::check() && Auth::guard('employee')->check()) {
            if (Auth::guard('employee')->user()->role_id==6) {
                 Notification::where('if_read','no')
                 ->where('delivery_employee_id',Auth::guard('employee')->user()->id)
                 ->update(['if_read'=>'yes']);
            }
            elseif (Auth::guard('employee')->user()->role_id==4) {
                 Notification::where('if_read','no')
                 ->where('delivery_employee_id',Auth::guard('employee')->user()->id)
                 ->update(['if_read'=>'yes']);
            }
         }
         else{
            $user_type=1;
            Notification::where('if_read','no')
            ->where('delivery_employee_id',0)
            ->update(['if_read'=>'yes']);
         }

        

        return ['status'=>true];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
