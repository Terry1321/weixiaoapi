<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class NameChangeController extends Controller{
	//修改用户信息
	public function nameChange(Request $request){
		//获取用户ID
		$id=$request->input('id');
		//获取需要修改的名字
		$changed=$request->except('id');
		if (DB::table('user')->where('id',$id)->update($changed)) {
			$return = $request->input('name');
			return $return;
		}else{
			return 0;
		}
	}	
}
?>