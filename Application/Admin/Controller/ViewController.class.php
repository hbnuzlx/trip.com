<?php
/**
 * Created by PhpStorm.
 * User: zhenglianxin
 * Date: 2016/3/21
 * Time: 18:44
 */

namespace Admin\Controller;
use Think\Controller;
class ViewController extends Controller{

    /**
     * 显示景点列表
     */
    public function index(){
        $states = array(1=>"促销中",2=>"新上架",3=>"暂不开放");
        $attr = M("attraction");
        $pic = M("attr_pics");
        $attractions = $attr->order("createtime desc")->select();
        foreach($attractions as $k=>$v){
            $picname = $pic->where("attr_id=".$v["attr_id"])->find();
            if($picname){
                $attractions[$k]["pic"]=$picname["pic_path"]."/".$picname["pic_name"];
            }else{
                $attractions[$k]["pic"] = 1;
            }
        }
//        echo "<pre>";
//        var_dump($attractions);exit;
        $this->assign("attrInfo",$attractions);
        $this->assign("state",$states);
        $this->display("index");
    }

    /**
     * 添加景点信息
     */
    public function viewAddForm(){
        $this->display("addform");
    }

    public function viewAdd(){
        $newInfo = I("post.");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
//        $upload->savePath  =     "/".date("Ymd"); // 设置附件上传（子）目录
        $upload->saveName  =     date("Ymd").rand(1000,9999); // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = date("Ymd");
        // 上传文件
        $info   =   $upload->upload();
//        echo $upload->saveName."<hr/>";
//        var_dump($info);
//        echo $info["pic"]["savename"]."<br/>";
//        var_dump($newInfo) ;exit;
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $attr = M("attraction");
            $pic = M("attr_pics");
            $res = $attr->add($newInfo);
            $newPic = array();
            if($res){//如果景点信息插入成功执行图片的入库
            $newPic["pic_name"] = $info["pic"]["savename"];
            $newPic["pic_path"] = $info["pic"]["savepath"];
            $newPic["attr_id"] = $res;
            $result = $pic->add($newPic);
                if($result){
                    redirect("index");
                }
            }
        }
    }

    /**
     * 景点信息修改
     */
    public function attrEditForm(){
        $attr_id = I("get.id");
        $attr = M("attraction");
        $info = $attr->where("attr_id=".$attr_id)->find();
//        echo "<pre>";
//        var_dump($info);exit;
//        $info[""]=$attr_id;
        $this->assign("info",$info);
        $this->display("editform");
    }

    public function attrEdit(){
        $info = I("post.");
//        echo "<pre>";
//        var_dump($info);exit;
        $res = M("attraction")->save($info);
        if($res){
            redirect("attrEditForm/id/".$info["attr_id"]);
        }else{
            redirect("attrEditForm/id/".$info["attr_id"]);
        }
    }

    /**
     * 景点图片修改
     */
    //改图表单
    public function picEditForm(){
        $attr_id = I("get.id");
        $pic = M("attr_pics");
        $info = M("attraction")->where("attr_id=".$attr_id)->find();
        $pictures = $pic->where("attr_id=".$attr_id)->select();
        $this->assign("info",$info);
        $this->assign("pics",$pictures);
        $this->display("picEditForm");
    }
    //删除图片
    public function picDel(){
        $picid = I("get.pic_id");
        $picInfo = M("attr_pics")->where("pic_id=".$picid)->find();
        $file = "./Public/Uploads/{$picInfo['pic_path']}/{$picInfo['pic_name']}";
//        echo $file;
////        @unlink($file);
//        echo "<pre>";
//        if(@unlink($file)){
//            var_dump($picInfo);exit;
//        }else{
//            echo "删除失败！";exit;
//        }
        $res = M("attr_pics")->where("pic_id=".$picid)->delete();
        if($res){//数据库删除图片记录成功后  删除服务器端文件
            @unlink($file);
//            redirect("/trip.com/index.php/admin/view/picEditForm/id/{$picInfo['attr_id']}");
            redirect(__CONTROLLER__."/picEditForm/id/".$picInfo['attr_id']);
        }
    }
    //添加图片
    public  function picAdd(){
        $attr_id = I("post.attr_id");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
//        $upload->savePath  =     "/".date("Ymd"); // 设置附件上传（子）目录
        $upload->saveName  =     date("Ymd").rand(1000,9999); // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = date("Ymd");
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $pic = M("attr_pics");
            $newPic = array();
                $newPic["pic_name"] = $info["pic"]["savename"];
                $newPic["pic_path"] = $info["pic"]["savepath"];
                $newPic["attr_id"] = $attr_id;
                $result = $pic->add($newPic);
                if($result){
                    redirect("picEditForm/id/{$attr_id}");
                }

        }
    }

    /**
     * 景点套餐操作
     */

    public function attrDetailShow(){
        $attr_id = I("get.id");
        $attrDetail = M("attr_detail");
        $attr = M("attraction");
        $attrName = $attr->where(" attr_id=".$attr_id)->find();
        $attrs = $attrDetail->where("attr_id = ".$attr_id)->select();
        if($attrs==null){
            $attrs = 1;
        }
        $state=array("1"=>"在售中","2"=>"已下架","3"=>"等待上架");
        $this->assign("states",$state);
        $this->assign("combos",$attrs);
        $this->assign("attrName",$attrName);
        $this->display("attrDetailList");
    }

    //套餐详情添加
    public function detailAddForm(){
        $attr_id = I("get.id");
        $attr_name=M("attraction")->where("attr_id = ".$attr_id)->find();
        $this->assign("attraction_name",$attr_name);
        $this->display("detailaddform");
    }
    public function attrDetailAdd(){
        $info = I("post.");
//        var_dump($info);exit;
        $attr_id = $info["attr_id"];
        $res = M("attr_detail")->add($info);
        if($res){
            redirect("attrDetailShow/id/".$attr_id);
        }else{

        }
    }

    //套餐修改
    public function detailEditForm(){
        $combo_id = I("get.id");
        $comboInfo = M('attr_detail')->where("id=".$combo_id)->find();
        $attr_name=M("attraction")->where("attr_id = ".$comboInfo["attr_id"])->find();
        $this->assign("attraction_name",$attr_name);
        $this->assign("detailInfo",$comboInfo);
        $this->display("detailEditForm");
    }
    public function attrDetailEdit(){
        $info = I("post.");
        $res = M("attr_detail")->where("id=".$info["id"])->save($info);
        if($res){
            redirect("attrDetailShow/id/".$info['attr_id']);
        }else{
            $this->error("修改失败！");
        }
    }

    //套餐删除
    public function attrDetailDel(){
        $combo_id = I("get.id");
        $combo = M("attr_detail")->where("id=".$combo_id)->find();
        $res = M("attr_detail")->where("id=".$combo_id)->delete();
        if($res){
            redirect(__CONTROLLER__."/attrDetailShow/id/".$combo["attr_id"]);
        }
    }
}
