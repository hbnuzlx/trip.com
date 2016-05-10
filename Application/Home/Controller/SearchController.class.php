<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Description of SearchController
 * @date    2016-05-06 00:21:30
 * @author Zhenglianxin
 */
class SearchController extends Controller{
    
    public function index() {
        $keyword = i("get.keyword");
        $res_attr = M("attraction")->where("attr_name like '%".$keyword."%'")->select();
        foreach($res_attr as $key=>$attr){
            //获取景点图片
            $pic = M("attr_pics")->where("attr_id=".$attr["attr_id"])->find();
            $res_attr[$key]["pic"]=$pic;
            //获取景点的最低价钱
            $prices = array();
            $attrDetail = M("attr_detail")->where("attr_id=".$attr["attr_id"])->select();
            foreach($attrDetail as $k=>$attrs){
                $prices[]=$attrs["attr_price"];
            }
            $res_attr[$key]["price"] = min($prices);
        }
        $res_hotel = M("hotel")->where("hotel_name like '%".$keyword."%'")->select();
//        var_dump($res_attr);exit;
//        var_dump($res);exit;
        $this->assign("hotels",$res_hotel);
        $this->assign("attractions",$res_attr);
        $this->display("search-index");
    }
}
