<?php
/**
 * 数据库字段校验规则
 * (系统自动生成)
 * @author zhaozhuobin
 * @date 2018-12-27 12:01
 */
return [
    "pha_user"  =>  [
        "uid"  =>  [
        ],
        "username"  =>  [
            "length"  =>  255,
        ],
        "password"  =>  [
            "length"  =>  32,
        ],
        "status"  =>  [
            "min"  =>  0,
            "max"  =>  999,
        ],
    ],
    "pha_user_profile"  =>  [
        "id"  =>  [
        ],
        "nickname"  =>  [
            "length"  =>  255,
        ],
        "uid"  =>  [
            "min"  =>  0,
            "max"  =>  99999999999,
        ],
    ],
];
