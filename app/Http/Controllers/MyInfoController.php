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
		$groupId=DB::table('user')->where('id',$id)->value('group_id');
		$terms=explode(',', $groupId);
		foreach ($terms as $term) {
			$group[]=DB::table('group')->where('id',$term)->first();
		}	
	}
}
 ?>