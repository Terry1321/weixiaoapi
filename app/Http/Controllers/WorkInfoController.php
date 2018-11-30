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
				$types=DB::table('notices_type')->where('name','通知')->get();
				break;
		}
		return $types;
	}
}
 
?>