<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class OrderController extends CommonController
{
    public function index()     //显示出全部订单页面
    {
        $Order = D('foodorder');
        $id = I('id');

        if($id == '1'){     //最新提交
            //引入分页类
            $counts = $Order ->where("orderstatus = 0") ->count();
            $per = 8;
            $Page = new Page($counts,$per);
            $show = $Page->show();          //分页显示输出
            $orderlist = $Order
                ->join('fd_address ON fd_address.addressid = fd_foodorder.addid')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('orderid desc')
                ->where("orderstatus = 0")
                ->select();
        }elseif ($id == '2'){       //已确认订单
            //引入分页类
            $counts = $Order ->where("orderstatus = 1") ->count();
            $per = 8;
            $Page = new Page($counts,$per);
            $show = $Page->show();          //分页显示输出
            $orderlist = $Order
                ->join('fd_address ON fd_address.addressid = fd_foodorder.addid')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('orderid desc')
                ->where("orderstatus = 1")
                ->select();
        }elseif ($id == '3'){       //配送中
            //引入分页类
            $counts = $Order ->where("orderstatus = 2") ->count();
            $per = 8;
            $Page = new Page($counts,$per);
            $show = $Page->show();          //分页显示输出
            $orderlist = $Order
                ->join('fd_address ON fd_address.addressid = fd_foodorder.addid')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('orderid desc')
                ->where("orderstatus = 2")
                ->select();
        }elseif ($id == '4'){       //已完成
            //引入分页类
            $counts = $Order ->where("orderstatus = 3") ->count();
            $per = 8;
            $Page = new Page($counts,$per);
            $show = $Page->show();          //分页显示输出
            $orderlist = $Order
                ->join('fd_address ON fd_address.addressid = fd_foodorder.addid')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('orderid desc')
                ->where("orderstatus = 3")
                ->select();
        }else{
            //引入分页类
            $counts = $Order ->count();
            $per = 8;
            $Page = new Page($counts,$per);
            $show = $Page->show();          //分页显示输出
            $orderlist = $Order
                ->join('fd_address ON fd_address.addressid = fd_foodorder.addid')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('orderid desc')
                ->select();
        }
        $this->assign('orderlist', $orderlist);
        $this->assign('page', $show);

        $css['id'] = $id;
        $this ->assign('css',$css);       //赋值一个id，点击不同选项显示出CSS
        $this ->display();
    }

    public function find()      //搜索
    {
        $val = $_POST['val'];
        $find = $_POST['condition'];

        $Order = D('foodorder');
        if ($val == '1'){   //按手机号查找
            $map['tel'] = array('like', "%$find%");
        }else{      //按订单号查找
            $map['orderid'] = array('like', "%$find%");
        }
        $counts = $Order ->where($map) ->count();
        $per = 8;
        $Page = new Page($counts,$per);
        $show = $Page->show();          //分页显示输出
        $orderlist = $Order
            ->join('fd_address ON fd_address.addressid = fd_foodorder.addid')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('orderid desc')
            ->where($map)
            ->select();

        $this ->assign('orderlist', $orderlist);
        $this ->assign('page', $show);
        $this ->display();
    }

    public function orderok()   //确认接单
    {
        $map['orderid'] = I('id');
        $Order = D('foodorder');
        $Order ->orderstatus = 1;
        $result = $Order ->where($map) ->save();
        if($result){
            $this ->redirect('index');
        }else{
            $this ->error('确认订单失败');
        }
    }

    public function cancel()    //不接单
    {
        $map['orderid'] = I('id');
        $Order = D('foodorder');
        $Order ->orderstatus = 4;
        $result = $Order ->where($map) ->save();
        if($result){
            $this ->redirect('index');
        }else{
            $this ->error('不接单失败');
        }
    }

    public function look()      //查看订单详情页
    {
        $map['orderid'] = I('id');
        $orderlist = D('foodorder') ->where($map) ->find();
        $uid['uid'] = $orderlist['uid'];
        $uname = D('user') ->field('username') ->where($uid) ->find();
        $this ->assign('uname',$uname);

        \Home\Controller\CartController::cartok();
    }

    public function send()      //送货
    {
        $map['orderid'] = I('id');
        $Order = D('foodorder');
        $Order ->orderstatus = 2;
        $result = $Order ->where($map) ->save();
        if($result){
            $this ->redirect('index');
        }else{
            $this ->error('派送失败');
        }
    }

    public function finish()    //完成
    {
        $map['orderid'] = I('id');
        $Order = D('foodorder');
        $Order ->orderstatus = 3;
        $result = $Order ->where($map) ->save();
        if($result){
            $this ->redirect('index');
        }else{
            $this ->error('派送失败');
        }
    }

}