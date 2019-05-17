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

    /**
     * Socket测试
     * @return \Phalcon\Http\ResponseInterface
     */
    public function socketAction()
    {
        /** @var WebsocketClient $socket */
        $socket = $this->socket;
        $conn = $socket->connect('127.0.0.1',9501);
        if($conn['code']!=0){
            return $this->error($conn['code'],$conn['msg']);
        }
        $send = $socket->sendData(['test'=>'data']);
        $socket->disconnect();
        return $this->success($send);
    }

}
