<?php 

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class AssignmentController extends Controller
{
	// 作业图片获取
	public function picture(Request $request){
		// 获取作业ID
		$assignmentsId=$request->input('workId');
		// 获取对应图片名称
		$picture=DB::table('image')->select('name')->where('assignments_id',$assignmentsId)->get();
		return $picture;
	}

	// 作业展示
	public function show(Request $request){
		// 获取用户ID
		$userId =$request->input('user_id');
		// 获取数据
		$data=DB::table('assignments')->select('assignments.*','user.name as teacherName','group.name as  typename','assignments_type.name as ClassName')
									->join('user','user.id','assignments.user_id')
									->join('group','group.id','assignments.group_id')
									->join('assignments_type','assignments_type.id','assignments.assignments_type_id')
									->where('assignments.user_id',$userId)
									->get();


			foreach ($data as $value) {
				$value->time=date('m-d H:i',$value->time);
				$value->workId=$value->id;
				$submited=DB::table('submited')->where('assignments_id',$value->id)->first();
				if ($submited) {
					$value->submited='true';
				}else{
					$value->submited='false';
				}
				unset($value->id,$value->assignments_type_id,$value->user_id,$value->group_id,$value->star);
			}

			return $data;
	}
	// 获取星星数
	public function star(Request $request){
		$id=$request->input('workId');
		$star=DB::table('assignments')->where('id',$id)->value('star');
		return $star;
	}

	/*学生列表*/
	public function studentList(Request $request){
		// 获取作业详情ID
		$workId =$request->input('workId');
		// 获取群ID
		$group_id=DB::table('assignments')->where('id',$workId)->value('group_id');
		// 获取群成员
		$list=DB::table('user')->where('group_id','like',"%$group_id%")->get();
		// 遍历数据
		foreach ($list as $key => $value) {
			// 判断是否已提交
			$submited=DB::table('submited')->where([['user_id',$value->id],['assignments_id',$workId]])->first();
			if ($submited) {
				$value->submited=TRUE;
			}else{
				$value->submited=FALSE;
			}
			
		}
		$list->count=count($list);
		return $list;
	}

	/*
		$request接收需要上传的图片
		上传图片
	*/
	public function upload(Request $request){
		// 获取图片信息
		$imageFile=$request->file('image');
		// 图片上传路径
		$path="Image";
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
				$assignmentsId=$request->input('assignments_id');
				$id=explode(',', $assignmentsId);
				foreach ($id as $key => $value) {
					$imagesData = array(
						'assignments_id' =>$value,
						'name'=>$newName 
					);
				// 添加进数据库
					$insert=DB::table('image')->insert($imagesData);
				}
				
				if ($insert) {
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
    	// 获取数据
    	$data=$assignment=$request->all();
    	if ($data) {
    		// 添加时间
	    	$assignment['time']=time();
	    	$groupId=explode(',', $assignment['group_id']);
	    	foreach ($groupId as $value) {
	    		$assignment['group_id']=$value;
	    		$assignment['star']=5;
		    	if ($id=DB::table('assignments')->insertGetId($assignment)) {
		    			$assigmentsId[]=$id;
				}
	    	}
	    	$id =join(',',$assigmentsId); 
	    	return $id;
		}

    }

    /*
		作业评价
    */
		public function submited(Request $request){
			$submited =$request->all();
			$workId=$request->input('assignments_id');
			if (DB::table('submited')->insert($submited)) {
				$peopleCount=DB::table('submited')->where('assignments_id',$workId)->count();
				$starCount=DB::table('submited')->where('assignments_id',$workId)->sum('star');
				$star = round($starCount/$peopleCount);	
				if (DB::table('assignments')->where('id',$workId)->update(['star'=>$star])) {
					return 1;
				}
			}
		}
} 
?>