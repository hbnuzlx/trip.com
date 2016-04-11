<?php
/**
 * Created by PhpStorm.
 * User: zhenglianxin
 * Date: 2016/2/23
 * Time: 17:30
 */

namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(!session("admin")){
            header("location:user/loginForm");
        }
//       $this->show("<span style='font-size:30px;font-weight: bold;font-family:microsoft yahei;margin:150px 100px;'>Wellcom To The Background Of The WebSite ！！！</span><span style='font-size:60px;font-family: microsoft yahei;;'>O(∩_∩)O~</span>");
//        echo "Hello !!!";
//        $_SESSION = array();
//        echo "<br/>";
//        var_dump($_SESSION);
        $this->display("index");
    }
}