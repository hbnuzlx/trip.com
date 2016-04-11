<?php
/**
 * Created by PhpStorm.
 * User: Zhenglianxin
 * Date: 2016/2/17
 * Time: 17:30
 */
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    /**
     *主页显示
     */
    public function index(){
        //$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div></script>','utf-8');
        $this->display("index");
    }

    /**
     *测试方法
     */
	public function say(){
		$this->show("<h3 style='color:red'>Hello World!!!</h3>");
        $this->assign("name","郑连新");
        $this->assign("age","24");
        $message = M("user")->find();
        $this->assign("msg",$message);
        $this->display("say");
	}

    /**
     *用户登录方法
     */
    public function loginForm(){
        $this->display("login");
    }
    public function login(){
        $username = I("post.user_name");//;
        $password = I("post.password");
        $send = array();
        $user = M("user")->where("user_name='".$username."'")->find();
        if($user){
            if(md5($password) == $user['password']){//$user['password']){e10adc3949ba59abbe56e057f20f883e
                //if($password == "123456"){
                //$this->session("user_name",$username);
                $this->ajaxReturn(array("status"=>true,"msg"=>"登陆成功！"),'json');
                $this->session("user_name",$username);
            }else{
                $this->ajaxReturn(array("status"=>false,"msg"=>"密码错误！"),'json');
                return false;
//                $send["status"]=false;
//                $send["msg"]="密码错误！";
            }
        }else{
//            $send["status"]=false;
//            $send["msg"]="用户不存在！";
            $this->ajaxReturn(array("status"=>false,"msg"=>"用户不存在！"),'json');
            return false;
        }

    }


}