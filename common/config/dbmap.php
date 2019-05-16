<?php
/**
 * 数据库字段校验规则
 * (系统自动生成)
 * @author zhaozhuobin
 * @date 2019-05-16 17:58
 */
return [
    "pha_user"  =>  [
        "uid"  =>  [
        ],
        "username"  =>  [
            "length"  =>  16,
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
            "max"  =>  9999999999,
        ],
        "update_time"  =>  [
            "min"  =>  0,
            "max"  =>  9999999999,
        ],
    ],
];
