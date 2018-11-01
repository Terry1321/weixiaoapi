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

		foreach ($workDetail as $key => $value) {
			$value->time=date('m-d H:i',$value->time);
			$value->img=DB::table('image')->select('name')->where('assignments_id',$id)->get();
		}
		return $workDetail;
	}
	// 作业展示
	public function show(Request $request){
		// $userId =$request->input('user_id');
		$assignments =DB::table('assignments')
			->select('assignments.*','user.name as username','user.group_id','assignments_type.name as typename','group.name as groupname')
			 ->join('user','user.id','assignments.user_id')
			 ->join('assignments_type','assignments_type.id','assignments_type_id')
			 ->join('group','group.id','assignments.group_id')
			 ->get();
			
			foreach ($assignments as $value) {
				$value->time=date('m-d H:i',$value->time);
			}
			return $assignments;
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
			if ($imageFile->move($path,$newName)) {
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
    	if ($request->all()) {
	    	$assignment=$request->except('openid');
	    	$assignment['time']=time();
	    	$assignment['read']=0;
	    	if ($workinfoId=DB::table('assignments')->insertGetId($assignment)) {
	    		return $workinfoId;	
	    	}else{
	    		return 0;
	    	}
		}
    }
} 
?>