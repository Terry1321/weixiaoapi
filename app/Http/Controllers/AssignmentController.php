<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class AssignmentController extends Controller
{
	//作业详情
	public function workDetail(Request $request){
		$id= $request->input('id');
		$workDetail =DB::table("assignments")
			->select('assignments.*','user.name as username','group.name as groupname','assignments_type.name as typename')
			->join('user','user.id','assignments.user_id')
			->join('group','group.id','assignments.group_id')
			->join('assignments_type','assignments_type.id','assignments.assignments_type_id')
			->where('assignments.id',$id)
			->get();

		if ($workDetail) {
			foreach ($workDetail as $key => $value) {
				$value->time=date('m-d H:i',$value->time);
			}
			return $workDetail;
		}else{
			return 0;
		}
	}
	// 作业图片获取
	public function picture(Request $request){
		$assignmentsId=$request->input('assignments_id');
		$picture=DB::table('image')->where('assignments_id',$assignmentsId)->pluck('name');
		return $picture;
	}
	// 查询作业展示的数据
	private function getAssignmentsData($condition,$symbol,$screen){
		// 获取数据
		$assignmentsData =DB::table('assignments')
		->select('assignments.*','user.name as username','user.group_id','assignments_type.name as typename','group.name as groupname')
		 ->join('user','user.id','assignments.user_id')
		 ->join('assignments_type','assignments_type.id','assignments_type_id')
		 ->join('group','group.id','assignments.group_id')
		 ->where($condition,$symbol,$screen)
		 ->get();

		 return $assignmentsData;
	}
	// 作业展示
	public function show(Request $request){
		// 获取用户id
		$userId =$request->input('user_id');
        // $userId=45;
		// 获取用户权限
		$power=$request->input('power');		
        // $power=1;
		switch ($power) {
			// power是0是 家长只可以获取自己加的群
			case '0':
			// 获取用户所在群
				$userGroupId=DB::table('user')->where('id',$userId)->value('group_id');
				$groupId=trim($userGroupId,',');
				if (is_numeric($groupId)) {
					// 获取数据
					$screen='%'.$groupId.'%';
					$assignments=$this->getAssignmentsData('assignments.group_id','like',$screen);
				}
			break;
			//power为1时 老师可以获取自己发的信息
			case '1':
			// 获取数据
				$assignments=$this->getAssignmentsData('assignments.user_id','=',$userId);
				foreach ($assignments as $key => $value) {
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
                }
			break;
		}
		// 遍历数据（日期）
		if ($assignments){
			foreach ($assignments as $value) {
				$value->time=date('m-d H:i',$value->time);
			}
			return $assignments;
		}else{
			return 0;
		}
		
	}
	/*
		$request接收需要上传的图片
		上传图片
	*/
	public function upload(Request $request){
		// 获取图片信息
		$imageFile=$request->file('image');
		// 图片上传路径
		$path="./Image";
		// 判断上传路径是否存在
		if (!file_exists($path)) {
			mkdir($path,0777,true);
		}
		// 上传到本地并添加数据库
		if (isset($imageFile)) {
			// 图片后缀
			$ext=$imageFile->getClientOriginalExtension();
			// 重命名图片
			$newName=time().'_'.rand().'.'.$ext;
			$data=$imageFile->move($path,$newName);
			if ($data) {
				$imagesData = array(
					'assignments_id' =>$request->input('assignments_id'),
					'name'=>$newName 
				);
				// 添加进数据库
				if (DB::table('image')->insert($imagesData)) {
					return 1;
				}else{
					return 0;
				}
				
			}
		}
	}
	/*
		$request接受表单数据
		作业提交
	*/
    public function workStore(Request $request){
    	if ($assignment=$request->all()) {
	    	$assignment['time']=time();
	    	if ($workinfoId=DB::table('assignments')->insertGetId($assignment)) {
	    		// 整理数据
	    		$noticesType=DB::table('notices_type')->where('name','作业')->value('id');
	    		$userId=$request->input('user_id');
	    		$groupId=$request->input('group_id');
	    		// 整理数组
	    		$notices= array(
	    			'text' =>'您有一条新作业',
	    			'user_id'=> $userId,
	    			'group_id'=>$groupId,
	    			'time'=>time(),
	    			'notices_type_id'=>$noticesType,
	    		);
	    		// 插入数据
	    		if (DB::table('notices')->insert($notices)) {
	    			// 成功返回workinfoid
	    			return $workinfoId;	
	    		}else{
	    			// 失败删除已插入的数据
	    			DB::table('assignments')->where('id',$workinfoId)->delete();
	    			// 返回错误代码
	    			return 0;
	    		}
	    	}else{
	    		// 返回错误代码
	    		return 0;
	    	}
		}
    }
} 
?>