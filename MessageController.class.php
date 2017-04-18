<?php
namespace Admin\Controller;

use Think\Controller;
use Think\Page;

class MessageController extends CommonController
{
    public function index()      //显示出所有评价
    {
        $Message = M('message');
        //引入分页类
        $counts = $Message ->where('replyid = 0') -> count(); // 0表示用户的留言  1 表示回复的留言
        $per = 8;
        $Page = new Page($counts,$per);

        //连表查询当前页面的留言信息
        $info = $Message
            ->join('fd_user ON fd_user.uid = fd_message.uid')
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->order('mid desc')
            ->where('replyid = 0')
            ->select();

        $page = $Page -> show();   //分页显示输出
        $this ->assign('mslist', $info);
        $this ->assign('page', $page);

        //查询后台boss回复用户留言的信息，用户留言replyid默认为 0，
        //回复留言则将用户的留言id存入replyid字段，就可以知道回复的哪条留言了
        $reply = $Message ->field('mcontent,replyid')-> where('replyid != 0') -> select();
        $this -> assign('reply',$reply);

        $this ->display();
    }

    public function reply()      //显示出回复留言的页面
    {
        $mid = I('id');
        $Message = D('Message');
        $mitem = $Message ->where('mid='.$mid) ->find(); //查询出要回复的留言id 和用户id

        $this->assign('mitem',$mitem);
        $this->display();
    }

    public function save()       //将回复信息保存到数据库
    {
        $data['uid'] = I('uid');
        $data['replyid'] = I('mid');    // 将用户id 存入replyid字段 标记回复的是哪条评价
        $data['mcontent'] = I('content');
        $Message = D('Message');
        $Message ->add($data);
        header("location:".$_SERVER["HTTP_REFERER"]);
    }
}