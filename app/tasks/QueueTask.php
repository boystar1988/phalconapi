<?php

class QueueTask extends \Phalcon\CLI\Task{

    public function listenAction()
    {
        echo "[".date("Y-m-d H:i:s")."] "."连接进程...".PHP_EOL;
        //连接Beanstalk
        $queue = new Phalcon\Queue\Beanstalk($this->config->beanstalk->toArray());
        echo "[".date("Y-m-d H:i:s")."] "."进入队列 ...".PHP_EOL;
        //监视指定tube
//        $queue->watch(Beanstalk::DEFAULT_TUBE);
        echo "[".date("Y-m-d H:i:s")."] "."监听成功 ...".PHP_EOL;
        while(true){
            //获取任务
            $job = $queue->reserve();
            if(!$job){
                echo "[".date("Y-m-d H:i:s")."] "."任务不可用，已忽略...".PHP_EOL;
            }else{
                $job_id = $job->getId();
                $jobInfo = $job->getBody();
                $listenerName = $jobInfo['listener'].'Listener';
                echo "[".date("Y-m-d H:i:s")."] ".'监听器：'.$listenerName.PHP_EOL;
                echo "[".date("Y-m-d H:i:s")."] ".'传入任务参数：'.json_encode($jobInfo['data']).PHP_EOL;
                (new $listenerName())->run($jobInfo['data']);
                echo "[".date("Y-m-d H:i:s")."] ".'删除完成的任务(ID:'.$job_id.')...'.PHP_EOL;
                $job->delete();
                echo "[".date("Y-m-d H:i:s")."] ".'成功...'.PHP_EOL;
            }
        }
    }

}