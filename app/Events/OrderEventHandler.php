<?php
/**
 * Created by PhpStorm.
 * User: rubin
 * Date: 5/15/2015
 * Time: 3:13 PM
 */

class OrderEventHandler {

    CONST EVENT = 'Api.orderdetails';
    CONST CHANNEL = 'Api.orderdetails';

    public function handle($data)
    {
        $redis = Redis::connection();
        $redis->publish(self::CHANNEL, $data);
    }
}