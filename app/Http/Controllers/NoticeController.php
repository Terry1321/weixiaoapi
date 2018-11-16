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
    //通知展示
    public function show(Request $request){
        // 获取用户id
        $userId=$request->input('user_id');
        // $userId=45;
        // 获取用户权限
        $power=$request->input('power');
        // $power=1;
        switch ($power) {
            // 用户属于家长
            case 0:
             // 搜索用户所在群
            $userGroupId=DB::table('user')->where('id',$userId)->value('group_id');
            // 数据整理
            $groupId=trim($userGroupId,',');
            $notices=DB::table('notices')
                    ->join('group','group.id','notices.group_id')
                    ->where('notices.group_id','like','%'.$groupId.'%')
                    ->get();
            break;
            // 用户属于老师
            case 1:
                $notices=DB::table('notices')->where('user_id',$userId)->get();
                foreach ($notices as $key => $value) {
                    // 把群id最后的，取消
                    $groupId=trim($value->group_id,',');
                    // 用逗号分隔id
                    $id[]=explode(',', $groupId);
                    // 查询班级名称
                    foreach ($id as $key => $value1) {
                        foreach ($value1 as $key => $value2) {
                           $value->groupName[]=DB::table('group')->where('id',$value2)->value("name");
                        }
                    }
                    // 判断通知类型 修改内容
                    if ($value->notices_type_id==2) {
                        $value->text='你成功发布了一条新消息';
                    }elseif($value->notices_type_id==1){
                        $value->text='你成功发布了一条新通知';
                    }
                }
            break;
        }
        if ($notices) {
           foreach ($notices as $key => $value) {
                $value->time=date('m-d H:i',$value->time);
            }
            return $notices;
        }else{
            return 0;
        }
    }  
}