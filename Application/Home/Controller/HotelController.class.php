<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Description of HotelController
 * @date    2016-05-04 23:54:32
 * @author Zhenglianxin
 */

class HotelController extends Controller {
    /**
     * 显示酒店详细信息页
     */
    public function hotelDetail(){
        $hotel_id = I("get.hotelId");
        $hotelInfo = M("hotel")->where("hotel_id=".$hotel_id)->find();
        $hotelDetail = M("hotel_detail")->where("hotel_id=".$hotel_id)->select();
        foreach($hotelDetail as $k=>$hotel){
            $hotelDetail[$k]["hotel_content"] = explode("；", trim($hotel["hotel_content"],"；"));
        }
//        var_dump($hotelDetail);exit;
        $pic = M("hotel_pics")->where("hotel_id=".$hotel_id)->select();
        $hotelInfo["detail"]=$hotelDetail;
        $hotelInfo["pic"]=$pic;
//        var_dump($hotelInfo);exit;
        $this->assign("hotelInfo",$hotelInfo);
        $this->display("hotelDetail");
    }
}
