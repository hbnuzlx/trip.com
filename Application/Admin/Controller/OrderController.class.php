<?php

namespace Admin\Controller;
use Think\Controller;

/**
 * 订单控制器
 *订单的浏览及其各类操作
 * @author Zhenglianxin
 * @create:2016-04-26 15:32:29
 */
class OrderController extends Controller{
    
    /**
     * 订单查看 显示所有订单
     */
    public function index(){
        $orders = M("order")->select();
        foreach($orders as $k=>$order){
            $user = M("user")->where("user_id=".$order["user_id"])->find();
            $order_detail = M("order_detail")->where("order_id=".$order["order_id"])->select();
            $order_price = 0;
            foreach ($order_detail as $key=>$value){
                $order_price+=(int)$value["goods_price"];
            }            
            $orders[$k]["user"] = $user["user_name"];
            $orders[$k]["order_price"] = $order_price;
        }
        $state = array("1"=>"待支付","2"=>"已支付","3"=>"已完成","4"=>"废弃订单");
        $this->assign("orders",$orders);
        $this->assign("state",$state);
//        var_dump($orders);exit;
        $this->display("orderList");
    }
    
    /**
     * 显示订单详情
     */
    public function orderDetail(){
        $order_id = I("get.orderId");
        $order_detail = M("order_detail")->where("order_id=".$order_id)->select();
//        var_dump($order_detail);exit;
        $this->assign("orderDetail",$order_detail);
        $this->assign("type",array("1"=>"旅游订单","2"=>"酒店订单"));
        $this->display("orderDetail");
    }
}
