<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class MessageController extends Controller
{
  // 留言板展示数据
    public function show(){
      $show=DB::table('message')->select('message.*','user.name')
                                ->join('user','message.user_id','user.id')
                                ->get();
        
        foreach ($show as $value) {
            $value->time=date('Y-m-d H:i',$value->time);
            unset($value->user_id);
        }
         return $show;
    }
    // 留言板发布
    public function message(Request $request){
       $text = $request->all();
       $text['time']=time();
       if (DB::table('message')->insert($text)) {
            return 1;
       }else{
            return 0;
       }
    }
}
?>

