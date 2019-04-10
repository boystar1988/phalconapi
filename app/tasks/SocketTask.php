<?php
use swoole_websocket_server;
use swoole_server;

class SocketTask extends \Phalcon\CLI\Task{

    /**
     * @var swoole_websocket_server
     */
    public $server;

    /**
     * @var Redis
     */
    public $redis;


    public function indexAction()
    {
        $socketConfig = $this->di->config->socket->toArray();
        $redisConfig = $this->di->config->redis->toArray();
        $this->redis = new \Redis();
        $this->redis->connect($redisConfig['host']??'127.0.0.1',$redisConfig['port']??6379,$redisConfig['timeout']??0);
        if(isset($redisConfig['password']) && $redisConfig['password']){
            $this->redis->auth($redisConfig['password']);
        }
        $this->redis->select($redisConfig['database']??0);
        $this->server = new swoole_websocket_server("0.0.0.0", $socketConfig['port'],$socketConfig['mode'], SWOOLE_SOCK_TCP | SWOOLE_SSL);
        if(!isset($socketConfig['config'])){
            echo "socket参数未配置完整\r\n";exit;
        }
        $this->server->set($socketConfig['config']);
        $this->server->on('connect',  [$this, 'onConnect' ]);
        $this->server->on('message',  [$this, 'onMessage' ]);
        $this->server->on('close',    [$this, 'onClose'   ]);
        $this->server->on('task',     [$this, 'onTask'    ]);
        $this->server->start();
    }

    //连接执行
    public function onConnect(swoole_websocket_server $server,$fd)
    {
        echo "FD{$fd}建立连接\r\n";
    }

    //断开连接执行
    public function onClose(swoole_websocket_server $server, $fd)
    {
        echo "FD{$fd}断开连接\r\n";
    }

    //收到发送信息执行
    public function onMessage(swoole_websocket_server $server, $frame)
    {
        echo "收到来自FD".$frame->fd.'的数据:'.$frame->data."\r\n";
        $data = $frame->data;
        $server->task($data);
    }

    //收到发送信息执行
    public function onTask(swoole_server $serv, $task_id, $src_worker_id, $data)
    {
        echo "执行任务，数据：".json_encode($data)."\r\n";
    }

}