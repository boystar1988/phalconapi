<?php

use Phalcon\Queue\Beanstalk;

class QueueService
{
    /**
     * @var \Phalcon\Queue\Beanstalk
     */
    public $queue;

    public function push($data,$options=[])
    {
//        $this->queue->choose(Beanstalk::DEFAULT_TUBE);
        return $this->queue->put($data,$options);
    }

}
