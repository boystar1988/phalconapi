<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        return $this->success(['info'=>'前台应用']);
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
        /** @var \Phalcon\Queue\Beanstalk $queue */
        $queue = $this->queue;
        $delay = 10;
        $listenerName = 'default';
        $param = [];
        $res = $queue->put(['listener' => $listenerName,'data'=>$param],['delay'=>$delay]);
        return $this->success(intval($res));
    }

}
