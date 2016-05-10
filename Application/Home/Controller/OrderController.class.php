<?php

namespace Home\Controller;
use Think\Controller;

/**
 * Description of OrderController
 * @date    2016-05-05 08:38:42
 * @author Zhenglianxin
 */
class OrderController extends Controller{
    
    /**
     * 生成订单前确认订单
     * @author  Zhenglianxin
     * @date    2016-5-3 21:00:07
     */
    public function hotelOrderConfirm(){
        $orderInfo = I("post.");
//        var_dump($orderInfo);exit;
        $user = session("user");//
        $orderInfo["user_id"] = $user['user_id'];
        $orderInfo["goods_num"] = $orderInfo["goods_num"];//+0.5*$orderInfo["child"];
        $order = array("order_id"=>time(),"order_state"=>1,"user_id"=>$orderInfo["user_id"],"order_createtime"=>date("Y-m-d H:i:s"));
        $res = M("order")->add($order);//插入订单
//        var_dump($order);exit;
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
//        var_dump($orderDetail);exit;
        $detailRes = M("order_detail")->add($orderDetail);
        if($res && $detailRes){
            $orderInfo = M("order")->where("order_id=".$order["order_id"])->find();
            $orderDetailInfo = M("order_detail")->where("order_id=".$order["order_id"])->find();
//            var_dump($orderDetailInfo);
//            var_dump($orderInfo);exit;
            $this->assign("orderInfo",$orderInfo);
            $this->assign("orderType",array(1=>"旅游订单",2=>"酒店订单"));
            $this->assign("orderDetailInfo",$orderDetailInfo);
            $this->display("orderConfirm");
//            echo "<h1 style='color:#fc0'><b>恭喜您，订单提交成功！！！</b></h1>";
        }
        
//        var_dump($orderDetail);
//        var_dump($orderInfo);exit;
    }
    
    public function orderOk(){
        $this->display("orderConfirm");
    }
}
