<?php
/**
 * Created by PhpStorm.
 * User: Zhenglianxin
 * Date: 2016/2/17
 * Time: 17:38
 */
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller{

    /**
     * 显示用户登录页面
     */
    public function loginForm(){
        $this->display("loginform");
    }

    /**
     *用户登录方法
     */
    public function login(){
        $username = I("post.user_name");//;
        $password = I("post.password");
        $send = array();
        $user = M("user")->where("user_name='".$username."'")->find();
        if($user){
            if(md5($password) == $user['password']){//$user['password']){e10adc3949ba59abbe56e057f20f883e
                //if($password == "123456"){
                //$this->session("user_name",$username);
                session("user_name",$username);
                $this->ajaxReturn(array("status"=>true,"msg"=>"登陆成功！"),'json');

            }else{
                $this->ajaxReturn(array("status"=>false,"msg"=>"密码错误！"),'json');
                return false;
            }
        }else{
            $this->ajaxReturn(array("status"=>false,"msg"=>"用户不存在！"),'json');
            return false;
        }
    }

    /**
     *用户退出登录方法
     */
    public function quit(){
        session("user_name","");
//        header("Location:");
//        $this->display("say");
        $this->redirect("index/say");
    }

    /**
     * 用户注册
     */
    public function registForm(){
        $this->display("regist");
    }

    public  function regist(){
        $user = I("post.");
        $user["password"] = md5($user['password']);
        $user['create_time'] = time();
        $add_user = M("user");
        if($add_user->add($user)){
            $this->success("恭喜您，注册成功！","loginForm");
        }else{
            $this->error("注册失败！");
        }
    }
}

