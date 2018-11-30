<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class NoticeController extends Controller
{
    // 通知发布
    public function notice(Request $request){
        $notice=$request->except('group_id');
        $notice['time']=time();
        if ($noticId=DB::table('notices')->insertGetId($notice)) {
            $group=$request->input('group_id');
            $groupId=explode(',', $group);
            foreach ($groupId as $key => $value) {
                $data=array(
                    'notices_id'=>$noticId,
                    'group_id'=>$value,
                );
                DB::table('notices_group_id')->insert($data);
            }
            
        }
        return 1;
    }

    //通知展示
    public function show(Request $request){
        // 获取用户id
        $userId=$request->input('user_id');
        // $userId=45;

        $data=DB::table('notices')->select('notices.*','group.name as noticeClass','notices_type.name as noticeType')
                                    ->join('group','group.id','notices.group_id')
                                    ->join('notices_type','notices_type.id','notices.notices_type_id')
                                    ->where('user_id',$userId)
                                    ->get();



        // foreach ($data as $value) {
        //     $value->noticeId= $value->id;

            
        //     // $noticeSummaryList[$value->id]=
        //     // $value->noticeContents=DB::table('notices')->select('text as noticeContent')
        // }

            echo '<pre>';
            print_r($data);

  }
}