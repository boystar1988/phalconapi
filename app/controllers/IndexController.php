<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->success(['info'=>'高性能API框架']);
    }

    public function testAction()
    {
        return $this->success(['info'=>'测试路由']);
    }

    public function errorAction()
    {
        return $this->success(['info'=>'404']);
    }

    /**
     * 消息队列测试
     * @return \Phalcon\Http\ResponseInterface
     */
    public function jobAction()
    {
        $res = $this->queue->pust(['listener' => 'default','data'=>['a'=>1]]);
        return $this->success(intval($res));
    }

}
