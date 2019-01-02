<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->success();
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
