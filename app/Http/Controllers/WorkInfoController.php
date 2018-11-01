<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class WorkInfoController extends Controller
{
	/*
		课程类别
	*/
	public function type(Request $request){
		$type=$request->input('type');
		switch ($type) {
			case 'assignments':
				$types=DB::table('assignments_type')->get();
				break;
			case 'notices':
				$types=DB::table('notices_type')->get();
				break;
		}
		return $types;
	}
	/*
		班级类别
	*/
	public function group(Request $request){
		$type=$request->input('type');
		switch ($type) {
			case 'assignments':
				$userId=$request->input('user_id');
				$groupId=DB::table('user')->where('id',$userId)->value('group_id');
				$terms=explode(',', $groupId);
				$pop=array_pop($terms);
				foreach ($terms as $term) {
					$group[]=DB::table('group')->where('id',$term)->first();
				}	
				break;
			case 'notices':
				$group=DB::table('group')->get();
				break;
		}
		
		return $group;
	}
}

?>