<?php

namespace app\admin\model;

use think\Model;

class Yundan4 extends Model
{
    //创建一个静态方法getYundan,来获取运单信息
    public static function getYundan($result){
        for($i=0;$i<count($result);$i++){
            $yundanid = $result[$i]['yundanid'];
            $yundan = self::get(['yundanid'=>$yundanid]);
            $array1['yundanid'] = $yundanid;
            $array1['k1'] = $yundan->k1;
            $array1['k2'] = $yundan->k2;
            $array1['k3'] = $yundan->k3;
            $array2[$i] = $array1;
        }
        return $array2;
    }
}