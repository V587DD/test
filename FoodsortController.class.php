<?php
namespace Admin\Controller;
use Think\Controller;

class FoodsortController extends CommonController
{
    public function index()     //显示出所有的商品类型
    {
        $foodsort = D('foodsort') ->select();
        $this ->assign('foodsort',$foodsort);
        $this ->display();
    }

    public function add()       //增加分类 两个逻辑 1、显示页面   2、post不为空 将信息保存数据库
    {
        if($_POST['fsname']){
            $data['fsname'] = $_POST['fsname'];
            $result = D('foodsort') ->add($data);
            if ($result){
                $this ->redirect('index');
            }else{
                $this ->error('新增失败');
            }
        }else{
            $this ->display();
        }
    }

    public function edit()       //点击修改 显示出要修改的分类信息
    {
        $fsid = I('id');
        $info = D('foodsort') ->where("fsid = $fsid") ->find();
        $this ->assign('info',$info);
        $this ->display();
    }

    public function save_edit()     //保存修改后的分类信息
    {
        if (!empty($_POST['fsname'])){
            $foodsort = D('foodsort');
            $foodsort ->create();
            $result = $foodsort ->save(); //post值含有主键fsid，所以可以不用where
            if($result){
                $this ->redirect('index');
            }else{
                $this->error('修改失败');
            }
        }else{
            $this->error('修改失败');
        }
    }

    public function del()       //删除分类
    {
        $data['fsid'] = I('id');
        $result = D('foodsort') ->where($data) ->delete();
        if($result){
            $this->redirect('index');
        }else{
            $this ->error('删除失败');
        }
    }

}