<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    public function success($data=null,$msg="操作成功",$code=API_SUCCESS_CODE)
    {
        return $this->asJson(['code'=>$code, 'msg'=>$msg, 'data'=>$data]);
    }

    public function error($code=API_FAIL_CODE,$msg="操作失败")
    {
        return $this->asJson(['code'=>$code, 'msg'=>$msg]);
    }

    public function asJson($param=[])
    {
        return $this->response->setJsonContent($param);
    }
}
