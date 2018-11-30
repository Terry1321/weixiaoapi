<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class LoginController extends Controller
{
	/*
		获取openid
		$request获取小程序提供的code
	*/ 
    public function openid(Request $request){
	//声明CODE，获取小程序传过来的CODE
		$code = $request->input('code');
		//配置appid
		$appid ="wx0141ad9ef266df0a";
		//配置appscret
		$secret ="c608e3785447e2d3a9f5cdea516e34d5";
		//api接口
		$api ="https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$secret}&js_code={$code}&grant_type=authorization_code";

		//获取请求
		$curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$api);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书下同
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
        $data = curl_exec($curl);//运行curl
        curl_close($curl);
		return $data;
    }
    /*
		把openid 添加至数据库
		$request 获取小程序提供的openid
    */ 

    public function register(Request $request){
    	// openid
    	$openid=$request->input('openid');
    	if ($openid) {
    		$checkOpenid=DB::table('user')->where('openid',$openid)->first();	
    		if ($checkOpenid) {
    			$data['user_id']=$checkOpenid->id;
    		}else{
                $userData=$request->all();
		    	$userData['isTeacher']='false';
  		 		$data['user_id']=DB::table('user')->insertGetId($userData);
            }

            if ($data['user_id']) {
                $info=DB::table('user')->where('id',$data['user_id'])->get();
                foreach ($info as $key => $value) {
                    $data['isTeacher']=$value->isTeacher;
                    $data['name']=$value->name;
                }
            }
                return $data;
		}
	}
}
?>

