<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class NoticeController extends Controller
{
    // 通知发布
    public function notice(Request $request){
        $notice=$request->all();
        $notice['time']=time();
        if (DB::table('notices')->insert($notice)) {
            return 1;
        }else{
            return 0;
        }
    }
}