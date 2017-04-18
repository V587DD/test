<?php
namespace Admin\Model;

use Think\Model;

class ShopModel extends Model
{
    protected $_validate = array(
        array('sname', 'require', '店铺名字不可以为空'),
        array('saddress', 'require', '店铺地址不可以为空'),
        array('tel', 'require', '电话不可以为空'),
    );
}