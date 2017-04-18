<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Image;
use Think\Page;
use Think\Upload;

class FoodController extends CommonController
{
    public function index()     //显示出商品页面
    {
        $Food = D('food');
        //引入分页类
        $counts = $Food ->count();
        $per = 10;
        $Page = new Page($counts,$per);
        $show = $Page->show();    //分页显示输出
        $foodlist = $Food
                ->join('fd_foodsort ON fd_foodsort.fsid = fd_food.fsid')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->order('fid desc')
                ->select();

        $this->assign('foodlist', $foodlist);
        $this->assign('page', $show);
        $this ->display();
    }

    public function edit()      //点击修改商品，显示出页面
    {
        $map['fid'] = I('id');
        $foodlist = D('food') ->where($map) ->find();
        $this ->assign('foodlist',$foodlist);           //显示要修改的内容

        $foodsortlist = D('foodsort') ->select();
        $this ->assign('foodsortlist',$foodsortlist);   //显示出可供选择的所有商品类型

        $this ->display();

    }

    public function editsave()  //将修改的商品信息读入数据库中
    {
        $fid = $_POST['fid'];                //商品id

        $Food = D("Food");
        $map['fname'] = $_POST['fname'];        //菜名
        $map['fsid'] = $_POST['fsid'];          //商品分类id
        $map['fcontent'] = $_POST['fcontent'];  //菜品内容
        $map['fprice'] = $_POST['fprice'];      //商品价格

        if (!$_FILES["fpic"]["name"]) {     //图片为空时不做图片处理
            if ($Food->create($map)) {  // 验证通过 可以进行其他数据操作
                $result = $Food->where("fid = $fid")->save($map);
                if ($result){
                    $this->success('操作成功', U('Food/index'));
                }else{
                    $this ->error('修改失败');
                }
            }
            else {  //如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Food->getError());
            }
        }else {     //图片不为空时

            //A. 处理上传的图片附件
            $cfg = array(
                'rootPath'      =>  './uploads/fimg/', //保存根路径
            );
            $up = new Upload($cfg);
            //uploadOne()方法执行成功后会把附件(在服务器上)的名字和路径等相关信息给我们返回
            $z = $up ->uploadOne($_FILES['fpic']);
            $ImgPath = $up -> rootPath.$z['savepath'].$z['savename'];//图片路径名

            //B. 对上传好的图片制作缩略图
            $img = new Image();         //实例化Image对象
            $img -> open($ImgPath);      //打开被处理的图片
            $img -> thumb(490,419,6);   //制作缩略图(默认有等比例缩放效果)
            $img-> save($ImgPath);      //保存缩略图到服务器,将之前的原图替换为缩略图

            $_POST['fpic'] = ltrim($ImgPath,'.');  //把上传好的附件存储到数据库里边
        }

        $map['fpic'] = $_POST['fpic'];
        $Food -> create();
        $info = $Food ->where("fid = $fid") ->save($map);
        if ($info){
            $this ->redirect('index');
        }else{
            $this ->error('修改商品信息失败');
        }
    }

    public function add()       //显示出商品添加页面
    {
        $foodsort = D('foodsort') ->select();
        $this ->assign('foodsort',$foodsort);
        $this ->display();
    }

    public function addsave()   //将添加的商品信息读入数据库中
    {
        $Food = D("Food");
        $map['fname'] = $_POST['fname'];        //菜名
        $map['fsid'] = $_POST['fsid'];          //商品分类id
        $map['fcontent'] = $_POST['fcontent'];  //菜品内容
        $map['fprice'] = $_POST['fprice'];      //商品价格

        //A. 处理上传的图片附件
        $cfg = array(
            'rootPath'      =>  './uploads/fimg/', //保存根路径
        );
        $up = new Upload($cfg);
        //uploadOne()方法执行成功后会把附件(在服务器上)的名字和路径等相关信息给我们返回
        $z = $up ->uploadOne($_FILES['fpic']);
        $ImgPath = $up -> rootPath.$z['savepath'].$z['savename'];//图片路径名

        //B. 对上传好的图片制作缩略图
        $img = new Image();         //实例化Image对象
        $img -> open($ImgPath);      //打开被处理的图片
        $img -> thumb(490,419,6);   //制作缩略图(默认有等比例缩放效果)
        $img-> save($ImgPath);      //保存缩略图到服务器,将之前的原图替换为缩略图

        $_POST['fpic'] = ltrim($ImgPath,'.');  //把上传好的附件存储到数据库里边

        $map['fpic'] = $_POST['fpic'];
        $Food -> create($map);
        $info = $Food ->add();
        if ($info){
            $this ->redirect('index');
        }else{
            $this ->error('添加商品失败');
        }

    }

    public function down()      //商品下架
    {
        $map['fid'] = I('id');
        $Food = D('food');
        $Food ->status = 1;
        $result = $Food ->where($map) ->save();
        if ($result){
            $this ->redirect('index');
        }else{
            $this ->error('下架失败');
        }
    }

    public function up()        //商品上架
    {
        $map['fid'] = I('id');
        $Food = D('food');
        $Food ->status = 0;
        $result = $Food ->where($map) ->save();
        if ($result){
            $this ->redirect('index');
        }else{
            $this ->error('上架失败');
        }
    }

    public function del()       //删除商品
    {
        $map['fid'] = I('id');
        $result = D('food') ->where($map) ->delete();
        if($result){
            $this ->redirect('index');
        }else{
            $this ->error('删除失败');
        }
    }
}