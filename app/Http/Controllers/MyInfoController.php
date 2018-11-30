<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
/**
 * 
 */
class MyInfoController extends Controller{
	// 我的信息
	public function myInfo(Request $request){
		$id=$request->input('id');
		// 所在班级
		$data=DB::table('user')->where('id',$id)->get();
		foreach ($data as $key => $value) {
			$groupId=$value->group_id;
		}
		$terms=explode(',', $groupId);
		foreach ($terms as $term) {
			$group[]=DB::table('group')->where('id',$term)->first();
		}	
		foreach ($data as $key => $value) {
			$value->group[]=$group;
		}
			return $data;
	}

	// 群家长列表
	public function chatParentList(Request $request){
		$userId=$request->input('user_id');
		$userGroupId=DB::table('user')->where('id',$userId)->value('group_id');
		$groupId=explode(',',$userGroupId);
		$pop=array_pop($groupId);
		foreach ($groupId as $key => $value) {
			$data[$value] =DB::table('user')->select('name','id')->where('group_id','like','%'.$value.'%')->get();
			foreach ($data[$value] as $key1 => $value1) {
				$chatParentList[$value][$value1->name] = $value1->id;
			}
		}

		return $chatParentList;
	}
}
 ?>