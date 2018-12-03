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
               $notice['group_id']=$value;
               DB::table('notices')->insert($notice);
            }
        }
        return 1;
    }

    //通知展示
    public function show(Request $request){
        // 获取用户id
        $userId=$request->input('user_id');
        $userId=45;
        //获取用户ID
        $groupId=DB::table('user')->where('id',$userId)->value('group_id');
        // 整理数据
        $trimed= rtrim($groupId,',');
        $groupArr=explode(',', $trimed);
        // 遍历群ID 获取数据
        foreach ($groupArr as $key => $value) {
            if (!DB::table('notices')->where('group_id',$value)->first()) {
                continue;
            }
            $data[]=DB::table('notices')
            ->select('notices.*','group.name as noticeClass')
            ->join('group','group.id','notices.group_id')
            ->where('notices.group_id',$value)
            ->groupBy('notices.group_id')->get();
        }

        // 时间戳改时间
            foreach ($data as $key => $value) {
                foreach ($value as $value2) {
                    $time=intval($value2->time);
                    $value2->time=date('m-d H:i',$time);
                }
            }

                // echo '<pre>';
                // print_r($data);
         
        // foreach ($data  as $key => $value) {
        //    // if (!$value[$key]) {
        //    //     unset($value[$key]);
        //    // }
        //     // var_dump(is_null($value[]));
        // }

    
        //     echo '<pre>';
        //     print_r($data)i;
        return $data;
  }

  // 获取通知信息详情
  public function noticesDetail(Request $request){
    // 获取用户ID
    $groupId=$request->input('group_id');
    // 查询对应此ID的所有通知
    $notices=DB::table('notices')->where('group_id',$groupId)->get();

    foreach ($notices as $key => $value) {
        $value->time=date('m-d H:i',$value->time);
    }
            return $notices;
  }

}