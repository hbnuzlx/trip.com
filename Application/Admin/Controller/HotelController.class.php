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
}