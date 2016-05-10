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
//        session_start();
//        var_dump($_SESSION);exit;
        if($_SESSION["user"]===null){
            
            header("Location:".__MODULE__."/user/loginForm");
        }
        $trips  = M("attraction")->where("state>0")->limit(0,5)->select();
        foreach($trips as $k=>$val){
            $pic = M("attr_pics")->where("attr_id=".$val["attr_id"])->find();
            $price = M("attr_detail")->field("attr_price")->where("attr_id=".$val["attr_id"])->select();
            $trips[$k]["pic"] = $pic["pic_path"]."/".$pic["pic_name"];
            $prices = array();
            foreach($price as $key=>$v){
                $prices[] =$v["attr_price"];
            }
            $trips[$k]["price"] = min($prices);//min($price["attr_price"]);
        }
//        var_dump($trips);exit;
        $this->assign("trips",$trips);
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