<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Description of ViewController
 * @date   2016-05-01 17:20:38
 * @author Zhenglianxin
 */
class ViewController extends Controller {
    
    /**
     * 显示景点详情信息
     * @author   zhenglianxin
     * @date     2016-05-01 17:27:32 
     */
    public function attrDetail(){
        $attr_id = I("get.id");
        //根据所传的ID获取到景点的详细信息
        $attrInfo = M("attraction")->where("attr_id=".$attr_id)->find();
        $attrDetail = M("attr_detail")->where("attr_id=".$attr_id)->select();
        foreach($attrDetail as $k=>$value){
            $detail = explode("；",$value["attr_con"]);
            array_pop($detail);
            $attrDetail[$k]["attr_con"] = $detail;// array_diff($detail,array(0=>array_pop($detail)));
        }
//        var_dump($attrDetail);exit;
        //获取景点的图片
        $attrPic = M("attr_pics")->where("attr_id=".$attr_id)->select();
        $prices = array();
        foreach($attrDetail as $key=>$price){
            $prices[] = $price["attr_price"];
        }
        $attrInfo["detail"]=$attrDetail;
        $attrInfo["pic"]=$attrPic;
        
//        获取对应景点附近的酒店信息
        $hotels=M("hotel")->where("attr_id=".$attr_id)->select();
//        获取酒店对应的图片
        foreach($hotels as $hotel_k => $hotel){
            $pic = M("hotel_pics")->where("hotel_id=".$hotel["hotel_id"])->find();
            $hotels[$hotel_k]["pic"]=$pic;
        }
//	echo "<pre>";
//        var_dump($hotels);//exit;
//        var_dump($attrInfo["detail"]);exit;
        $this->assign("tripInfo",$attrInfo);
        $this->assign("hotelInfo",$hotels);
        $this->assign("minPrice",min($prices));
        $this->display("attrDetail");
    }
    
    /**
     * 生成订单前确认订单
     * @author  Zhenglianxin
     * @date    2016-5-3 21:00:07
     */
    public function orderConfirm(){
        $orderInfo = I("post.");
        $user = session("user");//
        $orderInfo["user_id"] = $user['user_id'];
        $orderInfo["goods_num"] = $orderInfo["adult"]+0.5*$orderInfo["child"];
        $order = array("order_id"=>time(),"order_state"=>1,"user_id"=>$orderInfo["user_id"],"order_createtime"=>date("Y-m-d H:i:s"));
        $res = M("order")->add($order);//插入订单
        $goods_name = "";
        if($orderInfo["goods_type"]=="1"){
            $goods = M("attraction")->where("attr_id=".$orderInfo["goods_id"])->find();
            $goods_name = $goods["attr_name"];
        }else{
            $goods = M("hotel")->where("hotel_id=".$orderInfo["goods_id"])->find();
            $goods_name = $goods["hotel_name"];
        }
//        echo $goods_name;exit;
        $orderDetail = array("order_id"=>$order["order_id"],"go_time"=>$orderInfo["go_time"],"goods_name"=>$goods_name,"goods_id"=>$orderInfo["goods_id"],"goods_type"=>$orderInfo["goods_type"],"goods_num"=>$orderInfo["goods_num"],"goods_price"=>$orderInfo["goods_price"]*$orderInfo["goods_num"]);
        $detailRes = M("order_detail")->add($orderDetail);
        if($res && $detailRes){
            echo "<h1 style='color:#fc0'><b>恭喜您，订单提交成功！！！</b></h1>";
        }
//        var_dump($orderDetail);
//        var_dump($orderInfo);exit;
    }
}
