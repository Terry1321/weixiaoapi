<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

// 班级控制控制器
class ClassController extends Controller
{
	 /*
		班级列表
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
					$group[]=DB::table('group')->select('id as classId','name as className')->where('id',$term)->first();
				}	
				break;
			case 'notices':
				$group=DB::table('group')->select('id','name')->get();
				break;
		}
		return $group;
	}

	/*
		创建班级
	*/
	public function createGroup(Request $request){
		// 获取班级名称
		$groupName=$request->input('name');
		$userId=$request->input('user_id');
		$data=array(
			'name'=>$groupName,
			'user_id'=>$userId,
		);

		if (DB::table('group')->where('name',$groupName)->first()) {
			// 返回班级已存在信息
			return 3;		
		}else{
			//添加数据库
			if (DB::table('group')->insert($data)) {
				return 1;
			}else{
				return 0;
			}
		}


	
	}	
	// 班级信息
	public function classDetail(Request $request){
		// 获取搜索的班级名
		$serach=$request->all();
		// 班级信息
		$classInfo=DB::table('group')->select('group.*','user.name as master')
							->join('user','user.id','group.user_id')
							->where('group.name',$serach)
							->get();

		if (isset($classInfo[0])) {
			foreach ($classInfo as $key => $value) {
				$value->count=DB::table('user')->where('group_id','like',"%$value->id%")->count();
				$value->joined=DB::table('apply')->where('group_id','like',"%$value->id%")->count();
				unset($value->id,$value->user_id);
			}
			return $classInfo;
		}else{
			return 0;
		}
	}	

	 /*
	 	已申请班级
	 */
	public function joined(Request $request){
		//用户ID
		$userId=$request->all();
		// 该用户申请的班级
		$joined=DB::table('apply')->select('apply.*','group.name')
		->join('group','group.id','apply.group_id')
		->where('apply.user_id',$userId)
		->get();
	// 整理数据
		foreach ($joined as $key => $value) {
			$data[]=$value->name;
		}
			return $data;
	}

	/*
		待审核申请
	*/
	public function appliedList(Request $request){
		// 获取用户ID
		$userId=$request->all();
		// 获取群ID
		$data=DB::table('user')->where('id',$userId)->value('group_id');
		$str=rtrim($data,',');
		$groupId=explode(',', $str);
		// 获取对应的群数据
		foreach ($groupId as $key => $value) {

			$groupInfo[]=DB::table('group')->where('id',$value)->get();
		}
		// 整理数据
		foreach ($groupInfo as $key => $value) {
			foreach ($value as $k => $val) {
				$appliedList[$val->id]['id']=$val->id;
				$appliedList[$val->id]['groupName']=$val->name;
				$appliedList[$val->id]['list']=DB::table('apply')->select('user.id','user.name')
										->join('user','apply.user_id','user.id')
										->where('apply.group_id',$val->id)
										->get();
			}
		}
		
		return $appliedList;
	}
	/*
		审核
	*/
	public function checked(Request $request){
		// 家长ID
		$parentId =$request->input('parentId');
		// 班级Id
		$classId =$request->input('classId');
		/*
			同意还是拒绝
			1:同意
			0:拒绝
		*/
		$statu=$request->input('statu');
		// 获取用户本来的所属群组
		$oldGroup=DB::table('user')->where('id',$parentId)->value('group_id');
		// 判断同时还是拒绝
		if ($statu) {
			// 修改用户所在群组
			if (DB::table('user')->where('id',$parentId)->update(['group_id'=>$oldGroup.$classId.','])) {
				// 删除申请数据
				if (DB::table('apply')->where([['user_id',$parentId],['group_id',$classId],])->delete()) {
					return 1;
				}else{
					return 0;
				}
			}
		}else{
			// 删除申请数据
			if (DB::table('apply')->where([['user_id',$parentId],['group_id',$classId],])->delete()) {
				return 1;
			}else{
				return 0;
			}
		}

	}

	/*
		申请操作
	*/
	public function join(Request $request){
		//获取ID 
		$data=$request->except('groupname');
		// 获取群名字
		$name=$request->input('groupname');	
		// 整理数据
		$data['group_id']=DB::table('group')->where('name',$name)->value('id');
		// 判断
		// DB::table('apply')->where([['user_id',$data['user_id']],['group_id',$data['group_id']]])->get();
			
		if (DB::table('apply')->where([['user_id',$data['user_id']],['group_id',$data['group_id']]])->first()) {
			return 3;
		}
		if (DB::table('user')->where([['id',$data['user_id']],['group_id','like',"%".$data['group_id']."%"]])->first()) {
			return 4;
		}

		if (DB::table('apply')->insert($data)) {
			return 1;
		}else{
			return 0;
		}
	}
}
?>