<?php
//路由Map
return [
    //错误路由
    "error"=>[
        "path"=>"/error",
        "action"=>'Index::error',
        "method"=>["GET"],
    ],
    //测试路由
    "test"=>[
        "path"=>"/test",
        "action"=>'Index::test',
        "method"=>["GET"],
    ],
];
