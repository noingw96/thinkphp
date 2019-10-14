<?php

namespace app\admin\model;

use think\Model;

class Yundan2 extends Model
{
    //创建一个静态方法getYundan,来获取运单信息
    public static function getYundan($result){
        for($i=0;$i<count($result);$i++){
            $yundanid = $result[$i]['yundanid'];
            $yundan = self::get(['yundanid'=>$yundanid]);
            $array1['yundanid'] = $yundanid;
            $array1['c1'] = $yundan->c1;
            $array1['c2'] = $yundan->c2;
            $array1['c3'] = $yundan->c3;
            $array2[$i] = $array1;
        }
        return $array2;
    }
}
