<?php
/**
 * Created by PhpStorm.
 * User: zhenglianxin
 * Date: 2016/2/23
 * Time: 17:30
 */

namespace Admin\Controller;

use Think\Controller;

class UserController extends Controller{

    /**
     * 显示后台首页
     */
    public function index(){
        if($_SESSION["admin"]!=null){
            header("Location:/trip.com/index.php/admin/index/index");
        }else{
            header("Location:loginForm");
        }
    }

    /**
     * 后台登录界面
     */
    public function loginForm(){
        $this->display("loginForm");
    }

    /**
     * 用户登录方法
     */
    public function login(){
        $username = I("post.user_name");//;
        $password = I("post.password");
        $send = array();
        $user = M("user")->where("user_name='".$username."' or nick_name='".$username."'")->find();
        if($user && $user['user_cat']==1){
            if(md5($password) == $user['password']){
                //if($password == "123456"){
                //$this->session("user_name",$username);
                session("admin",$user);
                $this->ajaxReturn(array("status"=>true,"msg"=>$_SESSION['admin']["nick_name"]."登陆成功！"),'json');
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
     * 用户退出登录
     */
    public function logout(){
//        session("admin","");
        $_SESSION['admin']=NULL;
        header("Location:loginForm");
    }

    /**
     * 管理员列表显示
     */
    public function adminList(){
        $cat = array(1=>"系统管理员",2=>"酒店管理员",3=>"景点管理员");
        $userList = M("user")->where("user_cat between 1 and 3")->select();
        $this->assign("userlist",$userList);
        $this->assign("cat",$cat);
        $this->display("adminlist");
    }

    /**
     * 普通用户列表
     */
    public function userList(){
        $rand = array(0=>"普通会员",1=>"铜牌会员",2=>"银牌会员",3=>"金牌会员",4=>"白金会员",5=>"钻石会员");
        $userList = M("user")->where("user_cat=4")->select();
        $this->assign("userlist",$userList);
        $this->assign("rand",$rand);
        $this->display("userlist");
    }

    /**
     * 用户信息操作
     */

    /** 显示用户信息修改表单
     */
    public function usereditForm(){
        $user_id = I("get.id");
//        echo $user_id;exit;
        $rand = array(0=>"普通会员",1=>"铜牌会员",2=>"银牌会员",3=>"金牌会员",4=>"白金会员",5=>"钻石会员");
        $user = M('user')->where("user_id=".$user_id)->find();
//        var_dump($user);exit;
        $this->assign("userInfo",$user);
        $this->assign("band",$rand);
        $this->display("editform");
    }

    /**
     * 修改用户信息
     */
    public function userEdit(){
        $userInfo = I("post.");
        $user_id = $userInfo['user_id'];
        $userInfo['password'] = md5($userInfo['password']);
        $user = M("user");
        $res = $user->where("user_id=".$user_id)->save($userInfo);
        if($res){
            $this->ajaxReturn(array("status"=>true,"msg"=>"用户信息修改成功！"),'json');
        }else{
            $this->ajaxReturn(array("status"=>false,"msg"=>"用户信息修改失败！"),'json');
        }

    }

    /**
     *
     * 添加后台管理员
     */
    public function addForm(){
        $this->display("addForm");
    }

    public function adminAdd(){
        $user_info = $_POST;
        $user_info["password"]=md5($user_info['password']);
        $user_info['create_time']=time();
//        echo "<pre>";
//        print_r($user_info);exit;
        $user= M("user");
        $res = $user->add($user_info);
        if($res){
            $this->ajaxReturn(array("status"=>true,"msg"=>"用户添加成功！"),'json');
        }else{
            $this->ajaxReturn(array("status"=>false,"msg"=>"用户添加失败！"),'json');
        }
;    }

    /**
     * 删除用户
     */
    public function userDel(){
        $user_id = I("get.id");
        $res = M("user")->where("user_id = ".$user_id)->delete();
        if($res){
            $this->success("删除用户成功！",__CONTROLLER__."/adminList");
        }else{
            $this->error("删除失败！","user/adminList");
        }
    }


}