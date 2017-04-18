<?php
namespace Admin\Controller;

use Think\Controller;

class ShopController extends CommonController
{
    public function index()     //显示店铺设置首页
    {
        $info = D('shop') ->find();
        $this -> assign('shopinfo',$info);
        $this -> display();
    }

    public function save()      //将店铺信息保存到数据库，更新店铺信息
    {
            $shop = D('shop');
            if(!$shop -> create()){
                $this ->error($shop ->getError());
            }else{
                $result = $shop -> where('shopid = 1') ->save();
                if ($result){
                    $this ->redirect('index');
                }
            }
    }

}