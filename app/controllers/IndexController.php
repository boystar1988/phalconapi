<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->success(['info'=>'高性能API框架']);
    }

    /**
     * 消息队列测试
     * @return \Phalcon\Http\ResponseInterface
     */
    public function jobAction()
    {
        $res = $this->queue->put(['listener' => 'default','data'=>['a'=>1]]);
        return $this->success(intval($res));
    }

}
