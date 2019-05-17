<?php

class WsTask extends \Phalcon\CLI\Task{

    /**
     * @var swoole_websocket_server
     */
    public $server;

    /**
     * @var Redis
     */
    public $redis;

    /**
     * 操作列表
     * @var array
     */
    private $_actionMap = [
        '1'=>'_joinChatRoom',   //加入聊天室
        '2'=>'_leaveChatRoom',  //离开聊天室
        '3'=>'_sendChatMessage',//发送消息
    ];


    public function indexAction()
    {
        $socketConfig = $this->di->config->socket;
        if(empty($socketConfig)){
            echo "请先配置socket参数\r\n";exit;
        }
        $socketConfig = $socketConfig->toArray();
        $redisConfig = $this->di->config->redis;
        if(empty($socketConfig)){
            echo "请先配置redis参数\r\n";exit;
        }
        $redisConfig = $redisConfig->toArray();
        $this->redis = new \Redis();
        $this->redis->connect($redisConfig['host']??'127.0.0.1',$redisConfig['port']??6379,$redisConfig['timeout']??0);
        if(isset($redisConfig['password']) && $redisConfig['password']){
            $this->redis->auth($redisConfig['password']);
        }
        $this->redis->select($redisConfig['database']??0);
        $this->server = new swoole_websocket_server("0.0.0.0", $socketConfig['port']??9501,$socketConfig['mode']??SWOOLE_PROCESS);
        if(empty($socketConfig['config']) || !is_array($socketConfig['config'])){
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
        echo "[系统] FD{$fd}建立连接\r\n";

    }

    //断开连接执行
    public function onClose(swoole_websocket_server $server, $fd)
    {
        echo "[系统] FD{$fd}断开连接\r\n";
    }

    //收到发送信息执行
    public function onMessage(swoole_websocket_server $server, $frame)
    {
        echo "FD".$frame->fd.':'.$frame->data."\r\n";
        $data = json_decode($frame->data,true);
        if(!$this->redis->exists('fd:'.$frame->fd)){
            $this->redis->set('fd:'.$frame->fd,json_encode(['userId'=>$data['userId'],'nickname'=>$data['nickname'],'avatar'=>$data['avatar']]));
        }
        $task_data['roomId'] = $data['roomId'];
        $task_data['content'] = $data['content'];
        $task_data['action'] = $data['action'];
        $task_data['fd'] = $frame->fd;
        $server->task($task_data);
    }

    private function _joinChatRoom($data)
    {
        $this->redis->sAdd('room:'.$data['roomId'],$data['fd']);
    }

    private function _leaveChatRoom($data)
    {
        $this->redis->sRem('room:'.$data['roomId'],$data['fd']);
    }

    private function _sendChatMessage($data)
    {
        $fds = $this->redis->sMembers('room:'.$data['roomId']);
        $user = $this->redis->get('fd:'.$data['fd']);
        $content = array_merge(['content'=>$data['content']],$user);
        foreach ($fds as $v){
            $this->server->push($v,json_encode($content));
        }
    }

    //收到发送信息执行
    public function onTask(swoole_server $serv, $task_id, $src_worker_id, $data)
    {
        $action = $data['action'];
        unset($data['action']);
        $func = $this->_actionMap[$action]??'';
        $this->$func($data);
    }

}