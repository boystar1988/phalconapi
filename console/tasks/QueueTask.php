<?php

class QueueTask extends \Phalcon\CLI\Task{

    public function listenAction()
    {
        //监视指定tube
        echo "[".date("Y-m-d H:i:s")."] "."正在监听 ...".PHP_EOL;
        $this->queue->watch('phalconapi');
        while(true){
            //Todo: 获取任务
            /** @var \Phalcon\Queue\Beanstalk\Job $job */
            $job = $this->queue->reserve();
            if($job){
                $jobId = $job->getId();
                echo "[".date("Y-m-d H:i:s")."] "."任务".$jobId."执行中...".PHP_EOL;
                $jobInfo = $job->getBody();
                //Todo: 首字母大写，下划线转驼峰
                $listenerName = ucwords($jobInfo['listener']).'Listener';
//                echo "[".date("Y-m-d H:i:s")."] ".'监听器：'.$listenerName.PHP_EOL;
//                echo "[".date("Y-m-d H:i:s")."] ".'传入任务参数：'.json_encode($jobInfo['data']).PHP_EOL;
                /** @var ListenerInterface $listener */
                $listener = new $listenerName();
                $listener->run($jobInfo['data']);
                echo "[".date("Y-m-d H:i:s")."] ".'删除完成的任务(ID:'.$jobId.')...'.PHP_EOL;
//                $job->delete();
                echo "[".date("Y-m-d H:i:s")."] ".'成功...'.PHP_EOL;
            }
        }
    }

}