<?php
/**
 * 数据库字段校验规则
 * (系统自动生成)
 * @author zhaozhuobin
 * @date 2019-04-10 16:07
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
        "is_del"  =>  [
            "min"  =>  0,
            "max"  =>  9,
        ],
        "create_time"  =>  [
            "min"  =>  0,
            "max"  =>  99999999999,
        ],
        "update_time"  =>  [
            "min"  =>  0,
            "max"  =>  99999999999,
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
