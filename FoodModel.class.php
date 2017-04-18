<?php
namespace Admin\Model;
use Think\Model;

class FoodModel extends Model
{
    protected $_validate	 =	 array(

        array('fname','require','菜名不可以为空'),
        array('fprice','require','价格不可以为空'),

    );
}