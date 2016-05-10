<?php
/**
 * Created by PhpStorm.
 * User: Zhenglianxin
 * Date: 2016/4/11
 * Time: 9:42
 */
namespace Admin\Controller;
use Think\Controller;
class HotelController extends Controller {

    /**
     * date：2016-04-11 9:46
     * user:Zhenglianxin
     * 显示所有酒店信息
     */
    public function index(){
        $hotelList = M("hotel")->select();
        foreach($hotelList as $k=>$v){
            $pic = M("hotel_pics")->where("hotel_id=".$v["hotel_id"])->find();
            if($pic){
                $hotelList[$k]["pic"]=$pic["pic_path"]."/".$pic["pic_name"];
            }else{
                $hotelList[$k]["pic"] = 1;
            }
//            $hotelList[$k]['pic'] = $pic;
            $detail = M("hotel_detail")->where("hotel_id=".$v["hotel_id"])->find();
            $hotelList[$k]["detail"] = $detail;
        }
        $state = array(1=>"在售中",2=>"促销中",3=>"已下架",4=>"已住满");
//        var_dump($hotelList);exit;
        $this->assign("hotelList",$hotelList);
        $this->assign("state",$state);
        $this->display("index");
    }

    /**
     * 酒店详情显示
     */
    public function hotelDetailShow(){
        $hotel_id = I("get.id");
        $hotel = M("hotel")->where("hotel_id=".$hotel_id)->find();
        $hotel_detail = M("hotel_detail")->where("hotel_id=".$hotel_id)->select();
        foreach($hotel_detail as $key=>$val){
//            $hotelDetail[$k]["hotel_content"] = explode("；", trim($hotel["hotel_content"],"；"));
            $hotel_detail[$key]["hotel_content"] = explode("；",trim($val["hotel_content"],"；"));
        }
//        var_dump($hotel_detail);exit;
        $state = array(1=>"在售中",2=>"促销中",3=>"已下架",4=>"已住满");
        $this->assign("hotelDetail",$hotel_detail);
        $this->assign("hotel",$hotel);
        $this->assign("state",$state);
        $this->display("hotelDetailList");
    }

    /**
     * 酒店信息添加
     */
    public function addForm(){
        $attr = M("attraction")->field("attr_id,attr_name")->select();
        $this->assign("attr",$attr);
        $this->display("addForm");
    }

    public function hotelAdd(){
        $info = I("post.");
//        echo "<pre>";
//        var_dump($info);exit;
        $res = M("hotel")->add($info);
        if($res){
            redirect("index");
        }else{
            redirect("addForm");
        }
    }

    /**
     * 酒店信息修改
     */
    public function hotelEditForm(){
        $hotel_id = I("get.id");
        $hotel_info = M("hotel")->where("hotel_id=".$hotel_id)->find();
        $attraction = M("attraction")->select();
        $this->assign("hotelInfo",$hotel_info);
        $this->assign("attr",$attraction);
        $this->assign("attr_id",$hotel_info['attr_id']);
        $this->display("hotelEditForm");
    }

    public function hotelEdit(){
        $editInfo = I("post.");
//        var_dump($editInfo);exit;
        $res = M("hotel")->where("hotel_id=".$editInfo["hotel_id"])->save($editInfo);
        if($res){
            redirect(__CONTROLLER__."/index");
        }else{
            redirect(__CONTROLLER__."hotelEditForm/id/".$editInfo["hotel_id"]);
        }

    }

    /**
     * 酒店图片操作
     */

    //改图表单
    public function picEditForm(){
        $hotel_id = I("get.id");
        $pic = M("hotel_pics");
        $info = M("hotel")->where("hotel_id=".$hotel_id)->find();
        $pictures = $pic->where("hotel_id=".$hotel_id)->select();
        $this->assign("info",$info);
        $this->assign("pics",$pictures);
        $this->display("picEditForm");
    }
    //删除图片
    public function picDel(){
        $picid = I("get.pic_id");
        $picInfo = M("hotel_pics")->where("hotel_pic_id=".$picid)->find();
        $file = "./Public/Uploads/Hotel/{$picInfo['pic_path']}/{$picInfo['pic_name']}";
        $res = M("hotel_pics")->where("hotel_pic_id=".$picid)->delete();
        if($res){//数据库删除图片记录成功后  删除服务器端文件
            @unlink($file);
//            redirect("/trip.com/index.php/admin/view/picEditForm/id/{$picInfo['attr_id']}");
            redirect(__CONTROLLER__."/picEditForm/id/".$picInfo["hotel_id"]);
        }
    }

    //添加图片
    public  function picAdd(){
        $hotel_id = I("post.hotel_id");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小3MB
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Public/Uploads/Hotel/'; // 设置附件上传根目录
//        $upload->savePath  =     "/".date("Ymd"); // 设置附件上传（子）目录
        $upload->saveName  =     date("Ymd").rand(1000,9999); // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = date("Ymd");
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $pic = M("hotel_pics");
            $newPic = array();
            $newPic["pic_name"] = $info["pic"]["savename"];
            $newPic["pic_path"] = $info["pic"]["savepath"];
            $newPic["hotel_id"] = $hotel_id;
            $result = $pic->add($newPic);
            if($result){
                redirect("picEditForm/id/{$hotel_id}");
            }

        }
    }

    /**
     * 酒店套餐操作
     */

    /**
     * 酒店套餐添加
     */
    public function hotelDetailAddForm(){
        $hotel_id = I("get.id");
        $hotelInfo = M("hotel")->where("hotel_id=".$hotel_id)->find();
        $this->assign("hotelInfo",$hotelInfo);
        $this->display("detailaddform");
    }

    public function hotelDetailAdd(){
        $addInfo = I("post.");
        $res = M("hotel_detail")->add($addInfo);
        if($res){
            redirect(__CONTROLLER__."/hotelDetailShow/id/".$addInfo["hotel_id"]);
        }else{
            redirect(__CONTROLLER__."/hotelDetailAddForm/id/".$addInfo["hotel_id"]);
        }
    }

    /**
     * 酒店套餐信息修改
     */
    public function hotelDetailEditForm(){
        $detailId = I("get.id");
        $detailInfo = M("hotel_detail")->where("hotel_detail_id=".$detailId)->find();
//        var_dump($detailInfo);exit;
        $hotelInfo = M("hotel")->where("hotel_id=".$detailInfo["hotel_id"])->find();
        $this->assign("detailInfo",$detailInfo);
        $this->assign("hotelInfo",$hotelInfo);
        $this->display("detailEditForm");

    }
    public function hotelDetailEdit(){
        $newInfo = I("post.");
//        var_dump($newInfo);exit;
        $hotelDetailId = $newInfo['id'];
        $res = M("hotel_detail")->where("hotel_detail_id=".$hotelDetailId)->save($newInfo);
        if($res){
            redirect(__CONTROLLER__."/hotelDetailShow/id/".$newInfo["hotel_id"]);
        }else{
            redirect(__CONTROLLER__."/hotelDetailEditForm/id/".$hotelDetailId);
        }
    }

    /**
     * 酒店套餐删除
     */
    public function hotelDetailDel(){
        $detailId = I("get.id");
        $hotelId = M("hotel_detail")->field("hotel_id")->where("hotel_detail_id=".$detailId)->find();
//        var_dump($hotelId);exit;
        $res = M("hotel_detail")->where("hotel_detail_id=".$detailId)->delete();
        if($res){
            redirect(__CONTROLLER__."/hotelDetailShow/id/".$hotelId["hotel_id"]);
        }else{
            $this->error("删除失败！");
        }
    }

}