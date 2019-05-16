<?php

class DefaultListener implements ListenerInterface
{

    public function run($data)
    {
        echo "[".date("Y-m-d H:i:s")."] "."执行参数：".json_encode($data).PHP_EOL;
    }

}
