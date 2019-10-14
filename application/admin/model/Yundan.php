<?php

namespace app\admin\model;

use think\Model;

class Yundan extends Model
{
    //创建一个静态方法getYundan,来获取运单信息,供用户1使用
    public static function getYundan($result){
        for($i=0;$i<count($result);$i++){
            $yundanid = $result[$i]['yundanid'];
//            $yundan = Yundan::get(['yundanid'=>$yundanid]);
            $yundan = self::get(['yundanid'=>$yundanid]);
            $array1['yundanid'] = $yundanid;
            $array1['c1'] = $yundan->c1;
            $array1['c2'] = $yundan->c2;
            $array1['c3'] = $yundan->c3;
            $array2[$i] = $array1;
        }
        return $array2;
    }
    //创建一个静态方法getYundan3,来获取运单信息,供用户3使用
    public static function getYundan3($result){
        for($i=0;$i<count($result);$i++){
            $yundanid = $result[$i]['yundanid'];
            $yundan = self::get(['yundanid'=>$yundanid]);
            $array1['yundanid'] = $yundanid;
            $array1['c1'] = $yundan->c1;
            $array1['c2'] = $yundan->c2;
            $array1['c3'] = $yundan->c3;
            $array1['user'] = $yundan->user;
            $array2[$i] = $array1;
        }
        return $array2;
    }
}
