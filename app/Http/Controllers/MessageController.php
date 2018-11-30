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
            $value->comment=DB::table('comment')->select("user.name","comment.content")
            ->join('user','comment.user_id','user.id')
            ->where('message_id',$value->messageId)->get();
            $value->teacherName=$value->name;
            unset($value->name,$value->user_id);
        }
         return $show;
    }
    // 留言板发布
    public function message(Request $request){
       $text = $request->all();
       $text['user_id']=$request->input('user_id');
       $text['time']=time();
       if (DB::table('message')->insert($text)) {
            return 1;
       }else{
            return 0;
       }
    }
    // 发布评论
    public function comment(Request $request){
        $comment = $request->all();
       if (DB::table('comment')->insert($comment)) {
            return 1;
       }else{
            return 0;
       }
    }
}
?>

