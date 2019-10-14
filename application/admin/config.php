<?php
//后台配置文件
return [
    // 视图输出字符串内容替换
    'view_replace_str'       => [
//        重置_STATIC_常量,原始的在thinkphp/library/think/view.php下
        '__STATIC__'=>'/static/admin',
    ]
];